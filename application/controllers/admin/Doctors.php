<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Doctors extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Doctors_model');
        $this->load->model('credit_notes_model');
        $this->load->model('Medicos_model');
        $this->load->model('Convenios_model');
        $this->load->model('Clients_model');
        $this->load->model('invoice_items_model');
        $this->load->model('appointly_model');
        $this->load->model('Centro_custo_model');
        $this->load->model('Indicadores_model'); 
    }
    
    /* List all clients */
     public function index()
    {
         
        $data['convenios']             = $this->Doctors_model->get_convenios();
        
        $data['medicos']               = $this->Doctors_model->get_medicos();
       
        $data['tipos_atendimento']     = $this->Doctors_model->get_tipo_atendimento();
        
        
        $data['anos_data']         = $this->Doctors_model->get_ano_data_visita();
       
        $data['title'] = 'Busca Ativa - Doctors';
        $this->load->view('admin/doctors/manage', $data);
    }

   public function table()
    {
       
       
        $this->app->get_table_data('busca_ativa');
    }

  
}
