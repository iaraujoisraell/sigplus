<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>

<style>
 body {
 font-family:'Open Sans';
 background:#f1f1f1;
 }
 h3 {
 margin-top: 7px;
 font-size: 16px;
 }

 .install-row.install-steps {
 margin-bottom:15px;
 box-shadow: 0px 0px 1px #d6d6d6;
 }

 .control-label {
 font-size:13px;
 font-weight:600;
 }
 .padding-10 {
 padding:10px;
 }
 .mbot15 {
 margin-bottom:15px;
 }
 .bg-default {
 background: #03a9f4;
 border:1px solid #03a9f4;
 color:#fff;
 }
 .bg-success {
 border: 1px solid #dff0d8;
 }
 .bg-not-passed {
 border:1px solid #f1f1f1;
 border-radius:2px;
 }
 .bg-not-passed {
 border-right:0px;
 }
 .bg-not-passed.finish {
 border-right:1px solid #f1f1f1 !important;
 }
 .bg-not-passed h5 {
 font-weight:normal;
 color:#6b6b6b;
 }
 .form-control {
 box-shadow:none;
 }
 .bold {
 font-weight:600;
 }
 .col-xs-5ths,
 .col-sm-5ths,
 .col-md-5ths,
 .col-lg-5ths {
 position: relative;
 min-height: 1px;
 padding-right: 15px;
 padding-left: 15px;
 }
 .col-xs-5ths {
 width: 20%;
 float: left;
 }
 b {
 font-weight:600;
 }
 .bootstrap-select .btn-default {
 background: #fff !important;
 border: 1px solid #d6d6d6 !important;
 box-shadow: none;
 color: #494949 !important;
 padding: 6px 12px;
 }
</style>
  <div class="content">
      
    <div class="row">
      <div class="col-md-12">
          <section class="content-header">
              <h1>
                Parcelas a Pagar
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-home"></i> Home </a></li>  
                <li><a href="<?php echo admin_url('dashboard/menu_financeiro'); ?>"> Financeiro </a></li>
                <li class="active">Parcelas a Pagar</li>
              </ol>
            </section>
          
        <div class="panel_s">
          <div class="panel-body">
            <?php /* if(has_permission('items','','delete')){ ?>
             <a href="#" data-toggle="modal" data-table=".table-conta_pagar_parcelas" data-target="#items_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>
             <div class="modal fade bulk_actions" id="items_bulk_actions" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
               <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
               </div>
               <div class="modal-body">
                 <?php if(has_permission('leads','','delete')){ ?>
                   <div class="checkbox checkbox-danger">
                    <input type="checkbox" name="mass_pgto" id="mass_pgto">
                    <label for="mass_pgto"><?php echo 'Confirmar pagamento em massa'; ?></label>
                  </div>
                  <!-- <hr class="mass_pgto_separator" /> -->
                <?php } ?>
              </div>
              <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
               <a href="#" class="btn btn-info" onclick="items_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
             </div>
           </div>
           <!-- /.modal-content -->
         </div>
         <!-- /.modal-dialog -->
       </div>
            <!-- /.modal -->
        <?php } */ ?>
        
              <!-- ADD PAGAMENTO -->
              <?php if(has_permission('items','','delete')){ ?>
             <a href="#" data-toggle="modal" data-table=".table-conta_pagar_parcelas" data-target="#add_pagamento" class="hide bulk-actions-btn table-btn"><?php echo 'ADD PAGAMENTO'; ?></a>
             <div class="modal fade bulk_actions" id="add_pagamento" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                   <div class="modal-content">
                    <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title"><?php echo 'Add Pagamento'; ?></h4>
                   </div>
                   <div class="modal-body">
                     <?php if(has_permission('leads','','delete')){ ?>
                       <div class="form-group">
                            <label>Conta/Banco</label>
                            <select name="banco_id"  id="banco_id" class="form-control">
                                <option checked="true" value="">Selecione</option>
                                <?php foreach ($bancos as $banco){ ?>
                                 <option value="<?php echo $banco['id']; ?>"><?php echo $banco['banco']. ' ( AG: '.$banco['agencia'].' [Conta : '.$banco['numero_conta'].']) '; ?></option>   
                                <?php } ?>
                            </select>
                      </div>
                       <div class="form-group">
                           <label>Data do Pagamento</label>
                            <input type="date"  name="data_pagamento" id="data_pagamento"  value="<?php echo date('Y-m-d'); ?>" class="form-control">
                        </div>
                       <div class="form-group">
                            <label>Formas Pagamento</label>
                            <select name="forma_pagamento"  id="forma_pagamento" class="form-control">
                                <option checked="true" value="">Selecione</option>
                                <?php foreach ($formas_pagamentos as $forma){ ?>
                                 <option value="<?php echo $forma['id']; ?>"><?php echo $forma['name']; ?></option>   
                                <?php } ?>
                            </select>
                        </div>
                        
                       
                       
                       
                       <div class="form-group">
                           <label>Desconto</label>
                           <input type="text"  name="desconto" id="desconto" onKeyPress="return(moeda(this,'.',',',event))" maxlength="10" class="form-control">
                       </div>
                       
                       <div class="form-group">
                           <label>Descricao / Detalhes</label>
                           <input type="text"  name="descricao" id="descricao"   class="form-control">
                       </div>
                       
                       
                      <!-- <hr class="mass_delete_separator" /> -->
                    <?php } ?>
                  </div>
                  <div class="modal-footer">
                   <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                   <a href="#" class="btn btn-info" data-dismiss="modal"  onclick="add_pagamento_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                 </div>
               </div>
               <!-- /.modal-content -->
             </div>
             <!-- /.modal-dialog -->
           </div>
            <?php } ?>
           <!-- /.ADD PAGAMENTO --> 
            
     <?php hooks()->do_action('before_items_page_content'); ?>
     <?php if(has_permission('items','','create')){ ?>
       <div class="_buttons">
          <!-- <a href="<?php echo admin_url('invoice_items/add_procedimento_medicos'); ?>"  class="btn btn-info pull-left mleft5" ><?php echo 'Add Procedimento/Repasse'; ?></a> -->
 
      </div>
      <div class="clearfix"></div>
      <hr class="hr-panel-heading" />
    <?php } ?>
      <!-- CONVENIO -->
      <!-- CATEGORIA -->
      <div class="col-md-4">
          <div class="form-group">
               <?php
              $selected = '';
              echo render_select('categorias', $categorias_financeira, array('id', array('name')), 'Categoria', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
              ?>
            </div>
      </div>
      
     <!-- PLANO DE CONTA -->
      <div class="col-md-4">
          <div class="form-group">
               <?php
              $selected = '';
              echo render_select('plano_conta', $planos_conta, array('id', array('descricao')), 'Plano Contas', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
              ?>
            </div>
      </div>
      <!-- FORNECEDOR -->
      <div class="col-md-4">
          <div class="form-group">
               <?php
                  $selected = '';
                  echo render_select('fornecedor', $fornecedores, array('id', array('company')), 'Fornecedores', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
                  ?>
          </div>
      </div>  
      
      <!-- NÚMERO DO DOCUMENTO -->
      <div class="col-md-4">
          <div class="form-group">
               <?php
                  $selected = '';
                  echo render_select('documento', $numero_documentos, array('numero_documento', array('numero_documento')), 'Número Documento', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
                  ?>
          </div>
      </div>
      

      <div class="col-md-4">
          <div class="form-group">
               <?php
                                $hoje = date('Y-m-d');
                                ?>
                                <label>Vencimento de</label>
                                <input type="date" name="data_de" id="data_de" value="<?php echo $hoje; ?>" onkeypress="procedimentos_table_reload();" class="form-control">
            </div>
      </div>
      
      <div class="col-md-4">
          <div class="form-group">
                 <?php
                                $hoje = date('Y-m-d');
                                ?>
                                <label>Vencimento Até</label>
                                <input type="date" name="data_ate" id="data_ate" value="<?php echo $hoje; ?>" onkeypress="procedimentos_table_reload();" class="form-control">
            </div>

      </div>
      <div class="col-md-4">
          <div class="form-group">
                <label>Status</label>
                <select name="status_parcela" id="status_parcela" onchange="procedimentos_table_reload();" class="form-control">
                    <option value="">Todos</option>
                    <option value="1">Pago</option>
                    <option value="2">Não Pago</option>
                </select>
                
            </div>

      </div>
      <div class="col-md-4">
          <div class="form-group">
               <?php
                                $hoje = date('Y-m-d');
                                ?>
                                <label>Pagamento de</label>
                                <input type="date" name="pagamento_de" id="pagamento_de"  onkeypress="procedimentos_table_reload();" class="form-control">
            </div>
      </div>
      
      <div class="col-md-4">
          <div class="form-group">
                 <?php
                                $hoje = date('Y-m-d');
                                ?>
                                <label>Pagamento Até</label>
                                <input type="date" name="pagamento_ate" id="pagamento_ate"  onkeypress="procedimentos_table_reload();" class="form-control">
            </div>

      </div>
      
      <div class="col-md-6">
          <div class="modal-footer">
                 
                   <a href="#" class="btn btn-info" onclick="procedimentos_table_reload(); return false;"><?php echo 'Buscar'; ?></a>
                 </div>
      </div>
      
    <?php
    $table_data = [];

                if(has_permission('items','','delete')) {
                  $table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="conta_pagar_parcelas"><label></label></div>';
                   // $table_data[] = '<span class="hide"> - </span><label># Número Doc</label>';
                }

                $table_data = array_merge($table_data, array(
                  '# Número Doc',  
                  'Plano Conta',  
                  'Título',  
                  'Fornecedor',
                  'Centro de Custo',
                  'Parcela',
                  'Desconto',  
                  'Valor',
                  
                  'Dt Vencto',  
                  'Dt Pgto',
                  'Forma Pagto',
                  'Status'
                   ));

        $table_data = hooks()->apply_filters('invoices_table_columns', $table_data);
        render_datatable($table_data, (isset($class) ? $class : 'conta_pagar_parcelas'));
   //render_datatable($table_data,'invoice-items'); ?>
  </div>
</div>
</div>
</div>
</div>

<?php //$this->load->view('admin/invoice_items/item'); ?>

<?php //$this->load->view('admin/financeiro/conta_pagar/parcelas/modal_pagamento'); ?>
<?php $this->load->view('admin/financeiro/conta_pagar/parcelas/modal_parcela'); ?>

<?php init_tail(); ?>
<script>
    
  $(function(){
  
    var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
      
       CustomersServerParams['status_parcela'] = '[name="status_parcela"]';
       CustomersServerParams['plano_conta'] = '[name="plano_conta"]';
       CustomersServerParams['fornecedor'] = '[name="fornecedor"]'; 
       CustomersServerParams['categorias'] = '[name="categorias"]';
       CustomersServerParams['documento'] = '[name="documento"]'; 
       CustomersServerParams['data_de'] = '[name="data_de"]'; 
       CustomersServerParams['data_ate'] = '[name="data_ate"]';
       CustomersServerParams['pagamento_de'] = '[name="pagamento_de"]';
       CustomersServerParams['pagamento_ate'] = '[name="pagamento_ate"]';
       var tAPI = initDataTable('.table-conta_pagar_parcelas', admin_url+'financeiro/table_conta_pagar_parcela', [0], [0], CustomersServerParams, [1, 'desc']);
        $('select[name="categorias"]').on('change',function(){
          // alert('aqui');
           tAPI.ajax.reload();

         // procedimentos_table_reload();
       });
        //filtraCategoria();
  

   });
   
   function procedimentos_table_reload() {
      //alert('aqui');
       var CustomersServerParams = {};
     //  $.each($('._hidden_inputs._filters input'),function(){
     //     CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
     // });
       CustomersServerParams['status_parcela']      = '[name="status_parcela"]';
       CustomersServerParams['plano_conta'] = '[name="plano_conta"]';
       CustomersServerParams['fornecedor']  = '[name="fornecedor"]'; 
       CustomersServerParams['categorias']  = '[name="categorias"]';
       CustomersServerParams['documento']   = '[name="documento"]';
       CustomersServerParams['data_de']     = '[name="data_de"]'; 
       CustomersServerParams['data_ate']    = '[name="data_ate"]';
       CustomersServerParams['pagamento_de'] = '[name="pagamento_de"]';
       CustomersServerParams['pagamento_ate'] = '[name="pagamento_ate"]';
     if ($.fn.DataTable.isDataTable('.table-conta_pagar_parcelas')) {
       $('.table-conta_pagar_parcelas').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-conta_pagar_parcelas', admin_url+'financeiro/table_conta_pagar_parcela', [0], [0], CustomersServerParams, [1, 'desc']);
   // tAPI.ajax.reload();
     
    // filtraCategoria();
   }
   
   
  
  function filtraCategoria() {
      
      $.ajax({
        type: "POST",
        url: "<?php echo admin_url("invoice_items/retorno_filtro_categoria"); ?>",
        data: {
          convenios_procedimentos: $('#convenios_procedimentos').val()
          },
        success: function(data) {
          $('#filtro_categoria').html(data);
        }
      });
    }
    
    function add_pagamento_bulk_action(event) {
        if (confirm_delete()) {
          
          var banco_id = document.getElementById('banco_id');
	  var banco_value = banco_id.options[banco_id.selectedIndex].value;
           
          var data_pagamento = document.getElementById('data_pagamento').value;
	  //var data_pagamento_value = data_pagamento.options[data_pagamento.selectedIndex].value;
         
          var forma_pagamento = document.getElementById('forma_pagamento');
	  var forma_pagamento_value = forma_pagamento.options[forma_pagamento.selectedIndex].value;
          
          var desconto = document.getElementById('desconto').value;
	  
          var descricao = document.getElementById('descricao').value;
	  
          var ids = [];
          var data = {};
          
          data.banco_id = banco_value;
          data.data_pagamento = data_pagamento;
          data.forma_pagamento = forma_pagamento_value;
          data.desconto = desconto;
          data.descricao = descricao; 
           
         
          var rows = $('.table-conta_pagar_parcelas').find('tbody tr');
          $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
              ids.push(checkbox.val());
            }
          });
          data.ids = ids;
        
         // $(event).addClass('disabled');
          setTimeout(function() {
            $.post(admin_url + 'financeiro/bulk_action_add_pagamento', data).done(function() {
             // window.location.reload();
             procedimentos_table_reload();
            }).fail(function(data) {
              alert_float('danger', data.responseText);
            });
          }, 200);
        }
        
      }
   
  function items_bulk_action(event) {
    if (confirm_delete()) {
      var mass_pgto = $('#mass_pgto').prop('checked');
      var ids = [];
      var data = {};

      if(mass_pgto == true) {
        data.mass_pgto = true;
      }

      var rows = $('.table-conta_pagar_parcelas').find('tbody tr');
      $.each(rows, function() {
        var checkbox = $($(this).find('td').eq(0)).find('input');
        if (checkbox.prop('checked') === true) {
          ids.push(checkbox.val());
        }
      });
      data.ids = ids;
      $(event).addClass('disabled');
      setTimeout(function() {
        $.post(admin_url + 'financeiro/bulk_action_parcelas', data).done(function() {
          window.location.reload();
        }).fail(function(data) {
          alert_float('danger', data.responseText);
        });
      }, 200);
    }
  }
  
  
 </script>
</body>
</html>
