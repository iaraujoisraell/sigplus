<?php defined('BASEPATH') or exit('No direct script access allowed');

class Financeiro extends MY_Controller
{

    
    public function __construct()
    { 
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if ($this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->load('financeiro_lang', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->model('sales_model');
        $this->load->model('projetos_model');
        $this->load->model('atas_model');
        $this->load->model('site');
        $this->digital_upload_path = 'assets/uploads/projetos';
        $this->upload_path = 'assets/uploads/projetos';
        $this->thumbs_path = 'assets/uploads/thumbs/projetos';
         $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
    }

    public function index()
    {
        $this->sma->checkPermissions();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
       $parcelas_atrasadas = $this->sales_model->getAllTrsansactionsAbertasAndAtrasadas();
       
       foreach ($parcelas_atrasadas as $parcelas) {
            
           $id = $parcelas->id;
           
                 $payment = array(
                'status' => 'ATRASADO'
                //'usuario_id' => $this->session->userdata('username')
            );

          
             $this->sales_model->updateFinanceiro($id, $payment);
           
         
        }
       // exit;
        
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => 'financeiro', 'page' => lang('financeiro')));
        $meta = array('page_title' => lang('financeiro'), 'bc' => $bc);
        $this->page_construct('financeiro/index', $meta, $this->data);
    }

    public function getSales($warehouse_id = null)
    {
       // $this->sma->checkPermissions('index');
       // $this->sma->checkPermissions();
       
        
        
        $anexo =  anchor('financeiro/anexo/$1', '<i class="fa fa-file-text-o"></i> ' . lang('attachment'));
        //$payments_link = anchor('sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
        $add_payment_link = anchor('financeiro/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('Dados Fatura'), 'data-toggle="modal" data-target="#myModal"');
        //$add_delivery_link = anchor('sales/add_delivery/$1', '<i class="fa fa-truck"></i> ' . lang('add_delivery'), 'data-toggle="modal" data-target="#myModal"');
        //$email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
        $edit_link = anchor('financeiro/edit/$1', '<i class="fa fa-edit"></i> ' . lang('Editar'), 'class="sledit"');
        //$pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));
      //  $return_link = anchor('financeiro/return_sale/$1', '<i class="fa fa-angle-double-left"></i> ' . lang('return_sale'));
           $cancela_link = "<a href='#' class='po' title='<b>" . lang("Cancelar") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('financeiro/cancela/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-ban\"></i> "
        . lang('Cancelar') . "</a>";
           
        $delete_link = "<a href='#' class='po' title='<b>" . lang("Apagar") . "</b>' data-content=\"<p>"
        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('financeiro/delete/$1') . "'>"
        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
        . lang('Apagar') . "</a>";
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          
            <li>' . $add_payment_link . '</li>
            <li>' . $edit_link . '</li>
           
            <li>' . $delete_link . '</li>
        </ul>
    </div></div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';
        
       
         if ($this->input->get('contrato')) {
            $contrato = $this->input->get('contrato');
           // echo $contrato; exit;
        } else {
          // echo $contrato; exit;
            $contrato = NULL;
        }
        if ($this->input->get('provider')) {
            $fornecedor = $this->input->get('provider');
        } else {
            $fornecedor = NULL;
        }
         
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
        if ($start_date) {
            $start_date = $this->sma->fld($start_date);
            $end_date = $this->sma->fld($end_date);
        }
        
         $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
        
        $this->load->library('datatables');
            $this->datatables
                ->select("transactions.id as id, transactions.date, contratos.numero_contrato, companies.company,  transactions.description,  parcela_atual ,  amount, cr,  transactions.status, transactions.attachment")
                ->from('transactions')
                ->join('contratos', 'transactions.id_contrato = contratos.id', 'left')    
                ->join('companies', 'transactions.payer = companies.id', 'left')    
                ->where('type', 'Despesa')
            ->where('contratos.projeto', $projetos_usuario->projeto_atual);
           
            
            if ($contrato) {
                $this->datatables->where('transactions.id_contrato', $contrato);
            }
             if ($fornecedor) {
                $this->datatables->where('transactions.payer', $fornecedor);
            }
             
            if ($start_date) {
                $this->datatables->where('date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
            
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }

     public function movimentacao()
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
       

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => 'financeiro', 'page' => lang('financeiro')));
        $meta = array('page_title' => lang('financeiro'), 'bc' => $bc);
        $this->page_construct('financeiro/transacoes', $meta, $this->data);
    }
    
    
     public function getSales_movimentacao($warehouse_id = null)
    {
        $this->sma->checkPermissions('index');

        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
     
        $this->load->library('datatables');
            $this->datatables
                ->select("id,date,{$this->db->dbprefix('accounts')}.account, type,   dr, cr, description,  concat(parcela_atual,'/',parcela) as parcela, payer, category, method")
                ->from('transactions')
               // ->join('accounts', 'accounts.id=transactions.account_id', 'left')
                ->where('status', 'paid');
     
       
      
        echo $this->datatables->generate();
    }
    
    public function return_sales($warehouse_id = null)
    {
        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : null;
        } else {
            $user = $this->site->getUser();
            $this->data['warehouses'] = null;
            $this->data['warehouse_id'] = $user->warehouse_id;
            $this->data['warehouse'] = $user->warehouse_id ? $this->site->getWarehouseByID($user->warehouse_id) : null;
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('return_sales')));
        $meta = array('page_title' => lang('return_sales'), 'bc' => $bc);
        $this->page_construct('sales/return_sales', $meta, $this->data);
    }

    public function getReturns($warehouse_id = null)
    {
        $this->sma->checkPermissions('return_sales', true);

        if (!$this->Owner && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }

            $detail_link = anchor('sales/modal_view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('sale_details'), 'data-toggle="modal" data-target="#myModal"');
            $payments_link = anchor('sales/payments/$1', '<i class="fa fa-money"></i> ' . lang('view_payments'), 'data-toggle="modal" data-target="#myModal"');
            $add_payment_link = anchor('sales/add_payment/$1', '<i class="fa fa-money"></i> ' . lang('add_payment'), 'data-toggle="modal" data-target="#myModal"');
            $email_link = anchor('sales/email/$1', '<i class="fa fa-envelope"></i> ' . lang('email_sale'), 'data-toggle="modal" data-target="#myModal"');
            $pdf_link = anchor('sales/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));

            $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
            <ul class="dropdown-menu pull-right" role="menu">
                <li>' . $detail_link . '</li>
                <li>' . $payments_link . '</li>
                <li>' . $add_payment_link . '</li>
                <li>' . $pdf_link . '</li>
                <li>' . $email_link . '</li>
            </ul>
        </div></div>';

        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select("date, return_sale_ref, reference_no, biller, customer, surcharge, grand_total, id")
                ->from('sales')
                ->where('warehouse_id', $warehouse_id);
        } else {
            $this->datatables
                ->select("date, return_sale_ref, reference_no, biller, customer, surcharge, grand_total, id")
                ->from('sales');
        }
        $this->datatables->where('sale_status', 'returned');

        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
            $this->datatables->where('created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('customer_id', $this->session->userdata('customer_id'));
        }

        $this->datatables->add_column("Actions", $action, "id");

        echo $this->datatables->generate();
    }


    /* ------------------------------------------------------------------ */

    public function add($quote_id = null)
    {
        $this->sma->checkPermissions();

        //$this->form_validation->set_message('is_natural_no_zero', lang("no_zero_required"));
        $this->form_validation->set_rules('contrato', lang("contrato"), 'required');
        $this->form_validation->set_rules('description', lang("description"), 'required');
        $this->form_validation->set_rules('value', lang("value"), 'required');
        $this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');
        $this->form_validation->set_rules('categorie', lang("categorie"), 'required');
        $this->form_validation->set_rules('quantidade', lang("quantity_plots"), 'required');
        // $this->form_validation->set_rules('account', lang("account"), 'required');

        $date_baixa = date('Y-m-d H:i:s');               
        $payment_status = $this->input->post('payment_status');
        $value = str_replace(',', '.', str_replace('.', '', $this->input->post('value')));
         
         
        $data_baixa = "";
        $valor_baixado = 0;
        
        
        if($payment_status == 'paid'){
            $this->form_validation->set_rules('pagament_form', lang("pagament_form"), 'required');
            $data_baixa = $date_baixa;
        }
        
        if($payment_status != 'pending'){
            $valor_baixado = $value;
        }
        
        
            
        if ($this->form_validation->run() == true) {
            
           
            $contrato = $this->input->post('contrato');
           
             $provider_id = $this->input->post('provider'); 
            // $value = $this->input->post('value');
            $value = str_replace(',', '.', str_replace('.', '', $this->input->post('value')));
            $categorie = $this->input->post('categorie');
            $date_apagar = $this->input->post('date');
            $date_apagar_parcelas = $this->sma->hrld($this->input->post('date'));
            $description = $this->input->post('description');
            $payment_status = $this->input->post('payment_status');
            $quantidade = $this->input->post('quantidade');
           
        
          $usuario = $this->site->getUser($this->session->userdata('user_id'));
                
               $data = array(
                'type' => 'Despesa',
                'category' => $categorie,
                'amount' => $this->sma->formatDecimal($value),
                'id_contrato' => $contrato,
                'payer' => $provider_id,
                //'method' => $pagament_form ,
                //'ref' => $reference,
                'status' => $payment_status,
                'description' => $description,
                'date' => $date_apagar,
                //'date_pagamento' => $data_baixa,
                //'dr' => $this->sma->formatDecimal($valor_baixado),
                'cr' => '0',   
                'parcela' => $quantidade,
                'id_st' => '',
                'parcela_atual' => '1',
                'usuario_id' => $this->session->userdata('username'),
                //'note' => $note,
               // 'account_id' => $account_id,
               'empresa_id' => '301'
            );
          
           
            
           $id =   $this->sales_model->addExpense($data);
            
            
            
            if($quantidade > 1){
         
            $partes = explode("/", $date_apagar_parcelas);
            $dia = $partes[0];
            $mes = $partes[1];
            $ano2 = $partes[2];
            $partes_ano = explode(" ", $ano2);
            $ano = $partes_ano[0];
            $hora = $partes_ano[1];
            $date_apagar = $ano.'-'.$mes.'-'.$dia;
            $contador = 1;
            $qtde = $quantidade - 1;
            while ($contador <= $qtde) {
        
                $contador++;
                       if ($mes == '12') {
                        $mes = '1';
                        $ano = $ano + '1';
                        //echo $ano;
                    } else {
                        $mes = $mes + '1';
                       // echo $mes;
                    }
                   if(($dia == '31')&&($mes == '04')){
                    $date_apagar = $ano . '-' . $mes . '-' . '30';
                    }else if(($dia == '31')&&($mes == '06')){
                        $date_apagar = $ano . '-' . $mes . '-' . '30';
                    }else if(($dia == '31')&&($mes == '09')){
                        $date_apagar = $ano . '-' . $mes . '-' . '30';
                    }else if(($dia == '31')&&($mes == '11')){
                        $date_apagar = $ano . '-' . $mes . '-' . '30';
                    }else if (($mes == '02') && ($dia >= '29') && ($ano > '2016')) {
                        $date_apagar = $ano . '-' . $mes . '-' . '28';
                    } else if (($mes == '02') && ($dia >= '30') && ($ano == '2016')) {
                        $date_apagar = $ano . '-' . $mes . '-' . '29';
                    } else {
                        $date_apagar = $ano . '-' . $mes . '-' . $dia;                   
                    }   
                    
                     
                $data[$contador] = array(
                    'type' => 'Despesa',
                    'category' => $categorie,
                    'amount' => $this->sma->formatDecimal($value),
                    'status' => 'ABERTO',
                    'payer' => $provider_id,
                    'description' => $description,
                    'date' => $date_apagar.' '.$hora,
                    'dr' => '0',
                    'cr' => '0',
                    'parcela' => $quantidade,
                    'id_st' => $id,
                    'parcela_atual' => $contador,
                    'usuario_id' => $this->session->userdata('username'),
                    'id_contrato' => $contrato,
                    'empresa_id' => '301'
             );
         
                  //print_r($data[$contador]); echo '<br>';
            
                
                 $this->sales_model->addExpense($data[$contador]);
        }
            // exit;
             
                
            }
            
            
            $this->session->set_flashdata('message', lang("Títulos_Cadastrados"));
            redirect("financeiro");
        } else {

        

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['quote_id'] = $quote_id;
            $this->data['billers'] = $this->site->getAllAccount();
            $this->data['contratos'] = $this->site->getAllContratos();
            $this->data['providers'] = $this->site->getAllCompanies('supplier');
            $this->data['expense_categories'] = $this->site->getAllCategories_expencie('DESPESAS');
           
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('financeiro'), 'page' => lang('financeiro')), array('link' => '#', 'page' => lang('add_expends')));
            $meta = array('page_title' => lang('add_expends'), 'bc' => $bc);
            $this->page_construct('financeiro/add', $meta, $this->data);
        }
    }

    /* ------------------------------------------------------------------------ */

    public function anexo($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $inv = $this->sales_model->getContaPagarByID($id);
        $anexo = $inv->attachment;
        

        if($anexo){
        
        $this->load->helper('download');
        
        force_download('./files/'.$anexo, NULL); 
      exit(); 
        
        }else{
            $this->session->set_flashdata('message', lang("noattachment"));
            redirect("financeiro");
        }
       // site_url('welcome/download/' . $anexo);
        
    }
    
    public function edit($id = null)
    {
        $this->sma->checkPermissions();
      
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        $inv = $this->sales_model->getContaPagarByID($id);
        
        $this->form_validation->set_rules('provider', lang("provider"), 'required');
        $this->form_validation->set_rules('description', lang("description"), 'required');
        $this->form_validation->set_rules('value', lang("value"), 'required');
        $this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');
        $this->form_validation->set_rules('categorie', lang("categorie"), 'required');
         
         if ($this->form_validation->run() == true) {
            $date_apagar = $this->sma->fld(trim($this->input->post('date'))); 
            $contrato = $this->input->post('contrato');
            $provider_id = $this->input->post('provider'); 
            $description = $this->input->post('description');
            $value = str_replace(',', '.', str_replace('.', '', $this->input->post('value')));
            $payment_status = $this->input->post('payment_status');
            $categorie = $this->input->post('categorie');
            $note = $this->input->post('note');
            
            $id_p = $this->input->post('id');
           
            
            $data = array(
                'date' => $date_apagar,
                'id_contrato' => $contrato ,
                'payer' => $provider_id,
                'description' => $description,
                'amount' => $value,
                'status' => $payment_status,
                'category' => $categorie,
                'usuario_id' => $this->session->userdata('username'),
                'note' => $note
             );
             
            /*
                  if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
             * 
             */
            
           // echo $id_p; exit;
           //  print_r($data); exit;
             $this->sales_model->updateFinanceiro($id_p, $data);
             
            $this->session->set_flashdata('message', lang("sale_updated"));
            redirect("financeiro");
       
            
            
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['inv'] = $this->sales_model->getContaPagarByID($id);
         
            
            $this->data['id'] = $id;
            $this->data['contratos'] = $this->site->getAllContratos();
            $this->data['billers'] = $this->site->getAllAccount();
            $this->data['providers'] = $this->site->getAllCompanies('supplier');
            $this->data['expense_categories'] = $this->site->getAllCategories_expencie('DESPESAS');

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('edit_sale')));
            $meta = array('page_title' => lang('edit_sale'), 'bc' => $bc);
            $this->page_construct('financeiro/edit', $meta, $this->data);
        }
    }

    /* ------------------------------- */
 public function add_payment($id = null)
    {
        $this->sma->checkPermissions('payments', true);
        $this->load->helper('security');
        
        //$this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('fatura', lang("fatura"), 'required');
        $this->form_validation->set_rules('data_recebimento', lang("data_recebimento"), 'required');
       // $this->form_validation->set_rules('payment_status', lang("payment_status"), 'required');
        
        if ($this->form_validation->run() == true) {
            
           $value = str_replace(',', '.', str_replace('.', '', $this->input->post('valor_pago')));
           $value_pagar = str_replace(',', '.', str_replace('.', '', $this->input->post('value')));
            $payment = array(
                'data_recebimento' => $this->input->post('data_recebimento'),
                'data_envio' => $this->input->post('data_envio'),
                'date_pagamento' => $this->input->post('data_pagamento'),
                'titulo' => $this->input->post('titulo'),
                'fatura' => $this->input->post('fatura'),
                'cr' => $value,
                'amount' => $value_pagar,
                'status' => $this->input->post('payment_status')
            );

            $log = array(
                'date' => $date,
                'usuario_id' => $this->session->userdata('username'),
                'tabela' => 'sma_transactions',
                'id_tabela' => $id,
                'acao' => 'Adicionou pagamento em conta a pagar.',
                'type' => 'insert',
                'ip' => '',
            );
    

            //$this->sma->print_arrays($payment);

        } elseif ($this->input->post('add_payment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }

        if ($this->form_validation->run() == true && $this->sales_model->baixaConta($id,$payment)) {
            $this->session->set_flashdata('message', lang("payment_added"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $sale = $this->sales_model->getContaPagarByID($id);
            
            $this->data['inv'] = $sale;
            $this->data['payment_ref'] = ''; //$this->site->getReference('pay');
            $this->data['modal_js'] = $this->site->modal_js();

            $this->load->view($this->theme . 'financeiro/add_payment', $this->data);
        }
    }
  
    
    public function cancela_pagamento($id = null)
    {
        $this->sma->checkPermissions('payments', true);
        $this->load->helper('security');
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        
            
           $date = date('Y-m-d H:i:s');
            
            $payment = array(
                'date_pagamento' => $date,
                'dr' => '0',
                'method' => '',
                'status' => 'ABERTO'
                //'usuario_id' => $this->session->userdata('username')
            );

            $log = array(
                'date' => $date,
                'usuario_id' => $this->session->userdata('username'),
                'tabela' => 'sma_transactions',
                'id_tabela' => $id,
                'acao' => 'Cancelou pagamento de Título. ID : '.$id,
                'type' => 'update',
                'ip' => '',
            );
    

             $this->sales_model->updateFinanceiro($id, $payment);
             redirect('financeiro');

         

    
    }
    /* ------------------------------- */

    public function delete($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deleteContaPagar($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("sale_deleted");die();
            }
            $this->session->set_flashdata('message', lang('sale_deleted'));
            redirect('financeiro');
        }
    }
    
     public function cancela($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $data = array(
                 'status' => 'ABERTO',
                 'date_pagamento' => '',
                 'dr' => ''
              );
        
        if ($this->sales_model->updateFinanceiro($id, $data)) {
            if ($this->input->is_ajax_request()) {
                echo lang("sale_canceled");die();
            }
            $this->session->set_flashdata('message', lang('sale_canceled'));
            redirect('financeiro');
        }
    }

    public function delete_return($id = null)
    {
        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->sales_model->deleteReturn($id)) {
            if ($this->input->is_ajax_request()) {
                echo lang("return_sale_deleted");die();
            }
            $this->session->set_flashdata('message', lang('return_sale_deleted'));
            redirect('welcome');
        }
    }

    public function sale_actions()
    {
       
        if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {

                    $this->sma->checkPermissions('delete');
                    foreach ($_POST['val'] as $id) {
                        $this->sales_model->deleteContaPagar($id);
                    }
                    $this->session->set_flashdata('message', lang("sales_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);

                } elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);

                } elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('title_pagar'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('account'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('suppliers'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('description'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('value'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('parcela'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('payment_status'));

                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sale = $this->sales_model->getContaPagarByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->sma->hrld($sale->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sale->account);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sale->payer);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sale->description);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $sale->amount);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $sale->parcela_atual.'/'.$sale->parcela);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, lang($sale->status));
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'contas_a_pagar_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php";
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_sale_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    
    
     public function sale_actions_transacoes()
    {
        if (!$this->Owner && !$this->GP['bulk_actions']) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('title_pagar'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('account'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('type'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('expenses'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('income'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('description'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('parcela'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('suppliers'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('categorie'));
                    $this->excel->getActiveSheet()->SetCellValue('J1', lang('pagament_form'));
                    
                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $sale = $this->sales_model->getContaPagarByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->sma->hrld($sale->date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sale->account);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sale->type);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sale->dr);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $sale->cr);
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $sale->description);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $sale->parcela_atual.'/'.$sale->parcela);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $sale->payer);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $sale->category);
                        $this->excel->getActiveSheet()->SetCellValue('J' . $row, lang($sale->method));
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'Transações_Financeiras_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php";
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
                        }

                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', lang("no_sale_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    /* ------------------------------- */

   

}
