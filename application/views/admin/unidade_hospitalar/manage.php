<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_menu_admin(false); ?>
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
                Unidades Hospitalares
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Cadastros </a></li>
                <li class="active"><a href="<?php echo admin_url('Unidades_hospitalares'); ?>">Unidades Hospitalares</a></li>
              </ol>
            </section>
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                    <?php if(has_permission('unidade_hospitalar','','create')){ ?>  
                    <a href="<?php echo admin_url('unidades_hospitalares/unidades_hospitalares'); ?>" class="btn btn-info pull-left" ><?php echo 'Add Unidade Hospitalar'; ?></a>
                    <a href="<?php //echo admin_url('unidades_hospitalares/add_edit_unidades_hospitalares'); ?>" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#groups"><?php echo 'Setores'; ?></a>
                    <?php } ?>
                  </div>
                  <br>
                  <hr class="hr-panel-heading" />
              
                  <div class="clearfix mtop20"></div>
                  <?php
                    $table_data = [];
            
                    if(has_permission('unidade_hospitalar','','delete')) {
                      //$table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
                        $table_data[] = '<span class="hide"> - </span><label>#</label>';
                    }
        
                    $table_data = array_merge($table_data, array(
                      'Razão Social',
                      'Fantazia',    
                      'Contato',
                      _l('ativo')
                        ));

                    $cf = get_custom_fields('unidade_hospitalar');
                    foreach($cf as $custom_field) {
                      array_push($table_data,$custom_field['name']);
                    }
                    render_datatable($table_data,'unidade_hospitalar'); ?>
               </div>
            </div>
         </div>
      </div>
   </div>

<div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission('items','','create')){ ?>
          <div class="input-group">
            <input type="text" name="item_group_name" id="item_group_name" class="form-control" placeholder="<?php echo _l('item_group_name'); ?>">
            <span class="input-group-btn">
              <button class="btn btn-info p7" type="button" id="new-item-group-insert"><?php echo _l('new_item_group'); ?></button>
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
                <th><?php echo _l('item_group_name'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($setores_medicos as $group){ ?>
                <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                  <td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
                  <td data-order="<?php echo $group['nome']; ?>">
                      
                    <span class="group_name_plain_text"><?php echo $group['nome']; ?></span>
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
                      | <a href="<?php echo admin_url('unidades_hospitalares/delete_setor/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
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
      
       CustomersServerParams['busca_cpf'] = '[name="busca_cpf"]:checked';
       CustomersServerParams['exclude_inactive'] = '[name="exclude_inactive"]:checked';
       var tAPI = initDataTable('.table-unidade_hospitalar', admin_url+'unidades_hospitalares/table', [0], [0], CustomersServerParams, [1, 'desc']);
       $('input[name="exclude_inactive"]').on('change',function(){
           tAPI.ajax.reload();
       });
       
       
       
   });
   
   
   
   
   function busca_CPF(){
       var CustomersServerParams = {};
       $.each($('.busca_cpf'),function(){
          CustomersServerParams[$(this).attr('busca_cpf')] = '[name="'+$(this).attr('busca_cpf')+'"]';
      });
      
      
       var tAPI = initDataTable('.table-unidade_hospitalar', admin_url+'unidades_hospitalares/table', [0], [0], CustomersServerParams, [1, 'desc']);
       $('input[name="exclude_inactive"]').on('change',function(){
           tAPI.ajax.reload();
       });
    
    
   }    
   
   function customers_bulk_action(event) {
       var r = confirm(app.lang.confirm_action_prompt);
       if (r == false) {
           return false;
       } else {
           var mass_delete = $('#mass_delete').prop('checked');
           var ids = [];
           var data = {};
           if(mass_delete == false || typeof(mass_delete) == 'undefined'){
               data.groups = $('select[name="move_to_groups_customers_bulk[]"]').selectpicker('val');
               if (data.groups.length == 0) {
                   data.groups = 'remove_all';
               }
           } else {
               data.mass_delete = true;
           }
           var rows = $('.table-clients').find('tbody tr');
           $.each(rows, function() {
               var checkbox = $($(this).find('td').eq(0)).find('input');
               if (checkbox.prop('checked') == true) {
                   ids.push(checkbox.val());
               }
           });
           data.ids = ids;
           $(event).addClass('disabled');
           setTimeout(function(){
             $.post(admin_url + 'medicos/bulk_action', data).done(function() {
              window.location.reload();
          });
         },50);
       }
   }
</script>
</body>
</html>
