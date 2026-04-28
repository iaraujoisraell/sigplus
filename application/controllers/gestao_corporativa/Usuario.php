<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Usuario extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Usuario_model');
        $this->load->helper(array('form', 'url'));
    }

    public function index() {

        //$this->load->view('gestao_corporativa/intranet/index.php', $view_data);
    }
    
    public function contatos() {

        
        $this->load->view('gestao_corporativa/intranet/contatos.php', $view_data);
    }
    
}
