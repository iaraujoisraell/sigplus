<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<h4 class="customer-profile-group-heading"><?php echo 'Clientes'; ?></h4>

<div class="clearfix"></div>
<div class="mtop15">
   <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
             
         <?php hooks()->do_action('before_items_page_content'); ?>
         <?php if(has_permission('items','','create')){ ?>
           <div class="_buttons">
            <a href="#" class="btn btn-info pull-left"  data-toggle="modal" data-target="#add_cliente_modal" data-id=""><?php echo 'Add Cliente'; ?></a>      
          </div>
          <div class="clearfix"></div>
          <hr class="hr-panel-heading" />
        <?php } ?>
        
        

        <?php
        $table_data = [];

        if(has_permission('items','','delete')) {
          //$table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
            $table_data[] = '<span class="hide"> - </span><label>#</label>';
        }

        $table_data = array_merge($table_data, array(
          'Cliente',
          'CNPJ/ CPF',    
          'Contato'
           ));

        $cf = get_custom_fields('items');
        foreach($cf as $custom_field) {
          array_push($table_data,$custom_field['name']);
        }
        render_datatable($table_data,'clientes_financeiro'); ?>
      </div>
        </div>
      </div>
    </div>
</div>
<?php $this->load->view('admin/financeiro/cadastros/groups/novo_cliente'); ?>

<?php init_tail(); ?>
<script>

  $(function(){

    var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
       CustomersServerParams['categorias'] = '[name="categorias"]';
       CustomersServerParams['natureza'] = '[name="natureza"]'; 
       var tAPI = initDataTable('.table-clientes_financeiro', admin_url+'financeiro/table_cliente', [0], [0], CustomersServerParams, [1, 'desc']);
    
        //filtraCategoria();

       
  
   });

   function procedimentos_table_reload() {
      //alert('aqui');
       var CustomersServerParams = {};
     //  $.each($('._hidden_inputs._filters input'),function(){
     //     CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
     // });
       CustomersServerParams['categorias'] = '[name="categorias"]';
       CustomersServerParams['natureza'] = '[name="natureza"]'; 
     if ($.fn.DataTable.isDataTable('.table-clientes_financeiro')) {
       $('.table-clientes_financeiro').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-clientes_financeiro', admin_url+'financeiro/table_cliente', [0], [0], CustomersServerParams, [1, 'desc']);
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

      var rows = $('.table-clientes_financeiro').find('tbody tr');
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
