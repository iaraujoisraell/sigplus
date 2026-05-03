<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Acao extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Acao_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
        $this->load->model('Projeto_fase_model');
    }

    public function index()
    {
        $filtros = [
            'project_id'    => $this->input->get('project_id'),
            'status'        => $this->input->get('status'),
            'priority'      => $this->input->get('priority'),
            'criei'         => $this->input->get('criei'),
            'atribuida_eu'  => $this->input->get('atribuida_eu'),
            'envolve_eu'    => $this->input->get('envolve_eu'),
            'atrasadas'     => $this->input->get('atrasadas'),
            'concluidas'    => $this->input->get('concluidas'),
            'busca'         => $this->input->get('busca'),
        ];
        $data = [
            'title'    => 'Ações',
            'acoes'    => $this->Acao_model->listar($filtros),
            'filtros'  => $filtros,
            'projects' => $this->_get_projects(),
            'staffs'   => $this->Staff_model->get(),
        ];
        $this->load->view('gestao_corporativa/acoes/list', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        if ($id) {
            $a = $this->Acao_model->get($id);
            if (!$a || !$this->Acao_model->pode_editar($a)) access_denied('Ações');
        }
        $task_id = $this->Acao_model->save($this->input->post(), $id ?: null);
        if ($this->input->is_ajax_request()) {
            echo json_encode(['ok' => (bool) $task_id, 'id' => $task_id]);
            return;
        }
        set_alert('success', 'Ação salva.');
        redirect('gestao_corporativa/Acao');
    }

    public function concluir($id)
    {
        $a = $this->Acao_model->get($id);
        if (!$a) show_404();
        if (!$this->Acao_model->pode_editar($a)) access_denied('Ações');
        $this->Acao_model->marcar_concluida($id, true);
        if ($this->input->is_ajax_request()) { echo json_encode(['ok' => true]); return; }
        redirect('gestao_corporativa/Acao');
    }

    public function reabrir($id)
    {
        $a = $this->Acao_model->get($id);
        if (!$a) show_404();
        if (!$this->Acao_model->pode_editar($a)) access_denied('Ações');
        $this->Acao_model->marcar_concluida($id, false);
        if ($this->input->is_ajax_request()) { echo json_encode(['ok' => true]); return; }
        redirect('gestao_corporativa/Acao');
    }

    public function get_acao($id)
    {
        // pra carregar dados no modal de edição via AJAX
        $a = $this->Acao_model->get($id);
        if (!$a) { echo json_encode(null); return; }
        $a['assigned_ids'] = $this->db->select('staffid')->where('taskid', (int) $id)
            ->get('tbltask_assigned')->result_array();
        $a['assigned_ids'] = array_map(function ($r) { return (int) $r['staffid']; }, $a['assigned_ids']);
        echo json_encode($a);
    }

    public function ata_options()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $rows = $this->db->select('id, titulo')->from('tbl_atas')
            ->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('id', 'desc')->limit(100)->get()->result_array();
        echo json_encode($rows);
    }

    public function plano_options()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $rows = $this->db->select('id, titulo')->from('tbl_planos_acao')
            ->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('id', 'desc')->limit(100)->get()->result_array();
        echo json_encode($rows);
    }

    public function grupo_options()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $rows = $this->db->select('id, titulo')->from('tbl_grupos')
            ->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('id', 'desc')->limit(100)->get()->result_array();
        echo json_encode($rows);
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')->from('tblprojects')
            ->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('name', 'asc')->get()->result_array();
    }
}
