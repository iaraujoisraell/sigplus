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
                    <?php
                    include_once(APPPATH.'views/admin/financeiro/contas_receber/filter_params.php');
                    $this->load->view('admin/financeiro/contas_receber/list_template');
                    ?>
            </div>
    </div>

<?php $this->load->view('admin/includes/modals/sales_attach_file'); ?>
<?php $this->load->view('admin/financeiro/contas_receber/novo_pagamento'); ?>



<script>var hidden_columns = [1,2,6,7,8];</script>
<?php init_tail(); ?>
<script>
	$(function(){
               // init_invoice2();
                init_titular_receber();
	});
</script>
<script>

  $(function(){

    var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
       CustomersServerParams['competencia']     = '[name="competencia"]';
       CustomersServerParams['clientes']        = '[name="clientes"]'; 
       CustomersServerParams['situacao']        = '[name="situacao"]'; 
       CustomersServerParams['vencimento_de']   = '[name="vencimento_de"]'; 
       CustomersServerParams['vencimento_ate']  = '[name="vencimento_ate"]'; 
       CustomersServerParams['envio_de']        = '[name="envio_de"]'; 
       CustomersServerParams['envio_ate']       = '[name="envio_ate"]'; 
       
       
       var tAPI = initDataTable('.table-invoices_receber', admin_url+'financeiro_invoices/table', [0], [0], CustomersServerParams, [1, 'desc']);
        $('select[name="competencia"]').on('change',function(){
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
       CustomersServerParams['competencia']     = '[name="competencia"]';
       CustomersServerParams['clientes']        = '[name="clientes"]'; 
       CustomersServerParams['situacao']        = '[name="situacao"]'; 
       CustomersServerParams['vencimento_de']   = '[name="vencimento_de"]'; 
       CustomersServerParams['vencimento_ate']  = '[name="vencimento_ate"]'; 
       CustomersServerParams['envio_de']        = '[name="envio_de"]'; 
       CustomersServerParams['envio_ate']       = '[name="envio_ate"]';  
     if ($.fn.DataTable.isDataTable('.table-invoices_receber')) {
       $('.table-invoices_receber').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-invoices_receber', admin_url+'financeiro_invoices/table', [0], [0], CustomersServerParams, [1, 'desc']);
   // tAPI.ajax.reload();

    // filtraCategoria();
   }




 </script>
</body>
</html>
