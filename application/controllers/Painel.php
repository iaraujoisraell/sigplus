<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Painel SaaS — gestão multiempresa.
 * Controlador independente: NÃO usa autenticação do Perfex (staff).
 * Tem login próprio em tbl_saas_admins; sessão isolada via 'saas_admin_id'.
 */
class Painel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Saas_admin_model');
        $this->load->model('Saas_empresa_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form', 'security']);
    }

    /* ============ Auth ============ */

    public function login()
    {
        if ($this->_logado()) redirect('painel');

        $erro = '';
        if ($this->input->post('email')) {
            $admin = $this->Saas_admin_model->autenticar(
                $this->input->post('email'),
                $this->input->post('password')
            );
            if ($admin) {
                $this->session->set_userdata('saas_admin_id', (int) $admin['id']);
                $this->session->set_userdata('saas_admin_nome', $admin['nome']);
                redirect('painel');
            }
            $erro = 'E-mail ou senha incorretos.';
        }
        $this->load->view('painel/login', ['erro' => $erro]);
    }

    public function logout()
    {
        $this->session->unset_userdata('saas_admin_id');
        $this->session->unset_userdata('saas_admin_nome');
        redirect('painel/login');
    }

    /* ============ Dashboard / Empresas ============ */

    public function index()
    {
        $this->_check_auth();
        $filtros = [
            'busca'             => $this->input->get('busca'),
            'active'            => $this->input->get('active'),
            'mostrar_excluidas' => $this->input->get('mostrar_excluidas'),
        ];
        $data = [
            'empresas' => $this->Saas_empresa_model->listar($filtros),
            'filtros'  => $filtros,
        ];
        $this->load->view('painel/empresas', $data);
    }

    public function empresa_add()
    {
        $this->_check_auth();
        $this->load->view('painel/empresa_form', ['empresa' => null]);
    }

    public function empresa_edit($id)
    {
        $this->_check_auth();
        $empresa = $this->Saas_empresa_model->get($id);
        if (!$empresa) show_404();
        $this->load->view('painel/empresa_form', ['empresa' => $empresa]);
    }

    public function empresa_save()
    {
        $this->_check_auth();
        $id = (int) $this->input->post('id');
        $new_id = $this->Saas_empresa_model->save($this->input->post(), $id ?: null);

        // Se for nova empresa e tiver dados de admin inicial, cria
        $admin_email = $this->input->post('admin_email');
        if (!$id && $new_id && !empty($admin_email)) {
            $r = $this->Saas_empresa_model->criar_staff($new_id, [
                'firstname' => $this->input->post('admin_firstname'),
                'lastname'  => $this->input->post('admin_lastname'),
                'email'     => $admin_email,
                'password'  => $this->input->post('admin_password'),
                'admin'     => 1,
            ]);
            if ($r['ok']) {
                $this->session->set_flashdata('msg', 'Empresa criada. Admin: ' . $admin_email . ' · Senha: ' . $r['senha']);
            }
        }
        redirect('painel/empresa/' . $new_id);
    }

    public function empresa($id)
    {
        $this->_check_auth();
        $empresa = $this->Saas_empresa_model->get($id);
        if (!$empresa) show_404();
        $data = [
            'empresa' => $empresa,
            'staff'   => $this->Saas_empresa_model->listar_staff($id),
            'msg'     => $this->session->flashdata('msg'),
        ];
        $this->load->view('painel/empresa_view', $data);
    }

    public function empresa_ativar($id)
    {
        $this->_check_auth();
        $emp = $this->Saas_empresa_model->get($id);
        if (!$emp) show_404();
        $this->Saas_empresa_model->ativar($id, !$emp['active']);
        redirect('painel/empresa/' . $id);
    }

    public function empresa_excluir($id)
    {
        $this->_check_auth();
        $this->Saas_empresa_model->delete($id);
        redirect('painel');
    }

    /* ============ Staff de uma empresa ============ */

    public function staff_add($empresa_id)
    {
        $this->_check_auth();
        $empresa = $this->Saas_empresa_model->get($empresa_id);
        if (!$empresa) show_404();

        if ($this->input->post()) {
            $r = $this->Saas_empresa_model->criar_staff($empresa_id, [
                'firstname' => $this->input->post('firstname'),
                'lastname'  => $this->input->post('lastname'),
                'email'     => $this->input->post('email'),
                'password'  => $this->input->post('password'),
                'admin'     => $this->input->post('admin'),
            ]);
            if ($r['ok']) {
                $this->session->set_flashdata('msg', 'Usuário criado. E-mail: ' . $this->input->post('email') . ' · Senha: ' . $r['senha']);
                redirect('painel/empresa/' . $empresa_id);
            }
            $this->session->set_flashdata('msg', 'Erro: ' . $r['erro']);
            redirect('painel/staff_add/' . $empresa_id);
        }
        $this->load->view('painel/staff_form', [
            'empresa' => $empresa,
            'msg'     => $this->session->flashdata('msg'),
        ]);
    }

    public function staff_reset_senha($staffid)
    {
        $this->_check_auth();
        $staff = $this->db->where('staffid', $staffid)->get('tblstaff')->row();
        if (!$staff) show_404();
        $nova = $this->Saas_empresa_model->reset_senha_staff($staffid);
        $this->session->set_flashdata('msg', 'Senha resetada de ' . $staff->email . ' · Nova senha: ' . $nova);
        redirect('painel/empresa/' . (int) $staff->empresa_id);
    }

    public function staff_toggle($staffid)
    {
        $this->_check_auth();
        $staff = $this->db->where('staffid', $staffid)->get('tblstaff')->row();
        if (!$staff) show_404();
        $this->Saas_empresa_model->toggle_staff_active($staffid);
        redirect('painel/empresa/' . (int) $staff->empresa_id);
    }

    /* ============ Impersonate (logar como) ============ */

    public function entrar_como($staffid)
    {
        $this->_check_auth();
        $staff = $this->db->where('staffid', $staffid)->where('active', 1)->get('tblstaff')->row();
        if (!$staff) show_404();

        // Limpa sessão atual e popula como Perfex
        $this->session->unset_userdata('staff_user_id');
        $this->session->set_userdata('staff_user_id', (int) $staff->staffid);
        $this->session->set_userdata('staff_logged_in', true);
        $this->session->set_userdata('empresa_id', (int) $staff->empresa_id);
        $this->session->set_userdata('saas_impersonating', true);
        $this->session->set_userdata('saas_impersonating_from', $this->session->userdata('saas_admin_id'));

        redirect('admin');
    }

    public function sair_da_empresa()
    {
        // Volta pro painel SaaS sem perder a sessão SaaS
        $this->session->unset_userdata('staff_user_id');
        $this->session->unset_userdata('staff_logged_in');
        $this->session->unset_userdata('empresa_id');
        $this->session->unset_userdata('saas_impersonating');
        $this->session->unset_userdata('saas_impersonating_from');
        redirect('painel');
    }

    /* ============ Helpers ============ */

    private function _logado()
    {
        return (bool) $this->session->userdata('saas_admin_id');
    }

    private function _check_auth()
    {
        if (!$this->_logado()) redirect('painel/login');
    }
}
