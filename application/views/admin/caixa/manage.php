<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s mbot10">
               <div class="panel-body _buttons">
                  <?php
                  if(has_permission('tesouraria','','create')){ ?>
                  <a href="" onclick="new_category();return false;" class="btn btn-info"><?php echo _l('new_caixa'); ?></a>
                  <?php } ?>
                   <a href="#" class="btn btn-default pull-right btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view('.table-expenses','#expense'); return false;" data-toggle="tooltip" title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
                  <div id="stats-top" class="hide">
                     <hr />
                     <div id="expenses_total"></div>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12" id="small-table">
                  <div class="panel_s">
                     <div class="panel-body">
                        <div class="clearfix"></div>
                        <!-- if expenseid found in url -->
                        <?php echo form_hidden('expenseid',$expenseid); ?>
                        <?php $this->load->view('admin/caixa/table_html'); ?>
                     </div>
                  </div>
               </div>
               <div class="col-md-7 small-table-right-col">
                 
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- /.modal -->
<?php $this->load->view('admin/caixa/novo_caixa'); ?>
<script>var hidden_columns = [4,5,6,7,8,9];</script>
<?php init_tail(); ?>
<script>
   Dropzone.autoDiscover = false;
   $(function(){
             // Expenses additional server params
             var Expenses_ServerParams = {};
             $.each($('._hidden_inputs._filters input'),function(){
               Expenses_ServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
             });
             initDataTable('.table-expenses', admin_url+'caixas/table', 'undefined', 'undefined', Expenses_ServerParams, <?php echo hooks()->apply_filters('expenses_table_default_order', json_encode(array(5,'desc'))); ?>).column(0).visible(false, false).columns.adjust();

             init_caixa();

             $('#expense_convert_helper_modal').on('show.bs.modal',function(){
                var emptyNote = $('#tab_expense').attr('data-empty-note');
                var emptyName = $('#tab_expense').attr('data-empty-name');
                if(emptyNote == '1' && emptyName == '1') {
                    $('#inc_field_wrapper').addClass('hide');
                } else {
                    $('#inc_field_wrapper').removeClass('hide');
                    emptyNote === '1' && $('.inc_note').addClass('hide') || $('.inc_note').removeClass('hide')
                    emptyName === '1' && $('.inc_name').addClass('hide') || $('.inc_name').removeClass('hide')
                }
             });

          
           });
           
           
</script>
</body>
</html>
