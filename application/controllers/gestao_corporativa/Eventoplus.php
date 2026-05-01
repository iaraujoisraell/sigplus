<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Eventoplus extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_logged_in()) redirect(base_url('admin/authentication'));
        $this->load->model('Eventoplus_model');
        $this->load->model('Staff_model');
    }

    public function index()
    {
        $filtros = [
            'meu'        => $this->input->get('meu'),
            'futuros'    => $this->input->get('futuros'),
            'busca'      => $this->input->get('busca'),
            'project_id' => $this->input->get('project_id'),
            'grupo_id'   => $this->input->get('grupo_id'),
        ];
        $data = [
            'title'    => 'Eventos',
            'eventos'  => $this->Eventoplus_model->listar($filtros),
            'filtros'  => $filtros,
        ];
        $this->load->view('gestao_corporativa/eventos/list', $data);
    }

    public function add()
    {
        $data = $this->_form_defaults();
        $data['title']   = 'Novo Evento';
        $data['evento']  = null;
        $this->load->view('gestao_corporativa/eventos/form', $data);
    }

    public function edit($id)
    {
        $evento = $this->Eventoplus_model->get($id);
        if (!$evento) show_404();
        if (!$this->Eventoplus_model->pode_editar($evento)) access_denied('Eventos');

        $data = $this->_form_defaults();
        $data['title']  = 'Editar Evento';
        $data['evento'] = $evento;
        $this->load->view('gestao_corporativa/eventos/form', $data);
    }

    public function view($id)
    {
        $evento = $this->Eventoplus_model->get($id);
        if (!$evento) show_404();

        $data = [
            'title'       => 'Evento: ' . $evento['title'],
            'evento'      => $evento,
            'pode_editar' => $this->Eventoplus_model->pode_editar($evento),
        ];
        $this->load->view('gestao_corporativa/eventos/view', $data);
    }

    public function save()
    {
        if (!$this->input->post()) redirect('gestao_corporativa/Eventoplus');

        $id = (int) $this->input->post('id');
        $title = trim((string) $this->input->post('title'));
        if ($title === '') {
            set_alert('danger', 'Título obrigatório.');
            redirect('gestao_corporativa/Eventoplus/' . ($id ? 'edit/' . $id : 'add'));
        }

        if ($id) {
            $existing = $this->Eventoplus_model->get($id);
            if (!$existing || !$this->Eventoplus_model->pode_editar($existing)) access_denied('Eventos');
        }

        $event_id = $this->Eventoplus_model->save($this->input->post(), $id ?: null);
        if (!$event_id) {
            set_alert('danger', 'Falha ao salvar evento.');
            redirect('gestao_corporativa/Eventoplus');
        }

        set_alert('success', $id ? 'Evento atualizado.' : 'Evento criado.');
        redirect('gestao_corporativa/Eventoplus/view/' . $event_id);
    }

    public function delete($id)
    {
        $evento = $this->Eventoplus_model->get($id);
        if (!$evento) show_404();
        if (!$this->Eventoplus_model->pode_editar($evento)) access_denied('Eventos');
        $this->Eventoplus_model->delete($id);
        set_alert('success', 'Evento excluído.');
        redirect('gestao_corporativa/Eventoplus');
    }

    private function _form_defaults()
    {
        $empresa_id = (int) $this->session->userdata('empresa_id');
        $me = (int) get_staff_user_id();

        return [
            'staffs'   => $this->Staff_model->get(),
            'projects' => $this->db->select('id, name')->where('empresa_id', $empresa_id)->order_by('name', 'asc')->get('tblprojects')->result_array(),
            'atas'     => $this->db->select('id, titulo')->where('empresa_id', $empresa_id)->where('deleted', 0)->order_by('id', 'desc')->limit(100)->get('tbl_atas')->result_array(),
            'planos'   => $this->db->select('id, titulo')->where('empresa_id', $empresa_id)->where('deleted', 0)->order_by('id', 'desc')->limit(100)->get('tbl_planos_acao')->result_array(),
            'grupos'   => $this->db->query("SELECT DISTINCT g.id, g.titulo FROM tbl_grupos g
                LEFT JOIN tbl_grupos_membros m ON m.grupo_id = g.id AND m.deleted = 0
                WHERE g.deleted = 0 AND g.empresa_id = $empresa_id
                AND (g.lider_id = $me OR g.user_create = $me OR m.staff_id = $me) ORDER BY g.titulo")->result_array(),
        ];
    }
}
