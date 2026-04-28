<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tabela_preco extends AdminController
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
        $this->load->model('Invoice_items_model');
    }


    public function index($id = '')
    {
        $dados = $this->input->post();
        if($dados){
            $data_filtro = [];
            
            $convenio = $dados['convenios_procedimentos'];
            if($convenio){
                $data_filtro['convenio'] = $convenio;
                $data['convenio_filtro']       = $convenio;
            }
            
            $categoria = $dados['categorias_procedimentos'];
            if($categoria){
                $data_filtro['categoria'] = $categoria;
                $data['categoria_filtro']       = $categoria;
            }
            
            $inicio_vigencia = $dados['inicio_vigencia'];
            if($inicio_vigencia){
                $data_filtro['inicio_vigencia'] = $inicio_vigencia;
                $data['data_filtro']       = $inicio_vigencia;
            }
            
            
        }
        
        close_setup_menu();
        
        if (!has_permission('tesouraria', '', 'repasses')) {
            access_denied('tesouraria');
        }
        $this->load->model('invoice_items_model');
        $data['convenios']             = $this->Convenios_model->get();
        $data['categorias']            = $this->invoice_items_model->get_groups();
        $data['procedimentos']         = $this->invoice_items_model->get_ativos();
        
        $data['items'] = $this->invoice_items_model->get_convenios();
        //$data['expenseid']  = $id;
        $this->load->model('payments_model');
        $payments = $this->payment_modes_model->get();
        $data['payment_modes'] = $payments;
        $data['contas_financeiras']    = $this->Contas_financeiras_model->get();
        $data['title']      =  'Tabela de Preço - Procedimentos';
        
        $tabelas_preco = $this->Invoice_items_model->get_tabela_preco('',$data_filtro); 
        $data['tabelas_preco'] = $tabelas_preco;
        $this->load->view('admin/tabela_preco/index', $data);
    }
    
     public function add_tabela_preco()
    {
        if (!has_permission('items', '', 'view')) {
            access_denied('Invoice Items');
        }
        $payments = $this->payment_modes_model->get();
        $data['payment_modes'] = $payments;
        $data['contas_financeiras']    = $this->Contas_financeiras_model->get();
        $this->load->model('invoice_items_model');
        $data['convenios']   = $this->Convenios_model->get();
        $data['items'] = $this->invoice_items_model->get_convenios();
        //$data['procedimentos']         = $this->invoice_items_model->get_ativos();

        $data['title'] = _l('invoice_items');
        $this->load->view('admin/tabela_preco/add_tabela', $data);
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
       
        $hoje = date('Y-m-d');
        $data_inicio = $this->input->post('data_inicio');
        $data_fim = $this->input->post('data_fim');
        $item_id = $this->input->post('item_select');
        
       
       
        if(!$item_id){
             set_alert('danger', 'Informe o procedimento.');
             redirect($_SERVER['HTTP_REFERER']);
        }
        
        // 1-  verifica se ja tem um cadastro para o período informado
        $this->load->model('Invoice_items_model');
        $tabelas_preco = $this->Invoice_items_model->get_tabela_preco($item_id); 
        $item_id_o = $tabelas_preco->id;
        
        if($item_id_o){
        $data_inicio_registro = $tabelas_preco->data_inicio;    
        // 2 - verifica se a data de início é menor q a data atual    
           
            if($data_inicio > $data_inicio_registro){
                // ajusta a data fim do registro atual
                $data_fim_ajuste = date('Y-m-d', strtotime('-1 day', strtotime($data_inicio)));
                $data_ajuste['data_fim']       = $data_fim_ajuste;
                
                $success = $this->Invoice_items_model->edit_tabela_preco($data_ajuste, $item_id_o);
               
                
            }else{
                 set_alert('danger', 'A data de Início da vigência deve ser maior que o da tabela atual.');
                redirect($_SERVER['HTTP_REFERER']);
            }
            
        // 2 - verifica se a tabela já cadastrada tem data fim
       /* $data_fim_registro = $tabelas_preco->data_fim;
        if(!$data_fim_registro || $data_fim_registro == '0000-00-00'){
            set_alert('danger', 'Informe o fim da vigência da tabela atual deste procedimento, para cadastrar uma nova tabela.');
            redirect($_SERVER['HTTP_REFERER']);
        }*/
        
        
       
        }
        
        $valor = $this->input->post('valor');
        $valor = str_replace(',','.', $valor);
        
        if($valor == 0){
             set_alert('danger', 'Informe o valor.');
             redirect($_SERVER['HTTP_REFERER']);
        }
        
        $valor2 = $this->input->post('valor2');
        $valor2 = str_replace(',','.', $valor2);
        
        //$data = $this->input->post();
       
        if ($this->input->post()) {
             
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
        }
       
    }
   
    /*
    public function carga_tabela()
    {
        $hoje = date('Y-m-d');
        $this->load->model('invoice_items_model');
        $procedimentos = $this->invoice_items_model->get_tabela_procedimentos();
         foreach($procedimentos as $item){
            $item_id = $item['id'];
            $item_rate = $item['rate'];
             echo $item_id.'<Br>'; 
             
              // verifica se ja tem registro na tebala de preço
            $tabelas_preco = $this->invoice_items_model->get_tabela_preco($item_id); 
            $item_id_tp = $tabelas_preco->id;
            if($item_id_tp){
                //se tiver, faz o insert com a data_inicio e fim antes do inicio do registro ja encontrado
                
                $data_inicio_registro = $tabelas_preco->data_inicio;    

                $data_fim_ajuste = date('Y-m-d', strtotime('-1 day', strtotime($data_inicio_registro)));
                $data_carga['data_fim']       = $data_fim_ajuste;
                $data_carga['data_inicio']    = '2021-01-01';
                $data_carga['item_select']        = $item_id;
                $data_carga['valor']          = $item_rate;
                $data_carga['data_log']       = date('Y-m-d H:i:s');
                $data_carga['usuario_log']    = 1;
                $data_carga['empresa_id']     = 1;
                $data_carga['observacao']     = 'Carga sistema';
                
                $this->invoice_items_model->add_tabela_preco($data_carga);
                
            }else{
                // se n tiver, faz o insert somente com a data_inicio
                $data_carga_n['data_inicio']    = '2021-01-01';
                $data_carga_n['item_select']    = $item_id;
                $data_carga_n['valor']          = $item_rate;
                $data_carga_n['data_log']       = date('Y-m-d H:i:s');
                $data_carga_n['usuario_log']    = 1;
                $data_carga_n['empresa_id']     = 1;
                $data_carga_n['observacao']     = 'Carga sistema';
                
                $this->invoice_items_model->add_tabela_preco($data_carga_n);
                
                //exit;
                
            }
          
         }
        
        
    }*/
  
}
