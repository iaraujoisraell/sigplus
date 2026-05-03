<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Treinamento extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Treinamento_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
        $this->load->model('Projeto_fase_model');
        $this->load->model('Documentos_model');
        $this->load->model('Plano_acao_model');
        $this->load->model('Workgroup_model');
    }

    public function index()
    {
        $filtros = [
            'project_id'      => $this->input->get('project_id'),
            'plano_id'        => $this->input->get('plano_id'),
            'grupo_id'        => $this->input->get('grupo_id'),
            'documento_id'    => $this->input->get('documento_id'),
            'status'          => $this->input->get('status'),
            'modalidade'      => $this->input->get('modalidade'),
            'criei'           => $this->input->get('criei'),
            'responsavel_meu' => $this->input->get('responsavel_meu'),
            'inscrito_eu'     => $this->input->get('inscrito_eu'),
            'busca'           => $this->input->get('busca'),
        ];
        $data = [
            'title'        => 'Treinamentos',
            'treinamentos' => $this->Treinamento_model->listar($filtros),
            'filtros'      => $filtros,
            'projects'     => $this->_get_projects(),
        ];
        $this->load->view('gestao_corporativa/treinamentos/list', $data);
    }

    public function add()
    {
        $data = [
            'title'         => 'Novo treinamento',
            'treinamento'   => null,
            'participantes' => [],
            'staffs'        => $this->Staff_model->get(),
            'projects'      => $this->_get_projects(),
            'documentos'    => $this->Documentos_model->listar(['publicado' => 1]),
            'planos'        => $this->Plano_acao_model->listar([]),
            'grupos'        => $this->Workgroup_model->listar([]),
            'preencher'     => [
                'project_id'  => $this->input->get('project_id'),
                'plano_id'    => $this->input->get('plano_id'),
                'grupo_id'    => $this->input->get('grupo_id'),
                'documento_id'=> $this->input->get('documento_id'),
            ],
        ];
        $this->load->view('gestao_corporativa/treinamentos/form', $data);
    }

    public function edit($id)
    {
        $t = $this->Treinamento_model->get($id);
        if (!$t) show_404();
        if (!$this->Treinamento_model->pode_editar($t)) access_denied('Treinamentos');

        $data = [
            'title'         => 'Editar treinamento',
            'treinamento'   => $t,
            'participantes' => $this->Treinamento_model->get_participantes($id),
            'staffs'        => $this->Staff_model->get(),
            'projects'      => $this->_get_projects(),
            'documentos'    => $this->Documentos_model->listar([]),
            'planos'        => $this->Plano_acao_model->listar([]),
            'grupos'        => $this->Workgroup_model->listar([]),
            'preencher'     => [],
        ];
        $this->load->view('gestao_corporativa/treinamentos/form', $data);
    }

    public function view($id)
    {
        $t = $this->Treinamento_model->get($id);
        if (!$t) show_404();
        $data = [
            'title'         => $t['titulo'],
            'treinamento'   => $t,
            'participantes' => $this->Treinamento_model->get_participantes($id),
            'pode_editar'   => $this->Treinamento_model->pode_editar($t),
        ];
        $this->load->view('gestao_corporativa/treinamentos/view', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        if ($id) {
            $t = $this->Treinamento_model->get($id);
            if (!$t || !$this->Treinamento_model->pode_editar($t)) access_denied('Treinamentos');
        }
        $new_id = $this->Treinamento_model->save($this->input->post(), $id ?: null);
        if ($new_id) {
            $this->Treinamento_model->set_participantes($new_id, $this->input->post('participantes') ?: []);
        }
        set_alert('success', 'Treinamento salvo.');
        redirect('gestao_corporativa/Treinamento/view/' . $new_id);
    }

    public function delete($id)
    {
        $t = $this->Treinamento_model->get($id);
        if (!$t) show_404();
        if (!$this->Treinamento_model->pode_editar($t)) access_denied('Treinamentos');
        $this->Treinamento_model->delete($id);
        set_alert('success', 'Treinamento excluído.');
        redirect('gestao_corporativa/Treinamento');
    }

    public function presenca($participante_id)
    {
        $row = $this->db->where('id', (int) $participante_id)->get('tbl_treinamentos_participantes')->row();
        if (!$row) show_404();
        $t = $this->Treinamento_model->get($row->treinamento_id);
        if (!$t || !$this->Treinamento_model->pode_editar($t)) access_denied('Treinamentos');
        $presente = $this->input->get('presente') === '1' || $this->input->get('presente') === null && (int) $row->status_inscricao !== 'presente';
        $this->Treinamento_model->marcar_presenca($participante_id, $presente);
        if ($this->input->is_ajax_request()) { echo json_encode(['ok' => true]); return; }
        redirect('gestao_corporativa/Treinamento/view/' . (int) $row->treinamento_id);
    }

    public function nota($participante_id)
    {
        $row = $this->db->where('id', (int) $participante_id)->get('tbl_treinamentos_participantes')->row();
        if (!$row) show_404();
        $t = $this->Treinamento_model->get($row->treinamento_id);
        if (!$t || !$this->Treinamento_model->pode_editar($t)) access_denied('Treinamentos');
        $nota = $this->input->post('nota');
        $aprovado = null;
        if ($t['nota_minima'] !== null && $nota !== '') {
            $aprovado = ((float) str_replace(',', '.', $nota)) >= (float) $t['nota_minima'];
        }
        $this->Treinamento_model->lancar_nota($participante_id, $nota, $aprovado);
        redirect('gestao_corporativa/Treinamento/view/' . (int) $row->treinamento_id);
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')->from('tblprojects')
            ->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('name', 'asc')->get()->result_array();
    }
}
