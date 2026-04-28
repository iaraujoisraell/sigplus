<?php


header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @Wanna Luiza
 * 12/08/2022
 * Controller - Eventos->Intranet
 */
class Eventos extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Intranet_model');
        $this->load->model('Evento_model');
    }


    public function index() {
        echo 'index';
        exit;
    }
    
    function add_data() {


        $info = array(

            "titulo" => $this->input->post('titulo'),

            "inicio" => $this->input->post('inicio'),

            "fim" => $this->input->post('fim'),

            "cor" => $_POST['color'],

            "dt_created" => date('Y-m-d'),

            "user_create" => get_staff_user_id(),

            "empresa_id" => $this->session->userdata('empresa_id'),

        );

        if ($this->input->post('id')) {

            $this->db->where('id', $this->input->post('id'));

            if ($this->db->update("tbl_intranet_eventos", $info)) {

                redirect('gestao_corporativa/intranet_admin/index/?group=eventos');

            }

        } else {

            if ($this->db->insert("tbl_intranet_eventos", $info)) {

                redirect('gestao_corporativa/intranet_admin/index/?group=eventos');

            }

        }

    }



    public function delete_data() {

        $id = $_GET['id'];

        $dados['deleted'] = 1;

        $this->db->where('id', $id);



        $this->db->update('tbl_intranet_eventos', $dados);

        redirect('gestao_corporativa/intranet_admin/index/?group=eventos');

    }

}
