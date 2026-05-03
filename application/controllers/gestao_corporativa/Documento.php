<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Documento extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Documentos_model');
        $this->load->model('Staff_model');
        $this->load->model('projects_model');
        $this->load->model('Projeto_fase_model');
        $this->load->model('Departments_model');
        $this->load->library('upload');
    }

    public function index()
    {
        $filtros = [
            'categoria_id'    => $this->input->get('categoria_id'),
            'project_id'      => $this->input->get('project_id'),
            'fase_id'         => $this->input->get('fase_id'),
            'ata_id'          => $this->input->get('ata_id'),
            'plano_id'        => $this->input->get('plano_id'),
            'grupo_id'        => $this->input->get('grupo_id'),
            'publicado'       => $this->input->get('publicado'),
            'meu'             => $this->input->get('meu'),
            'criei'           => $this->input->get('criei'),
            'responsavel_meu' => $this->input->get('responsavel_meu'),
            'busca'           => $this->input->get('busca'),
        ];
        $data = [
            'title'       => 'Documentos',
            'documentos'  => $this->Documentos_model->listar($filtros),
            'filtros'     => $filtros,
            'projects'    => $this->_get_projects(),
            'categorias'  => $this->Documentos_model->get_categorias(),
        ];
        $this->load->view('gestao_corporativa/documentos/list', $data);
    }

    public function add()
    {
        $doc_id = $this->Documentos_model->save([
            'titulo'     => 'Documento sem título',
            'project_id' => $this->input->get('project_id'),
            'fase_id'    => $this->input->get('fase_id'),
            'ata_id'     => $this->input->get('ata_id'),
            'plano_id'   => $this->input->get('plano_id'),
            'grupo_id'   => $this->input->get('grupo_id'),
        ]);
        redirect('gestao_corporativa/Documento/edit/' . $doc_id);
    }

    public function edit($id)
    {
        $doc = $this->Documentos_model->get($id);
        if (!$doc) show_404();
        if (!$this->Documentos_model->pode_editar($doc)) access_denied('Documentos');

        $data = [
            'title'         => 'Editar documento',
            'doc'           => $doc,
            'aprovadores'   => $this->Documentos_model->get_aprovadores($id),
            'destinatarios' => $this->Documentos_model->get_destinatarios($id),
            'staffs'        => $this->Staff_model->get(),
            'projects'      => $this->_get_projects(),
            'categorias'    => $this->Documentos_model->get_categorias(),
            'departments'   => $this->Departments_model->get(),
        ];
        $this->load->view('gestao_corporativa/documentos/form', $data);
    }

    public function view($id)
    {
        $doc = $this->Documentos_model->get($id);
        if (!$doc) show_404();
        if (!$this->Documentos_model->pode_visualizar($doc)) access_denied('Documentos');

        $data = [
            'title'           => $doc['titulo'],
            'doc'             => $doc,
            'aprovadores'     => $this->Documentos_model->get_aprovadores($id),
            'destinatarios'   => $this->Documentos_model->get_destinatarios($id),
            'observacoes'     => $this->Documentos_model->get_observacoes($id),
            'aprovador_atual' => $this->Documentos_model->get_aprovador_atual($id),
            'pode_editar'     => $this->Documentos_model->pode_editar($doc),
        ];
        $this->load->view('gestao_corporativa/documentos/view', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        $doc = $id ? $this->Documentos_model->get($id) : null;
        if ($id && (!$doc || !$this->Documentos_model->pode_editar($doc))) {
            access_denied('Documentos');
        }

        // upload de arquivo
        if (!empty($_FILES['file']['name'])) {
            $up_path = FCPATH . 'uploads/documentos/';
            if (!is_dir($up_path)) @mkdir($up_path, 0755, true);
            $this->upload->initialize([
                'upload_path'   => $up_path,
                'allowed_types' => 'pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png',
                'max_size'      => 51200,
                'encrypt_name'  => true,
            ]);
            if ($this->upload->do_upload('file')) {
                $info = $this->upload->data();
                $_POST['file'] = $info['file_name'];
            } else {
                set_alert('warning', 'Falha no upload: ' . $this->upload->display_errors('', ''));
            }
        }

        $new_id = $this->Documentos_model->save($this->input->post(), $id ?: null);

        if ($new_id) {
            $aprovadores = $this->input->post('aprovadores') ?: [];
            $destinatarios = $this->input->post('destinatarios') ?: [];
            $this->Documentos_model->set_aprovadores($new_id, $aprovadores);
            $this->Documentos_model->set_destinatarios($new_id, $destinatarios);
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode(['ok' => (bool) $new_id, 'id' => $new_id]);
            return;
        }
        set_alert('success', 'Documento salvo.');
        redirect('gestao_corporativa/Documento/edit/' . $new_id);
    }

    public function delete($id)
    {
        $doc = $this->Documentos_model->get($id);
        if (!$doc) show_404();
        if (!$this->Documentos_model->pode_editar($doc)) access_denied('Documentos');
        $this->Documentos_model->delete($id);
        set_alert('success', 'Documento excluído.');
        redirect('gestao_corporativa/Documento');
    }

    public function publicar($id)
    {
        $doc = $this->Documentos_model->get($id);
        if (!$doc) show_404();
        if (!$this->Documentos_model->pode_editar($doc)) access_denied('Documentos');
        // se tem aprovadores, não pode pular o fluxo
        $apvs = $this->Documentos_model->get_aprovadores($id);
        $pendentes = array_filter($apvs, function ($a) { return (int) $a['status'] === 0; });
        if (!empty($apvs) && !empty($pendentes)) {
            set_alert('warning', 'Existem aprovações pendentes — publique pelo fluxo de aprovação.');
            redirect('gestao_corporativa/Documento/view/' . $id);
            return;
        }
        $this->Documentos_model->publicar($id);
        set_alert('success', 'Documento publicado.');
        redirect('gestao_corporativa/Documento/view/' . $id);
    }

    public function aprovar($id)
    {
        $doc = $this->Documentos_model->get($id);
        if (!$doc) show_404();
        $obs = (string) $this->input->post('observacao');
        $ok = $this->Documentos_model->aprovar($id, get_staff_user_id(), $obs);
        if ($ok) {
            $this->Documentos_model->add_observacao($id, '✓ Aprovado: ' . $obs);
            set_alert('success', 'Aprovação registrada.');
        } else {
            set_alert('warning', 'Não foi possível aprovar — não é a sua vez ou já aprovou.');
        }
        redirect('gestao_corporativa/Documento/view/' . $id);
    }

    public function reprovar($id)
    {
        $doc = $this->Documentos_model->get($id);
        if (!$doc) show_404();
        $obs = (string) $this->input->post('observacao');
        $ok = $this->Documentos_model->reprovar($id, get_staff_user_id(), $obs);
        if ($ok) {
            $this->Documentos_model->add_observacao($id, '✗ Reprovado: ' . $obs);
            set_alert('success', 'Reprovação registrada.');
        }
        redirect('gestao_corporativa/Documento/view/' . $id);
    }

    public function ciente($id)
    {
        $this->Documentos_model->marcar_ciente($id, get_staff_user_id());
        set_alert('success', 'Marcado como lido.');
        redirect('gestao_corporativa/Documento/view/' . $id);
    }

    public function add_observacao($id)
    {
        $doc = $this->Documentos_model->get($id);
        if (!$doc) show_404();
        $obs = trim((string) $this->input->post('observacao'));
        if ($obs !== '') $this->Documentos_model->add_observacao($id, $obs);
        redirect('gestao_corporativa/Documento/view/' . $id);
    }

    public function download($id)
    {
        $doc = $this->Documentos_model->get($id);
        if (!$doc) show_404();
        if (!$this->Documentos_model->pode_visualizar($doc)) access_denied('Documentos');
        if (empty($doc['file'])) show_404();

        $path = FCPATH . 'uploads/documentos/' . $doc['file'];
        if (!is_file($path)) show_404();
        $this->load->helper('download');
        force_download($doc['file'], file_get_contents($path));
    }

    private function _get_projects()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        return $this->db->select('id, name')
            ->from('tblprojects')->where('empresa_id', $empresa_id)->where('deleted', 0)
            ->order_by('name', 'asc')->get()->result_array();
    }
}
