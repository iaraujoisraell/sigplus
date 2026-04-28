<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<!-- 
Larissa Oliveira
27/07/2022 
Descrição: View com filtros de competência, unidade hospitalar e setor que geram os relatórios de frequencia em pdf
-->
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
    <div  class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <section class="content-header">
                        <h1>
                            Relatório de frequências
                        </h1>
                        <ol class="breadcrumb">
                          <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Gestão de Plantão </a></li>
                          <li class="active"><a href="<?php echo admin_url('financeiro'); ?>">Gestão de frequencia </a></li>
                        </ol>
                    </section>
        
              <?php hooks()->do_action('before_items_page_content'); ?>
            <?php echo form_open('admin/Lista_frequencia/pdf'); ?>
              <div class="col-md-12">        
                <div class="col-md-3">
                  <div class="form-group">
                      <label>Competência</label>
                      <select name="competencia_id" id="competencia_id"  required="true" class="form-control" onchange="procedimentos_table_reload();">
                            <?php foreach ($competencias as $comp){ ?>
                             <option value="<?php echo $comp['id']; ?>"><?php echo $comp['mes'].' / '.$comp['ano']; ?></option>   
                            <?php } ?>
                      </select>
                  </div>
                </div>  
             
              <div class="col-md-4">
                  <div class="form-group">
                       <label>Unidades Hospitalares</label>
                        <select name="unidade_id" id="unidade_id" onchange="setores(this.value); procedimentos_table_reload();" required="true" class="form-control">
                            <option value="">Selecione uma unidade</option>
                            <?php foreach ($unidades_hospitalares as $unidades){ ?>
                             <option value="<?php echo $unidades['id']; ?>"><?php echo $unidades['razao_social']; ?></option>   
                            <?php } ?>
                        </select>
                    </div>
              </div>
         
              <div class="col-md-4">
                  <div class="form-group">
                        <div id="setores_unidades">   
                            
                        </div>
                  </div>
              </div> 
              
                </div>
                    <div class="box-header ">
                           <div style="margin-left: 15px;" class="col-md-4">
                          
                               <button class="btn btn-primary">Gerar relatório<i style="margin-left: 10px;" class="fa fa-file-pdf-o"></i></button>
                           
                           </div>
                    </div>
                </div>  
            </div>
        </div>
        
</div>

    <?php init_tail(); ?>
    <script>
    function filtraEscala() {
      
      $.ajax({
        type: "POST",
        url: "<?php echo admin_url("Lista_frequencia/pdf"); ?>",
        data: {
          competencia_id: $('#competencia_id').val(),
          unidade_id: $('#unidade_id').val(),
          setor_id: $('#setor_id').val()
          //horarios_id: $('#horarios_id').val(),
          //dias_semana: $('#dias_semana').val()
        },
        success: function(data) {
          $('#conteudo').html(data);
        }
      });
    }
   </script>

    <script>

    $(function(){

        var CustomersServerParams = {};
           $.each($('._hidden_inputs._filters input'),function(){
              CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
          });

           CustomersServerParams['competencia_id'] = '[name="competencia_id"]';
           CustomersServerParams['unidade_id'] = '[name="unidade_id"]'; 
           CustomersServerParams['setor_id'] = '[name="setor_id"]';  
           CustomersServerParams['horario_id'] = '[name="horario_id"]';
           CustomersServerParams['dia_semana'] = '[name="dia_semana"]';
           CustomersServerParams['titular_id'] = '[name="titular_id"]';
           CustomersServerParams['status_escalado'] = '[name="status_escalado"]';
          // CustomersServerParams['horario_id'] = '[name="exclude_inactive"]:checked';
           var tAPI = initDataTable('.table-escala_gestao', admin_url+'gestao_plantao/table_escala_gestao', [0], [0], CustomersServerParams, [1, 'desc']);
            $('select[name="convenios_procedimentos"]').on('change',function(){
              // alert('aqui');
               tAPI.ajax.reload();

             // procedimentos_table_reload();
           });
            //filtraCategoria();


        if(get_url_param('groups_modal')){
           // Set time out user to see the message
           setTimeout(function(){
             $('#groups').modal('show');
           },1000);
         }

         $('#new-item-group-insert').on('click',function(){
          var group_name = $('#item_group_name').val();
          if(group_name != ''){
            $.post(admin_url+'invoice_items/add_group',{name:group_name}).done(function(){
             window.location.href = admin_url+'invoice_items?groups_modal=true';
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
            $.post(admin_url+'invoice_items/update_group/'+group_id,{name:name}).done(function(){
             window.location.href = admin_url+'invoice_items';
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
           CustomersServerParams['competencia_id'] = '[name="competencia_id"]';
           CustomersServerParams['unidade_id'] = '[name="unidade_id"]'; 
           CustomersServerParams['setor_id'] = '[name="setor_id"]';  
           CustomersServerParams['horario_id'] = '[name="horario_id"]'; 
           CustomersServerParams['dia_semana'] = '[name="dia_semana"]';
           CustomersServerParams['titular_id'] = '[name="titular_id"]';
           CustomersServerParams['status_escalado'] = '[name="status_escalado"]';
          if ($.fn.DataTable.isDataTable('.table-escala_gestao')) {
           $('.table-escala_gestao').DataTable().destroy();
          }
          var tAPI = initDataTable('.table-escala_gestao', admin_url+'gestao_plantao/table_escala_gestao', [0], [0], CustomersServerParams, [1, 'desc']);
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
         
    function setores(unidade_id) {
    $.ajax({
        type: "POST",
        url: "<?php echo admin_url("lista_frequencia/retorno_setores_gestao_escala"); ?>",
        data: {
          unidade_id: unidade_id        
        },
        success: function(data) {
          $('#setores_unidades').html(data);
        }
      });
    }

    
     </script>
    </body>
 </html>
