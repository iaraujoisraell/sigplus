<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Projeto_fase extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Projeto_fase_model');
    }

    public function tree_data($project_id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->Projeto_fase_model->tree_data((int) $project_id));
    }

    public function list_options($project_id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->Projeto_fase_model->list_options((int) $project_id));
    }

    public function save()
    {
        $id  = (int) $this->input->post('id');
        $pid = (int) $this->input->post('project_id');
        if (!$pid) { echo json_encode(['ok' => false]); return; }

        $data = $this->input->post();
        $new_id = $this->Projeto_fase_model->save($data, $id ?: null);
        echo json_encode(['ok' => (bool) $new_id, 'id' => $new_id]);
    }

    public function delete($id)
    {
        $n = $this->Projeto_fase_model->delete($id);
        echo json_encode(['ok' => true, 'deleted' => $n]);
    }

    public function reorder()
    {
        $id        = (int) $this->input->post('id');
        $parent_id = $this->input->post('parent_id') ? (int) $this->input->post('parent_id') : null;
        $position  = (int) $this->input->post('position');
        $ok = $this->Projeto_fase_model->reorder($id, $parent_id, $position);
        echo json_encode(['ok' => (bool) $ok]);
    }
}
