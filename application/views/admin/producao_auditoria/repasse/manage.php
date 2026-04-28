<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_menu_admin(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">
 
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
<script> 
function Mudarestado(el) {
    var display = document.getElementById(el).style.display;
    if(display == "none")
        document.getElementById(el).style.display = 'block';
    else
        document.getElementById(el).style.display = 'none';
}

 function moeda(a, e, r, t) {
    let n = ""
      , h = j = 0
      , u = tamanho2 = 0
      , l = ajd2 = ""
      , o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
    -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
    h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
        ;
    for (l = ""; h < u; h++)
        -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2) {
        for (ajd2 = "",
        j = 0,
        h = u - 3; h >= 0; h--)
            3 == j && (ajd2 += e,
            j = 0),
            ajd2 += l.charAt(h),
            j++;
        for (a.value = "",
        tamanho2 = ajd2.length,
        h = tamanho2 - 1; h >= 0; h--)
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
    }
    return !1
}
</script>



<div class="content">      
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
             <h3 class="customer-profile-group-heading"> <?php echo $title; ?> </h3>
            
             <?php hooks()->do_action('before_items_page_content'); ?>

              <!-- CONVENIO -->
              <div class="col-md-4">
                    <div class="form-group">
                       <label for="convenios"><?php echo 'Médicos'; ?></label>
                       <select onchange="procedimentos_table_reload()" class="selectpicker"
                               data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                               name="conta_financeira"
                               id="conta_financeira"
                               data-actions-box="true"
                               
                               data-width="100%"
                               data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                   <?php
                                   foreach ($medicos as $convenio) {
                                     
                                       ?>
                                     <option   value="<?php echo $convenio['medicoid']; ?>" <?php echo $selected; ?>><?php echo $convenio['nome_profissional']; ?></option>

                            <?php } ?>
                       </select>
                    </div>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12">
                  <label for="convenios"><?php echo _l('convenio'); ?></label>
                  <div class="form-group">
                   <select onchange="procedimentos_table_reload()" class="selectpicker"
                           data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                           name="convenios_producao_auditoria"
                           id="convenios_producao_auditoria"
                           data-actions-box="true"
                           multiple="true"
                           data-width="100%"
                           data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                               <?php
                               foreach ($convenios as $convenio) {
                                  $selected = ' selected';
                                   ?>
                                 <option  value="<?php echo $convenio['id']; ?>" <?php echo $selected; ?>><?php echo $convenio['name']; ?></option>

                        <?php } ?>
                   </select>
                   </div>   
              </div>
              
              <div class="col-md-4 col-sm-6 col-xs-12">
                  <label for="convenios"><?php echo 'Status'; ?></label>
                  <div class="form-group">
                   <select onchange="procedimentos_table_reload()" class="selectpicker"
                           data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                           name="status_producao_auditoria"
                           id="status_producao_auditoria"
                           data-actions-box="true"
                           data-width="100%"
                           data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                       
                            <option value="0" selected="true"> PENDENTE</option>
                            <option value="1" > AUTORIZADO</option>
                   </select>
                  </div>   
              </div>
              
              <!-- DATA DE -->
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="categorias"><?php echo 'Data de'; ?></label> <br>
                      <input onkeyup="procedimentos_table_reload()" type="date" name="data_de" data-width="100%" class="form-control" id="data_de">
                  </div>

              </div>  

              
              <div class="col-md-3">
                  <div class="form-group">

                      <label for="categorias"><?php echo 'Data Até'; ?></label> <br>
                      <input onkeyup="procedimentos_table_reload()" type="date" name="data_ate" data-width="100%" class="form-control" id="data_ate">
                    </div>

              </div> 
             <!-- <div class="col-md-4">
                  <div class="form-group">
                      <label for="categorias"><?php echo ''; ?></label> <br>
                      <button onclick="procedimentos_table_reload()" class="form-control btn btn-primary">Filtrar</button>
                  </div>
              </div>   --> 
              
            
                  <a href="#" data-toggle="modal" data-target="#customers_bulk_action" class="bulk-actions-btn table-btn hide" data-table=".table-repasse_producao_auditoria"><?php echo _l('bulk_actions'); ?></a>
                  <div class="modal fade bulk_actions" id="customers_bulk_action" tabindex="-1" role="dialog">
                     <div class="modal-dialog" role="document">
                        <div class="modal-content">
                           <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                           </div>
                           <div class="modal-body">
                              <?php if(has_permission('customers','','delete')){ ?>
                              <div class="checkbox checkbox-danger">
                                 <input type="checkbox" name="mass_repasse_caixa" id="mass_repasse_caixa" onclick="Mudarestado('minhaDiv')">
                                 <label for="mass_repasse_caixa"><?php echo 'Gerar Repasse (Caixa)'; ?></label>
                              </div>
                              <hr class="mass_delete_separator" />
                              <?php } ?>
                              
                              
                              <div class="col-md-12">
                                 <?php if(has_permission('tesouraria','','repasses')){ ?>   
                                <?php
                                $member = $this->staff_model->get(get_staff_user_id());
                                $caixa_atual = $member->caixa_id;

                                if($caixa_atual){

                                    $dados_caixa = $this->caixas_model->get_caixa_registro_atual($caixa_atual); 
                                    $nome_caixa = $dados_caixa->caixa;
                                    $caixa_id = $dados_caixa->id_caixa;
                                    $saldo_inicial = $dados_caixa->saldo;

                                    /********************************************************************
                                                D E S P E S A S    S A Í D A S
                                    ********************************************************************/
                                    $valor_despesa = 0;
                                    $dados_despesas = $this->caixas_model->get_despesas_caixa_registro_atual($caixa_atual); 
                                    $valor_despesa = $dados_despesas->valor;

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

                                      //  if($tipo_id == 1){
                                            $saldo_final += $valor_pgto_recebido;
                                      //  }
                                    }  


                                ?>
                                <!-- <a href="#" class="btn btn-primary "  onclick="Mudarestado('minhaDiv')"><?php echo 'Novo Repasse'; ?></a> -->

                                 <br><br><br>
                                 <div class="clearfix"></div>
                                 <div style="display: none" id="minhaDiv">
                                    <?php //echo form_open(admin_url('repasses/add_repasse/')); ?>
                                     <input type="hidden" required="true" value="<?php echo $caixa_atual; ?>" name="registro_id" id="registro_id" >
                                     <input type="hidden" required="true" value="<?php echo $caixa_id; ?>"      name="caixa_id" id="caixa_id" >
                                     <input type="hidden" name="saldo" id="saldo" value="<?php echo $saldo_final; ?>">
                                         <h3>
                                         <label class="btn btn-warning pull-right"><?php echo $operador; ?></label>
                                         <label class="btn btn-primary pull-right"><?php echo $nome_caixa; ?></label>
                                         <label class="btn btn-success pull-right"><?php echo 'saldo : '. app_format_money($saldo_final, 'R$'); ?></label>
                                         </h3>
                                     <hr class="hr-panel-heading" />
                                     <br><br>

                                     <label>SALDO EM CAIXA</label>
                                     <input type="text" readonly="true" value="<?php echo app_format_money($saldo_final, 'R$'); ?>"  class="form-control">
                                      <?php $hoje = date('Y-m-d'); ?>
                                     <br>
                                     <label>Data do Repasse</label>
                                     <input type="date" required="true" name="data_repasse" id="data_repasse" value="<?php echo $hoje; ?>" class="form-control">
                                     <br>
                                     <?php
                                     /*
                                     ?>
                                     <label for="medicos_pagamento_recebido"><?php echo 'Conta Financeira Destino'; ?></label>
                                     
                                     <select required="true" class="form-control" name="conta_id">
                                         <option value="">SELECIONE UMA CONTA PARA RECEBER O REPASSE</option>
                                       <?php
                                       foreach ($contas_financeiras as $medico) {
                                          $propria = $medico['nota_fiscal_propria'];
                                           ?>
                                         <option  value="<?php echo $medico['id']; ?>" <?php echo $selected; ?>><?php echo $medico['nome']; ?></option>
                                        <?php } ?>
                                   </select>
                                     <br>
                                     <?php
                                     */
                                    $amount = '0.00';
                                     //  echo render_input('valor','valor_repasse',$amount,'text',array('max'=>$saldo_final)); ?> 
                                    <!--<label>Valor </label>
                                    <input type="text" name="valor" id="valor" onKeyPress="return(moeda(this,'.',',',event))" maxlength="7" value="<?php echo $amount; ?>"  class="form-control"> -->



                                    <?php echo render_select('forma_id',$payment_modes,array('id','name'),'payment_mode'); ?>
                                    <?php echo render_textarea( 'observacao', 'observacao', '',array( 'rows'=>5)); ?>
                                    
                                    
                                   <!-- <button class="btn btn-info pull-right mbot15">
                                        <?php echo _l( 'confirm'); ?>
                                    </button> -->
                                    <?php //echo form_close(); ?>
                                </div>

                                 <?php
                                }
                               } ?>
                               </div>
                              
                           </div>
                           <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                              <a href="#" class="btn btn-info" onclick="customers_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                           </div>
                        </div>
                        <!-- /.modal-content -->
                     </div>
                     <!-- /.modal-dialog -->
                  </div>

            <?php
            $table_data = [];

            if(has_permission('items','','delete')) {
              //$table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
                $table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="repasse_producao_auditoria"><label></label></div>';
            }

            $table_data = array_merge($table_data, array(
              'Profissional',
              'Data',    
              'Paciente',
              'Convênio',  
              'Procedimento',  
              'Valor Repasse',
              'Repasse',
              
            ));

            $cf = get_custom_fields('items');
            foreach($cf as $custom_field) {
              array_push($table_data,$custom_field['name']);
            }
            render_datatable($table_data,'repasse_producao_auditoria'); ?>
          </div>
        </div>
      </div>
    </div>
</div>


<?php init_tail(); ?>
<script>
    
  $(function(){
  
    var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
      
       CustomersServerParams['data_de'] = '[name="data_de"]';
       CustomersServerParams['data_ate'] = '[name="data_ate"]';  
       CustomersServerParams['conta_financeira'] = '[name="conta_financeira"]';
       CustomersServerParams['convenios_producao_auditoria'] = '[name="convenios_producao_auditoria"]';
       CustomersServerParams['status_producao_auditoria'] = '[name="status_producao_auditoria"]';   
         
     //  CustomersServerParams['exclude_inactive'] = '[name="exclude_inactive"]:checked';
       var tAPI = initDataTable('.table-repasse_producao_auditoria', admin_url+'Producao_auditoria/table_repasse', [0], [0], CustomersServerParams, [1, 'desc']);
        $('select[name="convenios_procedimentos"]').on('change',function(){
          // alert('aqui');
           tAPI.ajax.reload();
          
         // procedimentos_table_reload();
       });
        //filtraCategoria();
  

    if(get_url_param('groups_modal')){
       // Set time out user to see the message
       setTimeout(function(){
         $('#groups').modal('show');
       },1000);
     }

     $('#new-item-group-insert').on('click',function(){
      var group_name = $('#item_group_name').val();
      if(group_name != ''){
        $.post(admin_url+'invoice_items/add_group',{name:group_name}).done(function(){
         window.location.href = admin_url+'invoice_items?groups_modal=true';
       });
      }
    });

     $('body').on('click','.edit-item-group',function(e){
      e.preventDefault();
      var tr = $(this).parents('tr'),
      group_id = tr.attr('data-group-row-id');
      tr.find('.group_name_plain_text').toggleClass('hide');
      tr.find('.group_edit').toggleClass('hide');
      tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
    });

     $('body').on('click','.update-item-group',function(){
      var tr = $(this).parents('tr');
      var group_id = tr.attr('data-group-row-id');
      name = tr.find('.group_edit input').val();
      if(name != ''){
        $.post(admin_url+'invoice_items/update_group/'+group_id,{name:name}).done(function(){
         window.location.href = admin_url+'invoice_items';
       });
      }
    });
   });
   
   function procedimentos_table_reload() {
      //alert('aqui');
       var CustomersServerParams = {};
     //  $.each($('._hidden_inputs._filters input'),function(){
     //     CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
     // });
       CustomersServerParams['data_de'] = '[name="data_de"]';
       CustomersServerParams['data_ate'] = '[name="data_ate"]'; 
       CustomersServerParams['conta_financeira'] = '[name="conta_financeira"]';  
       CustomersServerParams['convenios_producao_auditoria'] = '[name="convenios_producao_auditoria"]'; 
       CustomersServerParams['status_producao_auditoria'] = '[name="status_producao_auditoria"]'; 
       
       
     if ($.fn.DataTable.isDataTable('.table-repasse_producao_auditoria')) {
       $('.table-repasse_producao_auditoria').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-repasse_producao_auditoria', admin_url+'producao_auditoria/table_repasse', [0], [0], CustomersServerParams, [1, 'desc']);
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
   
  function customers_bulk_action(event) {
       var r = confirm(app.lang.confirm_action_prompt);
       if (r == false) {
           return false;
       } else {
           var mass_repasse_caixa = $('#mass_repasse_caixa').prop('checked');
           var ids = [];
           var data = {};
               data.mass_repasse_caixa = true;
               // regitro caixa
               if(document.getElementById('registro_id').value == ''){
                alert('É preciso ter um caixa aberto para esta ação.'); exit;   
               }else{
               data.registro_id = document.getElementById('registro_id').value; 
               }
               // caixa
               if(document.getElementById('caixa_id').value == ''){
                alert('É preciso ter um caixa aberto para esta ação.'); exit;   
               }else{
                data.caixa_id = document.getElementById('caixa_id').value;
               }
               // medico
               if(document.getElementById('conta_financeira').value == ''){
               alert('Selecione o Médico'); exit;
               }else{
               data.medico_id = document.getElementById('conta_financeira').value;
               }
               // valor
            //   if(document.getElementById('valor').value == ''){
            //   alert('Informe o valor do repasse'); exit;
            //   }else{
            //   data.valor = document.getElementById('valor').value;
            //  }
               // data repasse
               if(document.getElementById('data_repasse').value == ''){
               alert('Informe a data do repasse'); exit;
               }else{
               data.data_repasse = document.getElementById('data_repasse').value;
               }
               // data repasse
               if(document.getElementById('forma_id').value == ''){
               alert('Informe a forma de pagamento do repasse'); exit;
               }else{
               data.forma_id = document.getElementById('forma_id').value;
               }
               
               data.observacao = document.getElementById('observacao').value;
               data.saldo = document.getElementById('saldo').value;
               
           
           var cont_ids = 0;
           var rows = $('.table-repasse_producao_auditoria').find('tbody tr');
           $.each(rows, function() {
               var checkbox = $($(this).find('td').eq(0)).find('input');
               if (checkbox.prop('checked') == true) {
                   ids.push(checkbox.val());
                   cont_ids++;
               }
           });
           
              if(cont_ids == 0){
               alert('Selecione os procedimentos que irá fazer o repasse'); exit;
              }else{
               data.ids = ids;
              }
           
           $(event).addClass('disabled');
           setTimeout(function(){
             $.post(admin_url + 'producao_auditoria/bulk_action_repasse', data).done(function() {
            window.location.reload();
          });
         },50);
       }
   }
  
  
 </script>
</body>
</html>
