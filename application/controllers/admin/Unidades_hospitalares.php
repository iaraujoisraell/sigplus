<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Unidades_hospitalares extends AdminController
{

   private $not_importable_fields = ['id'];
  
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Unidades_hospitalares_model');  
        $this->load->model('invoice_items_model');
        $this->load->model('Horario_model');
        $this->load->model('Medicos_model');
        //$this->load->model('Contas_financeiras_model');
    }

    /* List all available items */
    public function index()
    {
         
        //if (!has_permission('cadastro_menu', '', 'cadastro_unidades')) {
          //  access_denied('Unidades Hospitalares');
        //}
        
 
       // exib todos os setores 
        
       $data['setores_medicos'] = $this->Unidades_hospitalares_model->get_setores();
      // echo 'aqui'; exit;
       // exib todos os setores 
       $data['title'] = _l('unidade_hospitalar');//ok
       $this->load->view('admin/unidade_hospitalar/manage', $data);//ok
       //echo 'aki 222gg';
        //exit;
    }
    
     public function unidades_hospitalares($id = '')
    {
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }

        
        if ($this->input->post() && !$this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            
            if ($id == '') {
                if (!has_permission('customers', '', 'create')) {
                    access_denied('customers');
                }

                $data = $this->input->post();
                
                $id      = $this->Unidades_hospitalares_model->add($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', 'Unidade Hospitalar'));
                    redirect(admin_url('unidades_hospitalares/unidades_hospitalares/' . $id));
                }
            } else {
                if (!has_permission('customers', '', 'edit')) {
                   // if (!is_customer_admin($id)) {
                    //    access_denied('customers');
                  //  }
                }

                $success = $this->Unidades_hospitalares_model->edit($this->input->post(), $id);
                if ($success == true) {
                    set_alert('success', _l('updated_successfully', 'Unidade Hospitalar'));
                }
                redirect(admin_url('unidades_hospitalares/unidades_hospitalares/' . $id));
            }
        }
     
        $group         = $this->input->get('group') ? $this->input->get('group') : 'unidade_hospitalar';
        $data['group'] = $group;

          

        // Customer groups
      
        if ($id == '') {
            $title = _l('add_new', 'Uniadde Hospitalar');
        } else {
             
            $client                = $this->Unidades_hospitalares_model->get($id);
            
            $data['customer_tabs'] = get_unidade_hospitalar_tabs();
            if (!$client) {
                show_404();
            }
            
           // print_r($data['customer_tabs'][$group]); exit;
           // $data['contacts'] = $this->clients_model->get_contacts($id);
            $data['tab']      = isset($data['customer_tabs'][$group]) ? $data['customer_tabs'][$group] : null;
            
            
            if (!$data['tab']) {
             //   show_404();
            }
           
            // Fetch data based on groups
            if ($group == 'unidade_hospitalar') {
                $data['unidade_id'] =  $this->Unidades_hospitalares_model->get($id);
              //  $data['customer_admins'] = $this->clients_model->get_admins($id);
            } else if ($group == 'configuracao') {
                //$pacienteid = $client['codpaciente'];
              
                $data['horarios'] = $this->Horario_model->get_horario();
                $data['setores'] = $this->Unidades_hospitalares_model->get_setores($id);
            } 

          
            $data['unidade'] = $client;
            $title          = $client->razao_social;
            
            // Get all active staff members (used to add reminder)
            $data['members'] = $data['staff'];
            if (!empty($data['unidade']->nome)) {
                // Check if is realy empty client company so we can set this field to empty
                // The query where fetch the client auto populate firstname and lastname if company is empty
                if (is_empty_customer_company($data['unidade']->id)) {
                    $data['unidade']->nome = '';
                }
            }
        }
       
     //   echo 'aqui '.$group; exit;
        
        $data['bodyclass'] = 'customer-profile dynamic-create-groups';
        $data['title']     = $title;
        
        $this->load->view('admin/unidade_hospitalar/unidade_hospitalar', $data);
        
    }
    
    
   
    public function table()
    {
               
        if (!has_permission('costumers', '', 'view')) {
            ajax_access_denied();
        }
  
        $this->app->get_table_data('unidade_hospitalar');
    }

    /* Edit or update items / ajax request /*/
    
   
    public function add_setor()
    {
        
      if ($this->input->post() && has_permission('items', '', 'create')) {
          $dados_post = $this->input->post();
          $unidade = $dados_post['unidade_id'];
          $unidade = trim($unidade);
            $this->Unidades_hospitalares_model->add_setor($dados_post);
            set_alert('success', _l('added_successfully', 'Setor'));
            redirect(admin_url("unidades_hospitalares/unidades_hospitalares/$unidade?group=setores"));
        }
        
      }

    public function update_setor($id)
    {
        //echo 'akkiii updadte';
       if ($this->input->post() && has_permission('items', '', 'edit')) {
            $this->Unidades_hospitalares_model->edit_setor($this->input->post(), $id);
            set_alert('success', _l('updated_successfully', _l('item_group')));
        }
        
        redirect(admin_url('unidades_hospitalares'));
          
    
    }

    public function delete_setor($id, $unidade)
    {
        $unidade = trim($unidade);
        if (!$id) {
            redirect(admin_url('unidades_hospitalares'));
        }

        $response = $this->Unidades_hospitalares_model->delete_setor($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('invoice_item')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url("unidades_hospitalares/unidades_hospitalares/$unidade?group=setores"));
    }
 
    /* Delete unidades hospitalares*/
    public function delete($id)
    {
       // if (!has_permission('unidade_hospitalar', '', 'delete')) {
         //   access_denied('Unidades Hospitalares');
        //}

        if (!$id) {
            redirect(admin_url('unidades_hospitalares'));
        }

        $response = $this->Unidades_hospitalares_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('invoice_item')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('unidades_hospitalares'));
    }

    
    /*
     * CONFIGURAÇÃO DE ESCALA POR SETOR
     */
    public function manage_configuracao()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
               
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }

                    $success = $this->Unidades_hospitalares_model->add_edit_horario_configuracao($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', 'Configuração');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                
            }
        }
    }
    
    
     public function delete_configuracao($id, $unidade)
    {
        $unidade = trim($unidade);
        if (!$id) {
            redirect(admin_url('unidades_hospitalares'));
        }

        $response = $this->Unidades_hospitalares_model->delete_configuracao($id);
        if ($response == true) {
            set_alert('success', _l('deleted', 'Cadastro'));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url("unidades_hospitalares/unidades_hospitalares/$unidade?group=configuracao"));
    }
    
    /*
     * GERA ESCALA FIXA
     */
    
    public function add_escala_fixa($config_id, $unidade_id){
        $info = $this->Unidades_hospitalares_model->get_config_escala_fica($config_id);
        $data['info']       =  $info;
        $data['config_id']  = $config_id;
        $data['unidade_id']  = $unidade_id;
        $medicos = $this->Medicos_model->get();
        $data['medicos']    = $medicos;
        $this->load->view('admin/gestao_escala/escala_fixa/cadastrar_apartir_configuracao', $data);
    }
    
    // CONSULTAR
    public function consultar_escala_fixa($config_id, $unidade_id){
        $info = $this->Unidades_hospitalares_model->get_config_escala_fica($config_id);
        $data['info']       =  $info;
        $data['config_id']  = $config_id;
        $data['unidade_id']  = $unidade_id;
        $medicos = $this->Medicos_model->get();
        $data['medicos']    = $medicos;
        $this->load->view('admin/gestao_escala/escala_fixa/cadastrar_apartir_configuracao', $data);
    }
    
    /* Change client status / active / inactive */
    public function change_item_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
           
            $this->unidades_hopsitalares_model->change_item_status($id, $status);
        }
    }
    
   

    /*Get item by id / ajax 
    public function get_unidade_hospitalar_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item                     = $this->unidades_hospitalares_model->get($id);
           // print_r($item); exit;
            $item->long_description   = nl2br($item->long_description);
            $procedimento   = nl2br($item->description);
            $item->description = $procedimento;
            $item->custom_fields_html = render_custom_fields('items', $id, [], ['items_pr' => true]);
            $item->custom_fields      = [];

            $cf = get_custom_fields('items');

            foreach ($cf as $custom_field) {
                $val = get_custom_field_value($id, $custom_field['id'], 'items_pr');
                if ($custom_field['type'] == 'textarea') {
                    $val = clear_textarea_breaks($val);
                }
                $custom_field['value'] = $val;
                $item->custom_fields[] = $custom_field;
            }

            echo json_encode($item);// exit;
        }
    //*
     * }
     */
     
}
