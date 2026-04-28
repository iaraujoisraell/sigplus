<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			include_once(APPPATH.'views/admin/invoices/filter_params.php');
			$this->load->view('admin/invoices/list_template');
			?>
		</div>
	</div>
</div>
<?php $this->load->view('admin/includes/modals/sales_attach_file'); ?>
<script>var hidden_columns = [1,6,7,8];</script>
<?php init_tail(); ?>
<script>
	$(function(){
		init_invoice();
	});
        
     $(function(){
       var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
      // CustomersServerParams['exclude_inactive'] = '[name="exclude_inactive"]:checked';
      // CustomersServerParams['dependentes'] = '[name="dependentes"]:checked';
     //  CustomersServerParams['cnpj'] = '[name="cnpj"]:checked';

       var tAPI = initDataTable('.table-invoices', admin_url+'invoices/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(2,'asc'))); ?>);
      // $('input[name="exclude_inactive"]').on('change',function(){
       //    tAPI.ajax.reload();
      // }); 
       
      
   });
   
    function busca_fatura_reload() {
        
       // if($("#busca_fatura").val()){
          var CustomersServerParams = {};
             
          if ($.fn.DataTable.isDataTable('.table-invoices')) {
           $('.table-invoices').DataTable().destroy();
          }
           var tAPI = initDataTable('.table-invoices', admin_url+'invoices/table/0/'+$("#busca_fatura").val(), [0], [0], CustomersServerParams, [2, 'asc']);
      // }else{
       //  alert('Infome o número da fatura');  
       // }
      }
</script>
</body>
</html>
