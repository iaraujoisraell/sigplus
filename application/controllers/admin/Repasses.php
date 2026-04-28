<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Repasses extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('caixas_model');
        $this->load->model('Repasses_model');
        $this->load->model('payments_model');
        $this->load->model('payment_modes_model');
        $this->load->model('invoice_items_model');
        $this->load->model('Medicos_model');
        $this->load->model('Convenios_model');
        $this->load->model('Contas_financeiras_model');
    }


    public function index($id = '')
    {
        close_setup_menu();

        if (!has_permission('tesouraria', '', 'repasses')) {
            access_denied('tesouraria');
        }
        $data['payment_modes'] = $this->payment_modes_model->get();  
        //$data['expenseid']  = $id;
        $data['convenios']             = $this->Convenios_model->get();
        $data['categorias']            = $this->invoice_items_model->get_groups();
        $data['procedimentos']         = $this->invoice_items_model->get_ativos();
        $data['contas_financeiras']    = $this->Contas_financeiras_model->get();
        $data['title']      = _l('repasses');
       
        $this->load->view('admin/repasses/manage', $data);
        //$this->load->view('admin/repasses/index', $data);
    }
    
   

    public function add_repasse()
    {
        
        $saldo = $this->input->post('saldo');
        $valor = $this->input->post('valor');
        $valor = str_replace(',','.', $valor);
        
        if($valor == 0){
             set_alert('danger', 'Informe o valor do repasse.');
             redirect($_SERVER['HTTP_REFERER']);
        }else if($valor > $saldo){
            set_alert('danger', 'O valor do repasse não pode ser maior que o saldo do caixa.');
             redirect($_SERVER['HTTP_REFERER']);
        }
        
      
        if ($this->input->post()) {
            $success = $this->Repasses_model->add($this->input->post());
            if ($success) {
                set_alert('success', _l('added_successfully', _l('repasse')));
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    
    public function table()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
       
        $this->app->get_table_data('repasses');
    }

    /* Edit or update items / ajax request /*/
    public function manage()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['itemid'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id      = $this->invoice_items_model->add($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', _l('sales_item'));
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->invoice_items_model->get($id),
                    ]);
                } else {
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $success = $this->invoice_items_model->edit($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', _l('sales_item'));
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }
            }
        }
    }
    
}
