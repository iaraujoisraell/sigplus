<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Indicador extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Indicador_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
        $this->load->model('Projeto_fase_model');
        $this->load->model('Departments_model');
    }

    public function index()
    {
        $filtros = [
            'setor_id'        => $this->input->get('setor_id'),
            'project_id'      => $this->input->get('project_id'),
            'fase_id'         => $this->input->get('fase_id'),
            'responsavel_id'  => $this->input->get('responsavel_id'),
            'status'          => $this->input->get('status'),
            'meu'             => $this->input->get('meu'),
            'criei'           => $this->input->get('criei'),
            'responsavel_meu' => $this->input->get('responsavel_meu'),
            'busca'           => $this->input->get('busca'),
        ];
        $data = [
            'title'        => 'Indicadores',
            'indicadores'  => $this->Indicador_model->listar($filtros),
            'filtros'      => $filtros,
            'projects'     => $this->_get_projects(),
            'departments'  => $this->Departments_model->get(),
            'staffs'       => $this->Staff_model->get(),
        ];
        $this->load->view('gestao_corporativa/indicadores/list', $data);
    }

    public function add()
    {
        $data = [
            'title'        => 'Novo indicador',
            'indicador'    => null,
            'projects'     => $this->_get_projects(),
            'departments'  => $this->Departments_model->get(),
            'staffs'       => $this->Staff_model->get(),
        ];
        $this->load->view('gestao_corporativa/indicadores/form', $data);
    }

    public function edit($id)
    {
        $ind = $this->Indicador_model->get($id);
        if (!$ind) show_404();
        if (!$this->Indicador_model->pode_editar($ind)) access_denied('Indicadores');

        $data = [
            'title'        => 'Editar indicador',
            'indicador'    => $ind,
            'projects'     => $this->_get_projects(),
            'departments'  => $this->Departments_model->get(),
            'staffs'       => $this->Staff_model->get(),
        ];
        $this->load->view('gestao_corporativa/indicadores/form', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        if ($id) {
            $ind = $this->Indicador_model->get($id);
            if (!$ind || !$this->Indicador_model->pode_editar($ind)) access_denied('Indicadores');
        }
        $new_id = $this->Indicador_model->save($this->input->post(), $id ?: null);
        set_alert('success', 'Indicador salvo.');
        redirect('gestao_corporativa/Indicador/view/' . $new_id);
    }

    public function view($id)
    {
        $ind = $this->Indicador_model->get($id);
        if (!$ind) show_404();
        $data = [
            'title'       => $ind['nome'],
            'indicador'   => $ind,
            'medicoes'    => $this->Indicador_model->get_medicoes($id),
            'pode_editar' => $this->Indicador_model->pode_editar($ind),
            'periodo_atual'=> $this->Indicador_model->periodo_atual($ind['periodicidade']),
        ];
        $this->load->view('gestao_corporativa/indicadores/view', $data);
    }

    public function delete($id)
    {
        $ind = $this->Indicador_model->get($id);
        if (!$ind) show_404();
        if (!$this->Indicador_model->pode_editar($ind)) access_denied('Indicadores');
        $this->Indicador_model->delete($id);
        set_alert('success', 'Indicador excluído.');
        redirect('gestao_corporativa/Indicador');
    }

    public function medicao_save()
    {
        $indicador_id = (int) $this->input->post('indicador_id');
        $ind = $this->Indicador_model->get($indicador_id);
        if (!$ind) { echo json_encode(['ok' => false]); return; }
        if (!$this->Indicador_model->pode_editar($ind)) { echo json_encode(['ok' => false]); return; }

        $ok = $this->Indicador_model->add_medicao([
            'indicador_id'       => $indicador_id,
            'periodo_referencia' => $this->input->post('periodo_referencia'),
            'valor'              => $this->input->post('valor'),
            'observacao'         => $this->input->post('observacao'),
        ]);
        if ($this->input->is_ajax_request()) {
            echo json_encode(['ok' => (bool) $ok]);
            return;
        }
        set_alert('success', 'Medição registrada.');
        redirect('gestao_corporativa/Indicador/view/' . $indicador_id);
    }

    public function medicao_delete($medicao_id)
    {
        $row = $this->db->where('id', (int) $medicao_id)
            ->get('tbl_indicadores_medicoes')->row();
        if (!$row) show_404();
        $ind = $this->Indicador_model->get($row->indicador_id);
        if (!$ind || !$this->Indicador_model->pode_editar($ind)) access_denied('Indicadores');
        $this->Indicador_model->delete_medicao($medicao_id);
        redirect('gestao_corporativa/Indicador/view/' . (int) $row->indicador_id);
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')
            ->from('tblprojects')->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('name', 'asc')->get()->result_array();
    }
}
