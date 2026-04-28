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
                    //include_once(APPPATH.'views/admin/financeiro/conta_pagar/filter_params.php');
                    $this->load->view('admin/gestao_escala/plantoes/list_template');
                    ?>
            </div>
    </div>


<?php $this->load->view('admin/gestao_escala/plantoes/nova_escala'); ?>

<script>var hidden_columns = [1,2,3,4,5];</script>

<?php init_tail(); ?>

<script>

  $(function(){

    var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
       CustomersServerParams['competencia_id'] = '[name="competencia_id"]';
           CustomersServerParams['unidade_id'] = '[name="unidade_filtro_id"]'; 
           CustomersServerParams['setor_id'] = '[name="setor_id"]';  
           CustomersServerParams['horario_id'] = '[name="horario_id"]';
       var tAPI = initDataTable('.table-escala_setores_competencia', admin_url+'gestao_plantao/table_escala_setores', [0], [0], CustomersServerParams, [1, 'desc']);
        $('select[name="categorias"]').on('change',function(){
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
       CustomersServerParams['competencia_id'] = '[name="competencia_id"]';
           CustomersServerParams['unidade_id'] = '[name="unidade_filtro_id"]'; 
           CustomersServerParams['setor_id'] = '[name="setor_id"]';  
           CustomersServerParams['horario_id'] = '[name="horario_id"]';
     if ($.fn.DataTable.isDataTable('.table-escala_setores_competencia')) {
       $('.table-escala_setores_competencia').DataTable().destroy();
     }
      var tAPI = initDataTable('.table-escala_setores_competencia', admin_url+'gestao_plantao/table_escala_setores', [0], [0], CustomersServerParams, [1, 'desc']);
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

      var rows = $('.table-escala_setores_competencia').find('tbody tr');
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
