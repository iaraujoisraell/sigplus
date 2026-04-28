<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Escala_fixa extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Unidades_hospitalares_model');
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
        if (!has_permission('customers', '', 'view')) {
            if (!have_assigned_customers() && !has_permission('customers', '', 'create')) {
                access_denied('customers');
            }
        }

         $data['title']          = _l('medicos');

       
        $this->load->view('admin/medicos/manage', $data);
    }

    public function table()
    {
        if (!has_permission('customers', '', 'view')) {
            if (!have_assigned_customers() && !has_permission('customers', '', 'create')) {
                ajax_access_denied();
            }
        }

        $this->app->get_table_data('medicos');
    }

    
    
    public function add_medicos_horarios_disponiveis()
    {
        
      if ($this->input->post() && has_permission('items', '', 'create')) {
          $dados_post = $this->input->post();
            $unidade_id = $dados_post['unidade_id'];
            
            // validações
            // 1 - VERIFICAR SE O NÚMERO DE MÉDICO INFORMADO É O PEREMITIDO
            // 2 - VERIFICAR SE O MÉDICO JÁ N ESTÁ EM OUTRA ESCALA NO MESMO HORÁRIO/ DIA
            
           $this->Unidades_hospitalares_model->add_medico_escala_fixa($dados_post);
           
            set_alert('success', _l('added_successfully', 'Cadastros'));
            redirect(admin_url("unidades_hospitalares/unidades_hospitalares/$unidade_id?group=configuracao"));
        }
        
      }
    
}
