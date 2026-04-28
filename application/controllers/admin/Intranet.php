<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Intranet extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('New_model');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Registro_ocorrencia_model');
    }

    public function change_status_ajax_registro($id, $status)
    {
        
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->Registro_ocorrencia_model->change_ro_status($id, $status));
        }
    }
    public function change_validade_ajax_registro($id, $validade)
    {
        
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->Registro_ocorrencia_model->change_ro_validade($id, $validade));
        }
    }
    

}
