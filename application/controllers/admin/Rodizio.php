<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rodizio extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('announcements_model');

    }

     public function index()
    {
        // header('Location: /sigplus.site');
         redirect(''); 
        //$this->load->view('admin/gestao_escala/configuracao_rotina/manage', $data);
    }
    
    
  
    
}
