<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Financeiro extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Financeiro_model');
        
        $this->load->model('cron_model');
        $this->load->model('Misc_model');
        
        $this->load->model('payments_model');
        $this->load->model('payment_modes_model');
        $this->load->model('invoice_items_model');
        
       
        
        
    }


    public function table_conta_pagar()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('conta_pagar');
    }
    
    public function table_conta_pagar_parcela()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('conta_pagar_parcela');
    }

    public function table_plano_conta()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('plano_contas');
    }
    
    public function table_movimento_bancario()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('movimento_bancario');
    }
    
    public function table_fluxo_lancamentos()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('fluxo_lanamentos');
    }
    
    public function table_fornecedor()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('fornecedores');
    }
    
    public function table_banco()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('bancos');
    }
    
    public function table_cliente()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('clientes_financeiro');
    }
    
    public function table_centro_custo()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('centro_custo');
    }
    
    public function table_forma_pgto()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('modo_pagamento_financeiro');
    }

   
    /* Edit client or add new client*/
    public function cadastros($id = '')
    {
        if (!has_permission('customers', '', 'view')) {
            if ($id != '' && !is_customer_admin($id)) {
                access_denied('customers');
            }
        }
        
       
       
        $group         = $this->input->get('group') ? $this->input->get('group') : 'plano_contas';
        $data['group'] = $group;
        
        $data['customer_tabs'] = get_financeiro_profile_tabs();
          
           // print_r($data['customer_tabs'][$group]); exit;
           // $data['contacts'] = $this->clients_model->get_contacts($id);
            $data['tab']      = isset($data['customer_tabs'][$group]) ? $data['customer_tabs'][$group] : null;
            
            if (!$data['tab']) {
             //   show_404();
            }
           
            // Fetch data based on groups
            if ($group == 'plano_contas') {
                
              $data['categorias'] = $this->Financeiro_model->get_categorias();
              $data['planos_contas'] = $this->Financeiro_model->get_plano_contas("");               
            } elseif ($group == 'fornecedores') {
                
            } elseif ($group == 'clientes') {
                // $data['items'] = $this->invoice_items_model->get_grouped_all_medicos($id);    
            }elseif ($group == 'centro_custo') {
              $data['tipos'] = $this->Financeiro_model->get_tipo_centro_custo();
            }

          
            $title = 'Cadastros Financeiros';

            // Get all active staff members (used to add reminder)
            $data['members'] = $data['staff'];
       
        $data['bodyclass'] = 'customer-profile dynamic-create-groups';
        $data['title']     = $title;
         
        $this->load->view('admin/financeiro/cadastros/cadastro', $data);
    }
    
    /*
     * MOVIMENTAÇÃO BANCÁRIA
     */
    public function movimentacao_bancaria($id = '')
    {
        $data['bancos'] = $this->Financeiro_model->get_bancos();
        $title = 'Movimentação Bancária';
        $data['title']     = $title;
        $this->load->view('admin/financeiro/movimentacao_bancaria/index', $data);
    }
    
    
    public function manage_movimentacao_bancaria()
    {
       
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['id'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                   
                    $id      = $this->Financeiro_model->add_movimento_bancario($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', 'Movimentação');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                } 
            }
        }
    }
    
    
    public function manage_transferencia_bancaria()
    {
       
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['id'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                   
                    $this->Financeiro_model->add_transferencia_bancario($data);
                    $success = true;
                    $message = _l('added_successfully', 'Movimentação');
                    redirect(admin_url('financeiro/movimentacao_bancaria'));
                    
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                    
                     
                } 
            }
        }
    }
    
    /*
     * PLANOS CONTAS
     */
    public function manage_plano_conta()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['id'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                   
                    $id      = $this->Financeiro_model->add_edit_plano_conta($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', 'Plano de Contas');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->Financeiro_model->get_plano_contas($id),
                    ]);
                } else {
                   
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id_reg = $data['id'];
                    $success = $this->Financeiro_model->add_edit_plano_conta($data, $id_reg);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', 'Plano de Contas');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }
            }
        }
    }
    
    
    /* Delete plano conta*/
    public function delete_plano_conta($id)
    {
       
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }
        
        if (!$id) {
            redirect(admin_url('financeiro/cadastros'));
        }

        $data['deleted'] = 1;
        $response = $this->Financeiro_model->delete_plano_conta($data, $id);
        if ($response == true) {
            set_alert('success', _l('deleted').' Plano de Conta');
        } 
        redirect(admin_url('financeiro/cadastros'));
    }
    
    
    /* Get item by id / ajax */
    public function get_plano_conta_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item  = $this->Financeiro_model->get_plano_contas($id);
            $item->descricao   = nl2br($item->descricao);
           
            echo json_encode($item);// exit;
        }
    }
    
    /*
     * FIM PLANO DE CONTAS
     */
    
    
    /*
     * CATEGORIA
     */
    
    public function add_categoria()
    {
       // echo 'aqui'; exit;
        if ($this->input->post() && has_permission('items', '', 'create')) {
            $this->Financeiro_model->add_categoria($this->input->post());
            set_alert('success', _l('added_successfully', 'Categoria'));
        }
    }

    public function update_categoria($id)
    {
        if ($this->input->post() && has_permission('items', '', 'edit')) {
            $this->Financeiro_model->edit_categoria($this->input->post(), $id);
            set_alert('success', _l('updated_successfully', 'Categoria'));
        }
    }

    public function delete_categoria($id)
    {
        if (has_permission('items', '', 'delete')) {
            $data['deleted'] = 1;
            if ($this->Financeiro_model->edit_categoria($data, $id)) {
                set_alert('success', _l('deleted', 'Categoria'));
            }
        }
        redirect(admin_url('financeiro/cadastros/?groups_modal=true'));
    }
    
    /*
     * FIM CATEGORIA
     */
    
    
    
    /*
     * FORNECEDORES
     */
    public function manage_fornecedores()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
               
                if ($data['id'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                   
                    $id      = $this->Financeiro_model->add_edit_fornecedores($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', 'Fornecedor');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->Financeiro_model->get_fornecedores($id),
                    ]);
                } else {
                   
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id_reg = $data['id'];
                    $success = $this->Financeiro_model->add_edit_fornecedores($data, $id_reg);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', 'Fornecedor');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }
            }
        }
    }
    
    /* Delete plano conta*/
    public function delete_fornecedores($id)
    {
       
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }
        
        if (!$id) {
            redirect(admin_url('financeiro/cadastros/?group=fornecedores'));
        }

        $data['deleted'] = 1;
        $response = $this->Financeiro_model->delete_fornecedores($data, $id);
        if ($response == true) {
            set_alert('success', _l('deleted').' Fornecedor');
        } 
        redirect(admin_url('financeiro/cadastros/?group=fornecedores'));
    }
    
    /* Get item by id / ajax */
    public function get_fornecedor_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item  = $this->Financeiro_model->get_fornecedores($id);
            echo json_encode($item);// exit;
        }
    }
    
    /*
     * FIM FORNECEDORES
     */
    
    
    
    /*
     * CLIENTES
     */
    public function manage_clientes()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                // print_R($data); exit;
                if ($data['id'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                  
                    $id      = $this->Financeiro_model->add_edit_clientes($data);
                    $success = false;
                    $message = 'OPS, ERRO AO SALVAR O CADASTRO!';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', 'Cliente');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->Financeiro_model->get_clientes($id),
                    ]);
                   
                } else {
                   
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id_reg = $data['id'];
                    $success = $this->Financeiro_model->add_edit_clientes($data, $id_reg);
                    $message = 'FALHA!';
                    if ($success) {
                        $message = _l('updated_successfully', 'Cliente');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }
            }
        }
        
    }
    
    
    /* Delete plano conta*/
    public function delete_clientes($id)
    {
       
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }
        
        if (!$id) {
            redirect(admin_url('financeiro/cadastros/?group=clientes'));
        }

        $data['deleted'] = 1;
        $response = $this->Financeiro_model->delete_clientes($data, $id);
        if ($response == true) {
            set_alert('success', ' Cliente '._l('deleted'));
        } 
        redirect(admin_url('financeiro/cadastros/?group=clientes'));
    }
    
    
    /* Get item by id / ajax */
    public function get_clientes_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item  = $this->Financeiro_model->get_clientes($id);
            echo json_encode($item);// exit;
        }
    }
    
    /*
     * FIM CLIENTES
     */
    
    
    
    /*
     * CENTRO DE CUSTO
     */
    public function manage_centro_custo()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['id'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                   
                    $id      = $this->Financeiro_model->add_edit_centro_custo($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', 'Centro de Custo');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->Financeiro_model->get_plano_contas($id),
                    ]);
                } else {
                   
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id_reg = $data['id'];
                    $success = $this->Financeiro_model->add_edit_centro_custo($data, $id_reg);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', 'Centro de Custo');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }
            }
        }
    }
    
    
    /* Delete centro de custo*/
    public function delete_centro_custo($id)
    {
       
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }
        
        if (!$id) {
            redirect(admin_url('financeiro/cadastros'));
        }

        $data['deleted'] = 1;
        $response = $this->Financeiro_model->delete_centro_custo($data, $id);
        if ($response == true) {
            set_alert('success', ' Centro de Custo'. _l('deleted'));
        } 
        redirect(admin_url('financeiro/cadastros/?group=centro_custo'));
    }
    
    
    /* Get centro de custo by id / ajax */
    public function get_centro_custo_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item  = $this->Financeiro_model->get_centro_custo($id);
            echo json_encode($item);// exit;
        }
    }
    
    /*
     * FIM CENTRO DE CUSTO
     */
    
    
    /*
     * TIPO CENTRO DE CUSTO
     */
    
    public function add_tipo_centro_custo()
    {
       //echo 'aqui'; exit;
        if ($this->input->post() && has_permission('items', '', 'create')) {
            $this->Financeiro_model->add_tipo_centro_custo($this->input->post());
            set_alert('success', _l('added_successfully', 'Categoria'));
        }
    }

    public function update_tipo_centro_custo($id)
    {
        if ($this->input->post() && has_permission('items', '', 'edit')) {
            $this->Financeiro_model->edit_tipo_centro_custo($this->input->post(), $id);
            set_alert('success', _l('updated_successfully', 'Categoria'));
        }
    }

    public function delete_tipo_centro_custo($id)
    {
        if (has_permission('items', '', 'delete')) {
            $data['deleted'] = 1;
            if ($this->Financeiro_model->delete_tipo_centro_custo($data, $id)) {
                set_alert('success', 'Cadatro'. _l('deleted'));
            }
        }
        redirect(admin_url('financeiro/cadastros/?group=centro_custo'));
    }
    
    /*
     * FIM CENTRO DE CUSTO
     */
    
    
    /*
     * FORNECEDORES
     */
    public function manage_bancos()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
               
                if ($data['id'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                   
                    $id      = $this->Financeiro_model->add_edit_bancos($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', 'Banco');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->Financeiro_model->get_bancos($id),
                    ]);
                } else {
                   
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id_reg = $data['id'];
                    $success = $this->Financeiro_model->add_edit_bancos($data, $id_reg);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', 'Banco');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }
            }
        }
    }
    
    /* Delete plano conta*/
    public function delete_bancos($id)
    {
       
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }
        
        if (!$id) {
            redirect(admin_url('financeiro/cadastros/?group=bancos'));
        }

        $data['deleted'] = 1;
        $response = $this->Financeiro_model->delete_bancos($data, $id);
        if ($response == true) {
            set_alert('success', _l('deleted').' Banco');
        } 
        redirect(admin_url('financeiro/cadastros/?group=bancos'));
    }
    
    /* Get item by id / ajax */
    public function get_banco_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item  = $this->Financeiro_model->get_bancos($id);
            echo json_encode($item);// exit;
        }
    }
    
    /*
     * FIM FORNECEDORES
     */
    
    
    /*
     * MODO PAGAMENTO
     */
    public function manage_modo_pagamento()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
               
                if ($data['id'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                   
                    $id      = $this->Financeiro_model->add_edit_forma_pagamento($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', 'Forma de Pagamento');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'item'    => $this->Financeiro_model->get_forma_pagamento($id),
                    ]);
                } else {
                   
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id_reg = $data['id'];
                    $success = $this->Financeiro_model->add_edit_forma_pagamento($data, $id_reg);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', 'Forma de Pagamento');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }
            }
        }
    }
    
    /* Delete plano conta*/
    public function delete_modo_pagamento($id)
    {
       
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }
        
        if (!$id) {
            redirect(admin_url('financeiro/cadastros/?group=forma_pagamento'));
        }

        $data['deleted'] = 1;
        $response = $this->Financeiro_model->delete_forma_pagamento($data, $id);
        if ($response == true) {
            set_alert('success', ' Forma de Pagamento'._l('deleted'));
        } 
        redirect(admin_url('financeiro/cadastros/?group=forma_pagamento'));
    }
    
    /* Get item by id / ajax */
    public function get_modo_pagamento_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item  = $this->Financeiro_model->get_forma_pagamento($id);
            echo json_encode($item);// exit;
        }
    }
    
    /*
     * FIM MODO PAGAMENTO
     */
    
    
    
    
    /**************************************************************************
     *************************** CONTA A PAGAR ********************************
     **************************************************************************/
    
    public function index($id = '')
    {
        $this->list_conta_pagar($id);
    }
    
    public function list_conta_pagar($id = '')
    {
      //echo $id; exit;
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('invoices');
        }
        
        close_setup_menu();
        $data['invoiceid']            = $id;
        // categorias de despesas
        
        $data['categorias_financeira'] = $this->Financeiro_model->get_categorias(1);
       
        // bancos
        $data['bancos'] = $this->Financeiro_model->get_bancos();
        // fornecedores
        $data['fornecedores'] = $this->Financeiro_model->get_fornecedores();
        // centro de custo
        $data['centro_custo'] = $this->Financeiro_model->get_centro_custo();
        // número documentos
        $data['numero_documentos'] = $this->Financeiro_model->get_documento_conta_pagar(); 
        
        $data['tipos_documentos'] = $this->Financeiro_model->get_tipos_documentos();
        
        $data['planos_conta'] = $this->Financeiro_model->get_plano_contas_by_categoria();
                

        $data['bodyclass']            = 'invoices-total-manual';
        $this->load->view('admin/financeiro/conta_pagar/manage', $data);
    }
    
    public function add_conta_pagar($id = '')
    {
      //echo $id; exit;
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('invoices');
        }
        
        close_setup_menu();
        $data['invoiceid']            = $id;
        // categorias de despesas
        
        $data['categorias_financeira'] = $this->Financeiro_model->get_categorias(1);
       
        // bancos
        $data['bancos'] = $this->Financeiro_model->get_bancos();
        // fornecedores
        $data['fornecedores'] = $this->Financeiro_model->get_fornecedores();
        // centro de custo
        $data['centro_custo'] = $this->Financeiro_model->get_centro_custo();
        // centro de custo
         
        $data['tipos_documentos'] = $this->Financeiro_model->get_tipos_documentos();
        
        $data['planos_conta'] = $this->Financeiro_model->get_plano_contas_by_categoria();
                

        $data['bodyclass']            = 'invoices-total-manual';
        $this->load->view('admin/financeiro/conta_pagar/add_conta_pagar', $data);
        
    }
    
    public function parcelas_conta_pagar($id = '')
    {
      //echo $id; exit;
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('invoices');
        }
        
        $data['parcelas']          = $this->Financeiro_model->get_parcelas_conta_pagar($id);
        $data['titulo'] = $this->Financeiro_model->get_conta_pagar($id);        
        $data['bancos'] = $this->Financeiro_model->get_bancos();
        // formas de pagamento
        $data['formas_pagamentos'] = $this->Financeiro_model->get_forma_pagamento(); 

        $data['bodyclass']            = 'invoices-total-manual';
        $this->load->view('admin/financeiro/conta_pagar/parcelas_conta_pagar', $data);
        
    }
    
    // tela de pagamento da parcela
    public function pagamento_parcelas_conta_pagar($id = '')
    {
      //echo $id; exit;
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('invoices');
        }
        
        $data['parcelas']    = $this->Financeiro_model->get_parcelas_conta_pagar('', $id);
        $titulo_id = $data['parcelas']->titulo_id;
        $data['titulo'] = $this->Financeiro_model->get_conta_pagar($titulo_id);        
        $data['bancos'] = $this->Financeiro_model->get_bancos();
        $data['titulo_id'] = $titulo_id;
        // formas de pagamento
        $data['formas_pagamentos'] = $this->Financeiro_model->get_forma_pagamento(); 

        $data['bodyclass']            = 'invoices-total-manual';
        $this->load->view('admin/financeiro/conta_pagar/parcelas/pagamento_parcelas_conta_pagar', $data);
        
    }
    
    public function manage_conta_pagar()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
               
                if ($data['id'] == '') {
                    if (!has_permission('items', '', 'create')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    
                    $id      = $this->Financeiro_model->add_edit_conta_pagar($data);
                    
                    $success = true;
                    $message = '';
                  
                    // vai p parcelas
                    if($id){
                    redirect(admin_url('financeiro/parcelas_conta_pagar/'.$id));
                    }else{
                        
                    }
                    //$this->parcelas_conta_pagar($id);
                } else {
                   
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id_reg = $data['id'];
                   
                    $success = $this->Financeiro_model->add_edit_conta_pagar($data, $id_reg);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', 'Conta a pagar');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                    redirect(admin_url('financeiro'));
                }
            }
        }
    }

    public function editar_titulo_pagar($id = '')
    {
      //echo $id; exit;
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('invoices');
        }
        
        close_setup_menu();
        $data['invoiceid']            = $id;
        // categorias de despesas
        
        $data['categorias_financeira'] = $this->Financeiro_model->get_categorias(1);
       
        // bancos
        $data['bancos'] = $this->Financeiro_model->get_bancos();
        // fornecedores
        $data['fornecedores'] = $this->Financeiro_model->get_fornecedores();
        // centro de custo
        $data['centro_custo'] = $this->Financeiro_model->get_centro_custo();
        // centro de custo
         
        $data['tipos_documentos'] = $this->Financeiro_model->get_tipos_documentos();
        
        $data['planos_conta'] = $this->Financeiro_model->get_plano_contas_by_categoria();
         
        $data['titulo'] = $this->Financeiro_model->get_conta_pagar($id);

        $data['bodyclass']            = 'invoices-total-manual';
        $this->load->view('admin/financeiro/conta_pagar/editar_conta_pagar', $data);
    }
    
    
    public function bulk_action_parcelas()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        if ($this->input->post()) {
            $ids                   = $this->input->post('ids');
           
            $has_permission_delete = has_permission('items', '', 'delete');
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_pgto')) {
                        if ($has_permission_delete) {
                            if ($this->Financeiro_model->add_baixa_parcela($id)) {
                                $total_deleted++;
                            }
                        }
                    }
                }
            }
        }

        if ($this->input->post('mass_delete')) {
            set_alert('success', _l('total_items_deleted', $total_deleted));
        }
    }
    
    public function bulk_action_add_pagamento()
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        
       
        if ($this->input->post()) {
            $banco_id           = $this->input->post('banco_id');
            $data_pagamento     = $this->input->post('data_pagamento');
            $forma_pagamento    = $this->input->post('forma_pagamento');
            $desconto              = $this->input->post('desconto');
            $descricao            = $this->input->post('descricao');
            $ids                   = $this->input->post('ids');
           
            $empresa_id = $this->session->userdata('empresa_id');
            $hoje = date('Y-m-d H:i:s');
        
            if($banco_id){
                if($data_pagamento){
                    if($forma_pagamento){
                
                            if (is_array($ids)) {
                                foreach ($ids as $id) {
                                        $dados_pag['banco_id']                  = 1;
                                        $dados_pag['data_pagamento']            = $data_pagamento;
                                        $dados_pag['forma_pagamento']           = $forma_pagamento;
                                        $dados_pag['desconto']                    = $desconto;
                                        $dados_pag['descricao']                = $descricao;
                                        $dados_pag['user_ultima_alteracao']     = get_staff_user_id();;
                                        $dados_pag['data_ultima_alteracao']     = $hoje;    
                                        
                                        if ($this->Financeiro_model->edit_parcela_pagamento($dados_pag, $id)) {
                                            $total_deleted++;
                                        }
                                        
                                    
                                }
                            }
                       
                                
                                
                                
                              //  echo '-------<br><br>';
                                //$this->db->insert(db_prefix() . 'events', $dados_seg);
                                $total_deleted++;
                            
                        
                
                    }// if dia semana
                
                }// if competencia
            }// if medico
             
        }// if post

        if ($total_deleted) {
            set_alert('success', _l('total_events_created', $total_deleted));
        }
    }
    
    
    public function registra_add_pagamento_by_id($parcela_id)
    {
        hooks()->do_action('before_do_bulk_action_for_items');
        $total_deleted = 0;
        
       
        if ($this->input->post()) {
            $dados_parcela  = $this->Financeiro_model->get_parcelas_conta_pagar('', $parcela_id);
            $titulo_id =    $dados_parcela->titulo_id;
            
            $banco_id           = $this->input->post('banco_id');
            $data_pagamento     = $this->input->post('data_pagamento');
            $forma_pagamento    = $this->input->post('forma_pagamento');
            $desconto              = $this->input->post('desconto');
            $descricao            = $this->input->post('descricao');
            
            $empresa_id = $this->session->userdata('empresa_id');
            $hoje = date('Y-m-d H:i:s');
        
            if($banco_id){
                if($data_pagamento){
                    if($forma_pagamento){
                        if ($parcela_id) {
                            $dados_pag['banco_id']                  = 1;
                            $dados_pag['data_pagamento']            = $data_pagamento;
                            $dados_pag['forma_pagamento']           = $forma_pagamento;
                            $dados_pag['desconto']                    = $desconto;
                            $dados_pag['descricao']                = $descricao;
                            $dados_pag['user_ultima_alteracao']     = get_staff_user_id();;
                            $dados_pag['data_ultima_alteracao']     = $hoje;    
                            
                            if ($this->Financeiro_model->edit_parcela_pagamento($dados_pag, $parcela_id)) {
                                set_alert('success', 'Pagamento Registrado');
                                redirect(admin_url('financeiro/parcelas_conta_pagar/'.$titulo_id));
                            }
                        }
                         
                
                    }// if dia semana
                
                }// if competencia
            }// if medico
             
        }// if post

        
    }
    
    public function manage_conta_pagar_parcelas()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
               
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id_reg = $data['id'];
                    $success = $this->Financeiro_model->add_edit_conta_pagar_parcela($data, $id_reg);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', 'Parcela');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                
            }
        }
    }
    
    public function manage_parcelas()
    {
        if (has_permission('items', '', 'view')) {
            if ($this->input->post()) {
                $data = $this->input->post();
               
                    if (!has_permission('items', '', 'edit')) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id_reg = $data['id'];
                    $success = $this->Financeiro_model->edit_parcela($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', 'Parcela');
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                
            }
        }
    }
    
    public function get_conta_pagar_data_ajax($id)
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
        
        $invoice = $this->invoices_model->get($id);
        $conta_pagar = $this->Financeiro_model->get_conta_pagar($id);

        if (!$invoice || !user_can_view_invoice($id)) {
            echo _l('invoice_not_found');
            die;
        }

     //    $conta_pagar = $this->Financeiro_model->get_conta_pagar($titulo_id);


        $conta_pagar->data_vencimento    = _d($conta_pagar->data_vencimento);
        $conta_pagar->duedate = _d($conta_pagar->duedate);

        $data['parcelas']          = $this->Financeiro_model->get_parcelas_conta_pagar($id);
        // bancos
        $data['bancos'] = $this->Financeiro_model->get_bancos();
        // formas de pagamento
        $data['formas_pagamentos'] = $this->Financeiro_model->get_forma_pagamento(); 
        

        $data['invoice'] = $conta_pagar;

        $data['record_payment'] = false;
        $data['send_later']     = false;

      

        $this->load->view('admin/financeiro/conta_pagar/conta_pagar_preview_template', $data);
    }
    
    /* Get item by id / ajax */
    public function get_conta_pagar_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item  = $this->Financeiro_model->get_conta_pagar($id);
            echo json_encode($item);// exit;
        }
    }
    
    public function get_parcela_by_id($id)
    {   
        if ($this->input->is_ajax_request()) {
            $item  = $this->Financeiro_model->get_parcela($id);
            echo json_encode($item);// exit;
        }
    }
    
    /* Get item by id / ajax */
    public function get_conta_pagar_parcela_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item  = $this->Financeiro_model->get_parcelas_conta_pagar('', $id);
            echo json_encode($item);// exit;
        }
    }
    
    public function retorno_plano_conta(){
       $categoria_id = $this->input->post("categoria_id");
       $plano_id = $this->input->post("plano_id");
     //  $producao_detalhes_resumo_medico = $this->dashboard_model->get_resumo_detalhes_producao_medico_by_id($where_convenio, $mes_2020, $medico_id);
       $data['planos_conta'] = $this->Financeiro_model->get_plano_contas_by_categoria($categoria_id);
       $data['categoria_id'] = $categoria_id;
       $data['plano_id'] = $plano_id;
       $this->load->view('admin/financeiro/conta_pagar/retorno_plano_conta', $data);
    }
    
    /* Delete conta pagar*/
    public function delete_conta_pagar($id)
    {
       
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }
        
        if (!$id) {
            redirect(admin_url('financeiro'));
        }

        $data['deleted'] = 1;
        $response = $this->Financeiro_model->delete_conta_pagar($data, $id);
        if ($response == true) {
            set_alert('success', 'Conta pagar '._l('deleted'));
        } 
        redirect(admin_url('financeiro'));
    }
    
    /* Delete conta pagar*/
    public function delete_pagamento_parcela($id)
    {
       
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }
        
        if (!$id) {
            redirect(admin_url('financeiro/list_conta_pagar_parcelas'));
        }
        
        $response = $this->Financeiro_model->cancela_parcela_pagamento($id);
        
        if ($response == true) {
            set_alert('success', 'Pagamento '._l('deleted'));
        } 
        redirect(admin_url('financeiro/list_conta_pagar_parcelas'));
    }
    
    public function list_conta_pagar_parcelas($id = '')
    {
      //echo $id; exit;
        if (!has_permission('invoices', '', 'view')
            && !has_permission('invoices', '', 'view_own')
            && get_option('allow_staff_view_invoices_assigned') == '0') {
            access_denied('invoices');
        }
     
        close_setup_menu();
        $data['invoiceid']            = $id;
        // categorias de despesas
        $data['categorias_financeira'] = $this->Financeiro_model->get_categorias(1);
        // bancos
        $data['bancos'] = $this->Financeiro_model->get_bancos();
        // fornecedores
        $data['fornecedores'] = $this->Financeiro_model->get_fornecedores();
        // centro de custo
        $data['centro_custo'] = $this->Financeiro_model->get_centro_custo();
        // centro de custo
        $data['tipos_documentos'] = $this->Financeiro_model->get_tipos_documentos();
        
        $data['planos_conta'] = $this->Financeiro_model->get_plano_contas_by_categoria();
        
        $data['formas_pagamentos'] = $this->Financeiro_model->get_forma_pagamento(); 
         
        $data['bodyclass']            = 'invoices-total-manual';
        $this->load->view('admin/financeiro/conta_pagar/parcelas/manage', $data);
    }
    
    public function delete_conta_pagar_parcela($id)
    {
       
        if (!has_permission('items', '', 'delete')) {
            access_denied('Invoice Items');
        }
        
        if (!$id) {
            redirect(admin_url('financeiro'));
        }

        $data['deleted'] = 1;
        $response = $this->Financeiro_model->delete_conta_pagar_parcela($data, $id);
        if ($response == true) {
            set_alert('success', 'Conta pagar '._l('deleted'));
        } 
        redirect(admin_url('financeiro'));
    }
    /**************************************************************************
     *************************** FIM CONTA A PAGAR ********************************
     **************************************************************************/
}
