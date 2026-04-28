<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tabela_preco_repasse extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('caixas_model');
        $this->load->model('Repasses_model');
        $this->load->model('payments_model');
        $this->load->model('payment_modes_model');
        $this->load->model('Contas_financeiras_model');
        $this->load->model('Convenios_model');
        $this->load->model('invoice_items_model');
        $this->load->model('Medicos_model');
        
       // $this->load->model('misc_model');
    }


    
    
    
    /* List all available items */
    public function index()
    {
        if (!has_permission('items', '', 'view')) {
            access_denied('Invoice Items');
        }
        
        $this->load->model('taxes_model');
        $data['taxes']        = $this->taxes_model->get();
        
        $data['items_groups'] = $this->invoice_items_model->get_groups();
        
        $tuss = $this->invoice_items_model->get_tabela_tuss();
        $data['items_tuss'] = $tuss;
       
        $this->load->model('currencies_model');
        $data['currencies'] = $this->currencies_model->get();
        $data['medicos']               = $this->Medicos_model->get();
        $data['convenios']             = $this->Convenios_model->get();
        $data['categorias']            = $this->invoice_items_model->get_groups();
        $data['procedimentos']         = $this->invoice_items_model->get_ativos();

        $data['base_currency'] = $this->currencies_model->get_base_currency();

        $data['title'] = _l('invoice_items');
        $this->load->view('admin/tabela_preco_repasse/manage', $data);
    }
    
     public function add_procedimento_medicos()
    {
        if (!has_permission('items', '', 'view')) {
            access_denied('Invoice Items');
        }
        ;
        $data['medicos']   = $this->Medicos_model->get();
        
        $data['items'] = $this->invoice_items_model->get_grouped_all_procedimentos();  
        //$data['procedimentos']         = $this->invoice_items_model->get_ativos();

        $data['title'] = _l('invoice_items');
        $this->load->view('admin/invoice_items/add_procedimento_medicos', $data);
    }

    public function table()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
        
        $this->app->get_table_data('tabela_preco_repasse');
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
    
    
    public function retorno_procedimentos()
    {
       $this->load->model('invoice_items_model'); 
       $convenio = $this->input->post("convenio");
       
       $data['ajaxItems'] = false;
       
       $items = $this->invoice_items_model->get_convenios($convenio);
      
       $data['items'] = $items;
       
       
       //$data['items'] = $this->invoice_items_model->get_grouped($convenio);    
       /*
       
        //echo 'Total : '.ajax_on_total_items(); exit;
        if (total_rows(db_prefix() . 'items') <= ajax_on_total_items()) {
            $data['items'] = $this->invoice_items_model->get_grouped($convenio);
        } else {
            $data['items']     = [];
            $data['ajaxItems'] = true;
        }*/
       //$data['ajaxItems'] = false;
       $this->load->view('admin/invoice_items/select_item_padrao', $data);
        //$this->load->view('admin/dashboard/retorno_dashboard_gestao', $data);
    }

    public function add_repasse()
    {
        
        
        
        if ($this->input->post()) {
            
            
            $dados = array();
            $data_inicio_nova = $this->input->post('data_inicio');
           
            $items =  $this->input->post('item_id');
            $valor =  $this->input->post('valor');
            $valor = str_replace('.','', $valor);
            $valor = str_replace(',','.', $valor);
            //
            $tipo =  $this->input->post('tipo');
            $medicos =  $this->input->post('medicoid');
            
            if(!$valor){
                 set_alert('danger', 'Informe o valor.');
                 redirect($_SERVER['HTTP_REFERER']);
            }
            
            if(!$items){
             set_alert('danger', 'Informe o procedimento.');
             redirect($_SERVER['HTTP_REFERER']);
            }
            
            foreach($medicos as $medico_id){
               
                
                foreach($items as $procedimento_id){
                    
                    
                    // 1-  verifica se ja tem um cadastro para o período informado
                    $this->load->model('Invoice_items_model');
                    $tabelas_preco = $this->Invoice_items_model->get_tabela_repasse_preco($procedimento_id, $medico_id); 
                    $exite_procedimento_cadastrado = $tabelas_preco->id;
                    
                    if($exite_procedimento_cadastrado){
                        $data_inicio_atual = $tabelas_preco->data_inicio;    
                        // 2 - verifica se a data de início é menor q a data atual    
                        if($data_inicio_nova > $data_inicio_atual){
                            // ajusta a data fim do registro atual
                            $data_fim_ajuste = date('Y-m-d', strtotime('-1 day', strtotime($data_inicio_nova)));
                            $data_ajuste['data_fim']       = $data_fim_ajuste;

                             $this->Invoice_items_model->edit_tabela_repasse_preco($data_ajuste, $exite_procedimento_cadastrado);


                        }else{
                             set_alert('danger', 'A data de Início da vigência deve ser maior que o da tabela atual.');
                            redirect($_SERVER['HTTP_REFERER']);
                        }


                    } // fim if
                    //$this->load->model('Misc_model');
                    
                    $dados['medico_id'] = $medico_id;
                    $dados['item_id'] = $procedimento_id;
                    $dados['data_inicio'] = $data_inicio_nova;
                    $dados['valor'] = $valor;
                    $dados['tipo'] = $tipo;
                   
                   $retorno =  $this->Invoice_items_model->add_tabela_repasse_medico($dados);
                    
                    
                    
                    
                }
                 
              
            }
            
            set_alert('success', _l('added_successfully', 'Tabela Repasse'));
            redirect(admin_url('tabela_preco_repasse'));
            
            
             /*
            $success = $this->Invoice_items_model->add_tabela_preco($this->input->post());
            if ($success) {
               
                set_alert('success', _l('added_successfully', 'Tabela'));
               // redirect('https://vision.sigplus.site/admin/tabela_preco');
                
                $this->load->model('invoice_items_model');
                $data['convenios']   = $this->Convenios_model->get();
               $data['items'] = $this->invoice_items_model->get_convenios();
                //$data['expenseid']  = $id;
                $data['contas_financeiras']    = $this->Contas_financeiras_model->get();
                $data['title']      =  'Tabela de Preço - Procedimentos';

                $this->load->view('admin/tabela_preco/index', $data);
            }
              * 
              */
        }
        
        
        
        
        
        
    
        
       
         
        //$data = $this->input->post();
       
        
       
    }

    
  
}
