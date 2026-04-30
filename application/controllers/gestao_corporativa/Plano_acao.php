<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Plano_acao extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) {
            redirect(base_url('admin/authentication'));
        }
        $this->load->model('Plano_acao_model');
        $this->load->model('Ata_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
    }

    public function index()
    {
        $filtros = [
            'project_id' => $this->input->get('project_id'),
            'ata_id'     => $this->input->get('ata_id'),
            'status'     => $this->input->get('status'),
            'minha'      => $this->input->get('minha'),
            'busca'      => $this->input->get('busca'),
        ];
        $data = [
            'title'   => 'Planos de Ação',
            'planos'  => $this->Plano_acao_model->listar($filtros),
            'filtros' => $filtros,
        ];
        $this->load->view('gestao_corporativa/planos_acao/list', $data);
    }

    public function add()
    {
        if (!has_permission_intranet('planos_acao', '', 'create') && !is_admin()) {
            access_denied('Planos de Ação');
        }

        $project_id = $this->input->get('project_id');
        $data = [
            'title'      => 'Novo Plano de Ação',
            'plano'      => null,
            'itens_5w2h' => [],
            'ishikawa'   => null,
            'project_id' => $project_id,
            'ata_id'     => $this->input->get('ata_id'),
            'staffs'     => $this->Staff_model->get(),
            'projects'   => $this->_get_projects(),
            'atas'       => $this->_get_atas($project_id),
        ];
        $this->load->view('gestao_corporativa/planos_acao/form', $data);
    }

    public function edit($id)
    {
        if (!has_permission_intranet('planos_acao', '', 'edit') && !is_admin()) {
            access_denied('Planos de Ação');
        }
        $plano = $this->Plano_acao_model->get($id);
        if (!$plano) show_404();

        $data = [
            'title'      => 'Editar Plano de Ação',
            'plano'      => $plano,
            'itens_5w2h' => $this->Plano_acao_model->get_5w2h($id),
            'ishikawa'   => $this->Plano_acao_model->get_ishikawa($id),
            'project_id' => $plano['project_id'],
            'ata_id'     => $plano['ata_id'],
            'staffs'     => $this->Staff_model->get(),
            'projects'   => $this->_get_projects(),
            'atas'       => $this->_get_atas($plano['project_id']),
        ];
        $this->load->view('gestao_corporativa/planos_acao/form', $data);
    }

    public function view($id)
    {
        $plano = $this->Plano_acao_model->get($id);
        if (!$plano) show_404();

        $data = [
            'title'      => 'Plano: ' . $plano['titulo'],
            'plano'      => $plano,
            'itens_5w2h' => $this->Plano_acao_model->get_5w2h($id),
            'ishikawa'   => $this->Plano_acao_model->get_ishikawa($id),
        ];
        $this->load->view('gestao_corporativa/planos_acao/view', $data);
    }

    public function save()
    {
        if (!$this->input->post()) {
            redirect('gestao_corporativa/Plano_acao');
        }

        $id = (int) $this->input->post('id');
        if ($id) {
            if (!has_permission_intranet('planos_acao', '', 'edit') && !is_admin()) access_denied('Planos de Ação');
        } else {
            if (!has_permission_intranet('planos_acao', '', 'create') && !is_admin()) access_denied('Planos de Ação');
        }

        $titulo = trim((string) $this->input->post('titulo'));
        if ($titulo === '') {
            set_alert('danger', 'Título obrigatório.');
            redirect('gestao_corporativa/Plano_acao/' . ($id ? 'edit/' . $id : 'add'));
        }

        $plano_id = $this->Plano_acao_model->save($this->input->post(), $id ?: null);
        if (!$plano_id) {
            set_alert('danger', 'Falha ao salvar plano.');
            redirect('gestao_corporativa/Plano_acao');
        }

        $project_id = (int) $this->input->post('project_id') ?: null;
        $metodologia = $this->input->post('metodologia') ?: '5w2h';

        if (in_array($metodologia, ['5w2h', 'ambos'], true)) {
            $this->Plano_acao_model->save_5w2h($plano_id, $this->input->post('itens_5w2h') ?: [], $project_id, true);
        }
        if (in_array($metodologia, ['ishikawa', 'ambos'], true)) {
            $this->Plano_acao_model->save_ishikawa($plano_id, $this->input->post('ishikawa') ?: []);
        }

        set_alert('success', $id ? 'Plano atualizado.' : 'Plano criado.');
        redirect('gestao_corporativa/Plano_acao/view/' . $plano_id);
    }

    public function delete($id)
    {
        if (!has_permission_intranet('planos_acao', '', 'delete') && !is_admin()) access_denied('Planos de Ação');
        $this->Plano_acao_model->delete($id);
        set_alert('success', 'Plano excluído.');
        redirect('gestao_corporativa/Plano_acao');
    }

    public function gerar_task_5w2h($item_id)
    {
        if (!has_permission_intranet('planos_acao', '', 'edit') && !is_admin()) access_denied('Planos de Ação');
        $task_id = $this->Plano_acao_model->gerar_task_5w2h($item_id);
        echo json_encode(['ok' => (bool) $task_id, 'task_id' => $task_id]);
    }

    public function atas_por_projeto($project_id)
    {
        $atas = $this->_get_atas($project_id);
        echo json_encode($atas);
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')
            ->where('empresa_id', $empresa_id)
            ->order_by('name', 'asc')
            ->get('tblprojects')->result_array();
    }

    private function _get_atas($project_id)
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $this->db->select('id, titulo, data')
            ->where('empresa_id', $empresa_id)
            ->where('deleted', 0);
        if ($project_id) $this->db->where('project_id', $project_id);
        $this->db->order_by('id', 'desc')->limit(100);
        return $this->db->get('tbl_atas')->result_array();
    }
}
