<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Horario_plantao extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Horario_model');

    }

    public function index($id = '')
    {
      
        $this->list_horarios($id);
    }

    public function list_horarios($id = '')
    {
        close_setup_menu();

        if (!has_permission('tesouraria', '', 'view')) {
            access_denied('tesouraria');
        }
         
        $data['expenseid']  = $id;

        $data['title']      = 'Horários de Plantão';
        
        $this->load->view('admin/horario_plantao/manage', $data);
    }
    

    public function table($clientid = '')
    {
       // if (!has_permission('expenses', '', 'view') && !has_permission('expenses', '', 'view_own')) {
       //     ajax_access_denied();
       // }

        $this->app->get_table_data('horario_plantao');
    }

    public function manage()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }

                    $success = $this->Horario_model->add_edit_horario($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', 'Plantão');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                
            }
        }
    }
    
    public function get_horario_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item  = $this->Horario_model->get_horario($id);
            echo json_encode($item);// exit;
        }
    }
   
     public function delete($id)
    {
       
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }
        
        if (!$id) {
            redirect(admin_url('horario_plantao'));
        }

        $data['deleted'] = 1;
        $response = $this->Horario_model->delete($data, $id);
        if ($response == true) {
            set_alert('success', 'Horário Plantão '._l('deleted'));
        } 
        redirect(admin_url('horario_plantao'));
    }   

}
