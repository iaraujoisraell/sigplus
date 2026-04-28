<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         
         <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'expense-form','class'=>'dropzone dropzone-manual')) ;?>
          <input type="hidden" name="caixa_id" value="<?php echo $caixa_id; ?>">
          <?php if ($registro_id){ ?>
          <input type="hidden" name="registro_id" value="<?php echo $registro_id; ?>">
          <?php } ?> 
         <div class="col-md-6">
            <div class="panel_s">
               <div class="panel-body">
                
                  <h4 class="no-margin"><?php echo $title; ?></h4>
                  <hr class="hr-panel-heading" />
                  <h1>Deseja Abrir o Caixa?</h1>
                  <?php
                    $amount = '0.00';
                    $totalAllowed = 0;
                    echo render_input('entrada_caixa','entrada_caixa',$amount,'number'); ?>
                  <?php $rel_id = (isset($expense) ? $expense->expenseid : false); ?>
                  <?php echo render_custom_fields('expenses',$rel_id); ?>
                  <div class="btn-bottom text-right">
                     <button type="submit" class="btn btn-success"><?php echo 'Abrir Caixa'; ?></button>
                  </div>
               </div>
            </div>
         </div>
        
         <?php echo form_close(); ?>
      </div>
      <div class="btn-bottom-pusher"></div>
   </div>
</div>

<?php init_tail(); ?>
<script>
   var customer_currency = '';
   Dropzone.options.expenseForm = false;
   var expenseDropzone;
   init_ajax_project_search_by_customer_id();
   var selectCurrency = $('select[name="currency"]');
   <?php if(isset($customer_currency)){ ?>
     var customer_currency = '<?php echo $customer_currency; ?>';
   <?php } ?>
     $(function(){
        $('body').on('change','#project_id', function(){
          var project_id = $(this).val();
          if(project_id != '') {
           if (customer_currency != 0) {
             selectCurrency.val(customer_currency);
             selectCurrency.selectpicker('refresh');
           } else {
             set_base_currency();
           }
         } else {
          do_billable_checkbox();
        }
      });

 

     appValidateForm($('#expense-form'),{
 
      repeat_every_custom: { min: 1},
    },expenseSubmitHandler);

     $('input[name="billable"]').on('change',function(){
       do_billable_checkbox();
     });

  

     // hide invoice recurring options on page load
     $('#repeat_every').trigger('change');

      $('select[name="clientid"]').on('change',function(){
       customer_init();
       do_billable_checkbox();
       $('input[name="billable"]').trigger('change');
     });

    });

</script>
</body>
</html>
