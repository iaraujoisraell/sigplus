<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Caixas extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('expenses_model');
        $this->load->model('caixas_model');
        $this->load->model('payments_model');
        $this->load->model('Repasses_model');
    }

    public function index($id = '')
    {
      
        $this->list_expenses($id);
    }

    public function list_expenses($id = '')
    {
        close_setup_menu();

        if (!has_permission('tesouraria', '', 'view')) {
            access_denied('tesouraria');
        }
         
        $data['expenseid']  = $id;

        $data['title']      = _l('expenses');
        
        $this->load->view('admin/caixa/manage', $data);
    }
    
    public function list_registros_caixas($id = '')
    {
        $data['caixa_atual']  = $id;
        $data['title']      = 'Caixa Registros';
        
        $this->load->view('admin/caixa/caixa_registros', $data);
    }

    public function table($clientid = '')
    {
       // if (!has_permission('expenses', '', 'view') && !has_permission('expenses', '', 'view_own')) {
       //     ajax_access_denied();
       // }

        $this->app->get_table_data('caixas');
    }

    public function detalhes_caixa($id = '')
    {
        $caixa_id = $this->input->post('caixa_registro_id');
        $data['caixa_atual'] = $id;
        $title = 'Detalhes Caixa';
     
         $data['title']      = $title;
        $this->load->view('admin/caixa/detalhes_caixa', $data);
    }
    
    public function retorno_table_movimentacao()
    {
        $registro_id = $this->input->post("registro_id");
        $data['registro_id']   = $registro_id;
        $this->load->view('admin/caixa/table_movimentacao', $data);
       
    }
    
    public function caixa($id = '')
    {
       $caixa_id = $this->input->post('caixa_registro_id');
       
        if ($this->input->post()) {
            
            
            if($caixa_id){
                if (!has_permission('tesouraria', '', 'edit')) {
                set_alert('danger', _l('access_denied'));
                redirect(admin_url('caixas'));
               }
              
                $success = $this->expenses_model->fechar_caixa($this->input->post());
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('caixa')));
                    redirect(admin_url('caixas'));
                }
                $this->load->view('admin/caixa/manage');
            }
            
        }
        
          
        
        if ($id == '') {
            $title = _l('add_new', _l('caixa'));
        } else {
            
            $data['dados_caixa'] = $this->caixas_model->get_caixa_registro_atual($id);
           
          //  if (!has_permission('tesouraria', '', 'close_caixa')) {
          //      blank_page(_l('expense_not_found'));
           // }
            $data['caixa_id'] = $id;
            $title = 'Fechar Caixa';
        }

        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }

     
        $data['bodyclass']  = 'expense';
         $data['title']      = $title;
        $this->load->view('admin/caixa/expense', $data);
    }
    
    public function abrir_caixa($id = '')
    {
      
        if ($this->input->post()) {
            $registro_id = $this->input->post('registro_id'); 
            if ($registro_id == '') {
                 
                if (!has_permission('tesouraria', '', 'create')) {
                    set_alert('danger', _l('access_denied'));
                    redirect(admin_url('caixas'));
                }
                $id = $this->expenses_model->abrir_caixa($this->input->post());
                if ($id) {
                    set_alert('success', 'Caixa Aberto com Sucesso');
                    //redirect(admin_url('caixas/list_expenses/' . $id));
                    redirect(admin_url('caixas'));
                }
                
            }
           
            if (!has_permission('tesouraria', '', 'edit')) {
                set_alert('danger', _l('access_denied'));
                echo json_encode([
                        'url' => admin_url('caixas/caixa/' . $id),
                    ]);
                die;
            }
            $success = $this->expenses_model->update($this->input->post(), $id);
            if ($success) {
                set_alert('success', _l('updated_successfully', _l('caixa')));
            }
            echo json_encode([
                    'url'       => admin_url('caixas/list_expenses/' . $id),
                    'expenseid' => $id,
                ]);
            die;
        }
       
        if ($id == '') {
            $title = _l('add_new', _l('caixa'));
        } else {
           // $data['expense'] = $this->expenses_model->get($id);
            $data['caixa_id'] = $id;
           // if (!has_permission('tesouraria', '', 'open_caixa')) {
          //     access_denied('caixa');
          //  }

            $title = _l('open_caixa');
        }

        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }

        $this->load->model('taxes_model');
        $this->load->model('payment_modes_model');
        $this->load->model('currencies_model');

        $data['taxes']         = $this->taxes_model->get();
        $data['categories']    = $this->expenses_model->get_category();
        $data['payment_modes'] = $this->payment_modes_model->get('', [
            'invoices_only !=' => 1,
        ]);
        $data['bodyclass']  = 'expense';
        $data['currencies'] = $this->currencies_model->get();
        $data['title']      = $title;
        $this->load->view('admin/caixa/abrir_caixa', $data);
    }
    
    public function transferir_caixa($id = '')
    {
      
        if ($this->input->post()) {
           
            $caixa_id = $this->input->post('caixa_id');
            $saldo = $this->input->post('saldo');
            $valor_transferencia = $this->input->post('valor_transferencia');
            $valor_transferencia = str_replace(',','.', $valor_transferencia);
            
            $caixa_destino = $this->input->post('caixa_destino');
            
            // caixa destino
            $dados_ultimo_registro_caixa_destino = $this->caixas_model->get_ultimo_registro_caixa($caixa_destino); 
            $caixa_atual_destino = $dados_ultimo_registro_caixa_destino->id;
            
            if($valor_transferencia > $saldo){
                set_alert('danger', 'Valor de transferência maior que o Saldo.');
                redirect(admin_url('caixas/transferir_caixa/'.$caixa_id));
            }else if(!$caixa_destino){
                set_alert('danger', 'Selecione um caixa de destino.');
                redirect(admin_url('caixas/transferir_caixa/'.$caixa_id));
            }else if(!$caixa_atual_destino){
                set_alert('danger', 'Abra o caixa de destino para realizar a transferência.');
                redirect(admin_url('caixas/transferir_caixa/'.$caixa_id));
            }else{
                /*if (!has_permission('tesouraria', '', 'create')) {
                    set_alert('danger', _l('access_denied'));
                    redirect(admin_url('caixas'));
                }*/
                $success = $this->expenses_model->transferencia_caixa($this->input->post());
                if ($success) {
                    set_alert('success', 'Transferência realizada com Sucesso');
                    //redirect(admin_url('caixas/list_expenses/' . $id));
                    redirect(admin_url('caixas'));
                }
            }
            
          
        }else{
            
            $title = "Transferência de Caixa";
            $data['caixa_id'] = $id;
        }
       
        $data['caixas']    = $this->caixas_model->get_caixas_transferencia($id);
      
        $data['title']      = $title;
        $this->load->view('admin/caixa/transferencia_caixa', $data);
    }

    public function get_expenses_total()
    {
        if ($this->input->post()) {
            $data['totals'] = $this->expenses_model->get_expenses_total($this->input->post());

            if ($data['totals']['currency_switcher'] == true) {
                $this->load->model('currencies_model');
                $data['currencies'] = $this->currencies_model->get();
            }

            $data['expenses_years'] = $this->expenses_model->get_expenses_years();

            if (count($data['expenses_years']) >= 1 && $data['expenses_years'][0]['year'] != date('Y')) {
                array_unshift($data['expenses_years'], ['year' => date('Y')]);
            }

            $data['_currency'] = $data['totals']['currencyid'];
            $this->load->view('admin/expenses/expenses_total_template', $data);
        }
    }

    public function delete($id)
    {
        if (!has_permission('expenses', '', 'delete')) {
            access_denied('expenses');
        }
        if (!$id) {
            redirect(admin_url('expenses/list_expenses'));
        }
        $response = $this->expenses_model->delete($id);
        if ($response === true) {
            set_alert('success', _l('deleted', _l('expense')));
        } else {
            if (is_array($response) && $response['invoiced'] == true) {
                set_alert('warning', _l('expense_invoice_delete_not_allowed'));
            } else {
                set_alert('warning', _l('problem_deleting', _l('expense_lowercase')));
            }
        }

        if (strpos($_SERVER['HTTP_REFERER'], 'expenses/') !== false) {
            redirect(admin_url('expenses/list_expenses'));
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }   

    public function get_expense_data_ajax($id)
    {
        if (!has_permission('expenses', '', 'view') && !has_permission('expenses', '', 'view_own')) {
            echo _l('access_denied');
            die;
        }
        $expense = $this->expenses_model->get($id);

        if (!$expense || (!has_permission('expenses', '', 'view') && $expense->addedfrom != get_staff_user_id())) {
            echo _l('expense_not_found');
            die;
        }

        $data['expense'] = $expense;
        if ($expense->billable == 1) {
            if ($expense->invoiceid !== null) {
                $this->load->model('invoices_model');
                $data['invoice'] = $this->invoices_model->get($expense->invoiceid);
            }
        }

        $data['child_expenses'] = $this->expenses_model->get_child_expenses($id);
        $data['members']        = $this->staff_model->get('', ['active' => 1]);
        $this->load->view('admin/expenses/expense_preview_template', $data);
    }

    public function get_customer_change_data($customer_id = '')
    {
        echo json_encode([
            'customer_has_projects' => customer_has_projects($customer_id),
            'client_currency'       => $this->clients_model->get_customer_default_currency($customer_id),
        ]);
    }

    public function categories()
    {
        if (!is_admin()) {
            access_denied('expenses');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('expenses_categories');
        }
        $data['title'] = _l('expense_categories');
        $this->load->view('admin/expenses/manage_categories', $data);
    }

    public function save_new_caixa()
    {
        if (!is_admin() && (!has_permission('tesouraria', '', 'delete'))) {
            access_denied('tesouraria');
        }
     
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $success = $this->expenses_model->add_caixa($this->input->post());
                $message = _l('success', _l('caixa'));
                echo json_encode(['success' => $success, 'message' => $message]);
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);
                $success = $this->expenses_model->update_category($data, $id);
                $message = _l('updated_successfully', _l('expense_category'));
                echo json_encode(['success' => $success, 'message' => $message]);
            }
        }
    }

    public function delete_category($id)
    {
        if (!is_admin()) {
            access_denied('expenses');
        }
        if (!$id) {
            redirect(admin_url('expenses/categories'));
        }
        $response = $this->expenses_model->delete_category($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('expense_category_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('expense_category')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('expense_category_lowercase')));
        }
        redirect(admin_url('expenses/categories'));
    }

    public function add_expense_attachment($id)
    {
        handle_expense_attachments($id);
        echo json_encode([
            'url' => admin_url('expenses/list_expenses/' . $id),
        ]);
    }

    public function delete_expense_attachment($id, $preview = '')
    {
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'expense');
        $file = $this->db->get(db_prefix().'files')->row();

        if ($file->staffid == get_staff_user_id() || is_admin()) {
            $success = $this->expenses_model->delete_expense_attachment($id);
            if ($success) {
                set_alert('success', _l('deleted', _l('expense_receipt')));
            } else {
                set_alert('warning', _l('problem_deleting', _l('expense_receipt_lowercase')));
            }
            if ($preview == '') {
                redirect(admin_url('expenses/expense/' . $id));
            } else {
                redirect(admin_url('expenses/list_expenses/' . $id));
            }
        } else {
            access_denied('expenses');
        }
    }
}
