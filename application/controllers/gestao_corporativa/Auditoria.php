<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auditoria extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Auditoria_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
        $this->load->model('Projeto_fase_model');
        $this->load->model('Departments_model');
        $this->load->model('Documentos_model');
        $this->load->model('Plano_acao_model');
        $this->load->model('Workgroup_model');
        $this->load->model('Formulario_model');
    }

    public function index()
    {
        $filtros = [
            'tipo'            => $this->input->get('tipo'),
            'status'          => $this->input->get('status'),
            'resultado'       => $this->input->get('resultado'),
            'setor_id'        => $this->input->get('setor_id'),
            'project_id'      => $this->input->get('project_id'),
            'plano_id'        => $this->input->get('plano_id'),
            'grupo_id'        => $this->input->get('grupo_id'),
            'documento_id'    => $this->input->get('documento_id'),
            'criei'           => $this->input->get('criei'),
            'responsavel_meu' => $this->input->get('responsavel_meu'),
            'busca'           => $this->input->get('busca'),
        ];
        $data = [
            'title'       => 'Auditorias',
            'auditorias'  => $this->Auditoria_model->listar($filtros),
            'filtros'     => $filtros,
            'projects'    => $this->_get_projects(),
            'departments' => $this->Departments_model->get(),
        ];
        $this->load->view('gestao_corporativa/auditorias/list', $data);
    }

    public function add()
    {
        $data = [
            'title'       => 'Nova auditoria',
            'auditoria'   => null,
            'auditados'   => [],
            'staffs'      => $this->Staff_model->get(),
            'projects'    => $this->_get_projects(),
            'departments' => $this->Departments_model->get(),
            'documentos'  => $this->Documentos_model->listar([]),
            'planos'      => $this->Plano_acao_model->listar([]),
            'grupos'      => $this->Workgroup_model->listar([]),
            'formularios' => $this->Formulario_model->listar([]),
            'preencher'   => [
                'project_id'              => $this->input->get('project_id'),
                'plano_id'                => $this->input->get('plano_id'),
                'grupo_id'                => $this->input->get('grupo_id'),
                'documento_referencia_id' => $this->input->get('documento_id'),
            ],
        ];
        $this->load->view('gestao_corporativa/auditorias/form', $data);
    }

    public function edit($id)
    {
        $a = $this->Auditoria_model->get($id);
        if (!$a) show_404();
        if (!$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');

        $data = [
            'title'       => 'Editar auditoria',
            'auditoria'   => $a,
            'auditados'   => $this->Auditoria_model->get_auditados($id),
            'staffs'      => $this->Staff_model->get(),
            'projects'    => $this->_get_projects(),
            'departments' => $this->Departments_model->get(),
            'documentos'  => $this->Documentos_model->listar([]),
            'planos'      => $this->Plano_acao_model->listar([]),
            'grupos'      => $this->Workgroup_model->listar([]),
            'formularios' => $this->Formulario_model->listar([]),
            'preencher'   => [],
        ];
        $this->load->view('gestao_corporativa/auditorias/form', $data);
    }

    public function view($id)
    {
        $a = $this->Auditoria_model->get($id);
        if (!$a) show_404();
        $data = [
            'title'       => $a['titulo'],
            'auditoria'   => $a,
            'auditados'   => $this->Auditoria_model->get_auditados($id),
            'achados'     => $this->Auditoria_model->get_achados($id),
            'pode_editar' => $this->Auditoria_model->pode_editar($a),
            'staffs'      => $this->Staff_model->get(),
            'planos'      => $this->Plano_acao_model->listar([]),
        ];
        $this->load->view('gestao_corporativa/auditorias/view', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        if ($id) {
            $a = $this->Auditoria_model->get($id);
            if (!$a || !$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');
        }
        $new_id = $this->Auditoria_model->save($this->input->post(), $id ?: null);
        if ($new_id) {
            $this->Auditoria_model->set_auditados($new_id, $this->input->post('auditados') ?: []);
        }
        set_alert('success', 'Auditoria salva.');
        redirect('gestao_corporativa/Auditoria/view/' . $new_id);
    }

    public function delete($id)
    {
        $a = $this->Auditoria_model->get($id);
        if (!$a) show_404();
        if (!$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');
        $this->Auditoria_model->delete($id);
        set_alert('success', 'Auditoria excluída.');
        redirect('gestao_corporativa/Auditoria');
    }

    /* ------- Achados (AJAX-ish) ------- */

    public function achado_save()
    {
        $auditoria_id = (int) $this->input->post('auditoria_id');
        $a = $this->Auditoria_model->get($auditoria_id);
        if (!$a || !$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');
        $id = (int) $this->input->post('id');
        $this->Auditoria_model->save_achado($this->input->post(), $id ?: null);
        redirect('gestao_corporativa/Auditoria/view/' . $auditoria_id);
    }

    public function achado_delete($id)
    {
        $row = $this->db->where('id', (int) $id)->get('tbl_auditorias_achados')->row();
        if (!$row) show_404();
        $a = $this->Auditoria_model->get($row->auditoria_id);
        if (!$a || !$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');
        $this->Auditoria_model->delete_achado($id);
        redirect('gestao_corporativa/Auditoria/view/' . (int) $row->auditoria_id);
    }

    public function achado_gerar_ocorrencia($id)
    {
        $row = $this->db->where('id', (int) $id)->get('tbl_auditorias_achados')->row();
        if (!$row) show_404();
        $a = $this->Auditoria_model->get($row->auditoria_id);
        if (!$a || !$this->Auditoria_model->pode_editar($a)) access_denied('Auditorias');
        $oc_id = $this->Auditoria_model->gerar_ocorrencia($id);
        if ($oc_id) set_alert('success', 'Ocorrência gerada (R.O #' . (int) $oc_id . ').');
        else        set_alert('warning', 'Não foi possível gerar ocorrência.');
        redirect('gestao_corporativa/Auditoria/view/' . (int) $row->auditoria_id);
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')->from('tblprojects')
            ->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('name', 'asc')->get()->result_array();
    }
}
