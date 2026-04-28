<?php

defined('BASEPATH') or exit('No direct script access allowed');

class payments_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoices_model');
        $this->load->model('clients_model');
        $this->load->model('nota_fiscal_model');
    }

    /**
     * Get payment by ID
     * @param  mixed $id payment idcan
     * @return object
     */
    public function get($id)
    {
      
        $this->db->select('*,' . db_prefix() . 'invoicepaymentrecords.id as paymentid');
        $this->db->join(db_prefix() . 'payment_modes', db_prefix() . 'payment_modes.id = ' . db_prefix() . 'invoicepaymentrecords.paymentmode', 'left');
        $this->db->order_by(db_prefix() . 'invoicepaymentrecords.id', 'asc');
        $this->db->where(db_prefix() . 'invoicepaymentrecords.id', $id);
        $this->db->where('tblinvoicepaymentrecords.deleted', 0);
        $payment = $this->db->get(db_prefix() . 'invoicepaymentrecords')->row();
         
         
        if (!$payment) {
            return false;
        }
        // Since version 1.0.1
        $this->load->model('payment_modes_model');
        $payment_gateways = $this->payment_modes_model->get_payment_gateways(true);
        if (is_null($payment->id)) {
            foreach ($payment_gateways as $gateway) {
                if ($payment->paymentmode == $gateway['id']) {
                    $payment->name = $gateway['name'];
                }
            }
        }

      
        return $payment;
    }

    /**
     * Get all invoice payments
     * @param  mixed $invoiceid invoiceid
     * @return array
     */
    public function get_invoice_payments($invoiceid)
    {
        
        $this->db->select('tblinvoicepaymentrecords.*, tblpayment_modes.*, tblinvoicepaymentrecords.id as pgto_id, ' . db_prefix() . 'invoicepaymentrecords.id as paymentid, ' . db_prefix() . 'conta_financeira.nome as conta_financeira, tblcaixas.name as caixa ');
        $this->db->join(db_prefix() . 'payment_modes', db_prefix() . 'payment_modes.id = ' . db_prefix() . 'invoicepaymentrecords.paymentmode', 'inner');
        $this->db->join(db_prefix() . 'conta_financeira', db_prefix() . 'conta_financeira.id = ' . db_prefix() . 'invoicepaymentrecords.conta_id', 'inner');
        $this->db->join(db_prefix() . 'caixas_registros', db_prefix() . 'caixas_registros.id = ' . db_prefix() . 'invoicepaymentrecords.caixa_id', 'left');
        $this->db->join(db_prefix() . 'caixas', db_prefix() . 'caixas.id = ' . db_prefix() . 'caixas_registros.caixa_id', 'left');
     //   $this->db->join(db_prefix() . 'notafiscal', db_prefix() . 'notafiscal.conta_id = ' . db_prefix() . 'conta_financeira.id and '.db_prefix().'notafiscal.invoice_id = '.$invoiceid .' and '.db_prefix().'notafiscal.status = "autorizado"' , 'left');
        
        $this->db->order_by(db_prefix() . 'invoicepaymentrecords.id', 'asc');
        
        $this->db->where('tblinvoicepaymentrecords.deleted', 0);
        $this->db->where('invoiceid', $invoiceid);
        $payments = $this->db->get(db_prefix() . 'invoicepaymentrecords')->result_array();
     
        // Since version 1.0.1
        $this->load->model('payment_modes_model');
        $payment_gateways = $this->payment_modes_model->get_payment_gateways(true);
        $i                = 0;
        foreach ($payments as $payment) {
            if (is_null($payment['id'])) {
                foreach ($payment_gateways as $gateway) {
                    if ($payment['paymentmode'] == $gateway['id']) {
                        $payments[$i]['id']   = $gateway['id'];
                        $payments[$i]['name'] = $gateway['name'];
                    }
                }
            }
            $i++;
        }

        return $payments;
    }
    
    
    // RETORNA O total de pagamento de cada conta
     public function get_invoice_payments_pdf($invoiceid)
    {
       $sql = "select  r.invoiceid, r.date,  sum(r.amount) as amount, pm.name as forma_pagamento
                from tblinvoicepaymentrecords r
                inner join tblpayment_modes pm on pm.id = r.paymentmode
                where r.invoiceid = $invoiceid and r.deleted = 0
                group by r.paymentmode, r.date";
       //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    // RETORNA O total de pagamento de cada conta
     public function get_resumo_invoice_payments_por_conta_financeira($invoiceid)
    {
       $sql = "select tblconta_financeira.nome as conta_financeira, sum(tblinvoicepaymentrecords.amount) as amount
                from tblinvoicepaymentrecords
                inner join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
                where invoiceid = $invoiceid and tblinvoicepaymentrecords.deleted = 0 group by tblinvoicepaymentrecords.conta_id ";
       //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    // RETORNA O total de detalhes de uma conta financeira
     public function get_conta_financeira_by_id($invoiceid)
    {
       $sql = "select tblconta_financeira.*
                from tblconta_financeira
                where id = $invoiceid ";
       
       $result = $this->db->query($sql)->row();
        return $result;
    }

    /**
     * Process invoice payment offline or online
     * @since  Version 1.0.1
     * @param  array $data $_POST data
     * @return boolean
     */
    public function process_payment($data, $invoiceid = '')
    {
        
        $pagamentos_ids = array(); 
        $invoice_id = $data['invoiceid'];
        if($data['conta_id']){
        $conta_id = $data['conta_id'];    
        }else{
        $conta_id = 0;    
        }
        
       
       
        $do_not_redirect = $data['do_not_redirect'];
        if($do_not_redirect == 'on'){
            $do_not_redirect = 1;    
        }
 
        // atualiza no cadastro da fatura se é p voltar p tela de pagamento
        $this->invoices_model->atualiza_retorno_fatura($invoice_id, $do_not_redirect);
        unset($data['do_not_redirect']);

        $soma_valor_caixa = 0;
        $valor_total_todos_medicos = 0 ;
        //Valor pago
        $valor_pagamento = $data['amount'];
         
        
        // valor da fatura
        $dados_fatura = $this->invoices_model->get($invoice_id);
        $subtotal = $dados_fatura->subtotal;
        $discount_total = $dados_fatura->discount_total; // Valor do desconto
        $adjustment = $dados_fatura->adjustment; // Valor do desconto
        $valor_fatura_total = $dados_fatura->total;  // inclui anestesista
        
        /*
         * VALOR DO CRÉDITO
         */
        $valor_credito_invoice = 0;
        $dados_credito = $this->credit_notes_model->get_applied_invoice_credits($invoice_id);
        foreach ($dados_credito as $credito) {
            $valor_credito_invoice .= $credito['amount'];
        }
        
        
        /*
         * VERIFICA OS PROCEDIMENTOS QUE NÃO TEM REPASSES (OS MÉDICOS QUE RECEBEM NOS CONSULTÓRIOS)
         */
        $medicos_invoices_itens_sem_repasses = $this->invoices_model->get_invoice_item_pagamento_sem_repasse($invoice_id);
        $conta_medico_repasse = count($medicos_invoices_itens_sem_repasses);
        if($conta_medico_repasse){
          foreach ($medicos_invoices_itens_sem_repasses as $repasse) {
            $medico_id =  $repasse['medicoid'];
            $repasse_medico_itemable_id =  $repasse['itemable_id'];
            $rate_desconto =  $repasse['rate_desconto']; // qtde procedimento da fatura
            
            $total_repasse = $rate_desconto; // valor total
            $soma_total_repasse += $total_repasse;
            
            // atualiza
            $invoices_itens_medico = $this->invoices_model->atualiza_valor_porcentagem_itemable($repasse_medico_itemable_id,  $total_repasse, 0);
          }// fim foreach
        }// fim if conta médico
        
         if(!$soma_total_repasse ){
             $soma_total_repasse = 0; 
         }
         
          /*
         * VERIFICA OS MEDICOS QUE NÃO TEM REPASSES 100%
         */
        
         if($valor_pagamento == $valor_fatura_total){
             $soma_total_repasse_100 = 0;
         }else{
            $soma_total_repasse_100 = 0;
            $medicos_invoices_itens_repasses_100 = $this->invoices_model->get_invoice_item_medico_100_comissao($invoice_id);
            $conta_medico_repasse_100 = count($medicos_invoices_itens_repasses_100);

            if($conta_medico_repasse_100){
              foreach ($medicos_invoices_itens_repasses_100 as $repasse2) {
                $medico_id =  $repasse2['medicoid'];
                $repasse_medico_itemable_id =  $repasse2['itemable_id'];
                $rate_desconto =  $repasse2['rate_desconto']; // qtde procedimento da fatura

                $soma_total_repasse_100 = $rate_desconto;
              }// fim foreach
            }// fim if conta médico
         }
         
        
        
       // ECHO $soma_total_repasse_100; exit;
        /***********************************************************************************************************************************************/
        
         // verifica se tem pagamentos parciais 
         $pagamentos_anteriores = $this->invoices_model->get_pagamentos_invoice($invoice_id);
         $valor_pgto_parciais = $pagamentos_anteriores->valor;  
         
         $valor_fatura = $valor_fatura_total - $soma_total_repasse + $valor_pgto_parciais - $valor_credito_invoice; // valor total - os médicos q n tem repasse
         $valor_fatura_original = $valor_fatura_total - $soma_total_repasse - $valor_credito_invoice - $soma_total_repasse_100; // 
        
        /****************************************************************************************************************************************************
         * 1 -  VERIFICA SE TEM UM PAGAMENTO DESTINADO A UMA CONTA ESPECÍFICA. 
         *      SE SIM, NÃO EXECUTA A REGRA DE PAGAMENTO, MAS REALIZA O PAGAMENTO INTEGRAL PARA A CONTA INFORMADA.
         ***************************************************************************************************************************************************/
        $conta_empresa = 0;
        $total_conta = count($conta_id); 
        // verifica se tem pagamento para a empresa, 
        if($total_conta == 1){
          foreach ($conta_id as $conta) { 
           if(!$valor_pgto_parciais){
               $valor_pgto_parciais = 0;
           }
           $valor_desconto_fatura = 0;
           $todos_pgto = $valor_pagamento + $valor_pgto_parciais ;
           // conta de médico
           if($conta > 1){
            // 2 - listar os procedimentos para distribuir o valor pagao entre eles
            $medicos_invoices_itens_sem_repasses = $this->invoices_model->get_invoice_item_pagamento_fatura($invoice_id);
            $conta_medico_repasse = count($medicos_invoices_itens_sem_repasses);
            $conta_procedimento = 1;
            if($conta_medico_repasse){
              $saldo =  $valor_pagamento;
              $saldo_total =  $valor_pagamento;
              foreach ($medicos_invoices_itens_sem_repasses as $repasse) {
                $medico_id              = $repasse['medicoid'];
                $itemable_id            = $repasse['itemable_id'];
                $item_id                = $repasse['item_id'];
                $qty                    = $repasse['qty']; // qtde procedimento da fatura
                $valor_procedimento     = $repasse['rate'];
                $rate_desconto          = $repasse['rate_desconto'];
                $desconto_porcentagem   = $repasse['desconto_porcentagem'];
                $desconto_valor         = $repasse['desconto_valor'];
                $destino_desconto       = $repasse['destino_desconto'];
                 // pega os dados ca conta do médico,  verifica se ele tem nota fiscal propria
                $invoices_itens_medico = $this->invoices_model->get_conta_financeira_medico($itemable_id);
                $conta_medico_procedimentos = count($invoices_itens_medico);
                $nota_fiscal_propria = $invoices_itens_medico->nota_fiscal_propria; 
                
                // 3 - atualiza o valor do repasse do médico de acordo c o vl pago
                //if($todos_pgto == $valor_fatura ){
                    
               /*
                * retorna a comissao do médico
                */
                $tabela_procedimento_medico_row = $this->invoices_model->get_medico_procedimento_invoice_item_conta_selecionada($item_id, $medico_id);
                $regra_procedimento = count($tabela_procedimento_medico_row); 
                // regra repasse 1: repasse cadastro na tabela de repasse médico. pode ser valor fixo ou %
                    if($regra_procedimento >= 1){
                       $tipo = $tabela_procedimento_medico_row->tipo;
                            // se o repasse do médico for com %
                           if($tipo == 2){
                               $valor_repasse = $tabela_procedimento_medico_row->valor;
                               $valor_repasse = $valor_repasse/100;
                               //$valor_comissao = ($valor_procedimento_c_desconto * $valor_repasse);
                               //$valor_comissao_pgto_total = ($valor_procedimento_c_desconto * $valor_repasse);
                               $valor_em_real_repasse = ($valor_procedimento *  $valor_repasse)* $qty;
                               
                              if($valor_repasse == 1){
                                  if($desconto_valor > 0){
                                   $valor_comissao = $valor_em_real_repasse - $desconto_valor;
                                   $valor_comissao_pgto_total = $valor_em_real_repasse - $desconto_valor;
                                  }else{
                                      $valor_comissao = $valor_em_real_repasse;
                                       $valor_comissao_pgto_total = $valor_em_real_repasse;
                                  }
                              }else{
                                  if($destino_desconto == 'AMBOS' || (!$destino_desconto)){
                                 $valor_comissao            = ($rate_desconto * $valor_repasse) ;
                                 $valor_comissao_pgto_total = ($rate_desconto * $valor_repasse);
                              }ELSE if($destino_desconto == 'MÉDICO'){
                                  $valor_comissao = $valor_pagamento ;
                                  $valor_comissao_pgto_total = $valor_em_real_repasse - $desconto_valor;
                              }ELSE if($destino_desconto == 'EMPRESA'){
                                  $valor_comissao = $valor_pagamento;
                                  $valor_comissao_pgto_total = $valor_em_real_repasse;
                              }
                              }
                               
                              
                             
                           }else{

                               // se o valor da comissao do médico for valor fixo
                               $valor_repasse = $tabela_procedimento_medico_row->valor;    
                               $valor_comissao = ($valor_repasse  * $qty) ;
                               $valor_comissao_pgto_total = ($valor_repasse  * $qty);
                              
                                if($destino_desconto == 'AMBOS' || (!$destino_desconto)){
                                  if($desconto_porcentagem > 0){
                                    $desconto_porcentagem = ($desconto_porcentagem/100);
                                    $valor_desconto_comissao = ($valor_comissao  * $desconto_porcentagem);
                                    $valor_comissao += -$valor_desconto_comissao;
                                    $valor_comissao_pgto_total  += -$valor_desconto_comissao;
                                  }
                                }ELSE if($destino_desconto == 'MÉDICO'){
                                    $valor_comissao += -$desconto_valor;
                                    $valor_comissao_pgto_total += -$desconto_valor;
                                }ELSE if($destino_desconto == 'EMPRESA'){
                                    $valor_comissao = $valor_comissao;
                                    $valor_comissao_pgto_total = $valor_comissao_pgto_total;
                                }

                             
                           }


                    //se não tiver tabela especial, entra na regra padrão
                    }else{

                    /*
                     * 4 - Caso não encontre segue a regra padrão
                     */
                       $todos_pgto = $valor_pagamento + $valor_pgto_parciais ;

                       $invoices_itens_medico_row = $this->invoices_model->get_invoice_item_pagamento_by_id($itemable_id);

                       $comissao                =  $invoices_itens_medico_row->comissao;
                       $comissao                = ($comissao/100);
                      // $conta_id                =  $invoices_itens_medico_row->conta_id;
                       $rate_procedimento       =  $invoices_itens_medico_row->rate;
                       $qty                     =  $invoices_itens_medico_row->qty;
                       $valor_medico_salvo      =  $invoices_itens_medico_row->valor_medico;
                       $valor_tesouraria        =  $invoices_itens_medico_row->valor_tesouraria;
                       $rate_desconto           =  $invoices_itens_medico_row->rate_desconto;
                       $desconto_valor          =  $invoices_itens_medico_row->rate_desconto;
                     
                      // $comissao_valor          = ($rate_desconto * $comissao);
                       $valor_comissao          = ($valor_pagamento * $comissao) ;
                       $valor_em_real_repasse   = ($valor_procedimento *  $valor_comissao)* $qty;
                       
                       if($destino_desconto == 'AMBOS'){
                            $valor_comissao = $valor_comissao ;
                            $valor_comissao_pgto_total = ($rate_desconto * $comissao);
                        }ELSE if($destino_desconto == 'MÉDICO'){
                              $valor_comissao = $valor_em_real_repasse - $desconto_valor;
                              $valor_comissao_pgto_total = $valor_em_real_repasse - $desconto_valor;
                        }ELSE if($destino_desconto == 'EMPRESA'){
                              $valor_comissao = $valor_em_real_repasse;
                              $valor_comissao_pgto_total = $valor_em_real_repasse ;
                        }
                         
                       $valor_comissao_pgto_total = ($rate_desconto * $comissao);  

                   }// fim else regra repasse %
                   
                    
                     $valor_tesouraria_pgto_total = $rate_desconto -  $valor_comissao_pgto_total;
                     $invoices_itens_medico = $this->invoices_model->atualiza_valor_porcentagem_itemable($itemable_id,  $valor_comissao_pgto_total, $valor_tesouraria_pgto_total); 
                //}

                $saldo_total += - $valor_tesouraria_pgto_total;
                $conta_procedimento++;



              }// fim foreach
             } 
             
           unset($data['nao_emitir_nota_fiscal']);
           $data['amount'] = $valor_pagamento;
           $data['conta_id'] = $conta; 
           $data['medicoid'] = $medico_id;
           $data['userid'] = get_staff_user_id();
           $empresa_id = $this->session->userdata('empresa_id');
           $data['empresa_id'] = $empresa_id;
           
          
           
           if (is_staff_logged_in()) {
            $id = $this->add($data);
            return $id;
           }
           // conta da empresa
          }else{
            if($valor_pagamento > 0){
              $data['amount'] = $valor_pagamento;
                $data['conta_id'] = 1; // parametrizar para buscar a conta padrão da empresa
                unset($data['nao_emitir_nota_fiscal']);
                $data['userid'] = get_staff_user_id();
                $empresa_id = $this->session->userdata('empresa_id');
                   $data['empresa_id'] = $empresa_id;
               if (is_staff_logged_in()) {
                $id = $this->add($data);
                return $id;
               }
            }
          }
        }    
        }// fim if conta médico
        /************* FIM PGTO PARA UM CONTA********************************************/
        
        else{
        /**************** PAGAMENTO MULTIMPLAS CONTAS   *****************************/    
            
        // verifica se n vai receber o valor do médico q recebe separado
        $todos_pgtos = $valor_pagamento + $valor_pgto_parciais ;
        
        if($todos_pgtos > $valor_fatura){
           echo 'O Pagamento informado é maior que o esperado. Verifique se todos os médicos envolvidos está parametrizado para receber repasse.';
           return false;
           exit;
        }
         
        // calcula a % do pagamento
        $porc_pagamento = ($valor_pagamento * 100)/$valor_fatura_original;  
        $porc_pagamento = $porc_pagamento/100;
       //  echo $porc_pagamento.'<br>'; //exit;
       
          // 1 - retorna as contas com médicos da fatura (único)
        $valor_caixa = 0;
        foreach ($conta_id as $conta_medico) {
            
            $pagamentos_anteriores = $this->invoices_model->get_pagamentos_invoice($invoice_id);
            $valor_pgto_parciais = $pagamentos_anteriores->valor;  
            $valor_fatura = $valor_fatura_total - $soma_total_repasse;
           
            $dados_conta = $this->get_conta_financeira_by_id($conta_medico);
           
            $medico_id = $dados_conta->medico_id;
            $nome_profissional = $dados_conta->nome;
            $regra_desconto_vision = $dados_conta->regra_desconto_vision;
           
            $soma_repasse_total_desconto = 0;
            $valor_comissao = 0;
            $valor_tesouraria = 0; // valor que fica no caixa de cada procedimento pago. (caixa e médico)
           
            // 2-  lista os procedimentos de um médico
           $cont_procedimento_medico = 1;
           $soma_desconto_procedimento_medico = 0;
           
        
           
           if($medico_id > 0){
                $soma_valor_medico = 0;
                 
                 
                $invoices_itens_medico = $this->invoices_model->get_conta_financeira_pagamento($invoice_id, $medico_id);
                $conta_medico_procedimentos = count($invoices_itens_medico);
                foreach ($invoices_itens_medico as $items) {
                  $nota_fiscal_propria  =  $items['nota_fiscal_propria']; 
                  $itemable_id          =  $items['id'];
                  $valor_procedimento   =  $items['rate'];
                  $qty                  =  $items['qty'];
                  $rate_desconto        =  $items['rate_desconto']; // valor total do procedimento com o desconto aplicado
                  $desconto_porcentagem =  $items['desconto_porcentagem'];
                  $desconto_valor       =  $items['desconto_valor'];
                  $destino_desconto     =  $items['destino_desconto'];
                  $item_id              =  $items['item_id'];
                 
                  /*
                   * CALCULA A % DE DESCONTO DO VALOR DO PROCEDIMENTO
                   */
                  
                  
                  /*
                   * VERIFICAR SE O CLIENTE PAGOU 100% DO VALOR DA FATURA, SOMANDO OS PAGAMENTOS PARCIAIS SE TIVER. 
                   * CASO TENHA PAGO ATUALIZA O FATURAMENTO DO MÉDICO
                   */
                 
                   
                 
                  
                   /*
                   * 3 - Verifica na ( Tabela de repasse do médico por procedimento)
                   */
                   //$valor_caixa = $valor_total_procedimento * $porc_pagamento;
                   $tabela_procedimento_medico_row_count = $this->invoices_model->get_medico_procedimento_invoice_item_row($item_id, $medico_id);
                   $count_regra_procedimento = $tabela_procedimento_medico_row_count->quantidade; 
                   
                 //  print_r($tabela_procedimento_medico_row); 
                   if($count_regra_procedimento >= 1){
                    $tabela_procedimento_medico_row = $this->invoices_model->get_medico_procedimento_invoice_item($item_id, $medico_id);   
                    foreach ($tabela_procedimento_medico_row as $procedimentos) {
                       $tipo = $procedimentos['tipo'];
                      
                       // se o repasse do médico for com %
                      if($tipo == 2){
                           $valor_repasse = $procedimentos['valor'];
                           $valor_repasse = $valor_repasse/100;
                           $valor_em_real_repasse = ($valor_procedimento *  $valor_repasse)* $qty;
                           
                            if($valor_repasse == 1){
                                  if($desconto_valor > 0){
                                   $valor_comissao = $valor_em_real_repasse - $desconto_valor;
                                   $valor_comissao_pgto_total = $valor_em_real_repasse - $desconto_valor;
                                  }else{
                                      $valor_comissao = $valor_em_real_repasse;
                                      $valor_comissao_pgto_total = $valor_em_real_repasse;
                                  }
                              }else{
                           
                                  if($destino_desconto == 'AMBOS'){
                                     $valor_comissao            = ($rate_desconto * $valor_repasse) * $porc_pagamento;
                                     $valor_comissao_pgto_total = ($rate_desconto * $valor_repasse);
                                  }ELSE if($destino_desconto == 'MÉDICO'){
                                      $valor_comissao = ($valor_em_real_repasse - $desconto_valor) * $porc_pagamento;
                                      $valor_comissao_pgto_total = $valor_em_real_repasse - $desconto_valor;
                                  }ELSE if($destino_desconto == 'EMPRESA'){
                                      $valor_comissao = $valor_em_real_repasse * $porc_pagamento;
                                      $valor_comissao_pgto_total = $valor_em_real_repasse;
                                  }
                         
                              }
                       }else{
                            // se o valor da comissao do médico for valor fixo
                           $valor_repasse = $procedimentos['valor'];    
                           $valor_repasse_total = ($valor_repasse  * $qty);    
                           $valor_comissao = ($valor_repasse  * $qty) * $porc_pagamento;
                           $valor_comissao_pgto_total = ($valor_repasse  * $qty) ;
                           
                           $soma_repasse_total_original += $valor_repasse_total;
                           
                           if($desconto_valor > 0){
                               
                            if($destino_desconto == 'AMBOS'){
                                $valor_desconto_comissao = $desconto_valor / 2;
                                
                            if($regra_desconto_vision){
                                 $valor_comissao = ($rate_desconto * $regra_desconto_vision * $porc_pagamento)/ 100;
                                 $valor_comissao_pgto_total = ($rate_desconto * $regra_desconto_vision)/ 100;
                                
                            }else{
                                $valor_comissao += -$valor_desconto_comissao;
                                $valor_comissao_pgto_total  += -$valor_desconto_comissao;
                            }
                                
                            
                            }ELSE if($destino_desconto == 'MÉDICO'){
                                $valor_comissao += -$desconto_valor;
                                $valor_comissao_pgto_total += -$desconto_valor;
                            }ELSE if($destino_desconto == 'EMPRESA'){
                                $valor_comissao = $valor_comissao;
                                $valor_comissao_pgto_total = $valor_comissao_pgto_total;
                            }
                            
                           
                           }
                           
                          if(get_staff_user_id() == 1){
                           // echo $itemable_id.'<br>';
                           // echo 'comissao :'.$valor_comissao.': <br>';
                           // echo 'empresa : '.$valor_tesouraria_pgto_total.'<br>';
                          //  exit;
                        }
                           
                       }
                        
                     }// fim foreach procedimentos médico
                    
                   //se não tiver tabela especial, entra na regra padrão
                   }else{
                    
                    /*
                     * 4 - Caso não encontre segue a regra padrão
                     
                        if($medico_id == 28){
                       $todos_pgto = $valor_pagamento + $valor_pgto_parciais ;
                      
                       $invoices_itens_medico_row = $this->invoices_model->get_invoice_item_pagamento_by_id($itemable_id);
                       
                       $rate_procedimento       =  $invoices_itens_medico_row->rate;
                       $qty                     =  $invoices_itens_medico_row->qty;
                       $rate_desconto           =  $invoices_itens_medico_row->rate_desconto;
                       $valor_medico_salvo      =  $invoices_itens_medico_row->valor_medico;
                       
                       $comissao                =  $invoices_itens_medico_row->comissao;
                       $comissao_porcentagem    = ($comissao/100);
                       $valor_pagamento_r       = ($rate_desconto  * $porc_pagamento);
                       $valor_comissao          = ($valor_pagamento_r  * $comissao_porcentagem) * $porc_pagamento;
                       
                      
                       $valor_em_real_repasse = ($valor_procedimento *  $valor_comissao)* $qty;
                       
                     
                       if($destino_desconto == 'AMBOS' || (!$destino_desconto)){
                           
                            $valor_comissao = $valor_comissao ;
                            $valor_comissao_pgto_total = ($rate_desconto * $comissao_porcentagem);
                        }ELSE if($destino_desconto == 'MÉDICO'){
                              $valor_comissao = ($valor_em_real_repasse - $desconto_valor) * $porc_pagamento;
                              $valor_comissao_pgto_total = $valor_em_real_repasse - $desconto_valor;
                        }ELSE if($destino_desconto == 'EMPRESA'){
                              $valor_comissao = $valor_em_real_repasse * $porc_pagamento;
                              $valor_comissao_pgto_total = $valor_em_real_repasse ;
                        }
                       
                       }*/
                       
                        $valor_comissao = 0;
                        $valor_comissao_pgto_total = 0;
                     
                       
                       
                   }// fim else regra repasse %
                  
                   // calcula o valor que fica no caixa do total do procedimento // a regra ta na coluna nota_fiscal_propria
                   $valor_tesouraria_pgto_total = $rate_desconto - $valor_comissao_pgto_total;
                  
                   // VERIFICA SE O PAGAMENTO É TOTAL OU PARCIAL. se for total, lança a comissão cheia do valor do procedimento, se não for, lança a comissão proporcional.
                   // consulta se ja tem pagamentos anteriores
                   $pagamentos_anteriores = $this->invoices_model->get_pagamentos_invoice($invoice_id);
                   $valor_pgto_parciais = $pagamentos_anteriores->valor;
                   if(!$valor_pgto_parciais){
                       $valor_pgto_parciais = 0;
                   }
                   
                   
                   /*
                    * VERIFICA SE O PAGAMENTO É O PAGAMENTO TOTAL E ATUALIZA OS VALORES DE FATURAMENTO
                    */
                    $todos_pgto = $valor_pagamento + $valor_pgto_parciais;
                    if($todos_pgto == $valor_fatura ){
                        // atualiza o repasse
                        
                         $invoices_itens_medico = $this->invoices_model->atualiza_valor_porcentagem_itemable($itemable_id,  $valor_comissao_pgto_total, $valor_tesouraria_pgto_total); 
                    }
                        $invoices_itens_medico = $this->invoices_model->atualiza_valor_porcentagem_itemable($itemable_id,  $valor_comissao_pgto_total, $valor_tesouraria_pgto_total); 
                  
                    $soma_valor_medico += $valor_comissao; 
                   
                   $cont_procedimento_medico++;
               }// fim lista por médico
                   
               
                    /*
                   * QUANDO TIVER DESCONTO
                   * VERIFICA NA REGRA DE REPASSE DOS SÓCIOS DA VISION, SE O VALOR DO REPASSE GERADO PELO DESCONTO, É MAIOR QUE O REPASSE NORMAL. CASO SEJA, AJUSTA PARA O REPASSE FIXO.
                   
                   $total_acumulado_soma_repasse = 0;
                   if($desconto_valor > 0){
                     // verifica se tem pagamentos parciais 
                     $pagamentos_anteriores_medico = $this->invoices_model->get_pagamentos_invoice_conta_medica($invoice_id, $conta_medico);
                     $valor_pgto_parciais_medico = $pagamentos_anteriores_medico->valor;  
                     if($valor_pgto_parciais_medico){
                        $total_acumulado_soma_repasse = $valor_pgto_parciais_medico + $soma_valor_medico;
                          
                        if($total_acumulado_soma_repasse > $soma_repasse_total_original){
                            $soma_valor_medico = $soma_repasse_total_original - $valor_pgto_parciais_medico;
                            if($soma_valor_medico < 0){
                                $soma_valor_medico = 0;
                            }
                        }
                     }
                     
                     // atualiza o valor da fatura
                     if($todos_pgto == $valor_fatura ){
                         
                     }
                     
                   }
                   */
                  
                
               
                  $soma_valor_caixa = $valor_pagamento - $soma_valor_medico ; 
             //echo $soma_valor_medico; exit;
                   $empresa_id = $this->session->userdata('empresa_id'); 
                   unset($data['amount']);
                   unset($data['conta_id']);
                   unset($data['nao_emitir_nota_fiscal']);
                   $data['amount'] = $soma_valor_medico;
                   $data['conta_id'] = $conta_medico; 
                   $data['medicoid'] = $medico_id;
                   $data['userid'] = get_staff_user_id();
                   $data['empresa_id'] = $empresa_id;
                 
                  
                   if($soma_valor_medico > 0){
                       //salva os pagamentos referentes a parte dos médicos
                    //   if(get_staff_user_id() != 1){
                           if (is_staff_logged_in()) {
                           $pgto_id = $this->add($data);
                            $pagamentos_ids[] = $pgto_id;
                            //return $id;
                        } 
                    //   }
                       
                    //echo '<br><br>';
                   }
                   
              
           }// fim if medico  
            
            $valor_total_todos_medicos += $soma_valor_medico;
         
            
        }// fim for conta medicos
        
        
     //   echo '<br> total caixa <br>: '.$soma_valor_caixa.'<br>';
     
        /******************************************************************/        
        $total_caixa_entrada = $valor_total_todos_medicos + $soma_valor_caixa; // - $soma_total_repasse;
     
        if($total_caixa_entrada > $valor_pagamento){
           $soma_valor_caixa =  $valor_pagamento - $valor_total_todos_medicos;
        }
                
      //echo $soma_valor_caixa; exit;
        // PAGAMENTO PARA O CAIXA EMPRESA
        unset($data['medicoid']);
        unset($data['amount']);
        unset($data['conta_id']);
        unset($data['nao_emitir_nota_fiscal']);
        
        $data['amount'] = $soma_valor_caixa;
        $data['conta_id'] = 1; // parametrizar para buscar a conta padrão da empresa
        $data['userid'] = get_staff_user_id();
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa_id'] = $empresa_id;
        //print_r($data); exit;
        //$this->gera_ncfe(5);
        //exit;
        
         if(get_staff_user_id() == 1){
          //  echo 'caixa empresa : '.$soma_valor_caixa.'<br>';
          //  exit;
        }
        
         if($soma_valor_caixa > 0){
            if (is_staff_logged_in()) {
                $caixa_id = $this->add($data);
                $pagamentos_ids[] = $caixa_id;
                return $pagamentos_ids;
            }
         }else{
             return 1;
         }
    }// fim regra de pagamento baseado nos parametros                 
        return false;
    }
    
    public function process_payment_old($data, $invoiceid = '')
    {
        
        $pagamentos_ids = array(); 
        $invoice_id = $data['invoiceid'];
        if($data['conta_id']){
        $conta_id = $data['conta_id'];    
        }else{
        $conta_id = 0;    
        }
        
      
        $do_not_redirect = $data['do_not_redirect'];
        if($do_not_redirect == 'on'){
            $do_not_redirect = 1;        }
 
        // atualiza no cadastro da fatura se é p voltar p tela de pagamento
        $this->invoices_model->atualiza_retorno_fatura($invoice_id, $do_not_redirect);
        unset($data['do_not_redirect']);

        $soma_valor_caixa = 0;
        $valor_total_todos_medicos = 0 ;
        //Valor pago
        $valor_pagamento = $data['amount'];
         
        
        // valor da fatura
        $dados_fatura = $this->invoices_model->get($invoice_id);
        $subtotal = $dados_fatura->subtotal;
        $discount_total = $dados_fatura->discount_total; // Valor do desconto
        $adjustment = $dados_fatura->adjustment; // Valor do desconto
        $valor_fatura_total = $dados_fatura->total;  // inclui anestesista
      
        /*
         * VALOR DO CRÉDITO
         */
        $valor_credito_invoice = 0;
        $dados_credito = $this->credit_notes_model->get_applied_invoice_credits($invoice_id);
        foreach ($dados_credito as $credito) {
            $valor_credito_invoice .= $credito['amount'];
        }
        
        
         /*
         * VERIFICA OS PROCEDIMENTOS QUE NÃO TEM REPASSES (OS MÉDICOS QUE RECEBEM NOS CONSULTÓRIOS)
         */
        $medicos_invoices_itens_sem_repasses = $this->invoices_model->get_invoice_item_pagamento_sem_repasse($invoice_id);
        $conta_medico_repasse = count($medicos_invoices_itens_sem_repasses);
        if($conta_medico_repasse){
          foreach ($medicos_invoices_itens_sem_repasses as $repasse) {
            $medico_id =  $repasse['medicoid'];
            $repasse_medico_itemable_id =  $repasse['itemable_id'];
            $rate_desconto =  $repasse['rate_desconto']; // qtde procedimento da fatura
            
            $total_repasse = $rate_desconto; // valor total
            $soma_total_repasse += $total_repasse;
            
            // atualiza
            $invoices_itens_medico = $this->invoices_model->atualiza_valor_porcentagem_itemable($repasse_medico_itemable_id,  $total_repasse, 0);
          }// fim foreach
        }// fim if conta médico
         if(!$soma_total_repasse ){
             $soma_total_repasse = 0; 
         }
        
        /***********************************************************************************************************************************************/
        $valor_fatura = $valor_fatura_total - $soma_total_repasse; // valor total - os médicos q n tem repasse
         // verifica se tem pagamentos parciais 
         $pagamentos_anteriores = $this->invoices_model->get_pagamentos_invoice($invoice_id);
         $valor_pgto_parciais = $pagamentos_anteriores->valor;  
        /****************************************************************************************************************************************************
         * 1 -  VERIFICA SE TEM UM PAGAMENTO DESTINADO A UMA CONTA ESPECÍFICA. 
         *      SE SIM, NÃO EXECUTA A REGRA DE PAGAMENTO, MAS REALIZA O PAGAMENTO INTEGRAL PARA A CONTA INFORMADA.
         ***************************************************************************************************************************************************/
        $conta_empresa = 0;
        $total_conta = count($conta_id); 
        // verifica se tem pagamento para a empresa, 
        foreach ($conta_id as $conta) {
           if($conta == 1){
               $conta_empresa = 1;
           }            
        }
        if($total_conta == 1){
            if($conta_empresa == 0){
           foreach ($conta_id as $conta) { 
           if(!$valor_pgto_parciais){
               $valor_pgto_parciais = 0;
           }
           $valor_desconto_fatura = 0;
           $todos_pgto = $valor_pagamento + $valor_pgto_parciais ;
           
        // 2 - listar os procedimentos para distribuir o valor pagao entre eles
           
        $medicos_invoices_itens_sem_repasses = $this->invoices_model->get_invoice_item_pagamento_fatura($invoice_id);
        $conta_medico_repasse = count($medicos_invoices_itens_sem_repasses);
        $conta_procedimento = 1;
        if($conta_medico_repasse){
          $saldo =  $valor_pagamento;
          $saldo_total =  $valor_pagamento;
          foreach ($medicos_invoices_itens_sem_repasses as $repasse) {
            $medico_id =  $repasse['medicoid'];
            $itemable_id =  $repasse['itemable_id'];
            $item_id =  $repasse['item_id'];
            $qty =  $repasse['qty']; // qtde procedimento da fatura
            $valor_procedimento_c_desconto =  $repasse['rate_desconto'];
            $desconto_porcentagem =  $repasse['desconto_porcentagem'];
             // pega os dados ca conta do médico,  verifica se ele tem nota fiscal propria
            $invoices_itens_medico = $this->invoices_model->get_conta_financeira_medico($itemable_id);
            $conta_medico_procedimentos = count($invoices_itens_medico);
            $nota_fiscal_propria = $invoices_itens_medico->nota_fiscal_propria; 
            
               // 3 - atualiza o valor do repasse do médico de acordo c o vl pago
            if($todos_pgto == $valor_fatura ){
               
           /*
            * retorna a comissao do médico
            */
            $tabela_procedimento_medico_row = $this->invoices_model->get_medico_procedimento_invoice_item_conta_selecionada($item_id, $medico_id);
            $regra_procedimento = count($tabela_procedimento_medico_row); 
                if($regra_procedimento >= 1){
                   $tipo = $tabela_procedimento_medico_row->tipo;
                   // se o repasse do médico for com %
                   
                    // se o repasse do médico for com %
                       if($tipo == 2){
                       
                           $valor_repasse = $tabela_procedimento_medico_row->valor;
                           $valor_repasse = $valor_repasse/100;
                           $valor_comissao = ($valor_procedimento_c_desconto * $valor_repasse);
                           $valor_comissao_pgto_total = ($valor_procedimento_c_desconto * $valor_repasse);
                       }else{
                          
                           // se o valor da comissao do médico for valor fixo
                           $valor_repasse = $tabela_procedimento_medico_row->valor;    
                           $valor_comissao = ($valor_repasse  * $qty) ;
                           $valor_comissao_pgto_total = ($valor_repasse  * $qty);
                           
                           if($desconto_porcentagem > 0){
                            $desconto_porcentagem = ($desconto_porcentagem/100);
                            $valor_desconto_comissao = ($valor_comissao  * $desconto_porcentagem);
                            $valor_comissao += -$valor_desconto_comissao;
                            $valor_deconto_comissao_pgto_total = ($valor_comissao_pgto_total  * $desconto_porcentagem);  
                            $valor_comissao_pgto_total += -$valor_deconto_comissao_pgto_total;
                           }
                       }
                   

                //se não tiver tabela especial, entra na regra padrão
                }else{

                /*
                 * 4 - Caso não encontre segue a regra padrão
                 */
                   $todos_pgto = $valor_pagamento + $valor_pgto_parciais ;

                   $invoices_itens_medico_row = $this->invoices_model->get_invoice_item_pagamento_by_id($itemable_id);

                   $comissao                =  $invoices_itens_medico_row->comissao;
                   $comissao                = ($comissao/100);
                   $conta_id                =  $invoices_itens_medico_row->conta_id;
                   $rate_procedimento       =  $invoices_itens_medico_row->rate;
                   $qty                     =  $invoices_itens_medico_row->qty;
                   $valor_medico_salvo      =  $invoices_itens_medico_row->valor_medico;
                   $valor_tesouraria        =  $invoices_itens_medico_row->valor_tesouraria;
                   $rate_desconto           =  $invoices_itens_medico_row->rate_desconto;
                   $valor_comissao          = ($valor_pagamento * $comissao) ;  
                   $valor_comissao_pgto_total = ($rate_desconto * $comissao);  
              
               }// fim else regra repasse %
                 if($nota_fiscal_propria == 1){
                    // tira a parte do médico do valor do caixa
                    $valor_tesouraria_pgto_total = $valor_procedimento_c_desconto -  $valor_comissao_pgto_total;
                   
                       
                }else{
                    $valor_tesouraria_pgto_total = $valor_procedimento_c_desconto ;
                    
                }
                
               
                 $invoices_itens_medico = $this->invoices_model->atualiza_valor_porcentagem_itemable($itemable_id,  $valor_comissao_pgto_total, $valor_tesouraria_pgto_total); 
               }
              
            $saldo_total += - $valor_tesouraria_pgto_total;
            $conta_procedimento++;
               
            
            
          }// fim foreach
         } 
            
           $data['userid'] = get_staff_user_id();
           if (is_staff_logged_in()) {
            $id = $this->add($data);
            return $id;
           }
        }    
        } 
        }// fim if conta médico
        /************* FIM PGTO PARA UM CONTA********************************************/
        else{
        // verifica se n vai receber o valor do médico q recebe separado
        $todos_pgtos = $valor_pagamento + $valor_pgto_parciais ;
      
        if($todos_pgtos > $valor_fatura){
           echo 'O Pagamento informado é maior que o esperado. Verifique se todos os médicos envolvidos está parametrizado para receber repasse.';
           //return false;
           exit;
        }
         
        // calcula a % do pagamento
        
        $porc_pagamento = ($valor_pagamento * 100)/$valor_fatura;  
        $porc_pagamento = $porc_pagamento/100;
        
        
        // 1 - retorna os médicos da fatura (único)
        $valor_caixa = 0;
        
        $repasse_medico_geral = 1;
        $medicos_invoices_itens = $this->invoices_model->get_medico_invoice_item_pagamento($invoice_id);
        foreach ($medicos_invoices_itens as $medico) {
           $medico_id =  $medico['medicoid'];
           $medico_repasse =  $medico['repasse'];
           $desconto =  $medico['desconto'];
           $soma_valor_medico = 0;
           
           echo $medico_id.'<br>';
      
           $valor_comissao = 0;
           $valor_tesouraria = 0; // valor que fica no caixa de cada procedimento pago. (caixa e médico)
           
            // 2-  lista os procedimentos de um médico
           $cont_procedimento_medico = 1;
           $soma_desconto_procedimento_medico = 0;
           if($medico_id > 0){
                
                $invoices_itens_medico = $this->invoices_model->get_conta_financeira_pagamento($invoice_id, $medico_id);
                $conta_medico_procedimentos = count($invoices_itens_medico);
                foreach ($invoices_itens_medico as $items) {
                  $nota_fiscal_propria =  $items['nota_fiscal_propria']; 
                  $itemable_id =  $items['id'];
                  $valor_procedimento =  $items['rate'];
                  $qty =  $items['qty'];
                  $rate_desconto =  $items['rate_desconto']; // valor total do procedimento com o desconto aplicado
                  $desconto_porcentagem =  $items['desconto_porcentagem'];
                  /*
                   * VERIFICAR SE O CLIENTE PAGOU 100% DO VALOR DA FATURA, SOMANDO OS PAGAMENTOS PARCIAIS SE TIVER. 
                   * CASO TENHA PAGO ATUALIZA O FATURAMENTO DO MÉDICO
                   */
                   $items_id  =  $items['id'];
                   $item_id   =  $items['item_id'];
                   $conta_id  =  $items['conta_id'];
                   echo $items_id.'<br>';
                   /*
                   * 3 - Verifica na excessão o médico para esse procedimento ( Tabela de repasse do médico por procedimento)
                   */
                   //$valor_caixa = $valor_total_procedimento * $porc_pagamento;
                   $tabela_procedimento_medico_row = $this->invoices_model->get_medico_procedimento_invoice_item($item_id, $medico_id);
                   $regra_procedimento = count($tabela_procedimento_medico_row); 
                 //  print_r($tabela_procedimento_medico_row); 
                   
                   if($regra_procedimento >= 1){
                    foreach ($tabela_procedimento_medico_row as $procedimentos) {
                       $tipo = $procedimentos['tipo'];
                       // se o repasse do médico for com %
                       if($tipo == 2){
                           $valor_repasse = $procedimentos['valor'];
                           $valor_repasse = $valor_repasse/100;
                            $valor_comissao = ($valor_pagamento  * $valor_repasse);
                           $valor_comissao_pgto_total = ($rate_desconto * $valor_repasse);
                       }else{
                            // se o valor da comissao do médico for valor fixo
                           $valor_repasse = $procedimentos['valor'];    
                           $valor_repasse_pgto_total = $procedimentos['valor'];
                           $valor_comissao = ($valor_repasse  * $qty);
                           $valor_comissao = ($valor_comissao * $porc_pagamento);
                           $valor_comissao = substr($valor_comissao,0,5);
                           $valor_comissao_pgto_total = ($valor_repasse  * $qty);
                           
                          
                           
                            if($desconto_porcentagem > 0){
                            $desconto_porcentagem = ($desconto_porcentagem/100);
                            $valor_desconto_comissao = ($valor_comissao  * $desconto_porcentagem);
                            $valor_comissao += -$valor_desconto_comissao;
                            $valor_deconto_comissao_pgto_total = ($valor_comissao_pgto_total  * $desconto_porcentagem);  
                            $valor_comissao_pgto_total += -$valor_deconto_comissao_pgto_total;
                           }
                       }
                        
                     }// fim foreach procedimentos médico
                   
                   //se não tiver tabela especial, entra na regra padrão
                   }else{
                      
                    /*
                     * 4 - Caso não encontre segue a regra padrão
                     */
                       $todos_pgto = $valor_pagamento + $valor_pgto_parciais ;
                      
                       $invoices_itens_medico_row = $this->invoices_model->get_invoice_item_pagamento_by_id($items_id);
                       
                       $comissao                =  $invoices_itens_medico_row->comissao;
                       $comissao                = ($comissao/100);
                       $conta_id                =  $invoices_itens_medico_row->conta_id;
                       $rate_procedimento       =  $invoices_itens_medico_row->rate;
                       $qty                     =  $invoices_itens_medico_row->qty;
                       $rate_desconto                     =  $invoices_itens_medico_row->rate_desconto;
                       $valor_medico_salvo      =  $invoices_itens_medico_row->valor_medico;
                      
                       echo $rate_desconto.'<br>';
                      
                       echo 'vl pgto : '.$todos_pgto.'<Br>';
                       echo 'vl fatura : '.$valor_fatura.'<Br>';
                       if($todos_pgto == $valor_fatura ){
                       $valor_comissao          = ($rate_desconto * $comissao) ;    
                        echo 'comissao 1: '.$valor_comissao.'<br>';
                       }else{
                       $valor_comissao          = ($valor_pagamento * $comissao) ; 
                        echo 'comissao 2: '.$valor_comissao.'<br>';
                       }
                      
                       $valor_comissao_pgto_total = ($rate_desconto * $comissao) ;
                       echo $rate_procedimento.'<br>';
                        echo 'aqui : '.$comissao.'<br>';
                        
                     
                   }// fim else regra repasse %
                   
                   // calcula o valor que fica no caixa do total do procedimento // a regra ta na coluna nota_fiscal_propria
                    if($nota_fiscal_propria == 1){
                       // tira a parte do médico do valor do caixa
                       $valor_tesouraria_pgto_total = $rate_desconto - $valor_comissao_pgto_total;
                    }else{
                       $valor_tesouraria_pgto_total = $rate_desconto;
                    }
                   
                   // VERIFICA SE O PAGAMENTO É TOTAL OU PARCIAL. se for total, lança a comissão cheia do valor do procedimento, se não for, lança a comissão proporcional.
                   // consulta se ja tem pagamentos anteriores
                   $pagamentos_anteriores = $this->invoices_model->get_pagamentos_invoice($invoice_id);
                   $valor_pgto_parciais = $pagamentos_anteriores->valor;
                   if(!$valor_pgto_parciais){
                       $valor_pgto_parciais = 0;
                   }
                  
                  
                    $todos_pgto = $valor_pagamento + $valor_pgto_parciais;
                    if($todos_pgto == $valor_fatura ){
                     if(!$conta_id){
                         $conta_id = 0;
                     }
                        // 1 - verifica se o valor da comissão do médico, está igual ao valor previsto. se não tiver ajusta.
                        $resumo_pagamento_medico = $this->invoices_model->get_pagamentos_invoice_conta_financeira($invoice_id, $conta_id);
                        $valor_pgto_parciais_medico = $resumo_pagamento_medico->valor;
                      
                        if(!$valor_pgto_parciais_medico){
                           $valor_pgto_parciais_medico = 0;
                        }
                        $valor_comissao_parcial = $valor_pgto_parciais_medico + $valor_comissao; // soma qto o sistema ta calculando
                      
                        // 2 - verifica se o valor somado é menor que a comissão do médico. se for, vai arredondar
                        if($valor_comissao_parcial < $valor_comissao_pgto_total){
                           $diferenca_exata_comissao = $valor_comissao_pgto_total - $valor_pgto_parciais_medico;
                           $valor_comissao = $diferenca_exata_comissao;
                       }
                       
                        // atualiza o repasse
                        $invoices_itens_medico = $this->invoices_model->atualiza_valor_porcentagem_itemable($itemable_id,  $valor_comissao_pgto_total, $valor_tesouraria_pgto_total); 
                    }
                   $soma_valor_medico += $valor_comissao;
                   $soma_valor_caixa = $valor_pagamento - $soma_valor_medico ;
                 
                   $cont_procedimento_medico++;
               }// fim lista por médico
              
               echo $valor_comissao.'<br>';
             //  echo $soma_valor_medico.'<br>';
               echo $soma_valor_caixa.'<br>';
               echo '<br>';
              // print_r($data); exit;
                
                   unset($data['amount']);
                   unset($data['conta_id']);
                   unset($data['nao_emitir_nota_fiscal']);
                   $data['amount'] = $soma_valor_medico;
                   $data['conta_id'] = $conta_id; 
                   $data['medicoid'] = $medico_id;
                   $data['userid'] = get_staff_user_id();
                   
                   if($soma_valor_medico > 0){
                     if($medico_repasse > 0){
                        //salva os pagamentos referentes a parte dos médicos
                       //registra o pagamento se a regra de repasse tiver habilitada
                        if (is_staff_logged_in()) {
                           // $medico_id = $this->add($data);
                            $pagamentos_ids[] = $medico_id;
                            //return $id;
                        }
                     }  
                    //echo '<br><br>';
                   }
           
              
           }// fim if medico
            
            $valor_total_todos_medicos += $soma_valor_medico;
            // faz o insert no banco de dados - conta do médico
                
        }// fim for médico
           exit;
       
        if($repasse_medico_geral == 0){
        $total_caixa_entrada = $soma_valor_caixa  ; // - $soma_total_repasse;    
        }else{
        $total_caixa_entrada = $valor_total_todos_medicos + $soma_valor_caixa; // - $soma_total_repasse;
        }
     
         
        if($total_caixa_entrada > $valor_pagamento){
           $soma_valor_caixa =  $valor_pagamento - $valor_total_todos_medicos;
        }
        
        // PAGAMENTO PARA O CAIXA EMPRESA
        unset($data['medicoid']);
        unset($data['amount']);
        unset($data['conta_id']);
        unset($data['nao_emitir_nota_fiscal']);
        $data['amount'] = $soma_valor_caixa;
        $data['conta_id'] = 1; // parametrizar para buscar a conta padrão da empresa
        $data['userid'] = get_staff_user_id();
        //print_r($data); exit;
        //$this->gera_ncfe(5);
        //exit;
        if (is_staff_logged_in()) {
            $caixa_id = $this->add($data);
         
            $pagamentos_ids[] = $caixa_id;
            
            return $pagamentos_ids;
        }
        
    }// fim regra de pagamento baseado nos parametros          
            
        
        
        return false;
    }

    /**
     * Record new payment
     * @param array $data payment data
     * @return boolean
     */
    public function add($data, $subscription = false)
    {
        // Check if field do not redirect to payment processor is set so we can unset from the database
        if (isset($data['do_not_redirect'])) {
            unset($data['do_not_redirect']);
        }
       
        /*
        if ($subscription != false) {
            $after_success = get_option('after_subscription_payment_captured');

            if ($after_success == 'nothing' || $after_success == 'send_invoice') {
                $data['do_not_send_email_template'] = true;
            }
        }*/
        /*
        if (isset($data['do_not_send_email_template'])) {
            unset($data['do_not_send_email_template']);
            $do_not_send_email_template = true;
        } elseif ($this->session->has_userdata('do_not_send_email_template')) {
            $do_not_send_email_template = true;
            $this->session->unset_userdata('do_not_send_email_template');
        }
         $do_not_send_email_template = true;
         */
         
        if (is_staff_logged_in()) {
            if (isset($data['date'])) {
                $data['date'] = to_sql_date($data['date']);
            } else {
                $data['date'] = date('Y-m-d H:i:s');
            }
            if (isset($data['note'])) {
                $data['note'] = nl2br($data['note']);
            } elseif ($this->session->has_userdata('payment_admin_note')) {
                $data['note'] = nl2br($this->session->userdata('payment_admin_note'));
                $this->session->unset_userdata('payment_admin_note');
            }
        } else {
            $data['date'] = date('Y-m-d H:i:s');
        }

        $data['daterecorded'] = date('Y-m-d H:i:s');
        $data                 = hooks()->apply_filters('before_payment_recorded', $data);
        //print_r($data); exit;
        // REGISTRA NO BANCO DE DADOS O PAGAMENTO
        $this->db->insert(db_prefix() . 'invoicepaymentrecords', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            $invoice      = $this->invoices_model->get($data['invoiceid']);
            $force_update = false;

            if (!class_exists('Invoices_model', false)) {
                $this->load->model('invoices_model');
            }

            if ($invoice->status == Invoices_model::STATUS_DRAFT) {
                $force_update = true;
            }

            update_invoice_status($data['invoiceid'], $force_update);

            $activity_lang_key = 'invoice_activity_payment_made_by_staff';
            if (!is_staff_logged_in()) {
                $activity_lang_key = 'invoice_activity_payment_made_by_client';
            }

            $this->invoices_model->log_invoice_activity($data['invoiceid'], $activity_lang_key, !is_staff_logged_in() ? true : false, serialize([
                app_format_money($data['amount'], $invoice->currency_name),
                '<a href="' . admin_url('payments/payment/' . $insert_id) . '" target="_blank">#' . $insert_id . '</a>',
            ]));

            log_activity('Payment Recorded [ID:' . $insert_id . ', Invoice Number: ' . format_invoice_number($invoice->id) . ', Total: ' . app_format_money($data['amount'], $invoice->currency_name) . ']');
           // $this->invoices_model->log_invoice_activity($data['invoiceid'], $activity_lang_key, false, serialize($data));
            // Send email to the client that the payment is recorded
            /*
            $payment               = $this->get($insert_id);
            $payment->invoice_data = $this->invoices_model->get($payment->invoiceid);
            set_mailing_constant();
            $paymentpdf           = payment_pdf($payment);
            $payment_pdf_filename = mb_strtoupper(slug_it(_l('payment') . '-' . $payment->paymentid), 'UTF-8') . '.pdf';
            $attach               = $paymentpdf->Output($payment_pdf_filename, 'S');

            if (!isset($do_not_send_email_template)
                || ($subscription != false && $after_success == 'send_invoice_and_receipt')
                || ($subscription != false && $after_success == 'send_invoice')
            ) {
                $template_name        = 'invoice_payment_recorded_to_customer';
                $pdfInvoiceAttachment = false;
                $attachPaymentReceipt = true;
                $emails_sent          = [];

                $where = ['active' => 1, 'invoice_emails' => 1];

                if ($subscription != false) {
                    $where['is_primary'] = 1;
                    $template_name       = 'subscription_payment_succeeded';

                    if ($after_success == 'send_invoice_and_receipt' || $after_success == 'send_invoice') {
                        $invoice_number = format_invoice_number($payment->invoiceid);
                        set_mailing_constant();
                        $pdfInvoice           = invoice_pdf($payment->invoice_data);
                        $pdfInvoiceAttachment = $pdfInvoice->Output($invoice_number . '.pdf', 'S');

                        if ($after_success == 'send_invoice') {
                            $attachPaymentReceipt = false;
                        }
                    }
                    // Is from settings: Send Payment Receipt
                }

                $contacts = $this->clients_model->get_contacts($invoice->clientid, $where);

                foreach ($contacts as $contact) {
                    $template = mail_template(
                        $template_name,
                        $contact,
                        $invoice,
                        $subscription,
                        $payment->paymentid
                    );

                    if ($attachPaymentReceipt) {
                        $template->add_attachment([
                                'attachment' => $attach,
                                'filename'   => $payment_pdf_filename,
                                'type'       => 'application/pdf',
                            ]);
                    }

                    if ($pdfInvoiceAttachment) {
                        $template->add_attachment([
                            'attachment' => $pdfInvoiceAttachment,
                            'filename'   => $invoice_number . '.pdf',
                            'type'       => 'application/pdf',
                        ]);
                    }
                    $merge_fields = $template->get_merge_fields();

                    if ($template->send()) {
                        array_push($emails_sent, $contact['email']);
                    }

                    $this->app_sms->trigger(SMS_TRIGGER_PAYMENT_RECORDED, $contact['phonenumber'], $merge_fields);
                }

                if (count($emails_sent) > 0) {
                    $additional_activity_data = serialize([
                       implode(', ', $emails_sent),
                     ]);
                    $activity_lang_key = 'invoice_activity_record_payment_email_to_customer';
                    if ($subscription != false) {
                        $activity_lang_key = 'invoice_activity_subscription_payment_succeeded';
                    }
                    $this->invoices_model->log_invoice_activity($invoice->id, $activity_lang_key, false, $additional_activity_data);
                }
            } 

            $this->db->where('staffid', $invoice->addedfrom);
            $this->db->or_where('staffid', $invoice->sale_agent);
            $staff_invoice = $this->db->get(db_prefix() . 'staff')->result_array();

            $notifiedUsers = [];
            foreach ($staff_invoice as $member) {
                if (get_option('notification_when_customer_pay_invoice') == 1) {
                    if (is_staff_logged_in() && $member['staffid'] == get_staff_user_id()) {
                        continue;
                    }
                    // E.q. had permissions create not don't have, so we must re-check this
                    if (user_can_view_invoice($invoice->id, $member['staffid'])) {
                        $notified = add_notification([
                        'fromcompany'     => true,
                        'touserid'        => $member['staffid'],
                        'description'     => 'not_invoice_payment_recorded',
                        'link'            => 'invoices/list_invoices/' . $invoice->id,
                        'additional_data' => serialize([
                            format_invoice_number($invoice->id),
                        ]),
                    ]);
                        if ($notified) {
                            array_push($notifiedUsers, $member['staffid']);
                        }
                        send_mail_template(
                            'invoice_payment_recorded_to_staff',
                            $member['email'],
                            $member['staffid'],
                            $invoice,
                            $attach,
                            $payment->id
                        );
                    }
                }
            }*/

            pusher_trigger_notification($notifiedUsers);

            hooks()->do_action('after_payment_added', $insert_id);

            return $insert_id;
        }

        return false;
    }
    
    public function process_payment_conta_receber($data, $invoiceid = '')
    {
       $id = $this->add_conta_receber($data);
       return $id;       
    }
    
    public function process_glosa_conta_receber($data, $invoiceid = '')
    {
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa_id'] = $empresa_id;
        $data['daterecorded'] = date('Y-m-d H:i:s');
        $data['date'] = to_sql_date($data['date']);
        
        $this->db->insert(db_prefix() . 'fin_glosas', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            $invoice      = $this->Financeiro_model->get_conta_receber($data['invoiceid']);
            $force_update = false;
            
            
            if (!class_exists('Invoices_model', false)) {
                $this->load->model('invoices_model');
            }
            update_fin_invoice_status($data['invoiceid'], $force_update);
            
            $activity_lang_key = 'invoice_activity_payment_made_by_staff';
            if (!is_staff_logged_in()) {
                $activity_lang_key = 'invoice_activity_payment_made_by_client';
            }

           

            log_activity('Glosa Recorded [ID:' . $insert_id . ', Invoice Number: ' . format_invoice_number($invoice->id) . ', Total: ' . app_format_money($data['amount'], $invoice->currency_name) . ']');
          
            hooks()->do_action('after_payment_added', $insert_id);

            return $insert_id;
        }
        
       
       return false;       
    }
    
    public function add_conta_receber($data, $subscription = false)
    {
       
         
        if (is_staff_logged_in()) {
            if (isset($data['date'])) {
                $data['date'] = to_sql_date($data['date']);
            } else {
                $data['date'] = date('Y-m-d H:i:s');
            }
            if (isset($data['note'])) {
                $data['note'] = nl2br($data['note']);
            } elseif ($this->session->has_userdata('payment_admin_note')) {
                $data['note'] = nl2br($this->session->userdata('payment_admin_note'));
                $this->session->unset_userdata('payment_admin_note');
            }
        } else {
            $data['date'] = date('Y-m-d H:i:s');
        }
        $empresa_id = $this->session->userdata('empresa_id');
        $data['empresa_id'] = $empresa_id;
        $data['daterecorded'] = date('Y-m-d H:i:s');
        $data                 = hooks()->apply_filters('before_payment_recorded', $data);
       
        // REGISTRA NO BANCO DE DADOS O PAGAMENTO
        $this->db->insert(db_prefix() . 'fin_invoicepaymentrecords', $data);
        $insert_id = $this->db->insert_id();
        
        if ($insert_id) {
            $invoice      = $this->Financeiro_model->get_conta_receber($data['invoiceid']);
            $force_update = false;
            
            if (!class_exists('Invoices_model', false)) {
                $this->load->model('invoices_model');
            }

            update_fin_invoice_status($data['invoiceid'], $force_update);
            
            $activity_lang_key = 'invoice_activity_payment_made_by_staff';
            if (!is_staff_logged_in()) {
                $activity_lang_key = 'invoice_activity_payment_made_by_client';
            }

           

            log_activity('Payment Recorded [ID:' . $insert_id . ', Invoice Number: ' . format_invoice_number($invoice->id) . ', Total: ' . app_format_money($data['amount'], $invoice->currency_name) . ']');
          
            hooks()->do_action('after_payment_added', $insert_id);

            return $insert_id;
        }

        return false;
    }
    /**
     * Update payment
     * @param  array $data payment data
     * @param  mixed $id   paymentid
     * @return boolean
     */
    public function update($data, $id)
    {
        $payment = $this->get($id);
        $com_cpf_nota_fiscal = $data['com_cpf_nota_fiscal'];
        if(!$com_cpf_nota_fiscal){
            $data['com_cpf_nota_fiscal'] = 0;
        }
        $data['date'] = to_sql_date($data['date']);
        $data['note'] = nl2br($data['note']);
        unset($data['invoiceid']);
     
        $data = hooks()->apply_filters('before_payment_updated', $data, $id);
       // print_R($data); exit;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'invoicepaymentrecords', $data);
      
        if ($this->db->affected_rows() > 0) {
             
            if ($data['amount'] != $payment->amount) {
                update_invoice_status($payment->invoiceid);
            }
            log_activity('Payment Updated [Number:' . $id . ']');

            return true;
        }

        return false;
    }
    
    public function update_nota_fiscal($data, $id)
    {
    
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'invoicepaymentrecords', $data);
        if ($this->db->affected_rows() > 0) {
           
            return true;
        }

        return false;
    }
    
    public function update_nota_fiscal_conta($data,  $conta_id, $pagamentos_id)
    {
        $this->db->where('id', $pagamentos_id);
        $this->db->where('conta_id', $conta_id);
       // $this->db->where('invoiceid', $id);
        $this->db->update(db_prefix() . 'invoicepaymentrecords', $data);
        if ($this->db->affected_rows() > 0) {
           
            return true;
        }

        return false;
    }

    /**
     * Delete payment from database
     * @param  mixed $id paymentid
     * @return boolean
     */
    public function delete($id)
    {
        $current         = $this->get($id);
        $current_invoice = $this->invoices_model->get($current->invoiceid);
        $invoiceid       = $current->invoiceid;
        hooks()->do_action('before_payment_deleted', [
            'paymentid' => $id,
            'invoiceid' => $invoiceid,
        ]);
        $data['deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'invoicepaymentrecords', $data);
        //$this->db->delete(db_prefix() . 'invoicepaymentrecords');
        if ($this->db->affected_rows() > 0) {
            update_invoice_status($invoiceid);
            $this->invoices_model->log_invoice_activity($invoiceid, 'invoice_activity_payment_deleted', false, serialize([
                $current->paymentid,
                app_format_money($current->amount, $current_invoice->currency_name),
            ]));
            log_activity('Payment Deleted [ID:' . $id . ', Invoice Number: ' . format_invoice_number($current->id) . ']');

            return true;
        }

        return false;
    }
    
    public function delete_financeiro($id, $invoice)
    {
        hooks()->do_action('before_payment_deleted', [
            'paymentid' => $id,
            'invoiceid' => $invoice,
        ]);
        $data['deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'fin_invoicepaymentrecords', $data);
        //$this->db->delete(db_prefix() . 'invoicepaymentrecords');
        if ($this->db->affected_rows() > 0) {
            
            update_fin_invoice_status($invoice);
            
            log_activity('Payment Deleted [ID:' . $id . ', Invoice Number: ' . format_invoice_number($invoice) . ']');

            return true;
        }

        return false;
    }
    
    public function delete_glosa($id, $invoice)
    {
        hooks()->do_action('before_payment_deleted', [
            'paymentid' => $id,
            'invoiceid' => $invoice,
        ]);
        $data['deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'fin_glosas', $data);
        //$this->db->delete(db_prefix() . 'invoicepaymentrecords');
        if ($this->db->affected_rows() > 0) {
            
            // DIMINUI O VALOR DA GLOSA DO TOTAL DA FATURA
            $glosas = $this->Financeiro_model->get_glosas_invoice($invoice);
            $totalGlosas = [];
            foreach ($glosas as $payment) {
                array_push($totalGlosas, $payment['amount']);
            }
            $totalGlosas = array_sum($totalGlosas);


            if(!$totalGlosas){
                $totalGlosas = 0;
            }
            // ATUALZIA O VALOR TOTAL
            $invoice_dados = $this->Financeiro_model->get_conta_receber($invoice);
            $data_fat['glosa_antes'] = $totalGlosas;
            $data_fat['total'] = $invoice_dados->total + $totalGlosas;
            $this->db->where('id', $invoice);
            $this->db->update(db_prefix() . 'fin_invoices', $data_fat);
            
            update_fin_invoice_status($invoice);
            
            log_activity('Glosa Deleted [ID:' . $id . ', Invoice Number: ' . format_invoice_number($invoice) . ']');

            return true;
        }

        return false;
    }
    
    // RETORNA OS MÉDICOS QUE NÃO TEM CNPJ PRÓPRIO E TEM A NOTA EMITIDA JUNTO COM A EMPRESA
     public function get_contas_invoice_payments_empresa($invoiceid)
    {
       $sql = "SELECT  tblinvoicepaymentrecords.invoiceid, tblconta_financeira.id as conta_id, tblconta_financeira.cnpj_empresa, tblconta_financeira.token_homologacao, tblconta_financeira.token_producao, tblconta_financeira.token_senha, tblconta_financeira.emissao_producao,
            sum(amount) as valor, tblinvoicepaymentrecords.paymentmode, tblinvoicepaymentrecords.date, tblinvoicepaymentrecords.nome_nota_fiscal, tblinvoicepaymentrecords.cpf_nota_fiscal,
            tblconta_financeira.iss, tblconta_financeira.pis, tblconta_financeira.confins, tblinvoicepaymentrecords.com_cpf_nota_fiscal, i.*
            FROM tblinvoicepaymentrecords
            inner join tblpayment_modes on tblpayment_modes.id = tblinvoicepaymentrecords.paymentmode
            inner join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
            inner join tblinvoices i on i.id = tblinvoicepaymentrecords.invoiceid
            where invoiceid = $invoiceid and tblconta_financeira.nota_fiscal_propria = 0";
       //echo $sql; exit;
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
    // RETORNA OS MÉDICOS QUE TEM CNPJ PRÓPRIO E TEM A NOTA EMITIDA INDIVIDUALMENTE DE UMA FATURA
     public function get_contas_medicos_cnpj_proprio($invoiceid)
    {
       $sql = "SELECT  tblinvoicepaymentrecords.id as pagamento_id, tblinvoicepaymentrecords.invoiceid, tblconta_financeira.id as conta_id, tblconta_financeira.cnpj_empresa, tblconta_financeira.token_homologacao, tblconta_financeira.token_producao, tblconta_financeira.token_senha, tblconta_financeira.emissao_producao,
            sum(amount) as valor, tblinvoicepaymentrecords.paymentmode, tblinvoicepaymentrecords.date, tblinvoicepaymentrecords.nome_nota_fiscal, tblinvoicepaymentrecords.cpf_nota_fiscal,
            tblconta_financeira.iss, tblconta_financeira.pis, tblconta_financeira.confins, tblinvoicepaymentrecords.com_cpf_nota_fiscal, i.*
            FROM tblinvoicepaymentrecords
            inner join tblpayment_modes on tblpayment_modes.id = tblinvoicepaymentrecords.paymentmode
            inner join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
            inner join tblinvoices i on i.id = tblinvoicepaymentrecords.invoiceid
            where invoiceid = $invoiceid and tblconta_financeira.nota_fiscal_propria = 1 and tblconta_financeira.cnpj_empresa is not null";
      // echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
     // RETORNA 1 MÉDICOS QUE TEM CNPJ PRÓPRIO E TEM A NOTA EMITIDA INDIVIDUALMENTE DE UMA FATURA
     public function get_conta_medicos_cnpj_proprio($invoiceid)
    {
       $sql = "SELECT  tblinvoicepaymentrecords.id as pagamento_id, tblinvoicepaymentrecords.invoiceid, tblconta_financeira.id as conta_id, tblconta_financeira.cnpj_empresa, tblconta_financeira.token_homologacao, tblconta_financeira.token_producao, tblconta_financeira.token_senha, tblconta_financeira.emissao_producao,
            sum(amount) as valor, tblinvoicepaymentrecords.paymentmode, tblinvoicepaymentrecords.date, tblinvoicepaymentrecords.nome_nota_fiscal, tblinvoicepaymentrecords.cpf_nota_fiscal,
            tblconta_financeira.iss, tblconta_financeira.pis, tblconta_financeira.confins, tblinvoicepaymentrecords.com_cpf_nota_fiscal, i.*
            FROM tblinvoicepaymentrecords
            inner join tblpayment_modes on tblpayment_modes.id = tblinvoicepaymentrecords.paymentmode
            inner join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
            inner join tblinvoices i on i.id = tblinvoicepaymentrecords.invoiceid
            where invoiceid = $invoiceid and tblconta_financeira.nota_fiscal_propria = 1 and tblconta_financeira.cnpj_empresa is not null";
      // echo $sql; exit;
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
    // retorna os pagamentos de uma fatura
     public function get_lista_contas_invoice_payments_empresa($invoiceid)
    {
       $sql = "SELECT tblinvoicepaymentrecords.id as id
            FROM tblinvoicepaymentrecords
            inner join tblpayment_modes on tblpayment_modes.id = tblinvoicepaymentrecords.paymentmode
            inner join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
            inner join tblinvoices i on i.id = tblinvoicepaymentrecords.invoiceid
            where invoiceid = $invoiceid and tblconta_financeira.nota_fiscal_propria = 0";
      // echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    // retorna os pagamentos de uma fatura realizados para um médico
     public function get_lista_contas_invoice_payments_medicos($payment_id)
    {
       $sql = "SELECT tblinvoicepaymentrecords.conta_id
            FROM tblinvoicepaymentrecords
            inner join tblpayment_modes on tblpayment_modes.id = tblinvoicepaymentrecords.paymentmode
            inner join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
            inner join tblinvoices i on i.id = tblinvoicepaymentrecords.invoiceid
            where tblinvoicepaymentrecords.id in ($payment_id) and tblconta_financeira.nota_fiscal_propria = 1 "
               . " group by tblinvoicepaymentrecords.conta_id";
       
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    
    // emitido qdo selecionado o pagamento para emitir nota
    // PAGAMENTO COM CNPJ DA EMPRESA
     public function get_contas_payments_list_id_selecionado($payment_id)
    {
       $sql = "SELECT  tblinvoicepaymentrecords.invoiceid,  tblconta_financeira.id as conta_id, tblconta_financeira.cnpj_empresa, tblconta_financeira.token_homologacao, tblconta_financeira.token_producao,
           tblconta_financeira.token_senha, tblconta_financeira.emissao_producao,  tblconta_financeira.cnpj_empresa, sum(amount) as valor, tblinvoicepaymentrecords.paymentmode, tblinvoicepaymentrecords.date,
           tblinvoicepaymentrecords.nome_nota_fiscal, tblinvoicepaymentrecords.cpf_nota_fiscal,  tblconta_financeira.iss, tblconta_financeira.pis, tblconta_financeira.confins, i.*
            FROM tblinvoicepaymentrecords
            inner join tblpayment_modes on tblpayment_modes.id = tblinvoicepaymentrecords.paymentmode
            inner join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
            inner join tblinvoices i on i.id = tblinvoicepaymentrecords.invoiceid
            where tblinvoicepaymentrecords.id in ($payment_id) and tblconta_financeira.nota_fiscal_propria = 0";
       //echo $sql; exit;
       $result = $this->db->query($sql)->row();
      
        return $result;
    }
    
    
      public function get_contas_payments_list_id_selecionado_lista_detalhes_empresa($payment_id)
    {
       $sql = "SELECT  tblinvoicepaymentrecords.id as pay_id, tblinvoicepaymentrecords.invoiceid,  tblconta_financeira.id as conta_id, tblconta_financeira.cnpj_empresa, tblconta_financeira.token_homologacao, 
           tblconta_financeira.token_producao,
           tblconta_financeira.token_senha, tblconta_financeira.emissao_producao,  tblconta_financeira.cnpj_empresa, amount as valor, tblinvoicepaymentrecords.paymentmode, tblinvoicepaymentrecords.date,
           tblinvoicepaymentrecords.nome_nota_fiscal, tblinvoicepaymentrecords.cpf_nota_fiscal,  tblconta_financeira.iss, tblconta_financeira.pis, tblconta_financeira.confins, i.*
            FROM tblinvoicepaymentrecords
            inner join tblpayment_modes on tblpayment_modes.id = tblinvoicepaymentrecords.paymentmode
            inner join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
            inner join tblinvoices i on i.id = tblinvoicepaymentrecords.invoiceid
            where tblinvoicepaymentrecords.id in ($payment_id) and tblconta_financeira.nota_fiscal_propria = 0";
       //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
      
        return $result;
    }
    
    
    // emitido qdo selecionado o pagamento para emitir nota
    // PAGAMENTO COM CNPJ DO MÉDICO
     public function get_contas_payments_list_id_selecionado_cnpj_medico($payment_id)
    {
       $sql = "SELECT  tblinvoicepaymentrecords.id as pagamento_id, 
                       tblinvoicepaymentrecords.invoiceid, 
                       tblconta_financeira.inscricao_municipal,
                       tblconta_financeira.tipo_nota,
                       tblconta_financeira.codigo_municipio,
                       tblconta_financeira.id as conta_id,
                       tblconta_financeira.cnpj_empresa, 
                       tblconta_financeira.token_homologacao, 
                       tblconta_financeira.token_producao, 
                       tblconta_financeira.token_senha, 
                       tblconta_financeira.emissao_producao, 
                       tblconta_financeira.cnpj_empresa, sum(amount) as valor, 
                       tblinvoicepaymentrecords.paymentmode, 
                       tblinvoicepaymentrecords.date, 
                       tblinvoicepaymentrecords.nome_nota_fiscal, 
                       tblinvoicepaymentrecords.cpf_nota_fiscal,
                       tblconta_financeira.iss,
                       tblconta_financeira.pis, 
                       tblconta_financeira.confins, i.*
            FROM tblinvoicepaymentrecords
            inner join tblpayment_modes on tblpayment_modes.id = tblinvoicepaymentrecords.paymentmode
            inner join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
            inner join tblinvoices i on i.id = tblinvoicepaymentrecords.invoiceid
            where tblinvoicepaymentrecords.id in ($payment_id) and tblconta_financeira.nota_fiscal_propria = 1";
     //  echo $sql; exit;
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
     public function get_contas_payments_list_id_selecionado_cnpj_medico_lista_detalhes_medicos($payment_id, $medico_id)
    {
       $sql = "SELECT  tblinvoicepaymentrecords.id as pagamento_id, tblinvoicepaymentrecords.invoiceid, 
           tblconta_financeira.id as conta_id, 
           tblconta_financeira.cnpj_empresa,
           tblconta_financeira.inscricao_municipal,
           tblconta_financeira.tipo_nota,
           tblconta_financeira.token_homologacao, 
           tblconta_financeira.token_producao, 
           tblconta_financeira.token_senha, 
           tblconta_financeira.emissao_producao,  
           tblconta_financeira.cnpj_empresa, amount as valor, 
           tblinvoicepaymentrecords.paymentmode, tblinvoicepaymentrecords.date, 
           tblinvoicepaymentrecords.nome_nota_fiscal, 
           tblinvoicepaymentrecords.cpf_nota_fiscal, tblconta_financeira.iss, tblconta_financeira.pis, tblconta_financeira.confins, i.*
            FROM tblinvoicepaymentrecords
            inner join tblpayment_modes on tblpayment_modes.id = tblinvoicepaymentrecords.paymentmode
            inner join tblconta_financeira on tblconta_financeira.id = tblinvoicepaymentrecords.conta_id
            inner join tblinvoices i on i.id = tblinvoicepaymentrecords.invoiceid
            where tblinvoicepaymentrecords.id in ($payment_id) and tblconta_financeira.nota_fiscal_propria = 1 and tblinvoicepaymentrecords.conta_id = $medico_id";
       //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    // retorna os items da fatura para a nota fiscal pelo ID da fatura, trazendo todos os pagamentos
    public function get_items_fatura($invoiceid)
    {
       $sql = "select r.invoiceid, tblconta_financeira.id as conta_id, sum(r.amount) as valor_servicos,  pmt.codigo_nf as codigo_nf, pm.bandeiraid as bandeiraid,
            (select texto_nota_fiscal from tblitemable t
            inner join tblitems i on i.id = t.item_id
            inner join tblitems_groups g on g.id = i.group_id
            where rel_id = $invoiceid and t.rel_type = 'invoice'
            group by texto_nota_fiscal) as discriminacao
            from tblinvoicepaymentrecords r
            inner join tblpayment_modes pm on pm.id = r.paymentmode
            inner join tblpayment_modes_tipo pmt on pmt.id = pm.tipoid
            inner join tblconta_financeira on tblconta_financeira.id = r.conta_id
            where r.invoiceid = $invoiceid and tblconta_financeira.nota_fiscal_propria = 0";
      //echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
   
    // CNPJ DA EMPRESA E MÉDICOS QUE EMITEM PELO CNPJ DA EMPRESA
    // retorna os items da fatura para a nota fiscal pelo ID(s) do pagamento, trazendo os pagamentos informados. 
    public function get_items_fatura_pelo_pagamento($pagamentosid, $invoiceid)
    {
       $sql = "select r.invoiceid, tblconta_financeira.id as conta_id, sum(r.amount) as valor_servicos,  pmt.codigo_nf as codigo_nf, pm.bandeiraid as bandeiraid,
            (select max(texto_nota_fiscal) as texto_nota_fiscal from tblitemable t
            inner join tblitems i on i.id = t.item_id
            inner join tblitems_groups g on g.id = i.group_id
            where rel_id = $invoiceid and t.rel_type = 'invoice') as discriminacao
            from tblinvoicepaymentrecords r
            inner join tblpayment_modes pm on pm.id = r.paymentmode
            inner join tblpayment_modes_tipo pmt on pmt.id = pm.tipoid
            inner join tblconta_financeira on tblconta_financeira.id = r.conta_id
            where r.id in ($pagamentosid) and tblconta_financeira.nota_fiscal_propria = 0";
     
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
     // retorna os procedimentos da fatura para a nota fiscal pelo ID(s) do pagamento, dos médicos com cnpj próprio
    public function get_items_fatura_pelo_pagamento_medico_cnpj_proprio($pagamentosid, $invoiceid)
    {
       $sql = "select r.invoiceid, tblconta_financeira.id as conta_id, sum(r.amount) as valor_servicos,  pmt.codigo_nf as codigo_nf, pm.bandeiraid as bandeiraid,
            (select texto_nota_fiscal 
            from tblitemable t
            inner join tblitems i on i.id = t.item_id
            inner join tblitems_groups g on g.id = i.group_id
            where rel_id = $invoiceid and t.rel_type = 'invoice'
            group by texto_nota_fiscal limit 1) as discriminacao
            from tblinvoicepaymentrecords r
            inner join tblpayment_modes pm on pm.id = r.paymentmode
            inner join tblpayment_modes_tipo pmt on pmt.id = pm.tipoid
            inner join tblconta_financeira on tblconta_financeira.id = r.conta_id
            where r.id in ($pagamentosid) and tblconta_financeira.nota_fiscal_propria = 1";
      
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
     // verifica se tem uma tributação especial para o procedimento da nota
    public function get_tributacao_diferente_medico($invoiceid, $conta_id)
    {
       $sql = "SELECT nf.* FROM tblitemable t
            inner join tblitems on tblitems.id = t.item_id
            inner join tblitems_groups on tblitems_groups.id = tblitems.group_id
            inner join tblconta_financeira_nota_fiscal nf on nf.categoria_id = tblitems_groups.id
            where rel_id = $invoiceid and rel_type = 'invoice' and nf.conta_id = $conta_id";
      
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
    // retorna os procedimentos das faturas
    public function get_procedimentos_fatura($invoiceid)
    {
       $sql = "SELECT i.description as description, i.codigo_tuss
                FROM tblitemable t
                inner join tblitems i on i.id = t.item_id
                inner join tblitems_groups g on g.id = i.group_id
                where t.rel_id = $invoiceid and t.rel_type = 'invoice'";
    //  echo $sql; exit;
       $result = $this->db->query($sql)->result_array();
        return $result;
    }
    
    public function get_valor_pagamentos_fatura($paamentoid)
    {
       $sql = " select sum(amount) as valor_servicos, paymentmode from tblinvoicepaymentrecords where id in ($paamentoid) ";
      //echo $sql; exit;
       $result = $this->db->query($sql)->row();
        return $result;
    }
    
    function validaCPF($cpf = null) {
       
        // Verifica se um número foi informado
        if(empty($cpf)) {
                return false;
        }

        // Elimina possivel mascara
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
         
        // Verifica se o numero de digitos informados é igual a 11 
        if (strlen($cpf) != 11) {
                return 0;
        }
       
        // Verifica se nenhuma das sequências invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' || 
                $cpf == '11111111111' || 
                $cpf == '22222222222' || 
                $cpf == '33333333333' || 
                $cpf == '44444444444' || 
                $cpf == '55555555555' || 
                $cpf == '66666666666' || 
                $cpf == '77777777777' || 
                $cpf == '88888888888' || 
                $cpf == '99999999999') {
                return 0;
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
         } else {   

                for ($t = 9; $t < 11; $t++) {

                        for ($d = 0, $c = 0; $c < $t; $c++) {
                                $d += $cpf{$c} * (($t + 1) - $c);
                        }
                        $d = ((10 * $d) % 11) % 10;
                        if ($cpf{$c} != $d) {
                                return 0;
                        }
                }

                return true;
        }
    }
    
    /*
     * EMITE A NOTA QUE SERÃO EMITIDAS PELO CNPJ DA EMRPESA
     * PAGAMENTO NORMAL
     * FUNÇÃO USADA QUANDO É DADO BAIXA NO PAGAMENTO
     */
    public function gera_nfce_cnpj_empresa($pagamentos_id, $faturaid)
    {
        /*
         * 1 - RETORNA OS PAGAMENTOS DA FATURA - DOS MÉDICOS QUE NÃO TEM CNPJ PRÓPRIO
         */
        $pagamentos_medico_empresa = $this->payments_model->get_contas_invoice_payments_empresa($faturaid);
        
        $valor              = $pagamentos_medico_empresa->valor;
        $cnpj_empresa       = $pagamentos_medico_empresa->cnpj_empresa;
        $nome_nota_fiscal   = $pagamentos_medico_empresa->nome_nota_fiscal;
        $cpf_nota_fiscal    = $pagamentos_medico_empresa->cpf_nota_fiscal;
        $conta_id           = $pagamentos_medico_empresa->conta_id;
        $token_homologacao  = $pagamentos_medico_empresa->token_homologacao;
        $token_producao     = $pagamentos_medico_empresa->token_producao;
        $token_senha        = $pagamentos_medico_empresa->token_senha;
        $emissao_producao   = $pagamentos_medico_empresa->emissao_producao;
        $iss                = $pagamentos_medico_empresa->iss;
        $pis                = $pagamentos_medico_empresa->pis;
        $confins            = $pagamentos_medico_empresa->confins;
        $com_cpf_nota_fiscal = $pagamentos_medico_empresa->com_cpf_nota_fiscal;
        
       
       
       
        if($valor == 0){
            echo 'Confira o valor pago 1'; exit;
        }
        
        $tomador = array();
        $tomador_endereco = array();
        $servicos = array();
        
        // dados da venda
        $dados_invoice = $this->invoices_model->get($faturaid);     
        //print_r($dados_invoice); exit;
        $cliente_id = $dados_invoice->clientid;
        
        // dados do cliente  
        $model_info_cliets  = $this->clients_model->get($cliente_id);
        $cpf = $model_info_cliets->vat; //CPF
        $cpf = str_replace('.', '', $cpf);
        $cpf = str_replace(',', '', $cpf);
        $cpf = str_replace('-', '', $cpf);
        $cpf = str_replace('/', '', $cpf);
        
        if($cpf_nota_fiscal){
            $cpf_nota_fiscal = str_replace('.', '', $cpf_nota_fiscal);
            $cpf_nota_fiscal = str_replace(',', '', $cpf_nota_fiscal);
            $cpf_nota_fiscal = str_replace('-', '', $cpf_nota_fiscal);
            $cpf_nota_fiscal = str_replace('/', '', $cpf_nota_fiscal);
            
            $tomador['cpf'] = $cpf_nota_fiscal;  
            
        }else if($cpf){
            $tomador['cpf'] = $cpf;      
        }
        
        
        
        $nome = $model_info_cliets->company; //NOME
        if($nome_nota_fiscal){
             $tomador['razao_social'] = $nome_nota_fiscal;  
        }else  if(!$nome){
            echo 'sem nome'; exit;
        }else{
            $tomador['razao_social'] = $nome;  
        }
        
        
        
        $email = $model_info_cliets->email; //EMAIL
        if($email){
            $tomador['email'] = $email; 
        }
       
        if(!$com_cpf_nota_fiscal){
            $tomador['cpf'] = null;
            $tomador['razao_social'] = null;
            $tomador['email'] = null;
        }
        
        
        $cep = $model_info_cliets->zip;
        if($cep){
           // $tomador_endereco['cep'] = $cep;    
            
            if(!$bairro){
               // $tomador_endereco['bairro'] = "Não informado";  
            }
            
            if(!$numero){
               //$tomador_endereco['numero'] = "s/n"; 
            }
            
        }
        $endereco = $model_info_cliets->address;
        if($endereco){
            //$tomador_endereco['logradouro'] = $endereco;  
        }
        $bairro = $model_info_cliets->endereco_bairro;
        if($bairro){
            //$tomador_endereco['bairro'] = $bairro;  
        }
        $estado = $model_info_cliets->state;
        if($estado){
           // $tomador_endereco['uf'] = $estado;  
        }else{
            //$tomador_endereco['uf'] = 'AM';  
        }
        $cidade = $model_info_cliets->city;
            $tomador_endereco['codigo_municipio'] = '1302603';
        
        $numero = $model_info_cliets->endereco_numero;
        if($numero){
            //$tomador_endereco['numero'] = $numero;  
        }
        
        $tomador['endereco'] = $tomador_endereco;
       //  print_r($tomador); exit;;
       // print_r($tomador_endereco); 
         
       
        /*
         * IDS DOS PAGAMENTOS DA NOTA para a referencia
         */
        //$list_pagamentos = $this->payments_model->get_lista_contas_invoice_payments_empresa($faturaid);
        $total_pagamentos = count($pagamentos_id);
       
        if($total_pagamentos > 1){
            $cont_pgto = 1;
            $pagamentoid = "";
            foreach ($pagamentos_id as $pagamentos) {
                $id_pto = $pagamentos;   

                if($cont_pgto >= $total_pagamentos){
                $pagamentoid .= "'$id_pto'";    
                }else{
                $pagamentoid .= "'$id_pto',";    
                }
                $cont_pgto++;
            }
        }else if($total_pagamentos == 1){
            $pagamentoid = $pagamentos_id;
        }
        //$valor_pgtos = $this->payments_model->get_valor_pagamentos_fatura($pagamentoid);
        //$valor_servicos = $valor_pgtos->valor_servicos;
        
        $items_servico = $this->payments_model->get_items_fatura_pelo_pagamento($pagamentoid, $faturaid);
        $cont_items = 0;
        //$valor = 0; 
        $codigo_forma_pagamento = "";
        $bandeiraid = "";
        foreach ($items_servico as $items) {
            $valor_servicos = $items['valor_servicos'];
            $invoiceid = $items['invoiceid'];
            $conta_id = $items['conta_id'];
            
            
            $servicos['discriminacao'] = $items['discriminacao'];
            $servicos['valor_servicos'] = $valor_servicos;
            $servicos['iss_retido'] = 'false';
            $servicos['item_lista_servico'] = '403';
            $servicos['codigo_cnae'] = '8299799'; 
           
             // Verifica se tem uma tributação fora do padrão
            $tribustos_medico_categoria = $this->payments_model->get_tributacao_diferente_medico($invoiceid, $conta_id);
            $retorno_tributo = count($tribustos_medico_categoria);
            if($retorno_tributo){
               $tipo_imposto = $tribustos_medico_categoria->tipo_imposto;
               $valor_tributo = $tribustos_medico_categoria->valor;
               
               //ISS
               if($tipo_imposto == 1){
                   $iss = $valor_tributo;
               }
               
               // pis
               if($tipo_imposto == 2){
                   $pis = $valor_tributo;
               }
               
               // cofins
               if($tipo_imposto == 3){
                   $confins = $valor_tributo;
               }
            }
            
            //PIS
            /*
             * CST : 01
             */
            $servicos['pis_situacao_tributaria'] = '01'; 
            if($pis){
            
            $servicos['pis_base_calculo'] = $pis; 
            $valor_pis = ($valor_servicos * $pis)/ 100;
            $servicos['valor_pis'] = $valor_pis; 
            }else{
              $valor_pis = 0;
            }
            //COFINS
            $servicos['cofins_situacao_tributaria'] = '01';
            if($confins){
            
            $servicos['cofins_base_calculo'] = $confins; 
            $valor_cofins = ($valor_servicos * $confins)/ 100;
            $servicos['valor_cofins'] = $valor_cofins; 
            }else{
             $valor_cofins = 0;
            }
            //ISS
            $valo_tributo = ($valor_servicos * $iss)/ 100;
            $servicos['valor_iss'] = $valo_tributo; 
            $servicos['valor_total_tributos'] = ($valo_tributo + $valor_pis + $valor_cofins); 
           
            $codigo_forma_pagamento = $items['codigo_nf'];
            $bandeiraid = $items['bandeiraid'];
            
         } 
        /***************** FIM SERVIÇOS  *************************************/
         
        if(!$codigo_forma_pagamento){
            $codigo_forma_pagamento = 99;
        }
        
        if(!$bandeiraid){
            $bandeiraid = 99;
        }
        
        // lista os procedimentos d fatura   // informações adicionais
        $procedimentos_fatura = $this->payments_model->get_procedimentos_fatura($faturaid);
       // print_r($items_servico); exit;
        $informacoes = "Fatura : ".$faturaid.' <br>';
        foreach ($procedimentos_fatura as $items) {
           $informacoes .= $items['description'].' - '.$items['codigo_tuss'].'<br>';
        }    
        
         $data_hora_atual = date('Y-m-d H:i:s');
          
        $nfse = array (
            "data_emissao" => "$data_hora_atual",
            "incentivador_cultural" => "false",
            "natureza_operacao" => "1",
            "optante_simples_nacional" => "false",
            "tipo" => "nfce",
            "prestador" => array(
                "cnpj" => "$cnpj_empresa",
                "codigo_municipio" => "1302603"
                ),
            
            "servico" => $servicos, 
            
            "tomador" =>$tomador,
            
            "informacoes_adicionais_contribuinte" => "$informacoes",
            
            "forma_pagamento" => "$codigo_forma_pagamento",
            "bandeira_operadora_pagamento" => "$bandeiraid"
           
                  
          );
       // print_r($nfse); exit;
        
         /**********************************************************************
          ************** INTEGRA PARA GERAR NOTA ****************************
          ********************************************************************/
       
        $ref =  $pagamentoid; // referencia: ID do pagamento
        $ref = str_replace("'", '', $ref);
       
         // conecta ao servidor
        if($emissao_producao == 1){
            $server = "https://api.focusnfe.com.br";
             $login = $token_producao; // PRODUÇÃO
        }else{
            $server = "https://homologacao.focusnfe.com.br";
            $login = $token_homologacao; // HOMOLOGAÇÃO
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $server."/v2/nfse?ref=" . $ref);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfse));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$token_senha");
        $body = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         //as três linhas abaixo imprimem as informações retornadas pela API, aqui o seu sistema deverá
         //interpretar e lidar com o retorno
         //print($http_code."\n");
         //print($body."\n\n");
         //print("");
         //exit; 
        
        //print_r($nfse); exit;
        
        // RETORNO DA NOTA

        //$body = '{ "status": "autorizado", "ref": "nfse_2", "cnpj_prestador": "37607436000153", "data_emissao": "2020-10-17T19:35:40-03:00", "numero": "4", "serie": "1", "url": "http://api.focusnfe.com.br/notas_fiscais_consumidor/NFe13201037607436000153650010000000041869463733.html", "caminho_xml_nota_fiscal": "/arquivos/37607436000153/202010/XMLs/13201037607436000153650010000000041869463733-nfe.xml", "codigo_verificacao": "NFe13201037607436000153650010000000041869463733" }';
        
        $json2 = json_decode($body, true);
        $status = $json2['status'];
        $ref = $json2['ref'];
        $cnpj_prestador = $json2['cnpj_prestador'];
        $data_emissao = $json2['data_emissao'];
        $numero = $json2['numero'];
        $serie = $json2['serie'];
        $url = $json2['url'];
        $caminho_xml_nota_fiscal = $json2['caminho_xml_nota_fiscal'];
        $codigo_verificacao = $json2['codigo_verificacao'];
        
        //if($status == 'autorizado'){
         foreach ($pagamentos_id as $pagamentos) {
                $id_pto = $pagamentos;   
            $data_atualiza_pagamento['numero_nf'] = $numero;
            //$this->update_nota_fiscal_conta($data_atualiza_pagamento, $conta_id, $pagamentos_id);  //ATUALIZA O NÚMERO DA NOTA NA FATURA
            
            $this->db->where('id', $id_pto);
            $this->db->update(db_prefix() . 'invoicepaymentrecords', $data_atualiza_pagamento);
         }
        //}
        
        $nf_data = array(
            "invoice_id" => $faturaid,
            "status" => $status,
            "ref" => $ref,
            "cnpj_prestador" => $cnpj_prestador,
            "data_emissao" => $data_emissao,           
            "numero" => $numero,
            "serie" => $serie,
            "url" => $url,
            "caminho_xml_nota_fiscal" => $caminho_xml_nota_fiscal,
            "codigo_verificacao" => $codigo_verificacao,            
            "deleted" => 0,
            "valor" => $valor_servicos,
            "conta_id" => $conta_id
        );
       
      $insert_id = $this->nota_fiscal_model->add($nf_data);
      
      
        
      return $insert_id;
      //  echo json_encode(array("success" => true,  'id' => $fatura_id, 'message' => lang('record_saved')));
     //curl_close($ch);  
        
        
    }      
    
    
     /*
     * EMITE A(S) NOTA(S) QUE SERÃO EMITIDAS PELO CNPJ DO MÉDICO
     * PAGAMENTO NORMAL
      * FUNÇÃO USADA QUANDO É DADO BAIXA NO PAGAMENTO
     */
    public function gera_nfce_cnpj_medico($faturaid)
    {       
        /*
         * 1 - RETORNA OS PAGAMENTOS DA FATURA - DOS MÉDICOS QUE TEM CNPJ PRÓPRIO
         */
        $pagamentos_medico_empresa = $this->payments_model->get_contas_medicos_cnpj_proprio($faturaid);
        if(count($pagamentos_medico_empresa) > 0){
        foreach ($pagamentos_medico_empresa as $detalhes_pagamentos) {
            $cnpj_empresa = $detalhes_pagamentos['cnpj_empresa'];
            $conta_id = $detalhes_pagamentos['conta_id'];
            $pagamentoid = $detalhes_pagamentos['pagamento_id'];
            $valor = $detalhes_pagamentos['valor'];
            $nome_nota_fiscal = $detalhes_pagamentos['nome_nota_fiscal'];
            $cpf_nota_fiscal = $detalhes_pagamentos['cpf_nota_fiscal'];
            $token_homologacao = $detalhes_pagamentos['token_homologacao'];
            $token_producao = $detalhes_pagamentos['token_producao'];
            $token_senha = $detalhes_pagamentos['token_senha'];
            $emissao_producao = $detalhes_pagamentos['emissao_producao'];
            $com_cpf_nota_fiscal = $detalhes_pagamentos['com_cpf_nota_fiscal'];
             
            
            $iss = $detalhes_pagamentos['iss'];
            $pis = $detalhes_pagamentos['pis'];
            $confins = $detalhes_pagamentos['confins'];
         
        if($valor == 0){
            echo 'Confira o valor pago  2'; exit;
        }
        
        
        $tomador = array();
        $tomador_endereco = array();
        $servicos = array();
        $servicos_nfse = array();
        
        // dados da venda
        $dados_invoice = $this->invoices_model->get($faturaid);     
        //print_r($dados_invoice); exit;
        $cliente_id = $dados_invoice->clientid;
        
        // dados do cliente  
        $model_info_cliets  = $this->clients_model->get($cliente_id);
        $cpf = $model_info_cliets->vat; //CPF
          $cpf = str_replace('.', '', $cpf);
            $cpf = str_replace(',', '', $cpf);
            $cpf = str_replace('-', '', $cpf);
            $cpf = str_replace('/', '', $cpf);

            if($cpf_nota_fiscal){
                $cpf_nota_fiscal = str_replace('.', '', $cpf_nota_fiscal);
                $cpf_nota_fiscal = str_replace(',', '', $cpf_nota_fiscal);
                $cpf_nota_fiscal = str_replace('-', '', $cpf_nota_fiscal);
                $cpf_nota_fiscal = str_replace('/', '', $cpf_nota_fiscal);
                $tomador['cpf'] = $cpf_nota_fiscal;  
            }else if($cpf){
                $tomador['cpf'] = $cpf;      
            }
        
        $nome = $model_info_cliets->company; //NOME
        if($nome_nota_fiscal){
             $tomador['razao_social'] = $nome_nota_fiscal;  
        }else 
        if(!$nome){
            echo 'sem nome'; exit;
        }else{
            $tomador['razao_social'] = $nome;  
        }
        
        $email = $model_info_cliets->email; //EMAIL
        if($email){
            $tomador['email'] = $email; 
        }
       
        if(!$com_cpf_nota_fiscal){
            $tomador['cpf'] = null;
            $tomador['razao_social'] = null;
            $tomador['email'] = null;
        }
        
        $cep = $model_info_cliets->zip;
        if($cep){
           // $tomador_endereco['cep'] = $cep;    
            
            if(!$bairro){
               // $tomador_endereco['bairro'] = "Não informado";  
            }
            
            if(!$numero){
               //$tomador_endereco['numero'] = "s/n"; 
            }
            
        }
        $endereco = $model_info_cliets->address;
        if($endereco){
            //$tomador_endereco['logradouro'] = $endereco;  
        }
        $bairro = $model_info_cliets->endereco_bairro;
        if($bairro){
            //$tomador_endereco['bairro'] = $bairro;  
        }
        $estado = $model_info_cliets->state;
        if($estado){
           // $tomador_endereco['uf'] = $estado;  
        }else{
            //$tomador_endereco['uf'] = 'AM';  
        }
        $cidade = $model_info_cliets->city;
            $tomador_endereco['codigo_municipio'] = '1302603';
        
        $numero = $model_info_cliets->endereco_numero;
        if($numero){
            //$tomador_endereco['numero'] = $numero;  
        }
        
        $tomador['endereco'] = $tomador_endereco;
        // print_r($tomador); exit;;
       // print_r($tomador_endereco); 
         
        
       
        
        $items_servico = $this->payments_model->get_items_fatura_pelo_pagamento_medico_cnpj_proprio($pagamentoid, $faturaid);
        $cont_items = 0;
        //$valor = 0; 
        $codigo_forma_pagamento = "";
        $bandeiraid = "";
        foreach ($items_servico as $items) {
            $valor_servicos = $items['valor_servicos'];
            $invoiceid = $items['invoiceid'];
            $conta_id = $items['conta_id'];
            
            $servicos['discriminacao'] = $items['discriminacao'];
            $servicos['valor_servicos'] = $valor_servicos;
            $servicos['iss_retido'] = 'false';
            $servicos['item_lista_servico'] = '403'; // Hospitais, clínicas, laboratórios, sanatórios, manicômios, casas de saúde, prontos-socorros, ambulatórios e congêneres.
            $servicos['codigo_cnae'] = '8299799'; 
            
             // Verifica se tem uma tributação fora do padrão
            $tribustos_medico_categoria = $this->payments_model->get_tributacao_diferente_medico($invoiceid, $conta_id);
            $retorno_tributo = count($tribustos_medico_categoria);
            if($retorno_tributo){
               $tipo_imposto = $tribustos_medico_categoria->tipo_imposto;
               $valor_tributo = $tribustos_medico_categoria->valor;
               
               //ISS
               if($tipo_imposto == 1){
                   $iss = $valor_tributo;
               }
               // pis
               if($tipo_imposto == 2){
                   $pis = $valor_tributo;
               }
               // cofins
               if($tipo_imposto == 3){
                   $confins = $valor_tributo;
               }
            }
            
            //PIS
            $servicos['pis_situacao_tributaria'] = '01'; 
            if($pis){
            
            $servicos['pis_base_calculo'] = $pis; 
            $valor_pis = ($valor_servicos * $pis)/ 100;
            $servicos['valor_pis'] = $valor_pis; 
            }else{
              $valor_pis = 0;
            }
            
            //COFINS
            $servicos['cofins_situacao_tributaria'] = '01';
            if($confins){
            $servicos['cofins_base_calculo'] = $confins; 
            $valor_cofins = ($valor_servicos * $confins)/ 100;
            $servicos['valor_cofins'] = $valor_cofins; 
            }else{
             $valor_cofins = 0;
            }
            
            //ISS
            $valo_tributo = ($valor_servicos * $iss)/ 100;
            
            
            $servicos['valor_iss'] = $valo_tributo; 
            $servicos['valor_total_tributos'] = ($valo_tributo + $valor_pis + $valor_cofins); 
           
            $codigo_forma_pagamento = $items['codigo_nf'];
            $bandeiraid = $items['bandeiraid'];
            
            
            /*
             * NFSE
             */
           // $servicos_nfse['aliquota'] = $iss;
           // $servicos_nfse['discriminacao'] = $items['discriminacao'];
            
            
         } 
        
        if(!$codigo_forma_pagamento){
            $codigo_forma_pagamento = 99;
        }
        
        if(!$bandeiraid){
            $bandeiraid = 99;
        }
        
        // lista os procedimentos d fatura   // informações adicionais
        $procedimentos_fatura = $this->payments_model->get_procedimentos_fatura($faturaid);
       // print_r($items_servico); exit;
        $informacoes = "Fatura : ".$faturaid.' <br>';
        foreach ($procedimentos_fatura as $items) {
           $informacoes .= $items['description'].' - '.$items['codigo_tuss'].'<br>';
        }    
         
       
       
        $data_hora_atual = date('Y-m-d H:i:s');
          
        
       
        
        /*
        * NFCE/FSE
        */ 
        $nfce = array (
            "data_emissao" => "$data_hora_atual",
            "incentivador_cultural" => "false",
            "natureza_operacao" => "1",
            "optante_simples_nacional" => "false",
            "tipo" => "nfce",
            "prestador" => array(
                "cnpj" => "$cnpj_empresa",
                "codigo_municipio" => "1302603"
                ),
            "servico" => $servicos, 
            "tomador" =>$tomador,
            "informacoes_adicionais_contribuinte" => "$informacoes",
            "forma_pagamento" => "$codigo_forma_pagamento",
            "bandeira_operadora_pagamento" => "$bandeiraid"
        );
      
        //print_r($nfse); exit;
        
        
         /**********************************************************************
          ************** INTEGRA PARA GERAR NOTA ****************************
          ********************************************************************/
       
        $ref =  $pagamentoid; // referencia: ID do pagamento
       
        // conecta ao servidor
        if($emissao_producao == 1){
            $server = "https://api.focusnfe.com.br";
             $login = $token_producao; // PRODUÇÃO
        }else{
            $server = "https://homologacao.focusnfe.com.br";
            $login = $token_homologacao; // HOMOLOGAÇÃO
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $server."/v2/nfse?ref=" . $ref);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfce));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$token_senha");
        $body = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         //as três linhas abaixo imprimem as informações retornadas pela API, aqui o seu sistema deverá
         //interpretar e lidar com o retorno
         //print($http_code."\n");
         //print($body."\n\n");
         //print("");
         //exit; 
        
        //print_r($nfse); exit;
        
        // RETORNO DA NOTA

        //$body = '{ "status": "autorizado", "ref": "nfse_2", "cnpj_prestador": "37607436000153", "data_emissao": "2020-10-17T19:35:40-03:00", "numero": "4", "serie": "1", "url": "http://api.focusnfe.com.br/notas_fiscais_consumidor/NFe13201037607436000153650010000000041869463733.html", "caminho_xml_nota_fiscal": "/arquivos/37607436000153/202010/XMLs/13201037607436000153650010000000041869463733-nfe.xml", "codigo_verificacao": "NFe13201037607436000153650010000000041869463733" }';
        
        $json2 = json_decode($body, true);
        $status = $json2['status'];
        $ref = $json2['ref'];
        $cnpj_prestador = $json2['cnpj_prestador'];
        $data_emissao = $json2['data_emissao'];
        $numero = $json2['numero'];
        $serie = $json2['serie'];
        $url = $json2['url'];
        $caminho_xml_nota_fiscal = $json2['caminho_xml_nota_fiscal'];
        $codigo_verificacao = $json2['codigo_verificacao'];
        
        if($status == 'autorizado'){
            $data_atualiza_pagamento['numero_nf'] = $numero;
            //$this->update_nota_fiscal($data_atualiza_pagamento, $pagamentoid);  //ATUALIZA O NÚMERO DA NOTA NA FATURA
           $this->update_nota_fiscal_conta($data_atualiza_pagamento, $conta_id, $pagamentoid); 
        }
        
        $nf_data = array(
            "invoice_id" => $faturaid,
            "status" => $status,
            "ref" => $ref,
            "cnpj_prestador" => $cnpj_prestador,
            "data_emissao" => $data_emissao,           
            "numero" => $numero,
            "serie" => $serie,
            "url" => $url,
            "caminho_xml_nota_fiscal" => $caminho_xml_nota_fiscal,
            "codigo_verificacao" => $codigo_verificacao,            
            "deleted" => 0,
            "valor" => $valor_servicos,
            "conta_id" => $conta_id
        );
       
        $this->nota_fiscal_model->add($nf_data);
        
      }
      
        return true;
        }
    }   
    
    
    /*
     * GERA NOTA FISCAL A PARTIR DE PAGAMENTOS SELECIONADOS E JÁ REALIZADOS
     * usado para gerar nota fiscal a partir de pagamentos selecionados
     */
    public function gera_nfce_pagamentos_informados($data_post)
    {
        
       // print_r($data_post); exit;
        $faturaid = $data_post['invoiceid'];
        $pagamentos = $data_post['pagamento_nf'];
       
        //print_r($pagamentos) ; exit;
        
        $count_id_pagamento = count($pagamentos);
        $cont = 1;
        $paymentid = "";
        foreach ($pagamentos as $pagamento_id) {
            if($cont == $count_id_pagamento){
            $paymentid .= "'".$pagamento_id."'";
            }else{
            $paymentid .= "'".$pagamento_id."',";    
            }
            $cont++;
        }
        
       
      
        /*
         * 1 - RETORNA OS PAGAMENTOS SELECIONADOS DA FATURA 
         */
        $pagamentos_medico_empresa = $this->payments_model->get_contas_payments_list_id_selecionado($paymentid);
        $valor_pagamentos_selecionados = $pagamentos_medico_empresa->valor;
        if($valor_pagamentos_selecionados == 0){
            echo 'Valor não informado ou Médico não está habilitado para emitir nota fiscal.'; die();
        }
        //$valor              = $pagamentos_medico_empresa->valor;
        $cnpj_empresa       = $pagamentos_medico_empresa->cnpj_empresa;
        $nome_nota_fiscal   = $pagamentos_medico_empresa->nome_nota_fiscal;
        $cpf_nota_fiscal    = $pagamentos_medico_empresa->cpf_nota_fiscal;
        $conta_id           = 1; //$pagamentos_medico_empresa->conta_id;
        $token_homologacao  = $pagamentos_medico_empresa->token_homologacao;
        $token_producao     = $pagamentos_medico_empresa->token_producao;
        $token_senha        = $pagamentos_medico_empresa->token_senha;
        $emissao_producao   = $pagamentos_medico_empresa->emissao_producao;
        $iss                = $pagamentos_medico_empresa->iss;
        $pis                = $pagamentos_medico_empresa->pis;
        $confins            = $pagamentos_medico_empresa->confins;
        $com_cpf_nota_fiscal = $pagamentos_medico_empresa->com_cpf_nota_fiscal;
        
        
        $tomador = array();
        $tomador_endereco = array();
        $servicos = array();
        
        // dados da venda
        $dados_invoice = $this->invoices_model->get($faturaid);     
        //print_r($dados_invoice); exit;
        $cliente_id = $dados_invoice->clientid;
       // $fatura_id = $pagamentoid; // "29"; // $dados_invoice->id;  <----
        //echo $cliente_id; exit;
        // dados do cliente  
        $model_info_cliets  = $this->clients_model->get($cliente_id);
       // print_r($model_info_cliets); exit;
        $cpf = $model_info_cliets->vat; //CPF
        $cpf = str_replace('.', '', $cpf);
        $cpf = str_replace(',', '', $cpf);
        $cpf = str_replace('-', '', $cpf);
        $cpf = str_replace('/', '', $cpf);
       
        if($cpf_nota_fiscal){
            $cpf_nota_fiscal = str_replace('.', '', $cpf_nota_fiscal);
            $cpf_nota_fiscal = str_replace(',', '', $cpf_nota_fiscal);
            $cpf_nota_fiscal = str_replace('-', '', $cpf_nota_fiscal);
            $cpf_nota_fiscal = str_replace('/', '', $cpf_nota_fiscal);
            $tomador['cpf'] = $cpf_nota_fiscal;  
        }else if($cpf){
            $tomador['cpf'] = $cpf;      
        }
     //   echo $tomador['cpf']; exit;
        
        
        $nome = $model_info_cliets->company; //NOME
        if($nome_nota_fiscal){
             $tomador['razao_social'] = $nome_nota_fiscal;  
        }else{
            $tomador['razao_social'] = $nome;  
        }
        
        $email = $model_info_cliets->email; //EMAIL
        if($email){
            $tomador['email'] = $email; 
        }
        
        
       /*
        if(!$com_cpf_nota_fiscal){
            $tomador['cpf'] = null;
            $tomador['razao_social'] = null;
            $tomador['email'] = null;
        }*/
       
        $cep = $model_info_cliets->zip;
        if($cep){
            $tomador_endereco['cep'] = $cep;    
            
            if(!$bairro){
                $tomador_endereco['bairro'] = "Não informado";  
            }
            
            if(!$numero){
                $tomador_endereco['numero'] = "s/n"; 
            }
            
        }
        $endereco = $model_info_cliets->address;
        if($endereco){
          //  $tomador_endereco['logradouro'] = $endereco;  
        }
        $bairro = $model_info_cliets->endereco_bairro;
        if($bairro){
           // $tomador_endereco['bairro'] = $bairro;  
        }
        $estado = $model_info_cliets->state;
        if($estado){
           // $tomador_endereco['uf'] = $estado;  
        }else{
          //  $tomador_endereco['uf'] = 'AM';  
        }
        $cidade = $model_info_cliets->city;
            $tomador_endereco['codigo_municipio'] = '1302603';
        
        $numero = $model_info_cliets->endereco_numero;
        if($numero){
          //  $tomador_endereco['numero'] = $numero;  
        }
        
        $tomador['endereco'] = $tomador_endereco;
       
        /*
         * ITENS DA NOTA
         */
        
        $items_servico = $this->payments_model->get_items_fatura_pelo_pagamento($paymentid, $faturaid);
       // print_r($items_servico); exit;
        $cont_items = 0;
        $valor = 0; 
        $codigo_forma_pagamento = "";
        $bandeiraid = "";
        //$informacoes = "Fatura : ".$faturaid.' <br>';
        foreach ($items_servico as $items) {
           
            $valor_pagamentos_selecionados = $items['valor_servicos'];
            $valor_servicos = $items['valor_servicos'];
            
            $servicos['discriminacao'] = $items['discriminacao'];
            $servicos['valor_servicos'] = $valor_pagamentos_selecionados;
            $servicos['iss_retido'] = 'false';
            $servicos['item_lista_servico'] = '403'; // Hospitais, clínicas, laboratórios, sanatórios, manicômios, casas de saúde, prontos-socorros, ambulatórios e congêneres.
            $servicos['codigo_cnae'] = '8299799'; 
            
            
            //PIS
            $servicos['pis_situacao_tributaria'] = '01';
            if($pis){
            $servicos['pis_base_calculo'] = $pis; 
            $valor_pis = ($valor_servicos * $pis)/ 100;
            $servicos['valor_pis'] = $valor_pis; 
            }else{
              $valor_pis = 0;
            }
            //COFINS
            $servicos['cofins_situacao_tributaria'] = '01';
            if($confins){
            $servicos['cofins_base_calculo'] = $confins; 
            $valor_cofins = ($valor_servicos * $confins)/ 100;
            $servicos['valor_cofins'] = $valor_cofins; 
            }else{
             $valor_cofins = 0;
            }
            //ISS
            $valo_tributo = ($valor_servicos * $iss)/ 100;
            $servicos['valor_iss'] = $valo_tributo; 
            $servicos['valor_total_tributos'] = ($valo_tributo + $valor_pis + $valor_cofins); 
            
            $codigo_forma_pagamento = $items['codigo_nf'];
            $bandeiraid = $items['bandeiraid'];
            $valor = $valor_pagamentos_selecionados; // $items['valor_servicos'];
         } 
        
        if(!$codigo_forma_pagamento){
            $codigo_forma_pagamento = 99;
        }
        
        if(!$bandeiraid){
            $bandeiraid = 99;
        }
        
        // lista os procedimentos d fatura   // informações adicionais
        $procedimentos_fatura = $this->payments_model->get_procedimentos_fatura($faturaid);
       // print_r($items_servico); exit;
        $informacoes = "Fatura : ".$faturaid.' <br>';
        foreach ($procedimentos_fatura as $items) {
           $informacoes .= $items['description'].' - '.$items['codigo_tuss'].'<br>';
        }    
        
        $data_hora_atual = date('Y-m-d H:i:s');
        $nfse = array (
            "data_emissao" => "$data_hora_atual",
            "incentivador_cultural" => "false",
            "natureza_operacao" => "1",
            "optante_simples_nacional" => "false",
            "tipo" => "nfce",
            "prestador" => array(
                "cnpj" => "$cnpj_empresa",
                "codigo_municipio" => "1302603"
                ),
            "servico" => $servicos, 
            "tomador" =>$tomador,
            "informacoes_adicionais_contribuinte" => "$informacoes",
            "forma_pagamento" => "$codigo_forma_pagamento",
            "bandeira_operadora_pagamento" => "$bandeiraid"
          );
         
        //print_r($nfse); exit;
        
         /**********************************************************************
          ************** INTEGRA PARA GERAR NOTA ****************************
          ********************************************************************/
        $HORA = date('h:m:s');
        $ref =  $paymentid; // referencia: ID do pagamento
        $ref = str_replace("'", '', $ref);
       
         // conecta ao servidor
        if($emissao_producao == 1){
            $server = "https://api.focusnfe.com.br";
             $login = $token_producao; // PRODUÇÃO
        }else{
            $server = "https://homologacao.focusnfe.com.br";
            $login = $token_homologacao; // HOMOLOGAÇÃO
        }
        
        
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $server."/v2/nfse?ref=" . $ref.'_'.$HORA);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfse));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$token_senha");
        $body = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         //as três linhas abaixo imprimem as informações retornadas pela API, aqui o seu sistema deverá
         //interpretar e lidar com o retorno
       //  print($http_code."\n");
    //     print($body."\n\n");
         //print("");
    //     exit; 
        
        //print_r($nfse); exit;
        
        // RETORNO DA NOTA

        //$body = '{ "status": "autorizado", "ref": "nfse_2", "cnpj_prestador": "37607436000153", "data_emissao": "2020-10-17T19:35:40-03:00", "numero": "4", "serie": "1", "url": "http://api.focusnfe.com.br/notas_fiscais_consumidor/NFe13201037607436000153650010000000041869463733.html", "caminho_xml_nota_fiscal": "/arquivos/37607436000153/202010/XMLs/13201037607436000153650010000000041869463733-nfe.xml", "codigo_verificacao": "NFe13201037607436000153650010000000041869463733" }';
        
        $json2 = json_decode($body, true);
        $status = $json2['status'];
        $ref = $json2['ref'];
        $cnpj_prestador = $json2['cnpj_prestador'];
        $data_emissao = $json2['data_emissao'];
        $numero = $json2['numero'];
        $serie = $json2['serie'];
        $url = $json2['url'];
        $caminho_xml_nota_fiscal = $json2['caminho_xml_nota_fiscal'];
        $codigo_verificacao = $json2['codigo_verificacao'];
        
        
       $data_atualiza_pagamento['numero_nf'] = $numero;
        
        if($status == 'autorizado'){
            
            $pagamentos_lista_empresa = $this->payments_model->get_contas_payments_list_id_selecionado_lista_detalhes_empresa($paymentid);
            foreach ($pagamentos_lista_empresa as $pagamento_id) {
                $id_pgto = $pagamento_id['pay_id'];
               //$this->update_nota_fiscal($data_atualiza_pagamento, $pagamento_id);
               // $this->update_nota_fiscal_conta($data_atualiza_pagamento, $conta_id, $id_pgto); 
                 $this->db->where('id', $id_pgto);
                $this->db->update(db_prefix() . 'invoicepaymentrecords', $data_atualiza_pagamento);
                
            }
            
        }else {
              
            $pagamentos_lista_empresa = $this->payments_model->get_contas_payments_list_id_selecionado_lista_detalhes_empresa($paymentid);
            foreach ($pagamentos_lista_empresa as $pagamento_id) {
                $id_pgto = $pagamento_id['pay_id'];
               //$this->update_nota_fiscal($data_atualiza_pagamento, $pagamento_id);
               // $this->update_nota_fiscal_conta($data_atualiza_pagamento, $conta_id, $id_pgto); 
                 $this->db->where('id', $id_pgto);
                $this->db->update(db_prefix() . 'invoicepaymentrecords', $data_atualiza_pagamento);
                
            }
            
            
            
          $json2 = json_decode($http_code, true);
          IF ($json2 == 422){
              ECHO '<h3>NOTA JÁ AUTORIZADA</h3>'; 
          }else IF ($json2 == 400){
              ECHO '<h3>O certificado do emitente está vencido</h3>';
          }
          else{
              print($body."\n\n");
          print("");
         // exit; 
          }
          
        }
        
        
        $nf_data = array(
            "invoice_id" => $faturaid,
            "status" => $status,
            "ref" => $ref,
            "cnpj_prestador" => $cnpj_prestador,
            "data_emissao" => $data_emissao,           
            "numero" => $numero,
            "serie" => $serie,
            "url" => $url,
            "caminho_xml_nota_fiscal" => $caminho_xml_nota_fiscal,
            "codigo_verificacao" => $codigo_verificacao,            
            "deleted" => 0,
            "valor" => $valor_pagamentos_selecionados,
            "conta_id" => $conta_id
        );
       
      $insert_id = $this->nota_fiscal_model->add($nf_data);
        
      return $insert_id;
      //  echo json_encode(array("success" => true,  'id' => $fatura_id, 'message' => lang('record_saved')));
    // curl_close($ch);  
        
        
    } 
    
    
    /*
     * GERA NOTA FISCAL A PARTIR DE PAGAMENTOS SELECIONADOS E JÁ REALIZADOS - PARA MÉDICOS COM CNPJ
     * usado para gerar nota fiscal a partir de pagamentos selecionados
     */
    public function gera_nfce_pagamentos_informados_cnpj_medico($faturaid, $paymentid, $conta_medico_id)
    {
       
    
        /*
         * 1 - RETORNA OS PAGAMENTOS SELECIONADOS DA FATURA 
         */
        $pagamentos_medico_empresa = $this->payments_model->get_contas_payments_list_id_selecionado_cnpj_medico($paymentid);
        $valor_pagamentos_selecionados = $pagamentos_medico_empresa->valor;
        if($valor_pagamentos_selecionados == 0){
            echo 'Valor não informado ou Médico não está habilitado para emitir nota fiscal.'; exit;
        }
        $pagamento_ref      = $pagamentos_medico_empresa->pagamento_id;
        $cnpj_empresa       = $pagamentos_medico_empresa->cnpj_empresa;
        $inscricao_municipal= $pagamentos_medico_empresa->inscricao_municipal;
        $codigo_municipio   = $pagamentos_medico_empresa->codigo_municipio;
        $tipo_nota          = $pagamentos_medico_empresa->tipo_nota;
        $nome_nota_fiscal   = $pagamentos_medico_empresa->nome_nota_fiscal;
        $cpf_nota_fiscal    = $pagamentos_medico_empresa->cpf_nota_fiscal;
        $conta_id           = $pagamentos_medico_empresa->conta_id;
        $token_homologacao  = $pagamentos_medico_empresa->token_homologacao;
        $token_producao     = $pagamentos_medico_empresa->token_producao;
        $token_senha        = $pagamentos_medico_empresa->token_senha;
        $emissao_producao   = $pagamentos_medico_empresa->emissao_producao;
        $iss                = $pagamentos_medico_empresa->iss;
        $pis                = $pagamentos_medico_empresa->pis;
        $confins            = $pagamentos_medico_empresa->confins;
        $com_cpf_nota_fiscal = $pagamentos_medico_empresa->com_cpf_nota_fiscal;
        
        $tomador = array();
        $tomador_endereco = array();
        $servicos = array();
        $prestador = array();
        
        // dados da venda
        $dados_invoice = $this->invoices_model->get($faturaid);     
        //print_r($dados_invoice); exit;
        $cliente_id = $dados_invoice->clientid;
       // $fatura_id = $pagamentoid; // "29"; // $dados_invoice->id;  <----
        
        // dados do cliente  
        $model_info_cliets  = $this->clients_model->get($cliente_id);
      //  print_r($model_info_cliets); exit;
        $cpf = $model_info_cliets->vat; //CPF
        $cpf = str_replace('.', '', $cpf);
        $cpf = str_replace(',', '', $cpf);
        $cpf = str_replace('-', '', $cpf);
        $cpf = str_replace('/', '', $cpf);
        
        if($cpf_nota_fiscal){
            $cpf_nota_fiscal = str_replace('.', '', $cpf_nota_fiscal);
            $cpf_nota_fiscal = str_replace(',', '', $cpf_nota_fiscal);
            $cpf_nota_fiscal = str_replace('-', '', $cpf_nota_fiscal);
            $cpf_nota_fiscal = str_replace('/', '', $cpf_nota_fiscal);
            $tomador['cpf'] = $cpf_nota_fiscal;  
        }else if($cpf){
            $tomador['cpf'] = $cpf;      
        }
        
        $nome = $model_info_cliets->company; //NOME
        if($nome_nota_fiscal){
             $tomador['razao_social'] = $nome_nota_fiscal;  
        }else{
            $tomador['razao_social'] = $nome;  
        }
        
        $email = $model_info_cliets->email; //EMAIL
        if($email){
            $tomador['email'] = $email; 
        }
        /*
        if(!$com_cpf_nota_fiscal){
            $tomador['cpf'] = null;
            $tomador['razao_social'] = null;
            $tomador['email'] = null;
        }*/
       
        $cep = $model_info_cliets->zip;
        if($cep){
            $tomador_endereco['cep'] = $cep;    
            
            if(!$bairro){
                $tomador_endereco['bairro'] = "Não informado";  
            }
            
            if(!$numero){
                $tomador_endereco['numero'] = "s/n"; 
            }
            
        }
        $endereco = $model_info_cliets->address;
        if($endereco){
          //  $tomador_endereco['logradouro'] = $endereco;  
        }
        $bairro = $model_info_cliets->endereco_bairro;
        if($bairro){
           // $tomador_endereco['bairro'] = $bairro;  
        }
        $estado = $model_info_cliets->state;
        if($estado){
           // $tomador_endereco['uf'] = $estado;  
        }else{
          //  $tomador_endereco['uf'] = 'AM';  
        }
        $cidade = $model_info_cliets->city;
            $tomador_endereco['codigo_municipio'] = '1302603';
        
        $numero = $model_info_cliets->endereco_numero;
        if($numero){
          //  $tomador_endereco['numero'] = $numero;  
        }
        
        $tomador['endereco'] = $tomador_endereco;
       
        /*
         * ITENS DA NOTA
         */
         
        $items_servico = $this->get_items_fatura_pelo_pagamento_medico_cnpj_proprio($pagamento_ref, $faturaid);
       // print_r($items_servico); exit;
        $cont_items = 0; 
       // $valor = 0; 
        $codigo_forma_pagamento = "";
        $bandeiraid = "";
        //$informacoes = "Fatura : ".$faturaid.' <br>';
         foreach ($items_servico as $items) {
           
            $valor_pagamentos_selecionados = $items['valor_servicos'];
            $invoiceid = $items['invoiceid'];
            $conta_id = $items['conta_id'];
              
            $servicos['discriminacao'] = $items['discriminacao'];
            $servicos['valor_servicos'] = $valor_pagamentos_selecionados;
            $servicos['iss_retido'] = 'false';
            $servicos['item_lista_servico'] = '403'; // Hospitais, clínicas, laboratórios, sanatórios, manicômios, casas de saúde, prontos-socorros, ambulatórios e congêneres.
            $servicos['codigo_cnae'] = '8299799'; 
            
            
            // Verifica se tem uma tributação fora do padrão
            $tribustos_medico_categoria = $this->payments_model->get_tributacao_diferente_medico($invoiceid, $conta_id);
            $retorno_tributo = count($tribustos_medico_categoria);
            if($retorno_tributo){
               $tipo_imposto = $tribustos_medico_categoria->tipo_imposto;
               $valor_tributo = $tribustos_medico_categoria->valor;
               
               //ISS
               if($tipo_imposto == 1){
                   $iss = $valor_tributo;
               }
               
               // pis
               if($tipo_imposto == 2){
                   $pis = $valor_tributo;
               }
               
               // cofins
               if($tipo_imposto == 3){
                   $confins = $valor_tributo;
               }
            }
            
            
            
            //PIS
            if($pis){
            $servicos['pis_situacao_tributaria'] = '01'; 
            $servicos['pis_base_calculo'] = $pis; 
            $valor_pis = ($valor_pagamentos_selecionados * $pis)/ 100;
            $servicos['valor_pis'] = $valor_pis; 
            }else{
              $valor_pis = 0;
            }
            //COFINS
            if($confins){
            $servicos['cofins_situacao_tributaria'] = '01';
            $servicos['cofins_base_calculo'] = $confins; 
            $valor_cofins = ($valor_pagamentos_selecionados * $confins)/ 100;
            $servicos['valor_cofins'] = $valor_cofins; 
            }else{
             $valor_cofins = 0;
            }
            //ISS
            
            $valo_tributo_iss = ($valor_pagamentos_selecionados * $iss)/ 100;
            $servicos['valor_iss'] = $valo_tributo_iss; 
            
            $servicos['valor_total_tributos'] = ($valo_tributo_iss + $valor_pis + $valor_cofins); 
            
            $codigo_forma_pagamento = $items['codigo_nf'];
            $bandeiraid = $items['bandeiraid'];
         }
         
        
        if(!$codigo_forma_pagamento){
            $codigo_forma_pagamento = 99;
        }
        
        if(!$bandeiraid){
            $bandeiraid = 99;
        }
        
        // lista os procedimentos d fatura   // informações adicionais
        $procedimentos_fatura = $this->payments_model->get_procedimentos_fatura($faturaid);
       
        $informacoes = "Fatura : ".$faturaid.' <br>';
        foreach ($procedimentos_fatura as $items) {
           $informacoes .= $items['description'].' - '.$items['codigo_tuss'].'<br>';
           
        }    
        
        
        /*
        * NFSE
        */ 
        $data_hora_atual = date('Y-m-d H:i:s');
        $nfse_teste = array (
            "data_emissao" => "$data_hora_atual",
            "prestador" => array(
                "cnpj" => "$cnpj_empresa",
                "inscricao_municipal" => "1302603",
                "codigo_municipio" => "1302603"
                ),
            $tomador,
            $servicos,
            /*    
            "servico" => array(
                "aliquota" => "$cnpj_empresa",
                "discriminacao" => "1302603",
                "iss_retido" => "1302603",
                "item_lista_servico" => "1302603",
                "codigo_tributario_municipio" => "1302603",
                "valor_servicos" => "1302603"
                ), */
          );
        
      
        if(!$codigo_municipio){
            $codigo_municipio = "1302603";
        }
        
        $prestador['cnpj'] = $cnpj_empresa;
        
        $prestador['codigo_municipio'] = '1302603';
        if($inscricao_municipal){
            $prestador['inscricao_municipal'] = $inscricao_municipal; 
        }
        
            
        
        $nfse = array (
            "data_emissao" => "$data_hora_atual",
            "incentivador_cultural" => "false",
            "natureza_operacao" => "1",
            "optante_simples_nacional" => "false",
            "tipo" => "$tipo_nota",
            "prestador" => array(
                "cnpj" => "$cnpj_empresa",
                "codigo_municipio" => "$codigo_municipio"
                ),
            "servico" => $servicos, 
            "tomador" =>$tomador,
            "informacoes_adicionais_contribuinte" => "$informacoes",
            "forma_pagamento" => "$codigo_forma_pagamento",
            "bandeira_operadora_pagamento" => "$bandeiraid"
          );
       // $bandeiraid = $items['bandeiraid'];
         
       // print_r($nfse); exit;
        
         /**********************************************************************
          ************** INTEGRA PARA GERAR NOTA ****************************
          ********************************************************************/
       
        $ref =  $pagamento_ref; // referencia: ID do pagamento
        $ref = str_replace("'", '', $ref);
       
         // conecta ao servidor
        if($emissao_producao == 1){
            $server = "https://api.focusnfe.com.br";
             $login = $token_producao; // PRODUÇÃO
        }else{
            $server = "https://homologacao.focusnfe.com.br";
            $login = $token_homologacao; // HOMOLOGAÇÃO
        }
        
       // echo $ref.'<br>';
       // echo $login;
       //exit;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $server."/v2/nfse?ref=" . $ref);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($nfse));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$token_senha");
        $body = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         //as três linhas abaixo imprimem as informações retornadas pela API, aqui o seu sistema deverá
         //interpretar e lidar com o retorno
         //print($http_code."\n");
         //print($body."\n\n");
         //print("");
         //exit; 
        
        //print_r($nfse); exit;
        
        // RETORNO DA NOTA

        //$body = '{ "status": "autorizado", "ref": "nfse_2", "cnpj_prestador": "37607436000153", "data_emissao": "2020-10-17T19:35:40-03:00", "numero": "4", "serie": "1", "url": "http://api.focusnfe.com.br/notas_fiscais_consumidor/NFe13201037607436000153650010000000041869463733.html", "caminho_xml_nota_fiscal": "/arquivos/37607436000153/202010/XMLs/13201037607436000153650010000000041869463733-nfe.xml", "codigo_verificacao": "NFe13201037607436000153650010000000041869463733" }';
        
        $json2 = json_decode($body, true);
        $status = $json2['status'];
        $ref = $json2['ref'];
        $cnpj_prestador = $json2['cnpj_prestador'];
        $data_emissao = $json2['data_emissao'];
        $numero = $json2['numero'];
        $serie = $json2['serie'];
        $url = $json2['url'];
        $caminho_xml_nota_fiscal = $json2['caminho_xml_nota_fiscal'];
        $codigo_verificacao = $json2['codigo_verificacao'];
        
        $data_atualiza_pagamento['numero_nf'] = $numero;
        
        if($status == 'autorizado'){
            $pagamentos_lista_medicos = $this->payments_model->get_contas_payments_list_id_selecionado_cnpj_medico_lista_detalhes_medicos($paymentid, $conta_medico_id);
            foreach ($pagamentos_lista_medicos as $pagamento_m_id) {
                $id_pgto = $pagamento_m_id['pagamento_id'];
               //$this->update_nota_fiscal($data_atualiza_pagamento, $pagamento_id);
               // $this->update_nota_fiscal_conta($data_atualiza_pagamento, $conta_medico_id, $id_pgto); 
                 $this->db->where('id', $id_pgto);
                $this->db->update(db_prefix() . 'invoicepaymentrecords', $data_atualiza_pagamento);
            }
            
        }else{
            
            $pagamentos_lista_medicos = $this->payments_model->get_contas_payments_list_id_selecionado_cnpj_medico_lista_detalhes_medicos($paymentid, $conta_medico_id);
            foreach ($pagamentos_lista_medicos as $pagamento_m_id) {
                $id_pgto = $pagamento_m_id['pagamento_id'];
                 $this->db->where('id', $id_pgto);
                $this->db->update(db_prefix() . 'invoicepaymentrecords', $data_atualiza_pagamento);
               //$this->update_nota_fiscal($data_atualiza_pagamento, $pagamento_id);
               // $this->update_nota_fiscal_conta($data_atualiza_pagamento, $conta_medico_id, $id_pgto); 
            }
            
          $json2 = json_decode($http_code, true);
          IF ($json2 == 422){
              ECHO '<h3>NOTA JÁ AUTORIZADA</h3>'; exit;
          }else IF ($json2 == 400){
              ECHO '<h3>O certificado do emitente está vencido</h3>'; exit;
          }
          else{
              print($body."\n\n");
          print("");
          exit; 
          }
          
        }
        
        
        $nf_data = array(
            "invoice_id" => $faturaid,
            "status" => $status,
            "ref" => $ref,
            "cnpj_prestador" => $cnpj_prestador,
            "data_emissao" => $data_emissao,           
            "numero" => $numero,
            "serie" => $serie,
            "url" => $url,
            "caminho_xml_nota_fiscal" => $caminho_xml_nota_fiscal,
            "codigo_verificacao" => $codigo_verificacao,            
            "deleted" => 0,
            "valor" => $valor_pagamentos_selecionados,
            "conta_id" => $conta_medico_id
        );
       
      $insert_id = $this->nota_fiscal_model->add($nf_data);
        
      return $insert_id;
      //  echo json_encode(array("success" => true,  'id' => $fatura_id, 'message' => lang('record_saved')));
    // curl_close($ch);  
        
        
    } 
    
    
}
