<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
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
     <?php if(has_permission('items','','create')){ ?>
       <div class="_buttons">
           <a href="<?php echo admin_url('invoice_items/add_procedimento_medicos'); ?>"  class="btn btn-info pull-left mleft5" ><?php echo 'Add Procedimento/Repasse'; ?></a>
 
      </div>
      <div class="clearfix"></div>
      <hr class="hr-panel-heading" />
    <?php } ?>
      <!-- CONVENIO -->
      <div class="col-md-6">
          <div class="form-group">
               <label for="convenios"><?php echo _l('convenio'); ?></label>
               <select onchange="procedimentos_table_reload()" class="selectpicker"
                       data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                       name="convenios_procedimentos_repasse"
                       id="convenios_procedimentos_repasse"
                       data-actions-box="true"
                       multiple="true"
                       data-width="100%"
                       data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           <?php
                           foreach ($convenios as $convenio) {
                              $selected = ' selected';
                               ?>
                             <option   value="<?php echo $convenio['id']; ?>" <?php echo $selected; ?>><?php echo $convenio['name']; ?></option>

                    <?php } ?>
               </select>
            </div>
      </div>
      <!-- CATEGORIA -->
      <div class="col-md-6">
          <div class="form-group">
               <label for="categorias"><?php echo _l('expense_dt_table_heading_category'); ?></label>
               
                <select onchange="procedimentos_table_reload()" class="selectpicker"
                   data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                   name="categorias_procedimentos"
                   id="categorias_procedimentos"
                   data-actions-box="true"
                   multiple="true"
                   data-width="100%"
                   data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                       <?php

                       foreach ($categorias as $categoria) {
                          $selected = ' selected';
                           ?>
                         <option  value="<?php echo $categoria['id']; ?>" <?php echo $selected; ?>><?php echo $categoria['name']; ?></option>

                <?php } ?>
            </select>
          </div>
      </div>  

      <div class="col-md-6">
          <div class="form-group">
               <?php
                  $selected = '';

                  echo render_select('procedimento_items', $procedimentos, array('itemid', array('description','group_name')), 'procedimentos', $selected,array('multiple'=>'true', 'onchange'=>'procedimentos_table_reload()'));
                  ?>
            </div>
      </div>
      
      <div class="col-md-6">
          <div class="form-group">
                <?php
                    $selected = '';
                    foreach($medicos as $medico){
                     if(isset($invoice)){
                       if($invoice->medicoid == $medico['medicoid']) {
                         $selected = $medico['medicoid'];
                       }
                     }
                    }
                    echo render_select('medicoid',$medicos,array('medicoid',array('nome_profissional',  'especialidade')),'medico',$selected,array('multiple'=>'true', 'onchange'=>'procedimentos_table_reload()') );
                ?>
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
      'Código ID',    
      'Procedimento',
      'Convênio',    
      'Profissional',   
      'Data Início',
      'Data Fim',
      'Vl Procedimento',
      'Vl Repasse'
    
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
</div>
<?php //$this->load->view('admin/invoice_items/item'); ?>


<?php init_tail(); ?>
<script>
    
  $(function(){
  
    var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
      
       CustomersServerParams['procedimento_items']              = '[name="procedimento_items"]';
       CustomersServerParams['convenios_procedimentos_repasse'] = '[name="convenios_procedimentos_repasse"]'; 
       CustomersServerParams['categorias_procedimentos']        = '[name="categorias_procedimentos"]';
       CustomersServerParams['medicoid']                        = '[name="medicoid"]';
     //  CustomersServerParams['exclude_inactive'] = '[name="exclude_inactive"]:checked';
       var tAPI = initDataTable('.table-invoice-items', admin_url+'tabela_preco_repasse/table', [0], [0], CustomersServerParams, [1, 'desc']);
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
       CustomersServerParams['procedimento_items']              = '[name="procedimento_items"]';
       CustomersServerParams['categorias_procedimentos']        = '[name="categorias_procedimentos"]';  
       CustomersServerParams['convenios_procedimentos_repasse'] = '[name="convenios_procedimentos_repasse"]';  
       CustomersServerParams['medicoid']                        = '[name="medicoid"]';
     if ($.fn.DataTable.isDataTable('.table-invoice-items')) {
       $('.table-invoice-items').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-invoice-items', admin_url+'tabela_preco_repasse/table', [0], [0], CustomersServerParams, [1, 'desc']);
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
