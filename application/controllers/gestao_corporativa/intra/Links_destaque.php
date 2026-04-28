<?php

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Links_destaque extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Intranet_model');
        $this->load->model('Categorias_campos_model');
        $this->load->model('Link_destaque_model');
        $this->load->model('departments_model');
    }

    /* List all leads */

    public function index() {

        $data['title'] = 'Link Destaque';

        $id = $_GET['id'];
        if ($id) {
            $data['id'] = $id;
            $data['link'] = $this->Link_destaque_model->get_link($id);

            $data['destinos'] = $this->Link_destaque_model->get_ways($id, true);
        }
        // CATEGORIAS LINKS EXTERNOS
        $data['categorias'] = $this->Categorias_campos_model->get_categorias('links_destaque');
        $data['departments'] = $this->departments_model->get();

        $this->load->view('gestao_corporativa/intranet/links_destaque/new_link.php', $data);
    }

    public function salvar() {
        if ($this->input->post()) {
            $id = $this->input->post('id');

            $array['nome'] = $this->input->post('nome');
            $array['url'] = $this->input->post('url');
            $array['categoria_id'] = $this->input->post('categoria_id');
            $array['departments'] = $this->input->post('departments');
            // if ($this->input->post('confirm') == 'img') {
            //  $config['upload_path'] = './assets/intranet/img/links_destaque/'; //pastaaaaa
            // $config['allowed_types'] = '*'; //tipoooos, wanna
            //
            //Configurando
            // $tipo = pathinfo($_FILES['arquivo']['name']);
            //  $this->load->library('upload', $config);
            // $this->upload->initialize($config);
            // if ($this->upload->do_upload('arquivo')) {
            //     $array['img'] = $_FILES['arquivo']['name'];
            //  }
            // } else 
            // if ($this->input->post('confirm') == 'icon') {
            $array['icon'] = $this->input->post('icon');
            $array['color'] = $this->input->post('color');
            // }// else {
            //   $array['icon'] = 'fa fa-link';
            //}
            if($array['icon'] == '') {
                $array['icon'] = 'fa fa-link';
            }
            $RESULT = $this->Link_destaque_model->add($array, $id);

            if ($RESULT) {
                redirect('gestao_corporativa/intranet_admin/index/?group=links_destaque');
            }
        }
    }

    public function delete_link() {
        $id = $_GET['id'];
        $dados['deleted'] = 1;
        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_link_destaque', $dados);
        redirect('gestao_corporativa/intranet_admin/index/?group=links_destaque');
    }

    public function add_categoria() {

        if ($this->input->post()) {
            $id = $this->input->post('id');
            $id = $this->Link_destaque_model->add_categoria($this->input->post(), $id);
            if ($id) {
                redirect('gestao_corporativa/intranet_admin/index/?group=links_destaque');
            }
        }
    }

    public function edit_categoria() {

        if ($this->input->post()) {
            $dados['titulo'] = $this->input->post('titulo');
            $this->db->where('id', $this->input->post('id'));

            $this->db->update('tbl_intranet_link_destaque_categoria', $dados);
            redirect('gestao_corporativa/intranet_admin/index/?group=links_destaque');
        }
    }

    public function delete_categoria() {
        $id = $_GET['id'];
        $dados['deleted'] = 1;
        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_link_destaque_categoria', $dados);
        redirect('gestao_corporativa/intranet_admin/index/?group=links_destaque');
    }

}
