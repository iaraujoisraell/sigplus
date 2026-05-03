<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Formulario extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Formulario_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
        $this->load->model('Projeto_fase_model');
    }

    public function index()
    {
        $filtros = [
            'project_id' => $this->input->get('project_id'),
            'fase_id'    => $this->input->get('fase_id'),
            'ata_id'     => $this->input->get('ata_id'),
            'plano_id'   => $this->input->get('plano_id'),
            'grupo_id'   => $this->input->get('grupo_id'),
            'status'     => $this->input->get('status'),
            'meu'        => $this->input->get('meu'),
            'busca'      => $this->input->get('busca'),
        ];
        $data = [
            'title'        => 'Formulários',
            'formularios'  => $this->Formulario_model->listar($filtros),
            'filtros'      => $filtros,
            'projects'     => $this->_get_projects(),
        ];
        $this->load->view('gestao_corporativa/formularios/list', $data);
    }

    public function add()
    {
        $form_id = $this->Formulario_model->save([
            'titulo'      => 'Formulário sem título',
            'project_id'  => $this->input->get('project_id'),
            'fase_id'     => $this->input->get('fase_id'),
            'ata_id'      => $this->input->get('ata_id'),
            'plano_id'    => $this->input->get('plano_id'),
            'grupo_id'    => $this->input->get('grupo_id'),
            'status'      => 'rascunho',
        ]);
        redirect('gestao_corporativa/Formulario/edit/' . $form_id);
    }

    public function edit($id)
    {
        $form = $this->Formulario_model->get($id);
        if (!$form) show_404();
        if (!$this->Formulario_model->pode_editar($form)) access_denied('Formulários');

        $data = [
            'title'        => 'Editar formulário',
            'form'         => $form,
            'perguntas'    => $this->Formulario_model->get_perguntas($id),
            'staffs'       => $this->Staff_model->get(),
            'projects'     => $this->_get_projects(),
            'tipos'        => $this->Formulario_model->get_tipos(),
            'statuses'     => $this->Formulario_model->get_statuses(),
        ];
        $this->load->view('gestao_corporativa/formularios/form', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        $new_id = $this->Formulario_model->save($this->input->post(), $id ?: null);
        echo json_encode(['ok' => (bool) $new_id, 'id' => $new_id]);
    }

    public function delete($id)
    {
        $form = $this->Formulario_model->get($id);
        if (!$form) show_404();
        if (!$this->Formulario_model->pode_editar($form)) access_denied('Formulários');
        $this->Formulario_model->delete($id);
        set_alert('success', 'Formulário excluído.');
        redirect('gestao_corporativa/Formulario');
    }

    public function duplicar($id)
    {
        $form = $this->Formulario_model->get($id);
        if (!$form) show_404();
        $new_id = $this->Formulario_model->duplicar($id);
        if ($new_id) {
            set_alert('success', 'Formulário duplicado.');
            redirect('gestao_corporativa/Formulario/edit/' . $new_id);
        }
        redirect('gestao_corporativa/Formulario');
    }

    /* ---------- Perguntas (AJAX) ---------- */

    public function pergunta_add()
    {
        $data = [
            'form_id' => (int) $this->input->post('form_id'),
            'tipo'    => $this->input->post('tipo') ?: 'text',
            'title'   => $this->input->post('title') ?: 'Pergunta sem título',
            'pagina'  => (int) $this->input->post('pagina') ?: 1,
        ];
        $form = $this->Formulario_model->get($data['form_id']);
        if (!$form || !$this->Formulario_model->pode_editar($form)) {
            echo json_encode(['ok' => false]); return;
        }
        $pid = $this->Formulario_model->save_pergunta($data);
        echo json_encode([
            'ok'       => (bool) $pid,
            'pergunta' => $pid ? $this->_get_pergunta($pid) : null,
        ]);
    }

    public function pergunta_save()
    {
        $id = (int) $this->input->post('id');
        if (!$id) { echo json_encode(['ok' => false]); return; }

        $pergunta = $this->db->where('id', $id)->get('tbl_intranet_formularios_perguntas')->row_array();
        if (!$pergunta) { echo json_encode(['ok' => false]); return; }
        $form = $this->Formulario_model->get($pergunta['form_id']);
        if (!$form || !$this->Formulario_model->pode_editar($form)) {
            echo json_encode(['ok' => false]); return;
        }

        $config = [];
        if ($this->input->post('placeholder')) $config['placeholder'] = $this->input->post('placeholder');

        $this->Formulario_model->save_pergunta([
            'title'        => $this->input->post('title'),
            'descricao'    => $this->input->post('descricao'),
            'tipo'         => $this->input->post('tipo'),
            'required'     => $this->input->post('required'),
            'pagina'       => (int) $this->input->post('pagina'),
            'configuracao' => $config,
        ], $id);
        echo json_encode(['ok' => true, 'pergunta' => $this->_get_pergunta($id)]);
    }

    public function pergunta_delete()
    {
        $id = (int) $this->input->post('id');
        $pergunta = $this->db->where('id', $id)->get('tbl_intranet_formularios_perguntas')->row_array();
        if (!$pergunta) { echo json_encode(['ok' => false]); return; }
        $form = $this->Formulario_model->get($pergunta['form_id']);
        if (!$form || !$this->Formulario_model->pode_editar($form)) {
            echo json_encode(['ok' => false]); return;
        }
        $this->Formulario_model->delete_pergunta($id);
        echo json_encode(['ok' => true]);
    }

    public function pergunta_reorder()
    {
        $form_id = (int) $this->input->post('form_id');
        $form = $this->Formulario_model->get($form_id);
        if (!$form || !$this->Formulario_model->pode_editar($form)) {
            echo json_encode(['ok' => false]); return;
        }
        $ordem = $this->input->post('ordem') ?: [];
        $this->Formulario_model->reorder_perguntas($form_id, $ordem);
        echo json_encode(['ok' => true]);
    }

    /* ---------- Opções (AJAX) ---------- */

    public function opcao_add()
    {
        $perg_id = (int) $this->input->post('pergunta_id');
        $name = $this->input->post('name');
        $pergunta = $this->db->where('id', $perg_id)->get('tbl_intranet_formularios_perguntas')->row_array();
        if (!$pergunta) { echo json_encode(['ok' => false]); return; }
        $form = $this->Formulario_model->get($pergunta['form_id']);
        if (!$form || !$this->Formulario_model->pode_editar($form)) {
            echo json_encode(['ok' => false]); return;
        }
        $oid = $this->Formulario_model->add_opcao($perg_id, $name);
        echo json_encode(['ok' => (bool) $oid, 'id' => $oid, 'name' => trim($name)]);
    }

    public function opcao_save()
    {
        $id = (int) $this->input->post('id');
        $name = $this->input->post('name');
        $this->Formulario_model->update_opcao($id, $name);
        echo json_encode(['ok' => true]);
    }

    public function opcao_delete()
    {
        $id = (int) $this->input->post('id');
        $this->Formulario_model->delete_opcao($id);
        echo json_encode(['ok' => true]);
    }

    /* ---------- Respostas ---------- */

    public function respostas($id)
    {
        $form = $this->Formulario_model->get($id);
        if (!$form) show_404();
        if (!$this->Formulario_model->pode_editar($form)) access_denied('Formulários');

        $data = [
            'title'      => 'Respostas — ' . $form['titulo'],
            'form'       => $form,
            'perguntas'  => $this->Formulario_model->get_perguntas($id),
            'respostas'  => $this->Formulario_model->get_respostas_agrupadas($id),
        ];
        $this->load->view('gestao_corporativa/formularios/respostas', $data);
    }

    public function exportar_csv($id)
    {
        $form = $this->Formulario_model->get($id);
        if (!$form) show_404();
        if (!$this->Formulario_model->pode_editar($form)) access_denied('Formulários');

        $perguntas = $this->Formulario_model->get_perguntas($id);
        $respostas = $this->Formulario_model->get_respostas_agrupadas($id);

        $filename = 'respostas_' . preg_replace('/[^a-z0-9]+/i', '_', $form['titulo']) . '_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // BOM utf-8

        $headers = ['Data', 'Quem respondeu', 'IP'];
        foreach ($perguntas as $p) $headers[] = $p['title'];
        fputcsv($out, $headers, ';');

        foreach ($respostas as $g) {
            $row = [$g['data_cadastro'], $g['staff_nome'] ?: '—', $g['ip']];
            foreach ($perguntas as $p) {
                $row[] = $g['respostas'][(int) $p['id']] ?? '';
            }
            fputcsv($out, $row, ';');
        }
        fclose($out);
        exit;
    }

    /* ---------- Helpers ---------- */

    private function _get_pergunta($id)
    {
        $row = $this->db->where('id', $id)->get('tbl_intranet_formularios_perguntas')->row_array();
        if (!$row) return null;
        $row['opcoes'] = $this->db->where('pergunta_id', $id)->where('deleted', 0)
            ->order_by('ordem', 'asc')->get('tbl_intranet_formularios_items_multiescolha')->result_array();
        $row['configuracao_arr'] = !empty($row['configuracao']) ? json_decode($row['configuracao'], true) : [];
        return $row;
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')
            ->from('tblprojects')
            ->where('empresa_id', $empresa_id)
            ->where('deleted', 0)
            ->order_by('name', 'asc')
            ->get()->result_array();
    }
}
