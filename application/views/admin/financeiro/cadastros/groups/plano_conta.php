<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="customer-profile-group-heading"><?php echo _l('plano_contas'); ?></h4>
<div class="col-md-12">

 <div class="clearfix"></div>

 </div>
<div class="clearfix"></div>
<div class="mtop15">
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
            <a href="#" class="btn btn-info pull-left"  data-toggle="modal" data-target="#plano_conta_modal" data-id=""><?php echo 'Add Plano de Conta'; ?></a>   
            <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#categorias_modal"><?php echo 'Categorias'; ?></a>
           
          </div>
          <div class="clearfix"></div>
          <hr class="hr-panel-heading" />
        <?php } ?>
          <!-- CONVENIO -->

          <!-- NATUREZA -->
          <div class="col-md-4">

                   <label for="categorias"><?php echo 'Natureza'; ?></label>

                    <select onchange="procedimentos_table_reload()" class="selectpicker"
                       data-toggle="<?php echo $this->input->get('allowed_payment_modes'); ?>"
                       name="natureza"
                       id="natureza"
                       data-actions-box="true"

                       data-width="100%"
                       data-title="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <option  value="1" selected="true">DESPESAS</option>
                        <option  value="2" >RECEITAS</option>
                </select>

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

        <?php
        $table_data = [];

        if(has_permission('items','','delete')) {
          //$table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
            $table_data[] = '<span class="hide"> - </span><label>#</label>';
        }

        $table_data = array_merge($table_data, array(
          'Categoria',
          'Plano Conta',
          'Status',
          'Natureza'
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


<?php $this->load->view('admin/financeiro/cadastros/groups/novo_plano_conta'); ?>

<!-- lista de categorias  -->
<div class="modal fade" id="categorias_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">
              <?php echo 'Categorias'; ?>
            </h4>
          </div>
          <div class="modal-body">
            <?php if(has_permission('items','','create')){ ?>
              <div class="input-group">
                <input type="text" name="categoria_name" id="categoria_name" class="form-control" placeholder="<?php echo 'Categoria'; ?>">
                <span class="input-group-btn">
                  <button class="btn btn-info p7" type="button" id="new-categoria-insert"><?php echo 'Add Categoria'; ?></button>
                </span>
              </div>
              <hr />
            <?php } ?>



            <div class="row">
             <div class="container-fluid">

              <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
                <thead>
                  <tr>
                    <th><?php echo _l('id'); ?></th>
                    <th><?php echo 'Nome da Categoria'; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($categorias as $group){ ?>
                    <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                      <td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
                      <td data-order="<?php echo $group['name']; ?>">
                        <span class="group_name_plain_text"><?php echo $group['name']; ?></span>
                        <div class="group_edit hide">
                         <div class="input-group">
                          <input type="text" class="form-control">
                          <span class="input-group-btn">
                            <button class="btn btn-info p8 update-item-group" type="button"><?php echo _l('submit'); ?></button>
                          </span>
                        </div>
                      </div>
                      <div class="row-options">
                        <?php if(has_permission('items','','edit')){ ?>
                          <a href="#" class="edit-item-group">
                            <?php echo _l('edit'); ?>
                          </a>
                        <?php } ?>
                        <?php if(has_permission('items','','delete')){ ?>
                          | <a href="<?php echo admin_url('financeiro/delete_categoria/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
                            <?php echo _l('delete'); ?>
                          </a>
                        <?php } ?>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
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
       CustomersServerParams['categorias'] = '[name="categorias"]';
       CustomersServerParams['natureza'] = '[name="natureza"]'; 
       var tAPI = initDataTable('.table-invoice-items', admin_url+'financeiro/table_plano_conta', [0], [0], CustomersServerParams, [1, 'desc']);
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
       CustomersServerParams['natureza'] = '[name="natureza"]'; 
     if ($.fn.DataTable.isDataTable('.table-invoice-items')) {
       $('.table-invoice-items').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-invoice-items', admin_url+'financeiro/table_plano_conta', [0], [0], CustomersServerParams, [1, 'desc']);
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
<?php //$this->load->view('admin/medicos/client_js'); Sigplus.2022 ?>
</body>
</html>
