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
                include_once(APPPATH.'views/admin/financeiro/conta_pagar/filter_params.php');
                $this->load->view('admin/financeiro/conta_pagar/list_template');
                ?>
        </div>
</div>

<?php $this->load->view('admin/includes/modals/sales_attach_file'); ?>
<?php $this->load->view('admin/financeiro/conta_pagar/novo_conta_pagar'); ?>
<?php $this->load->view('admin/financeiro/conta_pagar/parcelas/modal_parcela'); ?>
<?php //$this->load->view('admin/financeiro/conta_pagar/novo_conta_pagar_pagamento'); ?>
<script>var hidden_columns = [1,2,3,4,5];</script>
<?php init_tail(); ?>
<script>
    $(function(){
       init_conta_pagar();
    });
</script>
<script>

  $(function(){

    var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
       CustomersServerParams['plano_conta'] = '[name="plano_conta"]';
       CustomersServerParams['fornecedor'] = '[name="fornecedor"]'; 
       CustomersServerParams['categorias'] = '[name="categorias"]';
       CustomersServerParams['documento'] = '[name="documento"]';
       var tAPI = initDataTable('.table-conta_pagar', admin_url+'financeiro/table_conta_pagar', [0], [0], CustomersServerParams, [1, 'desc']);
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
       CustomersServerParams['plano_conta'] = '[name="plano_conta"]';
       CustomersServerParams['fornecedor'] = '[name="fornecedor"]';
       CustomersServerParams['categorias'] = '[name="categorias"]'; 
       CustomersServerParams['documento'] = '[name="documento"]';
     if ($.fn.DataTable.isDataTable('.table-conta_pagar')) {
       $('.table-conta_pagar').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-conta_pagar', admin_url+'financeiro/table_conta_pagar', [0], [0], CustomersServerParams, [1, 'desc']);
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

      var rows = $('.table-conta_pagar').find('tbody tr');
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
