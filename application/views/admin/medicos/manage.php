<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                     <?php if (has_permission('medico','','cadastro')) { ?>
                     <a href="<?php echo admin_url('medicos/medico'); ?>" class="btn btn-info mright5 test pull-left display-block">
                     <?php echo _l('new_medico'); ?></a>
                     <a href="<?php echo admin_url('medicos/import'); ?>" class="btn btn-info pull-left display-block mright5 hidden-xs">
                     <?php echo _l('import_medico'); ?></a>
                     <?php } ?>
                    
                    
                     
                  </div>
                  <br>
                  <hr class="hr-panel-heading" />
                  <div class="row mbot15">
                     <div class="col-md-12">
                        <h4 class="no-margin"><?php echo _l('medicos'); ?></h4>
                     </div>
                  </div>
                  
                  <div class="clearfix mtop20"></div>
                  <?php
                     $table_data = array();
                     $_table_data = array(
                       array(
                         'name'=>_l('the_number_sign'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-number')
                        ),
                         array(
                         'name'=>_l('medico'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-company')
                        ),
                         array(
                         'name'=>_l('cns_medico'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-primary-contact')
                        ),
                         array(
                         'name'=>_l('crm_medico'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-primary-contact-email')
                        ),
                        array(
                         'name'=>_l('cpf_medico'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-phone')
                        ),
                        
                        array(
                         'name'=>_l('celular_medico'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-groups')
                        ),
                        array(
                         'name'=>_l('email_medico'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-groups')
                        ),
                        array(
                         'name'=>_l('repasse_medico'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-active')
                        ), 
                        array(
                         'name'=>_l('customer_active'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-active')
                        ),
                         
                       
                      );
                     foreach($_table_data as $_t){
                      array_push($table_data,$_t);
                     }

                    
                     render_datatable($table_data,'clients',[],[
                           'data-last-order-identifier' => 'customers',
                           'data-default-order'         => get_table_last_order('customers'),
                     ]);
                     ?>
               </div>
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
      
       CustomersServerParams['busca_cpf'] = '[name="busca_cpf"]:checked';
       CustomersServerParams['exclude_inactive'] = '[name="exclude_inactive"]:checked';
       var tAPI = initDataTable('.table-clients', admin_url+'medicos/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(2,'asc'))); ?>);
       $('input[name="exclude_inactive"]').on('change',function(){
           tAPI.ajax.reload();
       });
       
       
       
   });
   
   
   
   
   function busca_CPF(){
       var CustomersServerParams = {};
       $.each($('.busca_cpf'),function(){
          CustomersServerParams[$(this).attr('busca_cpf')] = '[name="'+$(this).attr('busca_cpf')+'"]';
      });
      
      
       var tAPI = initDataTable('.table-clients', admin_url+'medicos/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(2,'asc'))); ?>);
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
