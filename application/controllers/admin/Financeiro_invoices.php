<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Financeiro_invoices extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoices_model');
        $this->load->model('Financeiro_model');
        $this->load->model('credit_notes_model');
        $this->load->model('Medicos_model');
        $this->load->model('Convenios_model');
        $this->load->model('Clients_model');
        $this->load->model('payments_model');
        $this->load->model('Nota_fiscal_model');
        $this->load->model('Centro_custo_model');
        $this->load->model('Contas_financeiras_model');
        $this->load->model('appointly_model', 'apm'); 
        $this->load->model('caixas_model');
        
        $this->load->model('Financeiro_model');
        
    }

    /* Get all invoices in case user go on index page */
    public function index($id = '')
    {
        $this->list_invoices($id);
    }

    /* List all invoices datatables */
    public function list_invoices($id = '')
    {
      
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('invoices');
        }
      
        close_setup_menu();

        $this->load->model('payment_modes_model');
        $data['invoiceid']            = $id;
        $data['title']                = _l('invoices');
        $data['clientes']              = $this->Financeiro_model->get_clientes();
        
        $data['bodyclass']            = 'invoices-total-manual';
        $this->load->view('admin/financeiro/contas_receber/manage', $data);
    }
    
    /* Get item by id / ajax */
    public function get_dados_invoices_financeiro_by_id($id)
    {
      
        if ($this->input->is_ajax_request()) {
            $item  = $this->Financeiro_model->get_conta_receber($id);
            echo json_encode($item);// exit;
        }
    }

    /* List all recurring invoices */
    public function recurring($id = '')
    {
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('invoices');
        }

        close_setup_menu();

        $data['invoiceid']            = $id;
        $data['title']                = _l('invoices_list_recurring');
        $data['invoices_years']       = $this->invoices_model->get_invoices_years();
        $data['invoices_sale_agents'] = $this->invoices_model->get_sale_agents();
        $this->load->view('admin/financeiro/contas_receber/invoices/recurring/list', $data);
    }
    
    

    public function table($clientid = '')
    {
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            ajax_access_denied();
        }
      
        $this->load->model('payment_modes_model');
        $data['payment_modes'] = $this->payment_modes_model->get('', [], true);

        $this->app->get_table_data('invoices_receber');
    }
    


    public function client_change_data($customer_id, $current_invoice = '')
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('projects_model');
            $data                     = [];
            $data['billing_shipping'] = $this->clients_model->get_customer_billing_and_shipping_details($customer_id);
            $data['client_currency']  = $this->clients_model->get_customer_default_currency($customer_id);

            $data['customer_has_projects'] = customer_has_projects($customer_id);
            $data['billable_tasks']        = $this->tasks_model->get_billable_tasks($customer_id);

            if ($current_invoice != '') {
                $this->db->select('status');
                $this->db->where('id', $current_invoice);
                $current_invoice_status = $this->db->get(db_prefix() . 'invoices')->row()->status;
            }

            $_data['invoices_to_merge'] = !isset($current_invoice_status) || (isset($current_invoice_status) && $current_invoice_status != Invoices_model::STATUS_CANCELLED) ? $this->invoices_model->check_for_merge_invoice($customer_id, $current_invoice) : [];

            $data['merge_info'] = $this->load->view('admin/invoices/merge_invoice', $_data, true);

            $this->load->model('currencies_model');

            $__data['expenses_to_bill'] = !isset($current_invoice_status) || (isset($current_invoice_status) && $current_invoice_status != Invoices_model::STATUS_CANCELLED) ? $this->invoices_model->get_expenses_to_bill($customer_id) : [];

            $data['expenses_bill_info'] = $this->load->view('admin/invoices/bill_expenses', $__data, true);
            echo json_encode($data);
        }
    }

    public function update_number_settings($id)
    {
        $response = [
            'success' => false,
            'message' => '',
        ];
        if (has_permission('invoices', '', 'edit')) {
            $affected_rows = 0;

            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'invoices', [
                'prefix' => $this->input->post('prefix'),
            ]);
            if ($this->db->affected_rows() > 0) {
                $affected_rows++;
            }

            if ($affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = _l('updated_successfully', _l('invoice'));
            }
        }
        echo json_encode($response);
        die;
    }

    public function validate_invoice_number()
    {
        $isedit          = $this->input->post('isedit');
        $number          = $this->input->post('number');
        $date            = $this->input->post('date');
        $original_number = $this->input->post('original_number');
        $number          = trim($number);
        $number          = ltrim($number, '0');

        if ($isedit == 'true') {
            if ($number == $original_number) {
                echo json_encode(true);
                die;
            }
        }

        if (total_rows(db_prefix() . 'invoices', [
            'YEAR(date)' => date('Y', strtotime(to_sql_date($date))),
            'number' => $number,
            'status !=' => Invoices_model::STATUS_DRAFT,
        ]) > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function add_note($rel_id)
    {
        if ($this->input->post() && user_can_view_invoice($rel_id)) {
            $this->misc_model->add_note($this->input->post(), 'invoice', $rel_id);
            echo $rel_id;
        }
    }
    
    public function add_note_atendimento($rel_id)
    {
      if ($this->input->post()) {
            $success = $this->misc_model->add_evolucao($this->input->post(), $rel_id);
            if ($success) {
                set_alert('success', _l('added_successfully', 'Registro'));
            }
        }
     
        redirect($_SERVER['HTTP_REFERER']);
         
        
    }

    public function get_notes($id)
    {
        if (user_can_view_invoice($id)) {
            $data['notes'] = $this->misc_model->get_notes($id, 'invoice');
            $this->load->view('admin/includes/sales_notes_template', $data);
        }
    }

    public function pause_overdue_reminders($id)
    {
        if (has_permission('invoices', '', 'edit')) {
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'invoices', ['cancel_overdue_reminders' => 1]);
        }
        redirect(admin_url('invoices/list_invoices/' . $id));
    }

    public function resume_overdue_reminders($id)
    {
        if (has_permission('invoices', '', 'edit')) {
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'invoices', ['cancel_overdue_reminders' => 0]);
        }
        redirect(admin_url('invoices/list_invoices/' . $id));
    }

    public function mark_as_cancelled($id)
    {
        if (!has_permission('invoices', '', 'edit') && !has_permission('invoices', '', 'create')) {
            access_denied('invoices');
        }

        $success = $this->invoices_model->mark_as_cancelled($id);

        if ($success) {
            set_alert('success', _l('invoice_marked_as_cancelled_successfully'));
        }

        redirect(admin_url('invoices/list_invoices/' . $id));
    }

    public function unmark_as_cancelled($id)
    {
        if (!has_permission('invoices', '', 'edit') && !has_permission('invoices', '', 'create')) {
            access_denied('invoices');
        }
        $success = $this->invoices_model->unmark_as_cancelled($id);
        if ($success) {
            set_alert('success', _l('invoice_unmarked_as_cancelled'));
        }
        redirect(admin_url('invoices/list_invoices/' . $id));
    }

    public function copy($id)
    {
        if (!$id) {
            redirect(admin_url('invoices'));
        }
        if (!has_permission('invoices', '', 'create')) {
            access_denied('invoices');
        }
        $new_id = $this->invoices_model->copy($id);
        if ($new_id) {
            set_alert('success', _l('invoice_copy_success'));
            redirect(admin_url('invoices/invoice/' . $new_id));
        } else {
            set_alert('success', _l('invoice_copy_fail'));
        }
        redirect(admin_url('invoices/invoice/' . $id));
    }

    public function get_merge_data($id)
    {
        $invoice = $this->invoices_model->get($id);
        $cf      = get_custom_fields('items');

        $i = 0;

        foreach ($invoice->items as $item) {
            $invoice->items[$i]['taxname']          = get_invoice_item_taxes($item['id']);
            $invoice->items[$i]['long_description'] = clear_textarea_breaks($item['long_description']);
            $this->db->where('item_id', $item['id']);
            $rel              = $this->db->get(db_prefix() . 'related_items')->result_array();
            $item_related_val = '';
            $rel_type         = '';
            foreach ($rel as $item_related) {
                $rel_type = $item_related['rel_type'];
                $item_related_val .= $item_related['rel_id'] . ',';
            }
            if ($item_related_val != '') {
                $item_related_val = substr($item_related_val, 0, -1);
            }
            $invoice->items[$i]['item_related_formatted_for_input'] = $item_related_val;
            $invoice->items[$i]['rel_type']                         = $rel_type;

            $invoice->items[$i]['custom_fields'] = [];

            foreach ($cf as $custom_field) {
                $custom_field['value']                 = get_custom_field_value($item['id'], $custom_field['id'], 'items');
                $invoice->items[$i]['custom_fields'][] = $custom_field;
            }
            $i++;
        }
        echo json_encode($invoice);
    }

    public function get_bill_expense_data($id)
    {
        $this->load->model('expenses_model');
        $expense = $this->expenses_model->get($id);

        $expense->qty              = 1;
        $expense->long_description = clear_textarea_breaks($expense->description);
        $expense->description      = $expense->name;
        $expense->rate             = $expense->amount;
        if ($expense->tax != 0) {
            $expense->taxname = [];
            array_push($expense->taxname, $expense->tax_name . '|' . $expense->taxrate);
        }
        if ($expense->tax2 != 0) {
            array_push($expense->taxname, $expense->tax_name2 . '|' . $expense->taxrate2);
        }
        echo json_encode($expense);
    }

    /* Add new invoice or update existing */
    public function invoice($id = '')
    {
        if ($this->input->post()) {
            $invoice_data = $this->input->post();
            if ($id == '') {
              //  print_r($invoice_data); exit;
                if (!has_permission('invoices', '', 'create')) {
                    access_denied('invoices');
                }
              
                $id = $this->Financeiro_model->add_invoices($invoice_data);
               
                //ECHO 'AQUI'; EXIT;
                if ($id) {
                  // $this->invoices_model->log_invoice_activity($id, 'criou_nova_CONTA_RECEBER', false, serialize($invoice_data));
                   // $this->invoices_model->log_invoice_activity($id, 'criou_nova_fatura', false, $invoice_data);
                     
                    set_alert('success', _l('added_successfully', _l('invoice')));
                    $redUrl = admin_url('financeiro_invoices/list_invoices/' . $id);

                    if (isset($invoice_data['save_and_record_payment'])) {
                        $this->session->set_userdata('record_payment', true);
                    } elseif (isset($invoice_data['save_and_send_later'])) {
                        $this->session->set_userdata('send_later', true);
                    }

                    redirect($redUrl);
                }
            } else {
               
                if (!has_permission('invoices', '', 'edit')) {
                    access_denied('invoices');
                }
                
                $success = $this->Financeiro_model->add_invoices($invoice_data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('invoice')));
                }
               // $this->invoices_model->log_invoice_activity($id, 'click_invoice_update_fatura');
                $redUrl = admin_url('financeiro_invoices/list_invoices/' . $id);
                redirect($redUrl);
            }
        }
        if ($id == '') {
            $title                  = _l('create_new_invoice');
            $data['billable_tasks'] = [];
        } else {
           
            $invoice = $this->Financeiro_model->get_conta_receber($id);
            
            if (!$invoice || !user_can_view_invoice($id)) {
                blank_page(_l('invoice_not_found'));
            }

            $data['invoice']        = $invoice;
            
            $data['edit']           = true;
            
             $title = _l('edit', _l('invoice_lowercase')) . ' - ' . format_invoice_number($invoice->id);
            
            // regitra que o usuário abriu a fatura
           // $this->invoices_model->log_invoice_activity($id, 'invoice_access_fatura');
        }

        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }

        $this->load->model('payment_modes_model');
        $data['payment_modes'] = $this->Financeiro_model->get_forma_pagamento();

        $this->load->model('taxes_model');
        $data['taxes'] = $this->taxes_model->get();
        $this->load->model('invoice_items_model');

    
            $data['items']     = [];
            $data['ajaxItems'] = true;
        
       
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();
        
        
        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $data['categorias_financeira'] = $this->Financeiro_model->get_categorias(2);
        $data['clientes']              = $this->Financeiro_model->get_clientes();
        // bancos
        $data['bancos'] = $this->Financeiro_model->get_bancos();
        // centro de custo
        $data['centro_custo'] = $this->Financeiro_model->get_centro_custo();
        
        $data['staff']     = $this->staff_model->get('', ['active' => 1]);
        $data['title']     = $title;
        $data['bodyclass'] = 'invoice';
        //$data['clientes']   = $this->Financeiro_model->get_clientes();
        $data['convenios']   = $this->Convenios_model->get_particular();
        
        
        
       
        $this->load->view('admin/financeiro/contas_receber/invoice', $data);
    }
    
    
    public function recalculaRepasses($id)
    {
        if (!$id) {
            redirect(admin_url('invoices'));
        }
       // if (!has_permission('invoices', '', 'create')) {
        //    access_denied('invoices');
       // }   stlog
        
        
        $this->invoices_model->processa_Repasse_faturamento($id);
        redirect(admin_url('invoices/list_invoices/' . $id));
    }
    
    
    public function calcularDesconto()
    {
        
        if(!has_permission('invoices','','desconto_aba')){
             access_denied('desconto_aba');
        }
        
        $invoiceid = $this->input->post("invoiceid");
        $vl_total_fatura = $this->input->post("vl_total_fatura");
        $discount_total = $this->input->post("discount_total");
        $desconto_maximo_empresa = $this->input->post("desconto_maximo_empresa");
        $valor_desconto_empresa = $this->input->post("valor_desconto_empresa");
        $valor_desconto_empresa = str_replace(".","", $valor_desconto_empresa);
        $valor_desconto_empresa = str_replace(",",".", $valor_desconto_empresa);
        $tipo_desconto_empresa = $this->input->post("tipo_desconto_empresa");
        $destino_desconto_empresa = $this->input->post("destino_desconto_empresa"); 
        //echo $valor_desconto_empresa; exit;
        if($discount_total){
            $vl_total_fatura += $discount_total;
        }
        
        
        
        // se o tipo do desconto for %
        if($tipo_desconto_empresa == 2){
            $valor_desconto_empresa_porc = ($vl_total_fatura * $valor_desconto_empresa)/100;
            $valor_desconto_empresa = $valor_desconto_empresa_porc;
        }
        
       // valida se o valor é > 0 
        if($valor_desconto_empresa <= 0){
            set_alert('danger', 'Informe um valor válido');
            redirect(admin_url('invoices/list_invoices/' . $invoiceid));
        }
        // valida se o valor do desconto é permitido
        if($valor_desconto_empresa > $desconto_maximo_empresa){
            set_alert('danger', 'Valor de desconto maior que o permitido');
            redirect(admin_url('invoices/list_invoices/' . $invoiceid));
        }
        
        // listar procedimento
        $item = 1;
        $soma_porc = 0;
        $soma_desconto  = 0;
        $ajusta_desc    = 0;
        $producao       = $this->invoices_model->get_medico_invoice_item_producao($invoiceid); 
        $total_procedimento = count($producao);
        foreach($producao as $producao_medico){
            
            $id =  $producao_medico['id'];
            $descricao = $producao_medico['description'];
            $rate = $producao_medico['rate'];
            $qty = $producao_medico['qty'];
            
            $vl_procedimento = ($rate * $qty);  // VALOR DO PROCEDIMENTO
            $porc_vl_procedimento = ($vl_procedimento * 100)/$vl_total_fatura; // % VL DO PROCEDIMENTO EM RELAÇÃO AO VALOR DA FTURA
            $porc_vl_procedimento = substr("$porc_vl_procedimento", 0, 5);
            $soma_porc +=  $porc_vl_procedimento;
            
            $valor_desconto_procedimento = ($valor_desconto_empresa * $porc_vl_procedimento)/100;
            $valor_desconto_procedimento = substr("$valor_desconto_procedimento", 0, 4);
            $soma_desconto+= $valor_desconto_procedimento;
           
            
            //echo $porc_vl_procedimento.'<br>';
           //echo $item.' - '.$valor_desconto_procedimento.'-';
          
            
            if($item == $total_procedimento){
                if($soma_desconto < $valor_desconto_empresa){
                    $ajusta_desc = $valor_desconto_empresa - $soma_desconto;
                    $ajusta_desc = substr("$ajusta_desc", 0, 5);
                    //$ajusta_desc = 
                    
                    
                    $valor_desconto_procedimento += $ajusta_desc;
                    $soma_desconto += $ajusta_desc;
                }
                
                
            }
            
              //echo $soma_desconto.'<br>'; 
            
            $data_item['desconto_valor']       = $valor_desconto_procedimento;
            $data_item['destino_desconto']     = $destino_desconto_empresa;
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'itemable', $data_item);
                
            
            
            $item++;
        }
        
        
        $invoice_dados = $this->invoices_model->get($invoiceid);
        $total_fat = $invoice_dados->total;
        
       
            // valor da fatura com desconto
            $vl_desc = $total_fat - $soma_desconto;
            
            if($destino_desconto_empresa == "EMPRESA"){
                $vl_empresa_log = $soma_desconto;
                $vl_profissional_log = 0;
            }else{
                $vl_empresa_log = 0;
                $vl_profissional_log = $soma_desconto;
            }
            
            
            $data_invoice['discount_total']  = $soma_desconto;
            $data_invoice['total']           = $vl_desc;
            $this->db->where('id', $invoiceid);
            $this->db->update(db_prefix() . 'invoices', $data_invoice);
            
            // cria o log
            $data_log['invoice_id']          = $invoiceid;
            $data_log['valor_empresa']       = $vl_empresa_log;
            $data_log['valor_profissional']  = $vl_profissional_log;
            $data_log['data_log']            = date('Y-m-d H:i:s');
            $data_log['usuario_log']         = get_staff_user_id();
            $this->db->insert(db_prefix() . 'invoices_descontos', $data_log);
            //$insert_id =  $this->db->insert_id();
            // atualiza status
            $pagamento = $this->payments_model->get_invoice_payments($invoiceid);
            if(count($pagamento)> 0){
                
                update_invoice_status($invoiceid);
            }
        //exit;
        
        set_alert('success', 'Desconto cadastrado com sucesso');
        redirect(admin_url('invoices/list_invoices/' . $invoiceid));
        
        
        
    }
    
    public function retorno_horario_medico()
    {
       $this->load->model('appointly_model');  
       $medicoid = $this->input->post("medicoid");
       
       $agenda_disponivel_medico   = $this->appointly_model->get_agenda_medico_disponivel_fatura($medicoid);
       $data['agenda_disponivel_medico'] = $agenda_disponivel_medico; 
       
      
       $this->load->view('admin/invoices/horario_medico_select', $data);
        //$this->load->view('admin/dashboard/retorno_dashboard_gestao', $data);
    }

    public function retorno_procedimentos()
    {
       $this->load->model('invoice_items_model'); 
       $convenio = $this->input->post("convenio");
      
       //$data['items'] = $this->invoice_items_model->get_grouped($convenio);    
       
       $data['ajaxItems'] = false;
        //echo 'Total : '.ajax_on_total_items(); exit;
        if (total_rows(db_prefix() . 'items') <= ajax_on_total_items()) {
            $data['items'] = $this->invoice_items_model->get_grouped($convenio);
        } else {
            $data['items']     = [];
            $data['ajaxItems'] = true;
        }
       
       $this->load->view('admin/invoice_items/item_select', $data);
        //$this->load->view('admin/dashboard/retorno_dashboard_gestao', $data);
    }
    
    public function add_nota_fiscal($invoice_id, $cliente_id)
    {
     //  $this->load->model('invoice_items_model'); 
       //$data['items'] = $this->invoice_items_model->get_grouped($convenio);    
        $data['invoice_id'] = $invoice_id;
        $data['cliente_id'] = $cliente_id;
        $data['attachments'] = get_all_customer_attachments($cliente_id);
       
       $this->load->view('admin/nf_avulsa/index', $data);
        //$this->load->view('admin/dashboard/retorno_dashboard_gestao', $data);
    }
    
    /* Get all invoice data used when user click on invoiec number in a datatable left side*/
    public function get_invoice_data_ajax($id)
    {
       

        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            echo _l('access_denied');
            die;
        }

        if (!$id) {
            die(_l('invoice_not_found'));
        }
        //echo 'aqui : '.$id; exit;
        $invoice = $this->Financeiro_model->get_invoices($id);
        //print_r($invoice); exit;
        if (!$invoice || !user_can_view_invoice($id)) {
            echo _l('invoice_not_found');
            die;
        }

        $invoice->date    = _d($invoice->date);
        $invoice->duedate = _d($invoice->duedate);

        $template_name = 'invoice_send_to_customer';

        if ($invoice->sent == 1) {
            $template_name = 'invoice_send_to_customer_already_sent';
        }
        
        //$data = prepare_mail_preview_data($template_name, $invoice->clientid);
       
         // Check for recorded payments
        $this->load->model('payments_model');
       
       // $data['members']                    = $this->staff_model->get('', ['active' => 1]);
       // $data['payments']                   = $this->Financeiro_model->get_invoice_payments($id);
        //$data['activity']                   = $this->invoices_model->get_invoice_activity($id);
         
        // This data is used only when credit can be applied to invoice
        
        

        $data['invoice'] = $invoice;

        $data['record_payment'] = false;
        $data['send_later']     = false;

        if ($this->session->has_userdata('record_payment')) {
            $data['record_payment'] = false;
            $this->session->unset_userdata('record_payment');
        } elseif ($this->session->has_userdata('send_later')) {
            $data['send_later'] = false;
            $this->session->unset_userdata('send_later');
        }

        $this->load->view('admin/financeiro/contas_receber/invoice_preview_template', $data);
    }
    
    public function get_agenda_medica_data_ajax($id)
    {
        
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            echo _l('access_denied');
            die;
        }
       
        if (!$id) {
            die(_l('invoice_not_found'));
        }
       
        $appointment = $this->apm->get_appointment_data($id);
    //   ECHO 'AQUI'; EXIT;
        /*
         * STATUS ATENDIMENTO
         */
       
        // CANCELADO
        if ($appointment['cancelled'] && $appointment['finished'] == 0) {
            $status_atendimento = '<span class="label label-danger">' . strtoupper(_l('appointment_cancelled')) . '</span>';
        } else if (!$appointment['finished'] && !$appointment['cancelled'] && $appointment['approved'] == 0 && date('Y-m-d H:i', strtotime($appointment['date'])) < date('Y-m-d H:i')) {
            $$status_atendimento = '<span class="label label-danger">' . strtoupper(_l('appointment_missed_label')) . '</span>';
            // CONFIRMAdO
        } else if (!$appointment['finished'] && !$appointment['cancelled'] && $appointment['approved'] == 1 && $appointment['confirmar_chegada'] == 0 ) {
            $label_status = strtoupper('Confirmado') ;
            $status_atendimento = '<button class="label label-info mleft5" data-toggle="tooltip" data-id="' . $appointment['id'] . '" onclick="appointmentUpdateModal(this)">'.$label_status.'</button>';
            // PENNDENTE
        } else if (!$appointment['finished'] && !$appointment['cancelled'] && $appointment['approved'] == 0) {
            $label_status = strtoupper('AGENDADO') ;
            $status_atendimento = '<button class="label label-warning mleft5" data-toggle="tooltip" data-id="' . $appointment['id'] . '" onclick="appointmentUpdateModal(this)">'.$label_status.'</button>';
            // FILA DE ESPERA
        } else if ($appointment['confirmar_chegada'] == 1 && $appointment['finished']== 0 && $appointment['inicio_atendimento']== 0) {
            $status_atendimento = '<span class="label label-warning">' . strtoupper('EM ESPERA') . '</span>';
            //ATENDIDO
        } else if ($appointment['confirmar_chegada'] == 1 && $appointment['finished']== 0 && $appointment['inicio_atendimento']== 1) {
            $status_atendimento = '<span class="label label-info">' . strtoupper('EM ATENDIMENTO') . '</span>';
            //ATENDIDO
        }else if ($appointment['finished'] == 1) {
            $status_atendimento = '<span class="label label-success">' . strtoupper('ATENDIDO') . '</span>';
        }
        
           
        
         // Check for recorded payments
        $atendimento = $appointment['id'];
        $data['user_historico'] = $this->misc_model->get_historico($atendimento);
        // This data is used only when credit can be applied to invoice
        
          
        
        $data['status_atendimento'] = $status_atendimento;
        $data['atendimento'] = $appointment;
      

        $this->load->view('admin/atendimentos/atendimento_preview_template', $data);
    }

    public function apply_credits($invoice_id)
    {
        $total_credits_applied = 0;
        foreach ($this->input->post('amount') as $credit_id => $amount) {
            $success = $this->credit_notes_model->apply_credits($credit_id, [
            'invoice_id' => $invoice_id,
            'amount'     => $amount,
        ]);
            if ($success) {
                $total_credits_applied++;
            }
        }

        if ($total_credits_applied > 0) {
            update_invoice_status($invoice_id, true);
            set_alert('success', _l('invoice_credits_applied'));
        }
        redirect(admin_url('invoices/list_invoices/' . $invoice_id));
    }

    public function get_invoices_total()
    {
        if ($this->input->post()) {
            load_invoices_total_template();
        }
    }

    public function lista_pagamentos($id = '')
    {
      //echo $id; exit;
       
       
        $data['invoices']  = $this->Financeiro_model->get_conta_receber($id);
        
        $data['payments'] = $this->Financeiro_model->get_invoice_payments($id);
        // formas de pagamento
       // $data['formas_pagamentos'] = $this->Financeiro_model->get_forma_pagamento(); 
        
        $data['bodyclass']            = 'invoices-total-manual';
        $this->load->view('admin/financeiro/conta_pagar/parcelas_conta_pagar', $data);
        
    }
    
    /* Record new inoice payment view */
    public function record_invoice_payment_ajax($id)
    {
        
        $this->load->model('payment_modes_model');
         
        $this->load->model('payments_model');
        
        $payments = $this->Financeiro_model->get_modos_payments();
        //print_r($payments); exit;
        $data['payment_modes'] = $payments;
      
        $data['invoice']  = $this->Financeiro_model->get_conta_receber($id);
        
        $data['bancos'] = $this->Financeiro_model->get_bancos();
        
        $data['payments'] = $this->payments_model->get_invoice_payments($id);
        
       
       //echo 'aqui3'; exit;
        $this->load->view('admin/financeiro/contas_receber/record_payment_template', $data);
    }

    /* This is where invoice payment record $_POST data is send */
    public function record_payment()
    {
        if (!has_permission('payments', '', 'create')) {
            access_denied('Record Payment');
        }
        
        if ($this->input->post()) {
         
            $dados = $this->input->post();      
            $this->load->model('payments_model');
            $pagamentos_id = $this->payments_model->process_payment_conta_receber($this->input->post(), '');
            if ($pagamentos_id) {
                $fatura_id = $this->input->post('invoiceid');
                $this->invoices_model->log_invoice_activity($fatura_id, 'registrou_novo_pagamento', false, serialize($dados));
                
                
               
                 set_alert('success', _l('invoice_payment_recorded'));
                
                 
               //redirect(admin_url('payments/payment/' . $id));
                redirect(admin_url('financeiro_invoices/list_invoices/' . $this->input->post('invoiceid')));
            } else {
                
                
                set_alert('danger', _l('invoice_payment_record_failed'));
            }
            redirect(admin_url('financeiro_invoices/list_invoices/' . $this->input->post('invoiceid')));
        }
    }
    
    
     public function record_invoice_glosa_ajax($id)
    {
        
        $this->load->model('payment_modes_model');
         
        $this->load->model('payments_model');
        
        $payments = $this->Financeiro_model->get_modos_payments();
        //print_r($payments); exit;
        $data['payment_modes'] = $payments;
      
        $data['invoice']  = $this->Financeiro_model->get_conta_receber($id);
        
        $data['bancos'] = $this->Financeiro_model->get_bancos();
        
        $data['payments'] = $this->payments_model->get_invoice_payments($id);
        
       
       //echo 'aqui3'; exit;
        $this->load->view('admin/financeiro/contas_receber/record_glosa_template', $data);
    }
    
    public function record_glosa()
    {
        if (!has_permission('payments', '', 'create')) {
            access_denied('Record Payment');
        }
        
        
        if ($this->input->post()) {
         
            $dados = $this->input->post();      
            $this->load->model('payments_model');
            $pagamentos_id = $this->payments_model->process_glosa_conta_receber($this->input->post(), '');
            if ($pagamentos_id) {
                $fatura_id = $this->input->post('invoiceid');
                $this->invoices_model->log_invoice_activity($fatura_id, 'registrou_novo_pagamento', false, serialize($dados));
                
                
               
                 set_alert('success', 'Glosa Cadastrado com Sucesso');
                
                 
               //redirect(admin_url('payments/payment/' . $id));
                redirect(admin_url('financeiro_invoices/list_invoices/' . $this->input->post('invoiceid')));
            } else {
                
                
                set_alert('danger', _l('invoice_payment_record_failed'));
            }
            redirect(admin_url('financeiro_invoices/list_invoices/' . $this->input->post('invoiceid')));
        }
    }
    
    /* Send invoice to email */
    public function send_to_email($id)
    {
        $canView = user_can_view_invoice($id);
        if (!$canView) {
            access_denied('Invoices');
        } else {
            if (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own') && $canView == false) {
                access_denied('Invoices');
            }
        }

        try {
            $statementData = [];
            if ($this->input->post('attach_statement')) {
                $statementData['attach'] = true;
                $statementData['from']   = to_sql_date($this->input->post('statement_from'));
                $statementData['to']     = to_sql_date($this->input->post('statement_to'));
            }

            $success = $this->invoices_model->send_invoice_to_client(
                $id,
                '',
                $this->input->post('attach_pdf'),
                $this->input->post('cc'),
                false,
                $statementData
            );
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        // In case client use another language
        load_admin_language();
        if ($success) {
            set_alert('success', _l('invoice_sent_to_client_success'));
        } else {
            set_alert('danger', _l('invoice_sent_to_client_fail'));
        }
        redirect(admin_url('invoices/list_invoices/' . $id));
    }

    /* Delete invoice payment*/
    public function delete_payment($id, $invoiceid)
    {
        if (!has_permission('payments', '', 'delete')) {
            access_denied('payments');
        }
        $this->load->model('payments_model');
        if (!$id) {
            redirect(admin_url('financeiro_invoices'));
        }
        
        $response = $this->payments_model->delete_financeiro($id, $invoiceid);
        
        if ($response == true) {
            set_alert('success', _l('deleted', _l('payment')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('payment_lowercase')));
        }
        redirect(admin_url('financeiro_invoices/list_invoices/' . $invoiceid));
    }

    /* Delete invoice payment*/
    public function delete_glosa($id, $invoiceid)
    {
        if (!has_permission('payments', '', 'delete')) {
            access_denied('payments');
        }
        $this->load->model('payments_model');
        if (!$id) {
            redirect(admin_url('financeiro_invoices'));
        }
        
        $response = $this->payments_model->delete_glosa($id, $invoiceid);
        
        if ($response == true) {
            set_alert('success', _l('deleted', _l('payment')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('payment_lowercase')));
        }
        redirect(admin_url('financeiro_invoices/list_invoices/' . $invoiceid));
    }
    
    /* Delete invoice */
    public function delete($id)
    {
        if (!has_permission('invoices', '', 'delete')) {
            access_denied('invoices');
        }
        if (!$id) {
            redirect(admin_url('financeiro_invoices/list_invoices'));
        }
        $success = $this->Financeiro_model->delete($id);
        $this->invoices_model->log_invoice_activity($id, 'delete_invoice_fatura');
        if ($success) {
            set_alert('success', _l('deleted', _l('invoice')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_lowercase')));
        }
        if (strpos($_SERVER['HTTP_REFERER'], 'list_invoices') !== false) {
            redirect(admin_url('financeiro_invoices/list_invoices'));
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function delete_attachment($id)
    {
        $file = $this->misc_model->get_file($id);
        if ($file->staffid == get_staff_user_id() || is_admin()) {
            echo $this->invoices_model->delete_attachment($id);
        } else {
            header('HTTP/1.0 400 Bad error');
            echo _l('access_denied');
            die;
        }
    }

    /* Will send overdue notice to client */
    public function send_overdue_notice($id)
    {
        $canView = user_can_view_invoice($id);
        if (!$canView) {
            access_denied('Invoices');
        } else {
            if (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own') && $canView == false) {
                access_denied('Invoices');
            }
        }

        $send = $this->invoices_model->send_invoice_overdue_notice($id);
        if ($send) {
            set_alert('success', _l('invoice_overdue_reminder_sent'));
        } else {
            set_alert('warning', _l('invoice_reminder_send_problem'));
        }
        redirect(admin_url('invoices/list_invoices/' . $id));
    }

    /* Generates invoice PDF and senting to email of $send_to_email = true is passed */
    public function pdf($id)
    {
        if (!$id) {
            redirect(admin_url('invoices/list_invoices'));
        }

        $canView = user_can_view_invoice($id);
        if (!$canView) {
            access_denied('Invoices');
        } else {
            if (!has_permission('invoices', '', 'view') && !has_permission('invoices', '', 'view_own') && $canView == false) {
                access_denied('Invoices');
            }
        }

        $invoice        = $this->invoices_model->get($id);
        $invoice        = hooks()->apply_filters('before_admin_view_invoice_pdf', $invoice);
        $invoice_number = format_invoice_number($invoice->id);

        try {
            $pdf = invoice_pdf($invoice);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'D';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $pdf->Output(mb_strtoupper(slug_it($invoice_number)) . '.pdf', $type);
    }

    public function mark_as_sent($id)
    {
        if (!$id) {
            redirect(admin_url('invoices/list_invoices'));
        }
        if (!user_can_view_invoice($id)) {
            access_denied('Invoice Mark As Sent');
        }

        $success = $this->invoices_model->set_invoice_sent($id, true);

        if ($success) {
            set_alert('success', _l('invoice_marked_as_sent'));
        } else {
            set_alert('warning', _l('invoice_marked_as_sent_failed'));
        }

        redirect(admin_url('invoices/list_invoices/' . $id));
    }

    public function get_due_date()
    {
        if ($this->input->post()) {
            $date    = $this->input->post('date');
            $duedate = '';
            if (get_option('invoice_due_after') != 0) {
                $date    = to_sql_date($date);
                $d       = date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime($date)));
                $duedate = _d($d);
                echo $duedate;
            }
        }
    }
    
    
    
    
     
    
}
