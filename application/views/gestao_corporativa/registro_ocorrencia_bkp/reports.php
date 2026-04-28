<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">
  
<style>
    .color-palette {
      height: 35px;
      line-height: 35px;
      text-align: center;
    }

    .color-palette-set {
      margin-bottom: 15px;
    }

    .color-palette span {
      display: none;
      font-size: 12px;
    }

    .color-palette:hover span {
      display: block;
    }

    .color-palette-box h4 {
      position: absolute;
      top: 100%;
      left: 25px;
      margin-top: -40px;
      color: rgba(255, 255, 255, 0.8);
      font-size: 12px;
      display: block;
      z-index: 7;
    }
  </style>
 
<style>
         body {
         font-family:'Open Sans';
         background:#f1f1f1;
         }
         h3 {
         margin-top: 7px;
         font-size: 18px;
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
<?php //init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight: bold;">INTRANET - Registro de ocorrência RELATÓRIOS</h5>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Registro_ocorrencia'); ?>"><i class="fa fa-backward"></i> Registro de Ocorrência </a></li>
                    <li><a href="<?= base_url('gestao_corporativa/Registro_ocorrencia/reports'); ?>"><i class="fa fa-backward"></i> Relatários </a></li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="row col-md-10">
                        <div class="row "  id="filters">
                            <div class="col-md-4">
                                <select id="categoria_id" name="categoria_id" class="selectpicker" required="true" data-width="100%" data-none-selected-text="Categorias" data-live-search="true" onchange="atualizarBotoes();">
                                    <option value=""></option><!-- comment -->
                                    <?php foreach ($categorias as $cat) { ?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['titulo']; ?></option>
                                    <?php } ?>
                                </select>

                            </div>

                            <div class="col-md-4">
                                <select id="status" name="status" class="selectpicker" required="true" data-width="100%" data-none-selected-text="Status" data-live-search="true" onchange="atualizarBotoes();" multiple>
                                    <option value=""></option><!-- comment -->
                                    <?php foreach ($statuses as $sts) { ?>
                                        <option value="<?php echo $sts['id']; ?>"><?php echo $sts['label']; ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                            <div class="col-md-2">
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker" id="date_start" name="report-from" placeholder="Por data" onchange="atualizarBotoes();" onkeyup="atualizarBotoes();">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar calendar-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group date">
                                    <input type="text" class="form-control datepicker"  id="date_end" name="report-to" placeholder="Até a data" onchange="atualizarBotoes();" onkeyup="atualizarBotoes();">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar calendar-icon"></i>
                                    </div>
                                </div>
                            </div>

                            <div id="filters_" class="row col-md-12">

                            </div>

                        </div>


                    </div>
                    <div class=" row col-md-2" style="text-align: center;">
                        <div id="button-container" class="col-md-12" >
                            <button onclick="report_general();" class="btn btn-success" disabled id="button1">
                                Gerar Relatório 
                            </button>
                            
                        </div>
                        <!--<div class="col-md-6">
                            <button onclick="report_detail()" class="btn btn-warning" disabled id="button2" disabled>Relatório Detalhado</button>
                        </div>-->
                        
                            
                     







                    </div>
                    <div class="col-md-12" style="margin-top: 20px; text-align: center;  display: none;  " id="spinner">
                        <div  class="spinner" style="margin-left: auto; margin-right: auto;"></div>
    </div>
                    

                </div>
                

            </div>
            
            <div id="trocar">
            </div>
            

            

         <div class="panel_s leads-overview">
                <div class="panel-body">
                    <div class="col-md-12">
                        <h4 class="no-margin">RESUMO DE REGISTROS POR DEPARTAMENTO</h4>
                        <br>
                    </div>

                    <?php
                    foreach ($departments_workflow as $dep) {
                        //print_r($dep);
                        ?>
                        <div class="col-md-2 col-xs-6 border-right">
                            <h3 class="bold"><?php echo $dep['total']; ?></h3>
                            <span style="color:<?php echo $dep['color']; ?>" class=""><?php echo $dep['name']; ?></span>
                        </div>
                    <?php } ?>


                </div>
            </div> 

<?php if(count($categorias) > 0){?>
           <div class="panel_s  cat-overview" >
                <div class="panel-heading-bg">
                    RESUMO DE REGISTROS POR CATEGORIA                  
                </div>
                <div class="panel-body">
                    <canvas  height="200" id="leads-sources-report"></canvas>
                </div>
            </div> 


        <div class="panel_s monthly-overview">

                <div class="panel-heading-bg">
                    ESTATÍSTICAS GERAIS       
                </div>
                <div class="panel-body" >

                    <div class="col-md-6" >
                        <canvas class="monthly chart" id="monthly" height="200"></canvas>
                    </div>
                    <div class="col-md-6 row" >
                        <div class="col-md-12" >
                            <canvas  id="rrr" height="200"></canvas>
                        </div>
                        <div class="col-md-12" >
                            <canvas class="anual chart" id="anual" height="200"></canvas>
                        </div>

                    </div>


                </div>
            </div> 
<?php }?>
            

        </div>
    </div>
</div>

<?php init_tail(); ?>
<script src="<?php echo base_url('assets/plugins/excellentexport/excellentexport.min.js'); ?>"></script>
<style>
    .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top: 4px solid #007bff;
        width: 30px;
        height: 30px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

</style>
<script>

                                // Obtenha os elementos HTML dos inputs e botões
                                const categoria_id = document.getElementById("categoria_id");
                                const status = document.getElementById("status");
                                const date_start = document.getElementById("date_start");
                                const date_end = document.getElementById("date_end");
                                const button1 = document.getElementById("button1");
                                const button2 = document.getElementById("button2");
// Função para verificar se todos os inputs estão preenchidos

// Função para habilitar ou desabilitar os botões com base no estado dos inputs
                                function atualizarBotoes() {
                                if (categoria_id.value.trim() !== "" && status.value.trim() !== "" && date_start.value.trim() !== "" && date_end.value.trim() !== "") {
                                button1.removeAttribute("disabled");
                                button2.removeAttribute("disabled");
                                } else {
                                button1.setAttribute("disabled", "true");
                                button2.setAttribute("disabled", "true");
                                }
                                }



</script>
<script>
    $(document).ready(function() {
    // Evento de mudança no select de categoria
    $("#categoria_id").change(function() {
    var categoriaSelecionada = $(this).val();
    $.ajax({
    type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Categorias_campos/get_campos_in_options_filter'); ?>",
            data: {
            categoria_id: categoriaSelecionada,
            rel_type: 'r.o'
            },
            success: function (data) {
            $("#filters_").html(data);
            }
    });
    });
    });</script>
<script>

    

    new Chart($('#leads-sources-report'), {
    type: 'bar',
            data:{
            "labels":[
<?php
for ($i = 0; $i < count($categorias_workflow); $i++) {
    ?>
                "<?php echo $categorias_workflow[$i]['titulo'] ?>",
    <?php
}
?>
            ],
                    "datasets":[{"label":"Registros",
                            "backgroundColor":"rgba(67, 127, 180, 0.7)",
                            "borderColor":"#7cb342",
                            "data":[
<?php for ($i = 0; $i < count($categorias_workflow); $i++) { ?>


    <?php echo $categorias_workflow[$i]['total']; ?>,
<?php }
?>
                            ]}]},
            options: {
            responsive: true,
                    legend: {
                    display: false,
                    },
                    scales: {
                    yAxes: [{
                    ticks: {
                    beginAtZero: true,
                    }
                    }]
                    },
            },
    });
    var chart_data = {"labels":["Segunda-feira", "Ter\u00e7a-feira", "Quinta-feira", "Quarta-feira", "Sexta-feira", "S\u00e1bado", "Domingo"],
            "datasets":[{"label":"Worwflow's", "backgroundColor":"rgba(197, 61, 169, 0.5)", "borderColor":"#c53da9", "borderWidth":1,
                    "tension":false, "data":[
<?php echo $semana[0]; ?>,
<?php echo $semana[1]; ?>,
<?php echo $semana[2]; ?>,
<?php echo $semana[3]; ?>,
<?php echo $semana[4]; ?>,
<?php echo $semana[5]; ?>,
<?php echo $semana[6]; ?>
                    ]}]};
    // Weekly ticket openings statistics
    new Chart($('#rrr'), {
    type:'line',
            data:chart_data,
            options:{
            responsive:true,
                    maintainAspectRatio:false,
                    legend: {
                    display: false,
                    },
                    scales: {
                    yAxes: [{
                    ticks: {
                    beginAtZero: true,
                    }
                    }]
                    }
            }
    });
    new Chart($('#monthly'), {
    'type': 'bar',
            data:{
            "labels":[
<?php foreach ($mes['dias'] as $dia) { ?>
                "<?php echo $dia; ?>",
<?php } ?>



            ],
                    "datasets":[{"label":"Worflow's",
                            "backgroundColor":"rgba(124, 179, 66, 0.5)",
                            "borderColor":"#7cb342",
                            "data":[
<?php foreach ($mes['totals'] as $total) { ?>
    <?php echo $total; ?>,
<?php } ?>



                            ]}]},
            options: {
            responsive: true,
                    legend: {
                    display: false,
                    },
                    scales: {
                    yAxes: [{
                    ticks: {
                    beginAtZero: true,
                    }
                    }]
                    },
            },
    });
    new Chart($('#anual'), {
    type: 'bar',
            data: {"labels":["Janeiro", "Fevereiro", "Mar\u00e7o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                    "datasets":[
                    {"label":"Registros interno", "backgroundColor":"#1acfe5", "borderColor":"#1acfe5", "borderWidth":1, "tension":false,
                            "data":[
<?php foreach ($meses['interno'] as $interno) { ?>
    <?php echo $interno; ?>,
<?php } ?>
                            ]},
                    {"label":"Registros  portal", "backgroundColor":"#e3b813", "borderColor":"#e3b813", "borderWidth":1, "tension":false,
                            "data":[
<?php foreach ($meses['portal'] as $portal) { ?>
    <?php echo $portal; ?>,
<?php } ?>
                            ]}]},
            options:{
            maintainAspectRatio:false,
                    scales: {
                    yAxes: [{
                    ticks: {

                    beginAtZero: true,
                    }
                    }]
                    }, }
    });



</script></body>
</html>
