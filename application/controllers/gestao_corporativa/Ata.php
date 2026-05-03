<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ata extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) {
            redirect(base_url('admin/authentication'));
        }
        $this->load->model('Ata_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
        $this->load->model('Projeto_fase_model');
    }

    public function index()
    {
        $filtros = [
            'project_id'      => $this->input->get('project_id'),
            'status'          => $this->input->get('status'),
            'minha'           => $this->input->get('minha'),
            'criei'           => $this->input->get('criei'),
            'responsavel_meu' => $this->input->get('responsavel_meu'),
            'busca'           => $this->input->get('busca'),
        ];
        $data = [
            'title'   => 'Atas de Reunião',
            'atas'    => $this->Ata_model->listar($filtros),
            'filtros' => $filtros,
        ];
        $this->load->view('gestao_corporativa/atas/list', $data);
    }

    public function add()
    {
        if (!has_permission_intranet('atas', '', 'create') && !is_admin()) {
            access_denied('Atas');
        }

        $data = [
            'title'          => 'Nova Ata',
            'ata'            => null,
            'participantes'  => [],
            'convidados'     => [],
            'visualizadores' => [],
            'decisoes'       => [],
            'project_id'     => $this->input->get('project_id'),
            'staffs'         => $this->Staff_model->get(),
            'projects'       => $this->_get_projects(),
        ];
        $this->load->view('gestao_corporativa/atas/form', $data);
    }

    public function edit($id)
    {
        $ata = $this->Ata_model->get($id);
        if (!$ata) show_404();
        if (!$this->Ata_model->pode_editar($ata)) access_denied('Atas');

        $pessoas = $this->Ata_model->get_pessoas($id);
        $data = [
            'title'          => 'Editar Ata',
            'ata'            => $ata,
            'participantes'  => $pessoas['participantes'],
            'convidados'     => $pessoas['convidados'],
            'visualizadores' => $pessoas['visualizadores'],
            'decisoes'       => $this->Ata_model->get_decisoes($id),
            'project_id'     => $ata['project_id'],
            'staffs'         => $this->Staff_model->get(),
            'projects'       => $this->_get_projects(),
        ];
        $this->load->view('gestao_corporativa/atas/form', $data);
    }

    public function view($id)
    {
        $ata = $this->Ata_model->get($id);
        if (!$ata) show_404();
        if (!$this->Ata_model->pode_visualizar($ata)) {
            access_denied('Atas — você não tem permissão pra ver esta ata.');
        }

        $pessoas = $this->Ata_model->get_pessoas($id);
        $data = [
            'title'          => 'Ata: ' . $ata['titulo'],
            'ata'            => $ata,
            'participantes'  => $pessoas['participantes'],
            'convidados'     => $pessoas['convidados'],
            'visualizadores' => $pessoas['visualizadores'],
            'decisoes'       => $this->Ata_model->get_decisoes($id),
            'pode_editar'    => $this->Ata_model->pode_editar($ata),
        ];
        $this->load->view('gestao_corporativa/atas/view', $data);
    }

    public function save()
    {
        if (!$this->input->post()) {
            redirect('gestao_corporativa/Ata');
        }

        $id = (int) $this->input->post('id');
        if ($id) {
            if (!has_permission_intranet('atas', '', 'edit') && !is_admin()) access_denied('Atas');
        } else {
            if (!has_permission_intranet('atas', '', 'create') && !is_admin()) access_denied('Atas');
        }

        $titulo = trim((string) $this->input->post('titulo'));
        if ($titulo === '') {
            set_alert('danger', 'Título obrigatório.');
            redirect('gestao_corporativa/Ata/' . ($id ? 'edit/' . $id : 'add'));
        }

        $ata_id = $this->Ata_model->save($this->input->post(), $id ?: null);
        if (!$ata_id) {
            set_alert('danger', 'Falha ao salvar a ata.');
            redirect('gestao_corporativa/Ata');
        }

        $this->Ata_model->save_pessoas(
            $ata_id,
            $this->input->post('participantes') ?: [],
            $this->input->post('convidados') ?: [],
            $this->input->post('visualizadores') ?: []
        );

        $project_id = (int) $this->input->post('project_id') ?: null;
        $this->Ata_model->save_decisoes($ata_id, $this->input->post('decisoes') ?: [], $project_id, true);

        set_alert('success', $id ? 'Ata atualizada.' : 'Ata criada com sucesso.');
        redirect('gestao_corporativa/Ata/view/' . $ata_id);
    }

    public function delete($id)
    {
        if (!has_permission_intranet('atas', '', 'delete') && !is_admin()) access_denied('Atas');
        $this->Ata_model->delete($id);
        set_alert('success', 'Ata excluída.');
        redirect('gestao_corporativa/Ata');
    }

    public function gerar_task_decisao($decisao_id)
    {
        if (!has_permission_intranet('atas', '', 'edit') && !is_admin()) access_denied('Atas');
        $task_id = $this->Ata_model->gerar_task_decisao($decisao_id);
        echo json_encode(['ok' => (bool) $task_id, 'task_id' => $task_id]);
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')
            ->where('empresa_id', $empresa_id)
            ->order_by('name', 'asc')
            ->get('tblprojects')->result_array();
    }
}
