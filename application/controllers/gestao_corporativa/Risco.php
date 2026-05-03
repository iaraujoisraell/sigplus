<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Risco extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Risco_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
        $this->load->model('Projeto_fase_model');
        $this->load->model('Departments_model');
        $this->load->model('Plano_acao_model');
        $this->load->model('Workgroup_model');
    }

    public function index()
    {
        $filtros = [
            'categoria'       => $this->input->get('categoria'),
            'status'          => $this->input->get('status'),
            'nivel'           => $this->input->get('nivel'),
            'project_id'      => $this->input->get('project_id'),
            'setor_id'        => $this->input->get('setor_id'),
            'plano_id'        => $this->input->get('plano_id'),
            'grupo_id'        => $this->input->get('grupo_id'),
            'criei'           => $this->input->get('criei'),
            'responsavel_meu' => $this->input->get('responsavel_meu'),
            'busca'           => $this->input->get('busca'),
        ];
        $data = [
            'title'       => 'Matriz de Riscos',
            'riscos'      => $this->Risco_model->listar($filtros),
            'matriz'      => $this->Risco_model->matriz($filtros),
            'filtros'     => $filtros,
            'projects'    => $this->_get_projects(),
            'departments' => $this->Departments_model->get(),
        ];
        $this->load->view('gestao_corporativa/riscos/list', $data);
    }

    public function add()
    {
        $data = [
            'title'       => 'Novo risco',
            'risco'       => null,
            'projects'    => $this->_get_projects(),
            'departments' => $this->Departments_model->get(),
            'staffs'      => $this->Staff_model->get(),
            'planos'      => $this->Plano_acao_model->listar([]),
            'grupos'      => $this->Workgroup_model->listar([]),
            'preencher'   => [
                'project_id' => $this->input->get('project_id'),
                'plano_id'   => $this->input->get('plano_id'),
                'grupo_id'   => $this->input->get('grupo_id'),
            ],
        ];
        $this->load->view('gestao_corporativa/riscos/form', $data);
    }

    public function edit($id)
    {
        $r = $this->Risco_model->get($id);
        if (!$r) show_404();
        if (!$this->Risco_model->pode_editar($r)) access_denied('Riscos');

        $data = [
            'title'       => 'Editar risco',
            'risco'       => $r,
            'projects'    => $this->_get_projects(),
            'departments' => $this->Departments_model->get(),
            'staffs'      => $this->Staff_model->get(),
            'planos'      => $this->Plano_acao_model->listar([]),
            'grupos'      => $this->Workgroup_model->listar([]),
            'preencher'   => [],
        ];
        $this->load->view('gestao_corporativa/riscos/form', $data);
    }

    public function view($id)
    {
        $r = $this->Risco_model->get($id);
        if (!$r) show_404();
        $data = [
            'title'       => $r['titulo'],
            'risco'       => $r,
            'avaliacoes'  => $this->Risco_model->get_avaliacoes($id),
            'pode_editar' => $this->Risco_model->pode_editar($r),
        ];
        $this->load->view('gestao_corporativa/riscos/view', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        if ($id) {
            $r = $this->Risco_model->get($id);
            if (!$r || !$this->Risco_model->pode_editar($r)) access_denied('Riscos');
        }
        $new_id = $this->Risco_model->save($this->input->post(), $id ?: null);
        set_alert('success', 'Risco salvo.');
        redirect('gestao_corporativa/Risco/view/' . $new_id);
    }

    public function delete($id)
    {
        $r = $this->Risco_model->get($id);
        if (!$r) show_404();
        if (!$this->Risco_model->pode_editar($r)) access_denied('Riscos');
        $this->Risco_model->delete($id);
        set_alert('success', 'Risco excluído.');
        redirect('gestao_corporativa/Risco');
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')->from('tblprojects')
            ->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('name', 'asc')->get()->result_array();
    }
}
