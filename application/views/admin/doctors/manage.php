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
        <div class="panel_s">
          <div class="panel-body">
             
            <?php if(has_permission('items','','delete')){ ?>
             <a href="#" data-toggle="modal" data-table=".table-invoice-items" data-target="#items_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>
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
                    <input type="checkbox" name="mass_delete" id="mass_delete">
                    <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                  </div>
                  <!-- <hr class="mass_delete_separator" /> -->
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
     <?php } ?>
     <?php hooks()->do_action('before_items_page_content'); ?>

      <!-- CONVENIO -->
      <div class="col-md-6">
          <div class="form-group">
               <label for="convenios"><?php echo _l('convenio'); ?></label>
               <select class="selectpicker"
                       data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                       onchange="procedimentos_table_reload()"
                       name="convenios"
                       id="convenios"
                       data-actions-box="true"
                       multiple="true"
                       data-width="100%"
                       data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <?php
                           foreach ($convenios as $convenio) {
                             
                               ?>
                             <option   value="<?php echo $convenio['convenio']; ?>" ><?php echo $convenio['convenio']; ?></option>

                    <?php } ?>
               </select>
            </div>
      </div>
      <!-- CATEGORIA -->
      <div class="col-md-6">
            <div class="form-group">
                   <label for="categorias"><?php echo 'Data Visita (ano)'; ?></label>
                   <select onchange="procedimentos_table_reload()" class="selectpicker"
                       
                       name="ano_visita"
                       id="ano_visita"
                       data-width="100%"
                       data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                       <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                           <?php

                           foreach ($anos_data as $ano) {
                               if($ano == date('Y')){
                                $selected = 'selected ';
                               }else{
                                   $selected = '';
                               }
                               ?>
                             <option  value="<?php echo $ano['ano_data_visita']; ?>" <?php echo $selected; ?>><?php echo $ano['ano_data_visita']; ?></option>

                    <?php } ?>
                </select>
                   
            </div>
      </div>  

      <div class="col-md-6">
          <div class="form-group">
               <label for="categorias"><?php echo 'Tipo Atendimentos'; ?></label>
               
                <select onchange="procedimentos_table_reload()" class="selectpicker"
                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                   
                   name="tipo_atendimento"
                   id="tipo_atendimento"
                   data-actions-box="true"
                   multiple="true"
                   data-width="100%"
                   data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                       <?php

                       foreach ($tipos_atendimento as $tipo) {
                          $selected = ' ';
                           ?>
                         <option  value="<?php echo $tipo['tipo_atendimento']; ?>" <?php echo $selected; ?>><?php echo $tipo['tipo_atendimento']; ?></option>

                <?php } ?>
            </select>
          </div>
          
      </div>
      
      <div class="col-md-6">
          <div class="form-group">
               <label for="categorias"><?php echo 'Médicos'; ?></label>
               
                <select onchange="procedimentos_table_reload()" class="selectpicker"
                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                   onchange="procedimentos_table_reload()"
                   name="medico"
                   id="medico"
                   data-actions-box="true"
                   multiple="true"
                   data-width="100%"
                   data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                       <?php

                       foreach ($medicos as $medico) {
                          $selected = '';
                           ?>
                         <option  value="<?php echo $medico['medico']; ?>" <?php echo $selected; ?>><?php echo $medico['medico']; ?></option>

                <?php } ?>
            </select>
          </div>
          

      </div>
      
    <?php
    $table_data = [];

    if(has_permission('items','','delete')) {
      //$table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
       // $table_data[] = '<span class="hide"> - </span><label>#</label>';
        // 'Categoria',  
    }

    $table_data = array_merge($table_data, array(
      'Cód. Paciente',    
      'Nome',
      'Idade',
      'Telefone',  
      'Convênio',  
      'Data da Visita',  
      'Médico',   
      'Tipo de Atendimento'
      
    
    ));

    $cf = get_custom_fields('items');
    foreach($cf as $custom_field) {
      array_push($table_data,$custom_field['name']);
    }
    render_datatable($table_data,'invoice-items'); ?>
  </div>
</div>
</div>
</div>
</div>

<?php //$this->load->view('admin/invoice_items/item'); ?>


<?php init_tail(); ?>
<script>
    
  $(function(){
  
    var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
      
       CustomersServerParams['convenios']           = '[name="convenios"]'; 
       CustomersServerParams['ano_visita']             = '[name="ano_visita"]';
       CustomersServerParams['data_ate']            = '[name="data_ate"]';
       CustomersServerParams['tipo_atendimento']    = '[name="tipo_atendimento"]';
       CustomersServerParams['medico']              = '[name="medico"]';
    
        var tAPI = initDataTable('.table-invoice-items', admin_url+'doctors/table', [0], [0], CustomersServerParams, [1, 'desc']);
        $('select[name="convenios_procedimentos_repasse"]').on('change',function(){
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
       CustomersServerParams['convenios']           = '[name="convenios"]'; 
       CustomersServerParams['ano_visita']             = '[name="ano_visita"]';
       CustomersServerParams['data_ate']            = '[name="data_ate"]';
       CustomersServerParams['tipo_atendimento']    = '[name="tipo_atendimento"]';
       CustomersServerParams['medico']              = '[name="medico"]';
     if ($.fn.DataTable.isDataTable('.table-invoice-items')) {
       $('.table-invoice-items').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-invoice-items', admin_url+'doctors/table', [0], [0], CustomersServerParams, [1, 'desc']);
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
   
  function items_bulk_action(event) {
    if (confirm_delete()) {
      var mass_delete = $('#mass_delete').prop('checked');
      var ids = [];
      var data = {};

      if(mass_delete == true) {
        data.mass_delete = true;
      }

      var rows = $('.table-invoice-items').find('tbody tr');
      $.each(rows, function() {
        var checkbox = $($(this).find('td').eq(0)).find('input');
        if (checkbox.prop('checked') === true) {
          ids.push(checkbox.val());
        }
      });
      data.ids = ids;
      $(event).addClass('disabled');
      setTimeout(function() {
        $.post(admin_url + 'invoice_items/bulk_action', data).done(function() {
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
