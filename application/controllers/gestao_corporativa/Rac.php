<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rac extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Rac_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
    }

    public function index()
    {
        $filtros = [
            'status'          => $this->input->get('status'),
            'project_id'      => $this->input->get('project_id'),
            'ano'             => $this->input->get('ano'),
            'criei'           => $this->input->get('criei'),
            'responsavel_meu' => $this->input->get('responsavel_meu'),
            'busca'           => $this->input->get('busca'),
        ];
        $data = [
            'title'    => 'Análise Crítica pela Direção (RAC)',
            'racs'     => $this->Rac_model->listar($filtros),
            'filtros'  => $filtros,
            'projects' => $this->_get_projects(),
        ];
        $this->load->view('gestao_corporativa/rac/list', $data);
    }

    public function add()
    {
        $data = [
            'title'         => 'Nova RAC',
            'rac'           => null,
            'participantes' => [],
            'staffs'        => $this->Staff_model->get(),
            'projects'      => $this->_get_projects(),
            'snapshot'      => null,
        ];
        $this->load->view('gestao_corporativa/rac/form', $data);
    }

    public function edit($id)
    {
        $rac = $this->Rac_model->get($id);
        if (!$rac) show_404();
        if (!$this->Rac_model->pode_editar($rac)) access_denied('RAC');

        $data = [
            'title'         => 'Editar RAC',
            'rac'           => $rac,
            'participantes' => $this->Rac_model->get_participantes($id),
            'staffs'        => $this->Staff_model->get(),
            'projects'      => $this->_get_projects(),
            'snapshot'      => $this->Rac_model->snapshot($rac['periodo_inicio'], $rac['periodo_fim'], $rac['project_id']),
        ];
        $this->load->view('gestao_corporativa/rac/form', $data);
    }

    public function view($id)
    {
        $rac = $this->Rac_model->get($id);
        if (!$rac) show_404();
        $data = [
            'title'         => $rac['titulo'],
            'rac'           => $rac,
            'participantes' => $this->Rac_model->get_participantes($id),
            'snapshot'      => $this->Rac_model->snapshot($rac['periodo_inicio'], $rac['periodo_fim'], $rac['project_id']),
            'pode_editar'   => $this->Rac_model->pode_editar($rac),
        ];
        $this->load->view('gestao_corporativa/rac/view', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        if ($id) {
            $r = $this->Rac_model->get($id);
            if (!$r || !$this->Rac_model->pode_editar($r)) access_denied('RAC');
        }
        $new_id = $this->Rac_model->save($this->input->post(), $id ?: null);
        if ($new_id) {
            $this->Rac_model->set_participantes($new_id, $this->input->post('participantes') ?: []);
        }
        set_alert('success', 'RAC salva.');
        redirect('gestao_corporativa/Rac/view/' . $new_id);
    }

    public function delete($id)
    {
        $r = $this->Rac_model->get($id);
        if (!$r) show_404();
        if (!$this->Rac_model->pode_editar($r)) access_denied('RAC');
        $this->Rac_model->delete($id);
        set_alert('success', 'RAC excluída.');
        redirect('gestao_corporativa/Rac');
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')->from('tblprojects')
            ->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('name', 'asc')->get()->result_array();
    }
}
