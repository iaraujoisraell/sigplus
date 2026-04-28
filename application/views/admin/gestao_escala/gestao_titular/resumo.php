<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<!-- 
Larissa Oliveira 
01/08/2022

-->
<?php //print_r($escalas_com_substitutos); ?>
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
                Gestão de Escala Fixa - Relatório
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?php echo admin_url('dashboard/menu'); ?>"><i class="fa fa-dashboard"></i> Gestão de Plantão </a></li>
                <li class="active"><a href="<?php echo admin_url('financeiro'); ?>">Gestão de escala </a></li>
              </ol>
            </section>
            
              <table class="table dt-table scroll-responsive" data-order-col="0" data-order-type="asc">
                <thead>
                    <tr>
                        <th width="5%">
                            Hierarquia
                        </th>
                        <th>
                            nome
                        </th>
                        
                        <th>
                            Plantões
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dados as $note){ ?>
                    <tr>
                        <td><?php echo $note['hierarquia'] ?></td>
                        <td><?php echo $note['nome_profissional'] ?></td>
                       
                        
                        <?php 
                            $soma_plantao = 0;
                            $planotes = $this->Medicos_model->get_planotes_escala_fixa_resumo($note['medicoid']);
                            foreach($planotes as $plantao){
                                $soma_plantao += $plantao['plantao'];
                                
                            }
                        ?>
                        <td><?php echo $soma_plantao; ?></td>
                        
                    </tr>
               <?php } ?>
                </tbody>
                </table>


                </div>
            </div>
        </div>
    </div>

</div>


<?php init_tail(); ?>

<script>

    function setores_substituto<?php echo $sub['id']; ?>(unidade_id) {
        $.ajax({
            type: "POST",
            url: "<?php echo admin_url("gestao_titular/retorno_setores_gestao_escala"); ?>",
            data: {
                unidade_id: unidade_id
            },
            success: function (data) {
                $('#setores_unidades_substituto<?php echo $sub['id']; ?>').html(data);
            }
        });
    }
</script>

<script>

    $(function () {

        var CustomersServerParams = {};

           $.each($('._hidden_inputs._filters input'),function(){
              CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
          });


           CustomersServerParams['unidade_id'] = '[name="unidade_id"]'; 
           CustomersServerParams['setor_id'] = '[name="setor_id"]';  
           CustomersServerParams['horario_id'] = '[name="horario_id"]';
           CustomersServerParams['dia_semana'] = '[name="dia_semana"]';
           CustomersServerParams['titular_id'] = '[name="titular_id"]';
           CustomersServerParams['escalado_filtro_id'] = '[name="escalado_filtro_id"]';
           CustomersServerParams['substituto_id'] = '[name="substituto_id"]';
           CustomersServerParams['status_escalado'] = '[name="status_escalado"]';  
           CustomersServerParams['med_duplicados'] = '[name="med_duplicados"]';
           CustomersServerParams['ordernar_por'] = '[name="ordernar_por"]';
           
          // CustomersServerParams['horario_id'] = '[name="exclude_inactive"]:checked';
           var tAPI = initDataTable('.table-gestao_titular', admin_url+'gestao_titular/table_gestao_titular', [0], [0], CustomersServerParams, [1, 'desc']);
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
      }

        $('body').on('click', '.edit-item-group', function (e) {
            e.preventDefault();
            var tr = $(this).parents('tr'),
                    group_id = tr.attr('data-group-row-id');
            tr.find('.group_name_plain_text').toggleClass('hide');
            tr.find('.group_edit').toggleClass('hide');
            tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
        });

        $('body').on('click', '.update-item-group', function () {
            var tr = $(this).parents('tr');
            var group_id = tr.attr('data-group-row-id');
            name = tr.find('.group_edit input').val();
            if (name != '') {
                $.post(admin_url + 'invoice_items/update_group/' + group_id, {name: name}).done(function () {
                    window.location.href = admin_url + 'invoice_items';
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
           CustomersServerParams['escalado_filtro_id'] = '[name="escalado_filtro_id"]';
           CustomersServerParams['substituto_id'] = '[name="substituto_id"]';
           CustomersServerParams['status_escalado'] = '[name="status_escalado"]';  
           CustomersServerParams['med_duplicados'] = '[name="med_duplicados"]';
           CustomersServerParams['ordernar_por'] = '[name="ordernar_por"]';
           
          if ($.fn.DataTable.isDataTable('.table-gestao_titular')) {
           $('.table-gestao_titular').DataTable().destroy();
          }
          var tAPI = initDataTable('.table-gestao_titular', admin_url+'gestao_titular/table_gestao_titular', [0], [0], CustomersServerParams, [1, 'desc']);
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
            success: function (data) {
                $('#filtro_categoria').html(data);
            }
        });
    }

    function setores(unidade_id) {
        $.ajax({
            type: "POST",
            url: "<?php echo admin_url("gestao_plantao/retorno_setores_gestao_escala"); ?>",
            data: {
                unidade_id: unidade_id
            },
            success: function (data) {
                $('#setores_unidades').html(data);
            }
        });
    }

    function medicos_duplicados(competencia_id) {
        $.ajax({

            type: "POST",
            url: "<?php echo admin_url("gestao_plantao/retorno_duplicados_gestao_escala"); ?>",
            data: {
                competencia_id: competencia_id

            },
            success: function (data) {
                $('#med_duplicados').html(data);
            }
        });
    }

    function horarios(setor_id) {
        $.ajax({
            type: "POST",
            url: "<?php echo admin_url("gestao_plantao/retorno_horarios_plantao_gestao_escala"); ?>",
            data: {
                setor_id: setor_id
            },
            success: function (data) {
                $('#horario_plantao').html(data);
            }
        });
    }



    function troca_horario_bulk_action(event) {
        if (confirm_delete()) {


            var mass_delete = $('#mass_delete').prop('checked');

            var horario_troca = document.getElementById('horario_troca_id');
            var horario_value = horario_troca.options[horario_troca.selectedIndex].value;

            //var horario_troca_id = $('#horario_troca_id').val();
            var ids = [];
            var data = {};


            data.horario_troca_id = horario_value;

       
          var rows = $('.table-gestao_titular').find('tbody tr');
          $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
              ids.push(checkbox.val());
            }
          });
         
            data.ids = ids;
            // $(event).addClass('disabled');
            setTimeout(function () {
                $.post(admin_url + 'gestao_plantao/bulk_action_trocar_escala', data).done(function () {
                    // window.location.reload();
                    procedimentos_table_reload();
                }).fail(function (data) {
                    alert_float('danger', data.responseText);
                });
            }, 200);
        }

    }

    function quebra_horario_bulk_action(event) {
        if (confirm_delete()) {


            var mass_delete = $('#mass_delete').prop('checked');

            var horario_quebra = document.getElementById('horario_novo_1');
            var horario_value = horario_quebra.options[horario_quebra.selectedIndex].value;

            var horario_quebra2 = document.getElementById('horario_novo_2');
            var horario_value2 = horario_quebra2.options[horario_quebra2.selectedIndex].value;
            //var horario_troca_id = $('#horario_troca_id').val();
            var ids = [];
            var data = {};


            data.horario_quebra_1 = horario_value;
            data.horario_quebra_2 = horario_value2;

            
          var rows = $('.table-gestao_titular').find('tbody tr');
          $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
              ids.push(checkbox.val());
            }
          });
         
            data.ids = ids;
            // $(event).addClass('disabled');
            setTimeout(function () {
                $.post(admin_url + 'gestao_plantao/bulk_action_quebra_escala', data).done(function () {
                    // window.location.reload();
                    procedimentos_table_reload();
                }).fail(function (data) {
                    alert_float('danger', data.responseText);
                });
            }, 200);
        }

    }

    function add_plantonista_bulk_action(event) {

        if (confirm_delete()) {

            var competencia = document.getElementById('add_plantonista_competencia_id');
            var competencia_value = competencia.options[competencia.selectedIndex].value;

            var dia_Semana = document.getElementById('add_plantonista_dia_semana');
            var dia_Semana_value = dia_Semana.options[dia_Semana.selectedIndex].value;

            var unidades = document.getElementById('add_plantonista_unidade_id');
            var unidades_value = unidades.options[unidades.selectedIndex].value;

            var setores = document.getElementById('setor_id_add_plantonista');
            var setores_value = setores.options[setores.selectedIndex].value;

            var horario = document.getElementById('horario_id_add_plantonista');
            var horario_value = horario.options[horario.selectedIndex].value;

            var horario_exec = document.getElementById('horario_executado_id_add_plantonista');
            var horario_exec_value = horario_exec.options[horario_exec.selectedIndex].value;

            var medico = document.getElementById('medicoid');
            var medico_value = medico.options[medico.selectedIndex].value;


            var escalado = document.getElementById('escalado');
            var escalado_value = escalado.options[escalado.selectedIndex].value;


            var data = {};

            data.competencia = competencia_value;
            data.dia_Semana = dia_Semana_value;
            data.unidades = unidades_value;
            data.setores = setores_value;
            data.horario = horario_value;
            data.horario_exec = horario_exec_value;
            data.medico = medico_value;
            data.escalado = escalado_value;


            //data.ids = ids;
            // $(event).addClass('disabled');
            setTimeout(function () {
                $.post(admin_url + 'gestao_plantao/bulk_action_add_plantonista', data).done(function () {
                    // window.location.reload();
                    procedimentos_table_reload();
                }).fail(function (data) {
                    alert_float('danger', data.responseText);
                });
            }, 200);
        }

    }

    function troca_titular_bulk_action(event) {
        if (confirm_delete()) {


            var titular_troca = document.getElementById('titular_troca_id');
            var titular_value = titular_troca.options[titular_troca.selectedIndex].value;

            //var horario_troca_id = $('#horario_troca_id').val();
            var ids = [];
            var data = {};


            data.titular_id = titular_value;

            var rows = $('.table-gestao_titular').find('tbody tr');
            $.each(rows, function () {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') === true) {
                    ids.push(checkbox.val());
                }
            });
            data.ids = ids;
            //$(event).addClass('disabled');
            setTimeout(function () {
                $.post(admin_url + 'gestao_titular/bulk_action_trocar_titular', data).done(function () {
                    // window.location.reload();
                    procedimentos_table_reload();
                }).fail(function (data) {
                    alert_float('danger', data.responseText);
                });
            }, 200);
        }
    }

    function troca_escalado_bulk_action(event) {
        if (confirm_delete()) {


            var escalado_troca = document.getElementById('escalado_troca_id');
            var escalado_value = escalado_troca.options[escalado_troca.selectedIndex].value;

            //var horario_troca_id = $('#horario_troca_id').val();
            var ids = [];
            var data = {};


            data.escalado_id = escalado_value;

            var rows = $('.table-gestao_titular').find('tbody tr');
            $.each(rows, function () {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') === true) {
                    ids.push(checkbox.val());
                }
            });
            data.ids = ids;
            //$(event).addClass('disabled');
            setTimeout(function () {
                $.post(admin_url + 'gestao_plantao/bulk_action_trocar_escalado', data).done(function () {
                    // window.location.reload();
                    procedimentos_table_reload();
                }).fail(function (data) {
                    alert_float('danger', data.responseText);
                });
            }, 200);
        }
    }

    function troca_substituto_bulk_action(event) {
        if (confirm_delete()) {


            var substituto_troca = document.getElementById('substituto_troca_id');
            var substituto_value = substituto_troca.options[substituto_troca.selectedIndex].value;

            var data_inicio = $('#data_inicio').val();
            var data_termino = $('#data_termino').val();
            /* 
             var unidade_substituto = document.getElementById('unidade_substituto');
             var unidade_substituto_value = unidade_substituto.options[unidade_substituto.selectedIndex].value;
             
             var setor_substituto = document.getElementById('setor_substituto');
             var setor_substituto_value = setor_substituto.options[setor_substituto.selectedIndex].value;
             
             var dia_semana = document.getElementById('dia_semana_substituto');
             var dia_semana_value = dia_semana.options[dia_semana.selectedIndex].value; */
            /*
             var dia_semana_value = [];
             for(let i = 0; i < dia_semana.options.length; i++)
             {
             if (dia_semana.options[i].selected)
             {
             dia_semana_value.push(dia_semana.options[i].value);
             }
             */

            // var horario_substituto = document.getElementById('horario_substituto');
            // var horario_substituto_value = horario_substituto.options[horario_substituto.selectedIndex].value;


            //var horario_troca_id = $('#horario_troca_id').val();
            var ids = [];
            var data = {};



            data.substituto_id = substituto_value;
            data.data_inicio = data_inicio;
            data.data_termino = data_termino;
            //    data.unidade_substituto  = unidade_substituto_value;
            //    data.setor_substituto    = setor_substituto_value;
            //    data.horario_substituto  = horario_substituto_value;
            //    data.dia_semana          = dia_semana_value;

            var rows = $('.table-gestao_titular').find('tbody tr');
            $.each(rows, function () {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') === true) {
                    ids.push(checkbox.val());
                }
            });
            data.ids = ids;
            //$(event).addClass('disabled');
            setTimeout(function () {

                $.post(admin_url + 'gestao_titular/bulk_action_trocar_substituto', data).done(function () {
                    // window.location.reload();
                    procedimentos_table_reload();
                }).fail(function (data) {
                    alert_float('danger', data.responseText);
                });
            }, 200);
        }
    }

    function deletar_horario_bulk_action(event) {
        if (confirm_delete()) {


            var ids = [];
            var data = {};



            var rows = $('.table-gestao_titular').find('tbody tr');
            $.each(rows, function () {
                var checkbox = $($(this).find('td').eq(0)).find('input');
                if (checkbox.prop('checked') === true) {
                    ids.push(checkbox.val());
                }
            });
            data.ids = ids;
            //$(event).addClass('disabled');
            setTimeout(function () {
                $.post(admin_url + 'gestao_titular/bulk_action_deletar_horario', data).done(function () {
                    // window.location.reload();
                    procedimentos_table_reload();
                }).fail(function (data) {
                    alert_float('danger', data.responseText);
                });
            }, 200);
        }
    }

    function add_plantonista_setores(unidade_id) {
        $.ajax({
            type: "POST",
            url: "<?php echo admin_url("gestao_plantao/retorno_setores_gestao_escala_add_plantonista"); ?>",
            data: {
                unidade_id: unidade_id
            },
            success: function (data) {
                $('#add_plantonista_setores_unidades').html(data);
            }
        });
    }

    function add_plantonista_horarios(setor_id) {
        $.ajax({
            type: "POST",
            url: "<?php echo admin_url("gestao_plantao/retorno_horarios_plantao_gestao_escala_add_plantonista"); ?>",
            data: {
                setor_id: setor_id
            },
            success: function (data) {
                $('#add_plantonista_horario_plantao').html(data);
            }
        });
    }

</script>
</body>
</html>
