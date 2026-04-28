<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<h4 class="customer-profile-group-heading"><?php echo _l('procedimento_repasse_medico'); ?></h4>

<div class="col-md-12">

 <div class="clearfix"></div>

 </div>
<div class="clearfix"></div>
<div class="mtop15">
   <div class="row">
       <div class="col-md-12">

         <a href="#" class="btn btn-success mtop15 mbot10" onclick="slideToggle('.userpalavra'); return false;"><?php echo _l('new_repasse_medico'); ?></a>
         <div class="clearfix"></div>
        <div class="row">
             <hr class="hr-panel-heading" />
        </div>
         <div class="clearfix"></div>
         <div class="userpalavra hide">
            <?php echo form_open(admin_url( 'misc/add_repasse')); ?>
             <input type="hidden" name="medicoid" value="<?php echo $medico->medicoid; ?>">
            <div class="panel-body mtop10">
            <div class="row">
                <div class="col-md-6">
                    <label>Procedimentos</label>
                    <div style="width: 100%" class="items-wrapper select-placeholder<?php if(has_permission('items','','create')){ echo ' input-group-select'; } ?>">
                        <div class="items-select-wrapper">
                            <select required="true" style="width: 100%" name="item_id" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?><?php if(has_permission('items','','create')){ echo ' _select_input_group'; } ?>" data-width="false" id="item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
                              <option value=""></option>
                              <?php foreach($items as $group_id=>$_items){ ?>
                              <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?>">
                               <?php foreach($_items as $item){ ?>
                               <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'],0,200)).'...'; ?>">(<?php echo app_format_number($item['rate']); ; ?>) <?php echo $item['description']; ?></option>
                               <?php } ?>
                             </optgroup>
                             <?php } ?>
                           </select>
                         </div>
                    </div>        
                </div>

                <div class="col-md-3">
                     <?php $attrs = array('required'=>true); ?>
                     <?php echo render_input( 'valor', 'valor_repasse','','text',$attrs); ?>
                </div>     

                <div class="col-md-3">
                 <div class="form-group select-placeholder">
                    <label  class="control-label"><?php echo _l('tipo_repasse_medico'); ?></label>
                    <select name="tipo" class="selectpicker" data-width="100%" >
                       <option value="1" selected> <?php echo 'R$ '; ?> </option>
                       <option value="2"><?php echo '% '; ?></option>

                    </select>
                 </div>
                </div>



            </div> 
              <button class="btn btn-info pull-right mbot15">
                <?php echo _l( 'submit'); ?>
            </button>  
            </div>


            <?php echo form_close(); ?>
        </div>
         </div>
       
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
             
                <?php if(has_permission('items','','delete')){ ?>
                 <a href="#" data-toggle="modal" data-table=".table-medico-procedimento" data-target="#items_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>
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
            <!-- <a href="#" class="btn btn-info pull-left"  data-toggle="modal" data-target="#plano_conta_modal" data-id=""><?php echo 'Add Plano de Conta'; ?></a>   
            <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#categorias_modal"><?php echo 'Categorias'; ?></a>
           -->
          </div>
          <div class="clearfix"></div>
          <hr class="hr-panel-heading" />
        <?php } ?>
          <!-- MEDICO -->
          <input type="hidden" name="medicoid" id="medicoid" value="<?php echo $medicoid; ?>">
          
          <div class="col-md-4">
          <div class="form-group">
              <?php
                      $selected = '';

                      echo render_select('convenios_procedimentos', $convenios, array('id', array('name')), 'Convênios', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
                      ?>
               
               
            </div>
      </div>
          

          <!-- CATEGORIA -->
          <div class="col-md-4">
              <div class="form-group">

                   <?php
                      $selected = '';

                      echo render_select('categorias', $categorias, array('id', array('name')), 'categorias', $selected,array('multiple'=>'true','onchange'=>'procedimentos_table_reload()'));
                      ?>
                </div>

          </div>  
          
          <div class="clearfix"></div>
          <hr class="hr-panel-heading" />
          <br><br>

        <?php
        $table_data = [];

       // if(has_permission('items','','delete')) {
          //$table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="medico-procedimento"><label></label></div>';
            $table_data[] = '<span class="hide"> - </span><label>#</label>';
       // }

        $table_data = array_merge($table_data, array(
         
          'Convênio',  
          'Categoria',
          'Procedimento',
          'Vl Procedimento',   
          'Vl Repasse',  
          'Tipo',
          'Repasse R$',  
          'Status'
           ));

        $cf = get_custom_fields('items');
        foreach($cf as $custom_field) {
          array_push($table_data,$custom_field['name']);
        }
        render_datatable($table_data,'medico-procedimento'); ?>
      </div>
        </div>
      </div>
    </div>
</div>


<?php $this->load->view('admin/financeiro/cadastros/groups/novo_plano_conta'); ?>



<?php init_tail(); ?>
<script>

  $(function(){

    var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
       CustomersServerParams['categorias'] = '[name="categorias"]';
       CustomersServerParams['convenios_procedimentos'] = '[name="convenios_procedimentos"]';
       CustomersServerParams['medicoid'] = '[name="medicoid"]';
       var tAPI = initDataTable('.table-medico-procedimento', admin_url+'medicos/table_medico_procedimento', [0], [0], CustomersServerParams, [1, 'desc']);
        $('select[name="categorias"]').on('change',function(){
          // alert('aqui');
           tAPI.ajax.reload();

         // procedimentos_table_reload();
       });
        //filtraCategoria();


    if(get_url_param('groups_modal')){
       // Set time out user to see the message
       setTimeout(function(){
         $('categorias_modal').modal('show');
       },1000);
     }

     $('#new-categoria-insert').on('click',function(){
      var group_name = $('#categoria_name').val();
      if(group_name != ''){
        $.post(admin_url+'financeiro/add_categoria',{name:group_name}).done(function(){
         window.location.href = admin_url+'financeiro/cadastros/?groups_modal=true';
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
        $.post(admin_url+'financeiro/update_categoria/'+group_id,{name:name}).done(function(){
         window.location.href = admin_url+'financeiro/cadastros';
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
       CustomersServerParams['categorias'] = '[name="categorias"]';
       CustomersServerParams['convenios_procedimentos'] = '[name="convenios_procedimentos"]'; 
       CustomersServerParams['medicoid'] = '[name="medicoid"]';
     if ($.fn.DataTable.isDataTable('.table-medico-procedimento')) {
       $('.table-medico-procedimento').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-medico-procedimento', admin_url+'medicos/table_medico_procedimento', [0], [0], CustomersServerParams, [1, 'desc']);
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

      var rows = $('.table-medico-procedimento').find('tbody tr');
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
<?php //$this->load->view('admin/medicos/client_js'); Sigplus.2022 ?>
</body>
</html>

 