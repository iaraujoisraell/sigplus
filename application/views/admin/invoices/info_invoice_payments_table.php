<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="table-responsive">
    <table class="table table-hover no-mtop">
        <thead>
            <tr>
                <th><span class="bold"><?php echo 'Conta'; ?></span></th>
                <th><span class="bold"><?php echo _l('payments_table_amount_heading'); ?></span></th>
            </tr>
        </thead>
        <tbody>
           
            <?php
           
            $soma_valor_medico = 0;
           $soma_valor_procedimento = 0;
            // valor da fatura
            $dados_fatura = $this->invoices_model->get($invoice->id);
            $invoice_id = $invoice->id;
            $subtotal = $invoice->subtotal;
            $discount_total = $invoice->discount_total; // Valor do desconto
            $valor_fatura = $invoice->total;
            //se tiver valor de desconto, calcula a porcentagem.
            
            // 1 - retorna os médicos da fatura (único)
            $medicos_invoices_itens = $this->invoices_model->get_todos_medico_invoice_item_pagamento($invoice_id);
           
           
            foreach ($medicos_invoices_itens as $medico) {
               $medico_id =  $medico['medicoid'];
               $soma_valor_medico = 0;
               $soma_valor_procedimento = 0;
               $valor_comissao = 0;
               // 2-  lista os procedimentos de um médico
               if($medico_id > 0){  
               $invoices_itens_medico = $this->invoices_model->get_conta_financeira_pagamento($invoice_id, $medico_id);
               
               foreach ($invoices_itens_medico as $items) {
                  $itemable_id =  $items['id'];
                  $valor_procedimento =  $items['rate'];
                  $qty =  $items['qty'];
                  $rate_desconto =  $items['rate_desconto'];
                  $desconto_porcentagem =  $items['desconto_porcentagem'];
                  $desconto_valor =  $items['desconto_valor'];
                  $destino_desconto =  $items['destino_desconto'];
                 
                  $items_id  =  $items['id'];
                  $item_id   =  $items['item_id'];
                  $conta_id  =  $items['conta_id'];
                  
                //  echo $conta_id.'<Br>'; 
                
                  
                  $dados_conta = $this->payments_model->get_conta_financeira_by_id($conta_id);
           
                  $medico_id = $dados_conta->medico_id;
                  $nome_profissional = $dados_conta->nome;
                  $regra_desconto_vision = $dados_conta->regra_desconto_vision;
                 
                  /*
                   * 3 - Verifica na excessão o médico para esse procedimento ( Tabela de repasse do médico por procedimento)
                   */

                   $tabela_procedimento_medico_row = $this->invoices_model->get_medico_procedimento_invoice_item($item_id, $medico_id);
                   $regra_procedimento = count($tabela_procedimento_medico_row); 
                  
                  // echo $regra_procedimento; exit;
                   if($regra_procedimento >= 1){
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
                              }else{
                                  $valor_comissao = $valor_em_real_repasse;
                              }
                          }else{
                              if($destino_desconto == 'AMBOS' || (!$destino_desconto)){
                                 $valor_comissao = ($rate_desconto * $valor_repasse)  ;
                              }ELSE if($destino_desconto == 'MÉDICO'){
                                  $valor_comissao = $valor_em_real_repasse - $desconto_valor;
                              }ELSE if($destino_desconto == 'EMPRESA'){
                                  $valor_comissao = $valor_em_real_repasse;
                              }
                          }
                          
                          
                          
                       }else{
                          
                            // se o valor da comissao do médico for valor fixo
                           $valor_repasse = $procedimentos['valor'];    
                           $valor_comissao = ($valor_repasse  * $qty);
                            if(get_staff_user_id() == 1){
                         
                            }
                          if($destino_desconto == 'AMBOS' || (!$destino_desconto) ){
                            if($desconto_porcentagem > 0 || $desconto_valor > 0){
                             $valor_desconto_comissao = $desconto_valor / 2;
                             $valor_procedimento_c_desconto = $valor_procedimento - $valor_desconto_comissao;
                                if($regra_desconto_vision){
                                 $valor_procedimento_c_desconto = $valor_procedimento - $valor_desconto_comissao;
                                 $valor_comissao = ($rate_desconto * $regra_desconto_vision)/ 100;
                                }else{
                                // $desconto_porcentagem = ($desconto_porcentagem/100);
                                // $valor_desconto_comissao = ($valor_comissao  * $desconto_porcentagem);
                                 $valor_comissao += -$valor_desconto_comissao;  
                                }
                              
                           
                           }
                          }ELSE if($destino_desconto == 'MÉDICO'){
                              $valor_comissao += -$desconto_valor;
                          }ELSE if($destino_desconto == 'EMPRESA'){
                              $valor_comissao = $valor_comissao;
                          }
                       }

                       $valor_caixa = $rate_desconto - $valor_comissao;

                     //echo 'comissao esp : '.$valor_comissao.'<br>';
                     //echo 'caixa : '.$valor_caixa.'<br>';
                    }
                    
                   //se não tiver tabela especial, entra na regra padrão
                   }else{
                       
                    /*
                     * 4 - Caso não encontre segue a regra padrão
                     */
                      $valor_comissao = 0;
                      $valor_caixa = $rate_desconto;
                   }
                  
                  //echo $valor_comissao; exit;

                   $soma_valor_procedimento += $rate_desconto;

                   $soma_valor_medico += $valor_comissao;

                   $soma_valor_caixa += $valor_caixa;

                     


               }// fim lista por médico

              }// fim if médico

            
                 $valor_total_todos_medicos += $soma_valor_medico;
            
                    // soma o valor de todos os procedimentos
          
                  $dados_medico = $this->invoices_model->get_medico($medico_id);
             
            ?>   
               
                <tr class="payment">
                <td><?php echo $dados_medico->nome_profissional; ?></td>
                <td><?php  if($soma_valor_medico > 0){
                            echo app_format_money($soma_valor_medico, $invoice->currency_name);
                        }else{
                            echo '<label class ="btn btn-danger"> PROCEDIMENTO NÃO PARAMETRIZADO PARA O MÉDICO </label>';
                        } ?>
                </td>
                </tr>
               
            <?php
            
             
            
            }// fim for médico

          
            
            $soma_valor_caixa = $valor_fatura - $valor_total_todos_medicos;  //  $soma_valor_procedimento - $valor_total_todos_medicos;
            
            $total_caixa_entrada = $valor_total_todos_medicos + $soma_valor_caixa;
            
           
            ?>
            <tr class="payment">
                <td><?php echo 'EMPRESA'; ?></td>
                <td><?php echo app_format_money($soma_valor_caixa, $invoice->currency_name); ?></td>
            </tr>
            
        </tbody>
        <thead>
            <tr>
                <th><span class="bold"><?php echo 'TOTAL'; ?></span></th>
                <td><?php echo app_format_money($total_caixa_entrada, $invoice->currency_name); ?></td>
            </tr>
        </thead>
    </table>
</div>
