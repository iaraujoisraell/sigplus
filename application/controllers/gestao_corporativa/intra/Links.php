<?php

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Links extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Intranet_model');
        $this->load->model('Link_model');
        $this->load->model('Categorias_campos_model');
        $this->load->model('departments_model');
    }

    /* List all leads */

    public function index() {

        $data['title'] = 'Link Externo';

        $id = $_GET['id'];
        if ($id) {
            $data['id'] = $id;
            $data['link'] = $this->Link_model->get_link($id);
            $data['destinos'] = $this->Link_model->get_ways($id, true);
        }
        // CATEGORIAS LINKS EXTERNOS
        $data['categorias'] = $this->Categorias_campos_model->get_categorias('links');
        $data['departments'] = $this->departments_model->get();

        $this->load->view('gestao_corporativa/intranet/links_externos/new_link.php', $data);
    }

    public function salvar() {
        if ($this->input->post()) {
            $id = $this->input->post('id');

            $array['nome'] = $this->input->post('nome');
            $array['url'] = $this->input->post('url');
            $array['categoria_id'] = $this->input->post('categoria_id');
            $array['departments'] = $this->input->post('departments');
//            if ($this->input->post('confirm') == 'img') {
//                $config['upload_path'] = './assets/intranet/img/links/'; //pastaaaaa
//
//                $config['allowed_types'] = '*'; //tipoooos, wanna
//                //
//                //Configurando
//                $tipo = pathinfo($_FILES['arquivo']['name']);
//                $this->load->library('upload', $config);
//                $this->upload->initialize($config);
//                if ($this->upload->do_upload('arquivo')) {
//                    $array['img'] = $_FILES['arquivo']['name'];
//                }
            // } else
            // if ($this->input->post('confirm') == 'icon') {
            $array['icon'] = $this->input->post('icon');
            $array['color'] = $this->input->post('color');
            // }// else {
            // $array['icon'] = 'fa fa-link';
            // }
            //print_r($array); exit;

            if ($array['icon'] == '') {
                $array['icon'] = 'fa fa-link';
            }
            $RESULT = $this->Link_model->add($array, $id);

            if ($RESULT) {
                redirect('gestao_corporativa/intranet_admin/index/?group=links');
            }
        }
    }

    public function delete_link() {
        $id = $_GET['id'];
        $dados['deleted'] = 1;
        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_link', $dados);
        redirect('gestao_corporativa/intranet_admin/index/?group=links');
    }

    public function add_categoria() {

        if ($this->input->post()) {
            //print_r($this->input->post()); exit;
            $id = $this->input->post('id');
            $id = $this->Link_model->add_categoria($this->input->post(), $id);
            //echo $id; exit;
            if ($id) {
                redirect('gestao_corporativa/intranet_admin/index/?group=links');
            }
        }
    }

    public function edit_categoria() {

        if ($this->input->post()) {
            $dados['titulo'] = $this->input->post('titulo');
            $this->db->where('id', $this->input->post('id'));

            $this->db->update('tbl_intranet_link_categoria', $dados);
            redirect('gestao_corporativa/intranet_admin/index/?group=links');
        }
    }

    public function delete_categoria() {
        $id = $_GET['id'];
        $dados['deleted'] = 1;
        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_link_categoria', $dados);
        redirect('gestao_corporativa/intranet_admin/index/?group=links');
    }

}
