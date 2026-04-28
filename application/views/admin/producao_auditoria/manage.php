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

         <!--   <div class="col-md-12">
                <div class="col-md-6">
                 <a href="#" class="btn btn-primary "  onclick="Mudarestado('minhaDiv')"><?php echo 'Novo Cadastro'; ?></a>
                 <div style="display: none" id="minhaDiv">
                    <?php echo form_open(admin_url('producao_auditoria/add_repasse_producao/')); ?>
                     <hr class="hr-panel-heading" />
                     <label>Data do Aendimento</label>
                     <?php $hoje = date('Y-m-d'); ?>
                     <input type="date" required="true" name="data_repasse" value="<?php echo $hoje; ?>" class="form-control">
                     <br>
                     <label for="medicos_pagamento_recebido"><?php echo 'Médico'; ?></label>
                    
                     <select required="true" class="form-control" name="medico_id">
                         <option value="">Selecione</option>
                       <?php
                       foreach ($medicos as $medico) {
                       ?>
                         <option  value="<?php echo $medico['medicoid']; ?>" ><?php echo $medico['nome_profissional']; ?></option>
                        <?php } ?>
                   </select>
                     <br>

                     <div class="form-group appointment_type_holder">
                     
                        <label for="appointment_hours">Procedimentos</label>
                        <div class="items-select-wrapper ">
                            <select required="true" id="procedimentos" name="procedimentos" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?>" data-width="false" id="item_select" data-live-search="true">
                             <option  value="" >Selecione</option>   
                          <?php  foreach($procedimentos as $group_id=>$_items){ ?>
                          <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?><?php echo ' ( '. $_items[0]['convenio'].' )'; ?>">
                           <?php foreach($_items as $item){ ?>
                           <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'],0,200)).'...'; ?>"> <?php echo $item['id'].' - ' ?> (<?php echo app_format_number($item['rate']); ; ?>) <?php echo $item['description']; ?></option>
                           <?php } ?>
                         </optgroup>
                         <?php } ?>
                       </select>
                        </div>
                      
                    </div>

                     <br> <br>
                    <label>Valor Cobrado </label>
                    <input type="text" name="valor_cobrado" id="valor_cobrado" required="true" onKeyPress="return(moeda(this,'.',',',event))" maxlength="7" class="form-control">
                     <br>
                    <label>Valor de Repasse </label>
                    <input type="text" name="valor_repasse" id="valor_repasse" required="true" onKeyPress="return(moeda(this,'.',',',event))" maxlength="7" class="form-control">

                    <br>

                    <button class="btn btn-info pull-right mbot15">
                        <?php echo _l( 'submit'); ?>
                    </button>
                    <?php echo form_close(); ?>
                </div>
                 <br><br><br>
                 <div class="clearfix"></div>
                 </div>
             </div> -->
            
            <div class="panel_s">
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
                           <option selected="true" value="">Selecione</option>
                                   <?php
                                   foreach ($medicos as $convenio) {
                                      $selected = ' selected';
                                       ?>
                                     <option   value="<?php echo $convenio['medicoid']; ?>"><?php echo $convenio['nome_profissional']; ?></option>

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
              <?php $hoje = date('Y-m-d'); ?>
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="categorias"><?php echo 'Data '; ?></label> <br>
                      <input onkeyup="procedimentos_table_reload()" type="date" name="data_de" value="<?php echo $hoje; ?>" data-width="100%" class="form-control" id="data_de">
                  </div>

              </div>  

              <!-- DATA ATE 
              <div class="col-md-3">
                  <div class="form-group">

                      <label for="categorias"><?php echo 'Data Até'; ?></label> <br>
                      <input onkeypress="procedimentos_table_reload()" type="date" name="data_ate" data-width="100%" class="form-control" id="data_ate">
                    </div>

              </div> 
              <div class="col-md-4">
                  <div class="form-group">
                      <label for="categorias"><?php echo ''; ?></label> <br>
                      <button onclick="procedimentos_table_reload()" class="form-control btn btn-primary">Filtrar</button>
                  </div>
              </div>    -->
              </div> 
              <hr class="hr-panel-heading" />
                  <a href="#" data-toggle="modal" data-target="#customers_bulk_action" class="bulk-actions-btn table-btn hide" data-table=".table-producao_auditoria"><?php echo _l('bulk_actions'); ?></a>
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
                                 <input type="checkbox" name="mass_approved" id="mass_approved">
                                 <label for="mass_approved"><?php echo 'Aprovar selecionados'; ?></label>
                              </div>
                              <hr class="mass_delete_separator" />
                              <div class="checkbox checkbox-danger">
                                 <input type="checkbox" name="mass_deleted" id="mass_deleted">
                                 <label for="mass_deleted"><?php echo 'Deletar selecionados'; ?></label>
                              </div>
                              <?php } ?>
                              
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
                $table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="producao_auditoria"><label></label></div>';
            }

            $table_data = array_merge($table_data, array(
              'Profissional',
              'Data',    
              'Paciente',
              'Convênio',  
              'Procedimento',  
              'Valor Repasse',
              'Status'
            ));

            $cf = get_custom_fields('items');
            foreach($cf as $custom_field) {
              array_push($table_data,$custom_field['name']);
            }
            render_datatable($table_data,'producao_auditoria'); ?>
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
       var tAPI = initDataTable('.table-producao_auditoria', admin_url+'Producao_auditoria/table', [0], [0], CustomersServerParams, [1, 'desc']);
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
       
       
     if ($.fn.DataTable.isDataTable('.table-producao_auditoria')) {
       $('.table-producao_auditoria').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-producao_auditoria', admin_url+'producao_auditoria/table', [0], [0], CustomersServerParams, [1, 'desc']);
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
           var mass_approved = $('#mass_approved').prop('checked');
           var mass_deleted = $('#mass_deleted').prop('checked');
           var ids = [];
           var data = {};
           
           
           if(mass_approved == true && mass_deleted == true){
               alert('SELECIONE 1 AÇÃO POR VEZ'); exit;
            }    
            
           
           if(mass_approved == true){
               data.mass_approved = true;
           }else if(mass_deleted == true){
               data.mass_deleted = true;
            }    
           
           var cont_ids = 0;
           var rows = $('.table-producao_auditoria').find('tbody tr');
           $.each(rows, function() {
               var checkbox = $($(this).find('td').eq(0)).find('input');
               if (checkbox.prop('checked') == true) {
                   ids.push(checkbox.val());
                   cont_ids++;
               }
           });
           
           if(cont_ids == 0){
               alert('Selecione pelo menos 1 procedimentos'); exit;
              }else{
               data.ids = ids;
              }
           
           $(event).addClass('disabled');
           setTimeout(function(){
               
             $.post(admin_url + 'producao_auditoria/bulk_action', data).done(function() {
              window.location.reload();
          });
         },50);
       }
   }
  
  
 </script>
</body>
</html>
