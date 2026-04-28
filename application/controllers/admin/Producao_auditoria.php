<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Producao_auditoria extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('caixas_model');
        $this->load->model('cron_model');
        $this->load->model('Misc_model');
        $this->load->model('Repasses_model');
        $this->load->model('payments_model');
        $this->load->model('payment_modes_model');
        $this->load->model('invoice_items_model');
        $this->load->model('Medicos_model');
        $this->load->model('Convenios_model');
        $this->load->model('Contas_financeiras_model');
    }


    public function index()
    {
        close_setup_menu();

        if (!has_permission('tesouraria', '', 'repasses')) {
            access_denied('tesouraria');
        }
        $data['payment_modes'] = $this->payment_modes_model->get();  
        //$data['expenseid']  = $id;
        $data['convenios']             = $this->Convenios_model->get();
        $data['categorias']            = $this->invoice_items_model->get_groups();
        //$data['procedimentos']         = $this->invoice_items_model->get_ativos();
        $data['procedimentos']  = $this->invoice_items_model->get_grouped_todos();
        $data['medicos']               = $this->Medicos_model->get();
        $data['title']      = 'Produção Auditoria';
       
        $this->load->view('admin/producao_auditoria/manage', $data);
        //$this->load->view('admin/repasses/index', $data);
    }
    
     public function add_repasse_producao()
    {
        $data_repasse = $this->input->post('data_repasse');
        $medico_id = $this->input->post('medico_id');
        $procedimentos = $this->input->post('procedimentos');
        $valor_cobrado = $this->input->post('valor_cobrado');
        $valor_cobrado = str_replace(',','.', $valor_cobrado);
        $valor_repasse = $this->input->post('valor_repasse');
        $valor_repasse = str_replace(',','.', $valor_repasse);
        
       
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
       
        $this->app->get_table_data('producao_auditoria');
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
    
    public function bulk_action()
    {
        hooks()->do_action('before_do_bulk_action_for_customers');
        $total_deleted = 0;
        
        $mass_approved = $this->input->post('mass_approved');
        $mass_deleted = $this->input->post('mass_deleted');
       
        
       
       
        if ($mass_approved) {
            $ids    = $this->input->post('ids');
            $groups = $this->input->post('groups');
            
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_approved')) {
                        if ($this->Misc_model->edit_status_producao_medica($id)) {
                            $total_deleted++;
                        }
                    } 
                }
            }
        }
        
        
        if ($mass_deleted) {
            $ids    = $this->input->post('ids');
            $groups = $this->input->post('groups');
          
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_deleted')) {
                        if ($this->Misc_model->deletar_producao_medica($id)) {
                            $total_deleted++;
                        }
                    } 
                }
            }
        }
        

        if ($this->input->post('mass_approved')) {
            set_alert('success', 'Produção Confirmada : '.$total_deleted);
        }
    }
    
    /**************************************************************************/
    
    public function controle_Guias()
    {
        close_setup_menu();

        if (!has_permission('faturamento_menu', '', 'view')) {
            access_denied('tesouraria');
        }
        $data['payment_modes'] = $this->payment_modes_model->get();  
        //$data['expenseid']  = $id;
        $data['convenios']             = $this->Convenios_model->get_controla_guia();
        $data['categorias']            = $this->invoice_items_model->get_groups();
        //$data['procedimentos']         = $this->invoice_items_model->get_ativos();
        $data['procedimentos']  = $this->invoice_items_model->get_grouped_todos();
        $data['medicos']               = $this->Medicos_model->get();
        $data['title']      = 'Controle de Entrega de Guias';
       
        $this->load->view('admin/producao_auditoria/controle_guia/manage', $data);
        //$this->load->view('admin/repasses/index', $data);
    }
    
    
    public function bulk_action_controle_guias()
    {
        $total_deleted = 0;
        
        $mass_approved = $this->input->post('mass_approved');
        $mass_deleted = $this->input->post('mass_deleted');
       
      
       
       // confirma recebimento
        if ($mass_approved) {
            $ids    = $this->input->post('ids');
            $groups = $this->input->post('groups');
            
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_approved')) {
                        if ($this->Misc_model->confirmar_entrega_guia_agendamento($id)) {
                            $total_deleted++;
                        }
                    } 
                }
            }
        }
        
        
        if ($mass_deleted) {
            $ids    = $this->input->post('ids');
            $groups = $this->input->post('groups');
          
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    if ($this->input->post('mass_deleted')) {
                        if ($this->Misc_model->cancelar_entrega_guia_agendamento($id)) {
                            $total_deleted++;
                        }
                    } 
                }
            }
        }
        

        if ($this->input->post('mass_approved')) {
            set_alert('success', 'Ação Confirmada : '.$total_deleted);
        }
    }
    
    
    public function table_controle_guias()
    {
        if (!has_permission('faturamento_menu', '', 'view')) {
            ajax_access_denied();
        }
      
        $this->app->get_table_data('producao_auditoria_controle_guia');
    }
    
    /****************************************************************/
    
    
    public function repasse_medico()
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
        $medicos                        = $this->Medicos_model->get();
    
        $data['medicos']               = $medicos;
        $data['title']      = 'Confirmar Repasse Médico';
       
        $this->load->view('admin/producao_auditoria/repasse/manage', $data);
        //$this->load->view('admin/repasses/index', $data);
    }
    
     public function table_repasse()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
       
        $this->app->get_table_data('producao_auditoria_repasse');
    }
    
    public function bulk_action_repasse()
    {
        hooks()->do_action('before_do_bulk_action_for_customers');
        $total_deleted = 0;
      
        if ($this->input->post()) {
            $ids    = $this->input->post('ids');
            $groups = $this->input->post('groups');
            $medico_id   = $this->input->post('medico_id');
            
            $data_repasse   = $this->input->post('data_repasse');
            $forma_id       = $this->input->post('forma_id');
            $observacao      = $this->input->post('observacao');
            $registro_id      = $this->input->post('registro_id');
            $caixa_id      = $this->input->post('caixa_id');
            $saldo      = $this->input->post('saldo');
            
            $dados_conta_financeira = $this->Contas_financeiras_model->get_medico_id($medico_id);
            $conta_financeira_id = $dados_conta_financeira->id;
            
            
            $soma_valor_total_repasse = 0;
            foreach ($ids as $id) {
                $procedimento = $this->Misc_model->get_producao_medica($id);
                $valor_repasse = $procedimento->valor_procedimento;
                $soma_valor_total_repasse +=$valor_repasse;
                $total_deleted++;
            }
            
            
            $data['registro_id'] = $registro_id;
            $data['data_repasse'] = $data_repasse;
            $data['conta_id'] = $conta_financeira_id;
            $data['valor'] = $soma_valor_total_repasse;
            $data['observacao'] = $observacao;
            $data['caixa_id'] = $caixa_id;
            $data['forma_id'] = $forma_id;
            $data['saldo'] = $saldo;
            
           
            $repasse = $this->add_repasse($data, $ids);
            
            
           /* if (is_array($ids)) {
                $soma_valor_total_repasse = 0;
                foreach ($ids as $id) {
                    $procedimento = $this->Misc_model->get_producao_medica($id);
                    $valor_repasse = $procedimento->valor_procedimento;
                    $soma_valor_total_repasse +=$valor_repasse;
                }
                
                // cria o repasse
                 
                // vincula os procedimentos_producao ao repasse
                foreach ($ids as $id) {
                    if ($this->input->post('mass_approved')) {
                        if ($this->Misc_model->edit_status_producao_medica($id)) {
                            $total_deleted++;
                        }
                    } 
                }
                
            }*/
            
           
            
        }

        if ($this->input->post('mass_repasse_caixa')) {
            set_alert('success', 'Repasse de Produção Confirmada : '.$total_deleted);
        }
    }
    
   public function add_repasse($data, $ids)
    {
        
        $saldo = $data['saldo'];
        $valor = $data['valor'];
        $valor = str_replace(',','.', $valor);
        
        
        
        if($valor == 0){
             set_alert('danger', 'Informe o valor do repasse.');
           // redirect($_SERVER['HTTP_REFERER']);
        }else if($valor > $saldo){
            set_alert('danger', 'O valor do repasse não pode ser maior que o saldo do caixa.');
           // redirect($_SERVER['HTTP_REFERER']);
        }
        
    
      
        $repasse_id = $this->Repasses_model->add($data);
        
        if ($repasse_id) {
            $datos['repasse_id'] = $repasse_id;
            foreach ($ids as $id) {
                $procedimento = $this->Misc_model->edit_status_producao_repasse_medica($datos, $id);
                
            }
            
          
        }
        
        //redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function faturamento_produzido()
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
        $data['medicos']               = $this->Medicos_model->get();
        $data['title']                 = 'Faturamento Médico';
       
        $this->load->view('admin/producao_auditoria/faturamento/manage', $data);
        //$this->load->view('admin/repasses/index', $data);
    }
    
    public function table_faturamento()
    {
        if (!has_permission('items', '', 'view')) {
            ajax_access_denied();
        }
       
        $this->app->get_table_data('producao_auditoria_faturamento');
    }
    
    
    public function bulk_action_faturamento()
    {
        hooks()->do_action('before_do_bulk_action_for_customers');
        $total_deleted = 0;
        
        
        $medico_id   = $this->input->post('medico_id');
        
        $reprocessa = $this->input->post('mass_repasse_caixa');
        if ($reprocessa == 'true') {
             $ids    = $this->input->post('ids');
             foreach ($ids as $item_id) {
                 $info = $this->cron_model->get_dados_item_medico($medico_id, $item_id);
                 print_r($info); exit;      
                         
                        $fatura_id = $atendimentos_fat->fatura_id;
                        $valor_tesouraria = $atendimentos_fat->valor_tesouraria;
                        $rate_desconto = $atendimentos_fat->rate_desconto;
                        $qty = $atendimentos_fat->qty;
                        $status = $atendimentos_fat->status;
                        $medicoid = $atendimentos_fat->medicoid;
                        //$desconto_valor = $atendimentos_fat->desconto_valor;
                       // $destino_desconto = $atendimentos_fat->destino_desconto;
                        $vl_medico = $atendimentos_fat->valor_medico;
                        if($vl_medico < 0){
                            $vl_medico = 0;
                        }
                        // 5 - Faz o insert na tabela de produção médica para as faturas pagas
                        if($status == 2){
                            
                            
                            $data_producao['fatura_id']             = $fatura_id;
                            $data_producao['agenda_id']             = $atendimento_id;
                            $data_producao['medico_id']             = $medicoid;
                            $data_producao['item_id']               = $item_id;
                            $data_producao['empresa_id']            = $empresa_id;
                            $data_producao['valor_medico']          = $vl_medico;
                            $data_producao['valor_empresa']         = $valor_tesouraria;
                            $data_producao['valor_procedimento']    = $rate_desconto;
                            $data_producao['data_atendimento']      = $data_atendimento;
                            $data_producao['quantidade']            = $qty;
                            $data_producao['data_log']              = date('Y-m-d H:i:s');
                            
                            $this->cron_model->add_producao_medica($data_producao);

                            //6 - informa que ja registrou a produção
                            $data_procedimento['producao_medica']       = 1;
                            $this->cron_model->edit_atendimento_procedimento($data_procedimento, $atendimento_item_id);
                        }
                 
                 
                
            }
        }
        
        
       
        if ($this->input->post()) {
            $ids    = $this->input->post('ids');
            $groups = $this->input->post('groups');
            $medico_id   = $this->input->post('medico_id');
            
            
            
            $soma_valor_total_repasse = 0;
            foreach ($ids as $id) {
                //$procedimento = $this->Misc_model->get_producao_medica($id);
                $valor_repasse = $procedimento->valor_procedimento;
                $soma_valor_total_repasse +=$valor_repasse;
                $total_deleted++;
            }
            
            
            
            
           /* if (is_array($ids)) {
                $soma_valor_total_repasse = 0;
                foreach ($ids as $id) {
                    $procedimento = $this->Misc_model->get_producao_medica($id);
                    $valor_repasse = $procedimento->valor_procedimento;
                    $soma_valor_total_repasse +=$valor_repasse;
                }
                
                // cria o repasse
                 
                // vincula os procedimentos_producao ao repasse
                foreach ($ids as $id) {
                    if ($this->input->post('mass_approved')) {
                        if ($this->Misc_model->edit_status_producao_medica($id)) {
                            $total_deleted++;
                        }
                    } 
                }
                
            }*/
            
           
            
        }

        if ($this->input->post('mass_repasse_caixa')) {
            set_alert('success', 'Repasse de Produção Confirmada : '.$total_deleted);
        }
    }
    
}
