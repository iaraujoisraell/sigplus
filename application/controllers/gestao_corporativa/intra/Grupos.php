<?php

header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

class Grupos extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Intranet_model');
        $this->load->model('Grupo_model');
        $this->load->model('departments_model');
    }

    /* List all leads */

    public function index() {
        //$staffs = $this->Intranet_model->get_bday()->result();
        //$view_data["staffs"] = $staffs;

        $departments_staffs = $this->Intranet_model->get_departamentos_staffs_selecionados();
        // CATEGORIAS LINKS EXTERNOS
        $id = $_GET['id'];
        if($id){
            $grupo = $this->Grupo_model->get_grupo($id)->row();
            $staffs = $this->Grupo_model->get_grupo_staffs($id);
            //print_r($staffs); exit;
            for($i = 0; $i < count($staffs); $i++){
                $array[$i] = $staffs[$i]['staffid'];
            }
            //print_r($array); exit;
            $data['grupo'] = $grupo;
            $data['staffs'] = $array;
        }
        $data['departments_staffs'] = $departments_staffs;
        $this->load->view('gestao_corporativa/intranet/grupos/index.php', $data);
    }

    public function salvar() {
        if ($this->input->post()) {
            $id = $this->input->post('id');

            $array['nome'] = $this->input->post('nome');
            $staffs = $this->input->post('for_staffs');
            if($staffs){
            for($i = 0; $i < count($staffs); $i++){
                $staff_origem = explode('-', $staffs[$i]);
                $staffs[$i] = $staff_origem;
            }
            }
            $array['staff'] = $staffs;
            $RESULT = $this->Grupo_model->add($array, $id);

            if ($RESULT) {
                redirect('gestao_corporativa/intranet_admin/index/?group=grupos');
            }
        }
    }

    public function delete_grupo() {
        $id = $_GET['id'];
        $dados['deleted'] = 1;
        $this->db->where('id', $id);

        $this->db->update('tbl_intranet_grupo', $dados);
        redirect('gestao_corporativa/intranet_admin/index/?group=grupos');
    }


}
