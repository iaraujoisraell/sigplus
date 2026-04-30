<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Workgroup extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) {
            redirect(base_url('admin/authentication'));
        }
        $this->load->model('Workgroup_model');
        $this->load->model('Staff_model');
    }

    public function index()
    {
        $filtros = [
            'status'     => $this->input->get('status'),
            'project_id' => $this->input->get('project_id'),
            'meu'        => $this->input->get('meu'),
            'busca'      => $this->input->get('busca'),
        ];
        $data = [
            'title'   => 'Grupos',
            'grupos'  => $this->Workgroup_model->listar($filtros),
            'filtros' => $filtros,
        ];
        $this->load->view('gestao_corporativa/grupos/list', $data);
    }

    public function add()
    {
        // Qualquer staff pode criar
        $data = [
            'title'      => 'Novo Grupo',
            'grupo'      => null,
            'membros'    => [],
            'project_id' => $this->input->get('project_id'),
            'staffs'     => $this->Staff_model->get(),
            'projects'   => $this->_get_projects(),
        ];
        $this->load->view('gestao_corporativa/grupos/form', $data);
    }

    public function edit($id)
    {
        $grupo = $this->Workgroup_model->get($id);
        if (!$grupo) show_404();
        if (!$this->Workgroup_model->pode_editar($grupo)) access_denied('Grupos');

        $data = [
            'title'      => 'Editar Grupo',
            'grupo'      => $grupo,
            'membros'    => $this->Workgroup_model->get_membros($id),
            'project_id' => $grupo['project_id'],
            'staffs'     => $this->Staff_model->get(),
            'projects'   => $this->_get_projects(),
        ];
        $this->load->view('gestao_corporativa/grupos/form', $data);
    }

    public function view($id)
    {
        $grupo = $this->Workgroup_model->get($id);
        if (!$grupo) show_404();
        if (!$this->Workgroup_model->pode_visualizar($grupo)) {
            access_denied('Grupos — você não é membro deste grupo.');
        }

        $data = [
            'title'       => 'Grupo: ' . $grupo['titulo'],
            'grupo'       => $grupo,
            'membros'     => $this->Workgroup_model->get_membros($id),
            'atas'        => $this->Workgroup_model->get_atas_do_grupo($id),
            'planos'      => $this->Workgroup_model->get_planos_do_grupo($id),
            'tasks'       => $this->Workgroup_model->get_tasks_do_grupo($id),
            'pode_editar' => $this->Workgroup_model->pode_editar($grupo),
        ];
        $this->load->view('gestao_corporativa/grupos/view', $data);
    }

    public function save()
    {
        if (!$this->input->post()) redirect('gestao_corporativa/Workgroup');

        $id = (int) $this->input->post('id');
        $titulo = trim((string) $this->input->post('titulo'));
        if ($titulo === '') {
            set_alert('danger', 'Título obrigatório.');
            redirect('gestao_corporativa/Workgroup/' . ($id ? 'edit/' . $id : 'add'));
        }

        if ($id) {
            $existing = $this->Workgroup_model->get($id);
            if (!$existing || !$this->Workgroup_model->pode_editar($existing)) access_denied('Grupos');
        }

        $grupo_id = $this->Workgroup_model->save($this->input->post(), $id ?: null);
        if (!$grupo_id) {
            set_alert('danger', 'Falha ao salvar grupo.');
            redirect('gestao_corporativa/Workgroup');
        }

        $this->Workgroup_model->save_membros($grupo_id, $this->input->post('membros') ?: []);

        set_alert('success', $id ? 'Grupo atualizado.' : 'Grupo criado.');
        redirect('gestao_corporativa/Workgroup/view/' . $grupo_id);
    }

    public function delete($id)
    {
        $grupo = $this->Workgroup_model->get($id);
        if (!$grupo) show_404();
        if (!$this->Workgroup_model->pode_editar($grupo)) access_denied('Grupos');

        $this->Workgroup_model->delete($id);
        set_alert('success', 'Grupo excluído.');
        redirect('gestao_corporativa/Workgroup');
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')->where('empresa_id', $empresa_id)->order_by('name', 'asc')
            ->get('tblprojects')->result_array();
    }
}
