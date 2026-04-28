<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <?php
            $dados_caixa = $this->caixas_model->get_caixa_registro_atual($caixa_atual); 
            $nome_caixa = $dados_caixa->caixa;
            $saldo_inicial = $dados_caixa->saldo;
            
            /********************************************************************
                        D E S P E S A S    S A Í D A S
            ********************************************************************/
            $valor_despesa = 0;
            $dados_despesas = $this->caixas_model->get_despesas_caixa_registro_atual($caixa_atual); 
            $valor_despesa = $dados_despesas->valor;
        
            /*******************************************************************/
             /********************************************************************
                        R E P A S S E S    S A Í D A S
            ********************************************************************/
            $valor_repasse = 0;
            $dados_repasse = $this->Repasses_model->get_repasses_caixa_registro_atual($caixa_atual); 
            $valor_repasse = $dados_repasse->valor;
            /*******************************************************************/
            
            $id_caixa = $dados_caixa->id_caixa;
            if($dados_caixa->entrada_caixa){
            $entrada_caixa = $dados_caixa->entrada_caixa;
            }else{
            $entrada_caixa = 0;    
            }
            $usuario_abertura = $dados_caixa->usuario_abertura;
            $member_caixa = $this->staff_model->get($usuario_abertura);
            $operador = $member_caixa->firstname.' '.$member_caixa->lastname;
            
            $caixa['user_nome'] = $operador;
              
            $saldo_final = 0;
            $saldo_final += $saldo_inicial - $valor_despesa - $valor_repasse;
            
            $soma_valor_recebido = 0;
            $quantidade_pgtos = $this->caixas_model->get_caixa_quantidade_pagamento($caixa_atual); 
            foreach ($quantidade_pgtos as $aRow) {
                $tipo_id             = $aRow['tipo_id'];
                $forma_pagamento     = $aRow['forma_pagamento'];
                $qtde_pgto           = $aRow['quantidade'];
                $valor_pgto_recebido = $aRow['valor'];

                $soma_valor_recebido += $valor_pgto_recebido;
                
               // if($tipo_id == 1){
                    $saldo_final += $valor_pgto_recebido;
               // }
            }
            
            
            
          
            ?>
         <?php //echo form_open(admin_url('caixas/caixa'),array('id'=>'fechar_caixa-form')); ?>
          <input type="hidden" name="caixa_registro_id" value="<?php echo $caixa_atual; ?>">
          <input type="hidden" name="caixa_id" value="<?php echo $id_caixa; ?>">
      
          <input type="hidden" name="valor_recebido" value="<?php echo $soma_valor_recebido; ?>">
          <input type="hidden" name="saldo" value="<?php echo $saldo_final; ?>">
          <input type="hidden" name="valor_despesas" value="<?php echo $valor_despesa; ?>">
         <div class="col-md-6">
            <div class="panel_s">
               <div class="panel-body">
                  <?php
                     if(isset($expense) && $expense->recurring_from != NULL){
                       $recurring_expense = $this->expenses_model->get($expense->recurring_from);
                       echo '<div class="alert alert-info">'._l('expense_recurring_from','<a href="'.admin_url('expenses/list_expenses/'.$expense->recurring_from).'" target="_blank">'.$recurring_expense->category_name.(!empty($recurring_expense->expense_name) ? ' ('.$recurring_expense->expense_name.')' : '').'</a></div>');
                     }
                     ?>
                   <h4 class="no-margin"><h3><?php echo $title; ?> <label class="btn btn-warning pull-right"><?php echo $operador; ?></label> <label class="btn btn-primary pull-right"><?php echo $nome_caixa; ?></label></h3></h4>
                  <hr class="hr-panel-heading" />
                
                     
                    <label><h3>Data Abertura: <?php echo _d($dados_caixa->data_abertura); ?>   </h3></label>
                    <br>  
                    <?php $value = (isset($expense) ? $expense->note : ''); ?>
                    <label><h3>Data Fechamento: <?php echo _d(date('Y-m-d')); ?> </h3></label>
                    <br>
                    <hr>
                    <?php $value_ec = (isset($entrada_caixa) ? $entrada_caixa : ''); ?>
                     
                    <label><h3><font style="color : blue;">Saldo Inicial:<b> <?php echo app_format_money($saldo_inicial, 'R$'); ?></b> </font> </h3></label>
                     <hr>
                     <label><h3><font style="color : red;">Saídas/ Despesas :<b> <?php echo app_format_money($valor_despesa, 'R$'); ?></b>  </font></h3></label>
                     <hr>
                     <label><h3><font style="color : red;">Repasses Médicos :<b> <?php echo app_format_money($valor_repasse, 'R$'); ?></b>  </font></h3></label>
                    
                     <hr>
                     <h3>Recebimentos</h3>
                     <table class="table">
                          
                         <tr>
                            <td><h4><b>Forma de Pagamento</b> </h4></td>
                            <td><h4><b>Qtde pgto</b> </h4></td>
                            <td><h4><b>Valor</b> </h4></td>
                         </tr>
                        <?php
                        $soma_pagamentos = 0;
                        $quantidade_pgtos = $this->caixas_model->get_caixa_quantidade_pagamento($caixa_atual); 
                        foreach ($quantidade_pgtos as $aRow) {
                            $tipo_id             = $aRow['tipo_id'];
                            $forma_pagamento     = $aRow['forma_pagamento'];
                            $qtde_pgto           = $aRow['quantidade'];
                            $valor_pgto_recebido = $aRow['valor'];
                            
                            $soma_pagamentos += $valor_pgto_recebido;
                        ?>  
                        <tr>
                         <td><h4><b><?php echo $forma_pagamento; ?></b> </h4></td>
                         <td><h4><b><?php echo $qtde_pgto; ?></b> </h4></td>
                         <td><h4><b><?php echo app_format_money($valor_pgto_recebido, 'R$'); ?></b> </h4></td>
                        </tr>
                    
                    <?php
                        }
                     ?>
                         <tr>
                         <td><h4><b>TOTAL</b> </h4></td>
                         <td></td>
                         <td><h4><b><?php echo app_format_money($soma_pagamentos, 'R$'); ?></b> </h4></td>
                        </tr>
                     </table>
                     
                     <br>
                     <label><h2>Total em Caixa: <b><?php echo app_format_money($saldo_final, 'R$'); ?></b> </h2></label>
                  
                  
               </div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="panel_s">
               <div class="panel-body">
                  <h4 class="no-margin"><?php echo 'Movimentações de Caixa'; ?></h4>
                  <hr class="hr-panel-heading" />
                  <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                      
                        <thead>
                             
                            <tr>
                                <th>
                                    <?php echo 'ID'; ?>
                                </th>
                                <th >
                                    <?php echo 'Fatura'; ?>
                                </th>
                                <th >
                                    <?php echo 'Cliente'; ?>
                                </th>
                                <th >
                                    <?php echo 'Modo Pagto'; ?>
                                </th>
                                <th >
                                    <?php echo 'Conta / Descrição'; ?>
                                </th>
                                <th >
                                    <?php echo 'Entrada'; ?>
                                </th>
                                <th >
                                    <?php echo 'Saída'; ?>
                                </th>
                                <th >
                                    <?php echo 'Data'; ?>
                                </th>
                                   <th >
                                <?php echo 'Log'; ?>
                            </th>
                          
                            </tr>
                            
                        </thead>
                        <tbody>
                           
                        
                            <?php
                            $soma_total = 0;
                            $soma_total_saida = 0;
                            $caixa_pgtos = $this->caixas_model->get_pagamentos_by_registro_caixa($caixa_atual); 
                            foreach($caixa_pgtos as $caixa){
                                
                                
                            $soma_total += $caixa['valor'];    
                            $soma_total_saida += $caixa['saida'];  
                            
                            IF($caixa['invoiceid']){
                                $fatura = '#FAT-'.$caixa['invoiceid'];
                            }else{
                                $fatura = "";
                            }
                            
                            if($caixa['cliente']){
                              $cliente = $caixa['cliente'];
                            }else{
                              $cliente = "";  
                            }
                            ?>
                        <tr>
                            <td>
                              <?php echo $caixa['id'];   ?>
                            </td>
                            <td>
                              <?php echo $fatura;   ?>
                            </td>
                            <td>
                              <?php echo $cliente;   ?>
                            </td>
                            <td>
                              <?php echo $caixa['modo_pagamento'];   ?>
                            </td>
                            <td>
                              <?php echo $caixa['conta_financeira'];   ?>
                            </td>
                            <td>
                              <?php echo app_format_money($caixa['valor'], 'R$');  ?>
                            </td>
                            <td>
                              <?php echo app_format_money($caixa['saida'], 'R$');  ?>
                            </td>
                            <td>
                              <?php
                              if($caixa['data_pagamento']){
                                  $data_registro = $caixa['data_pagamento'];
                              }else{
                                  $data_registro = $caixa['data_movimento'];
                              }
                              
                              echo _d($data_registro);   ?>
                            </td>

                             <td>
                              <?php echo $operador;?>   
                              <?php echo _d($caixa['log']);   ?>
                                
                            </td>
                            
                            
                        </tr>
                        
                    <?php }
                    
                    $saldo = $soma_total - $soma_total_saida;
                    ?>
                        <tr>
                            <td>
                              ENTRADAS
                              
                              
                            </td>
                            
                                
                            
                            <td>
                             
                            </td>
                            
                              <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                              
                            </td>
                            <td>
                                <label class="btn btn-primary"><?php echo app_format_money($soma_total, 'R$');  ?></label>
                            </td>
                            <td>
                              
                            </td>
                            <td>
                              
                            </td>

                            
                        </tr>
                        <tr>
                            <td>
                              SAÍDAS
                            </td>
                            
                              <td>
                             
                            </td>
                            
                            <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                              
                            </td>
                            <td>
                              
                            </td>
                            <td>
                                <label class="btn btn-danger"><?php echo app_format_money($soma_total_saida, 'R$');  ?></label>
                            </td>
                            <td>
                              
                            </td>

                            
                        </tr>
                        <tr>
                            <td>
                              SALDO (Total Entradas)
                            </td>
                            
                              <td>
                             
                            </td>
                            
                            <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                              
                            </td>
                            <td >
                              <label class="btn btn-success"><?php echo app_format_money($saldo, 'R$');  ?></label>
                            </td>
                            <td>
                              
                            </td>
                            <td>
                              
                            </td>

                            
                        </tr>
                        <tr style="background-color: gray">
                            <td>
                                <b>RESUMO  FORMA DE PAGAMENTO</b>
                            </td>
                            
                              <td>
                             
                            </td>
                            
                            <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                              
                            </td>
                            <td >
                             
                            </td>
                            <td>
                              
                            </td>
                            <td>
                              
                            </td>

                            
                        </tr>
                        <?php
                     
                        $quantidade_pgtos = $this->caixas_model->get_caixa_quantidade_pagamento($caixa_atual); 
                        foreach ($quantidade_pgtos as $aRow) {
                            $tipo_id             = $aRow['tipo_id'];
                            $forma_pagamento     = $aRow['forma_pagamento'];
                            $qtde_pgto           = $aRow['quantidade'];
                            $valor_pgto_recebido = $aRow['valor'];
                        ?>  
                        <tr>
                            <td>
                              <?php echo $forma_pagamento; ?>
                            </td>
                            
                              <td>
                             
                            </td>
                            <td>
                             
                                
                            </td>
                            <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                              
                            </td>
                            <td >
                                <b><?php echo app_format_money($valor_pgto_recebido, 'R$'); ?></b>
                            </td>
                            <td>
                              
                            </td>
                            <td>
                              
                            </td>
                              
                            
                        </tr>
                        <?php
                        }
                        ?>
                        <br>
                         <tr>
                            <td>
                                <h4>Dinheiro em Caixa:</h4>
                            </td>
                            
                                
                              <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                             
                            </td>
                            <td>
                              
                            </td>
                            <td >
                                <label><h4> <b><?php echo app_format_money($saldo_final, 'R$'); ?></b> </h4></label>
                            </td>
                            <td>
                              
                            </td>
                            <td>
                              
                            </td>
                           
                        </tr>
                        
                        <tr style="background-color: darkseagreen">
                            <td><?php echo $nome_caixa; ?></td>
                           
                            <td></td>
                            <td></td>
                            <td> </td>
                            <td> </td>
                            <td>OPERADOR</td>
                            <td><?php echo $operador ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        
                </tbody>
                
                <thead>
                        <tr>
                            <th>
                                <?php echo 'ID'; ?>
                            </th>
                            <th >
                                <?php echo 'Fatura'; ?>
                            </th>
                            <th >
                                <?php echo 'Cliente'; ?>
                            </th>
                            <th >
                                <?php echo 'Modo Pagto'; ?>
                            </th>
                            <th >
                                <?php echo 'Conta'; ?>
                            </th>
                            <th >
                                <?php echo 'Entrada'; ?>
                            </th>
                            <th >
                                <?php echo 'Saída'; ?>
                            </th>
                            <th >
                                <?php echo 'Data'; ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                  
                </div>
               </div>
            </div>
         </div>
         <?php //echo form_close(); ?>
      </div>
      <div class="btn-bottom-pusher"></div>
   </div>
</div>
<?php $this->load->view('admin/expenses/expense_category'); ?>
<?php init_tail(); ?>
<script>
   var customer_currency = '';
   Dropzone.options.expenseForm = false;
   var expenseDropzone;
   init_ajax_project_search_by_customer_id();
   var selectCurrency = $('select[name="currency"]');
   <?php if(isset($customer_currency)){ ?>
     var customer_currency = '<?php echo $customer_currency; ?>';
   <?php } ?>
     $(function(){
        $('body').on('change','#project_id', function(){
          var project_id = $(this).val();
          if(project_id != '') {
           if (customer_currency != 0) {
             selectCurrency.val(customer_currency);
             selectCurrency.selectpicker('refresh');
           } else {
             set_base_currency();
           }
         } else {
          do_billable_checkbox();
        }
      });

     if($('#dropzoneDragArea').length > 0){
        expenseDropzone = new Dropzone("#expense-form", appCreateDropzoneOptions({
          autoProcessQueue: false,
          clickable: '#dropzoneDragArea',
          previewsContainer: '.dropzone-previews',
          addRemoveLinks: true,
          maxFiles: 1,
          success:function(file,response){
           response = JSON.parse(response);
           if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
             window.location.assign(response.url);
           }
         },
       }));
     }

 

     $('input[name="billable"]').on('change',function(){
       do_billable_checkbox();
     });

      $('#repeat_every').on('change',function(){
         if($(this).selectpicker('val') != '' && $('input[name="billable"]').prop('checked') == true){
            $('.billable_recurring_options').removeClass('hide');
          } else {
            $('.billable_recurring_options').addClass('hide');
          }
     });

     // hide invoice recurring options on page load
     $('#repeat_every').trigger('change');

      $('select[name="clientid"]').on('change',function(){
       customer_init();
       do_billable_checkbox();
       $('input[name="billable"]').trigger('change');
     });

     <?php if(!isset($expense)) { ?>
        $('select[name="tax"], select[name="tax2"]').on('change', function () {

            delay(function(){
                var $amount = $('#amount'),
                taxDropdown1 = $('select[name="tax"]'),
                taxDropdown2 = $('select[name="tax2"]'),
                taxPercent1 = parseFloat(taxDropdown1.find('option[value="'+taxDropdown1.val()+'"]').attr('data-percent')),
                taxPercent2 = parseFloat(taxDropdown2.find('option[value="'+taxDropdown2.val()+'"]').attr('data-percent')),
                total = $amount.val();

                if(total == 0 || total == '') {
                    return;
                }

                if($amount.attr('data-original-amount')) {
                  total = $amount.attr('data-original-amount');
                }

                total = parseFloat(total);

                if(taxDropdown1.val() || taxDropdown2.val()) {

                    $('#tax_subtract').removeClass('hide');

                    var totalTaxPercentExclude = taxPercent1;
                    if(taxDropdown2.val()){
                      totalTaxPercentExclude += taxPercent2;
                    }

                    var totalExclude = accounting.toFixed(total - exclude_tax_from_amount(totalTaxPercentExclude, total), app.options.decimal_places);
                    $('#tax_subtract_total').html(accounting.toFixed(totalExclude, app.options.decimal_places));
                } else {
                   $('#tax_subtract').addClass('hide');
                }
                if($('#tax1_included').prop('checked') == true) {
                    subtract_tax_amount_from_expense_total();
                }
              }, 200);
        });

        $('#amount').on('blur', function(){
          $(this).removeAttr('data-original-amount');
          if($(this).val() == '' || $(this).val() == '') {
              $('#tax1_included').prop('checked', false);
              $('#tax_subtract').addClass('hide');
          } else {
            var tax1 = $('select[name="tax"]').val();
            var tax2 = $('select[name="tax2"]').val();
            if(tax1 || tax2) {
                setTimeout(function(){
                    $('select[name="tax2"]').trigger('change');
                }, 100);
            }
          }
        })

        $('#tax1_included').on('change', function() {

          var $amount = $('#amount'),
          total = parseFloat($amount.val());

          // da pokazuva total za 2 taxes  Subtract TAX total (136.36) from expense amount
          if(total == 0) {
              return;
          }

          if($(this).prop('checked') == false) {
              $amount.val($amount.attr('data-original-amount'));
              return;
          }

          subtract_tax_amount_from_expense_total();
        });
      <?php } ?>
    });

    function subtract_tax_amount_from_expense_total(){
         var $amount = $('#amount'),
         total = parseFloat($amount.val()),
         taxDropdown1 = $('select[name="tax"]'),
         taxDropdown2 = $('select[name="tax2"]'),
         taxRate1 = parseFloat(taxDropdown1.find('option[value="'+taxDropdown1.val()+'"]').attr('data-percent')),
         taxRate2 = parseFloat(taxDropdown2.find('option[value="'+taxDropdown2.val()+'"]').attr('data-percent'));

         var totalTaxPercentExclude = taxRate1;
         if(taxRate2) {
          totalTaxPercentExclude+= taxRate2;
        }

        if($amount.attr('data-original-amount')) {
          total = parseFloat($amount.attr('data-original-amount'));
        }

        $amount.val(exclude_tax_from_amount(totalTaxPercentExclude, total));

        if($amount.attr('data-original-amount') == undefined) {
          $amount.attr('data-original-amount', total);
        }
    }

    function customer_init(){
        var customer_id = $('select[name="clientid"]').val();
        var projectAjax = $('select[name="project_id"]');
        var clonedProjectsAjaxSearchSelect = projectAjax.html('').clone();
        var projectsWrapper = $('.projects-wrapper');
        projectAjax.selectpicker('destroy').remove();
        projectAjax = clonedProjectsAjaxSearchSelect;
        $('#project_ajax_search_wrapper').append(clonedProjectsAjaxSearchSelect);
        init_ajax_project_search_by_customer_id();
        if(!customer_id){
           set_base_currency();
           projectsWrapper.addClass('hide');
         }
       $.get(admin_url + 'expenses/get_customer_change_data/'+customer_id,function(response){
         if(customer_id && response.customer_has_projects){
           projectsWrapper.removeClass('hide');
         } else {
           projectsWrapper.addClass('hide');
         }
         var client_currency = parseInt(response.client_currency);
         if (client_currency != 0) {
           customer_currency = client_currency;
           do_billable_checkbox();
         } else {
           customer_currency = '';
           set_base_currency();
         }
       },'json');
     }
     function expenseSubmitHandler(form){

      selectCurrency.prop('disabled',false);

      $('select[name="tax2"]').prop('disabled',false);
      $('input[name="billable"]').prop('disabled',false);
      $('input[name="date"]').prop('disabled',false);

      $.post(form.action, $(form).serialize()).done(function(response) {
        response = JSON.parse(response);
        if (response.expenseid) {
         if(typeof(expenseDropzone) !== 'undefined'){
          if (expenseDropzone.getQueuedFiles().length > 0) {
            expenseDropzone.options.url = admin_url + 'expenses/add_expense_attachment/' + response.expenseid;
            expenseDropzone.processQueue();
          } else {
            window.location.assign(response.url);
          }
        } else {
          window.location.assign(response.url);
        }
      } else {
        window.location.assign(response.url);
      }
    });
      return false;
    }
    function do_billable_checkbox(){
      var val = $('select[name="clientid"]').val();
      if(val != ''){
        $('.billable').removeClass('hide');
        if ($('input[name="billable"]').prop('checked') == true) {
          if($('#repeat_every').selectpicker('val') != ''){
            $('.billable_recurring_options').removeClass('hide');
          } else {
            $('.billable_recurring_options').addClass('hide');
          }
          if(customer_currency != ''){
            selectCurrency.val(customer_currency);
            selectCurrency.selectpicker('refresh');
          } else {
            set_base_currency();
         }
       } else {
        $('.billable_recurring_options').addClass('hide');
        // When project is selected, the project currency will be used, either customer currency or base currency
        if($('#project_id').selectpicker('val') == ''){
            set_base_currency();
        }
      }
    } else {
      set_base_currency();
      $('.billable').addClass('hide');
      $('.billable_recurring_options').addClass('hide');
    }
   }
   function set_base_currency(){
    selectCurrency.val(selectCurrency.data('base'));
    selectCurrency.selectpicker('refresh');
   }
</script>
</body>
</html>
