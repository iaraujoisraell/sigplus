<?php
//$assets = 'themes/default/assets/'
?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <base href="<?= site_url() ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Controle de Projetos - TI UnimedManaus</title>

    
    
                <!-- PACE LOAD BAR PLUGIN - This creates the subtle load bar effect at the top of the page. -->
    <link href="<?= $assets ?>dashboard/css/plugins/pace/pace.css" rel="stylesheet">
    <script src="<?= $assets ?>dashboard/js/plugins/pace/pace.js"></script>

    <!-- GLOBAL STYLES - Include these on every page. -->
    <link href="<?= $assets ?>dashboard/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel="stylesheet" type="text/css">
    <link href="<?= $assets ?>dashboard/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- PAGE LEVEL PLUGIN STYLES -->
    <link href="<?= $assets ?>dashboard/css/plugins/messenger/messenger.css" rel="stylesheet">
    <link href="<?= $assets ?>dashboard/css/plugins/messenger/messenger-theme-flat.css" rel="stylesheet">
    <link href="<?= $assets ?>dashboard/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
    <link href="<?= $assets ?>dashboard/css/plugins/morris/morris.css" rel="stylesheet">
    <link href="<?= $assets ?>dashboard/css/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet">
    <link href="<?= $assets ?>dashboard/css/plugins/datatables/datatables.css" rel="stylesheet">

    <!-- THEME STYLES - Include these on every page. -->
    <link href="<?= $assets ?>dashboard/css/style.css" rel="stylesheet">
    <link href="<?= $assets ?>dashboard/css/plugins.css" rel="stylesheet">

    <!-- THEME DEMO STYLES - Use these styles for reference if needed. Otherwise they can be deleted. -->
    <link href="<?= $assets ?>dashboard/css/demo.css" rel="stylesheet">
    
    
    
    

     

        </head>


        <body >
 

        <div id="wrapper">
        <!-- begin MAIN PAGE CONTENT -->
            <div id="page-wrapper">
            <br>
                <div class="page-content">


                    <div class="row">
                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">
                                <a >
                                    <div class="circle-tile-heading dark-blue">
                                        <i class="fa fa-users fa-fw fa-3x"></i>
                                    </div>
                                </a>
                                <div class="circle-tile-content dark-blue">
                                    <div class="circle-tile-description text-faded">
                                        Equipe 
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $equipe; ?>
                                    </div>
                                    <a style="text-decoration: none"  class="circle-tile-footer" target="_blank" href="<?= site_url('Planos'); ?>"><?= lang('Mais Informações') ?><i class="fa fa-chevron-circle-right"></i></a>

                                </div>
                            </div>
                        </div>


                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">
                                <a >
                                    <div class="circle-tile-heading blue">
                                        <i class="fa fa-tasks fa-fw fa-3x"></i>
                                    </div>
                                </a>
                                <div class="circle-tile-content blue">
                                    <div class="circle-tile-description text-faded">
                                        Atas
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $ata->ata; ?>

                                    </div>
                                    <a style="text-decoration: none"  class="circle-tile-footer" target="_blank" href="<?= site_url('Atas'); ?>"><?= lang('Mais Informações') ?><i class="fa fa-chevron-circle-right"></i></a>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">
                                <a >
                                    <div class="circle-tile-heading gray">
                                        <i class="fa fa-list-ol fa-fw fa-3x"></i>
                                    </div>
                                </a>
                                <div class="circle-tile-content gray">
                                    <div class="circle-tile-description text-faded">
                                        Ações
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $total_acoes; ?>
                                    </div>
                                    <a style="text-decoration: none"  class="circle-tile-footer" target="_blank" href="<?= site_url('Planos'); ?>"><?= lang('Mais Informações') ?><i class="fa fa-chevron-circle-right"></i></a>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">
                                <a >
                                    <div class="circle-tile-heading green">
                                        <i class="fa fa-check fa-fw fa-3x"></i>
                                    </div>
                                </a>
                                <div class="circle-tile-content green">
                                    <div class="circle-tile-description text-faded">
                                        Ações Concluídas
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $concluido; ?>

                                    </div>
                                    <a style="text-decoration: none"  class="circle-tile-footer" target="_blank" href="<?= site_url('Planos/planosConcluidos'); ?>"><?= lang('Mais Informações') ?><i class="fa fa-chevron-circle-right"></i></a>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">
                                <a >
                                    <div class="circle-tile-heading orange">
                                        <i class="fa fa-bell fa-fw fa-3x"></i>
                                    </div>
                                </a>
                                <div class="circle-tile-content orange">
                                    <div class="circle-tile-description text-faded">
                                        Ações Pendentes
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $pendente; ?>
                                    </div>
                                    <a style="text-decoration: none"  class="circle-tile-footer" target="_blank" href="<?= site_url('Planos/planosPendentes'); ?>"><?= lang('Mais Informações') ?><i class="fa fa-chevron-circle-right"></i></a>


                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-6">
                            <div class="circle-tile">
                                <a >
                                    <div class="circle-tile-heading red">
                                        <i class="fa fa-clock-o fa-fw fa-3x"></i>
                                    </div>
                                </a>
                                <div class="circle-tile-content red">
                                    <div class="circle-tile-description text-faded">
                                        Ações Atrasadas
                                    </div>
                                    <div class="circle-tile-number text-faded">
                                        <?php echo $atrasadas; ?>

                                    </div>
                                    <a style="text-decoration: none"  class="circle-tile-footer" target="_blank" href="<?= site_url('Planos/planosPendentes'); ?>"><?= lang('Mais Informações') ?><i class="fa fa-chevron-circle-right"></i></a>

                                </div>
                            </div>
                        </div>

                    </div>


                    <!-- TIMELINE DOS EVENTOS -->
                    <div  class="row">
                        <div class="col-lg-12">
                            <div class="portlet portlet-blue">
                                <div class="portlet-heading">
                                    <div class="portlet-title">
                                        <h4>TIMELINE DOS EVENTOS</h4>
                                    </div>
                                    <div class="portlet-widgets">
                                        <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                        <span class="divider"></span>
                                        <a data-toggle="collapse" data-parent="#accordion" href="#example"><i class="fa fa-chevron-down"></i></a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div  id="main_content_wrap" class="outer">
                                    <section id="main_content" class="inner">


                                        <div style="height:100%" id="example" ></div>
                                     </section>   
                                </div>


                            </div>

                        </div>
                    </div>
                </div>


                 <!-- Line Chart Example -->


                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                                google.charts.load("current", {packages:["timeline"]});
                                google.charts.setOnLoadCallback(drawChart);
                                        function drawChart() {
                                                var container = document.getElementById('example');
                                        var chart = new google.visualization.Timeline(container);
                                        var dataTable = new google.visualization.DataTable();
                                dataTable.addColumn({ type: 'string', id: 'Term' });
                                dataTable.addColumn({ type: 'string', id: 'Name' });
                                        dataTable.addColumn({ type: 'date', id: 'Start' });
                                dataTable.addColumn({ type: 'date', id: 'End' });




                                        dataTable.addRows([
                <?php
                    $cont = 1;

                    foreach ($eventos as $evento) {

                        $data_ini = substr("$evento->data_inicio", 0, 10);
                        $data_ini_n = str_replace("-", ",", $data_ini);

                        $partes_ini_n = explode("-", $data_ini);
                        $dia_ini_n = $partes_ini_n[2];
                        $mes_ini_n = $partes_ini_n[1];
                        $ano_ini_n = $partes_ini_n[0];
                        $nova_data_ini = $ano_ini_n . ',' . $mes_ini_n . ',' . $dia_ini_n;

                        $data_fim = substr("$evento->data_fim", 0, 10);
                        $data_fim_n = str_replace("-", ",", $data_fim);

                        $partes_fim_n = explode("-", $data_fim);
                        $dia_fim_n = $partes_fim_n[2];
                        $mes_fim_n = $partes_fim_n[1] - 1;
                        $ano_fim_n = $partes_fim_n[0];
                        $nova_data_fim = $ano_fim_n . ',' . $mes_fim_n . ',' . $dia_fim_n;


                        ?>    
                            [ "<?php echo $evento->tipo; ?>", "<?php  echo  $evento->nome_evento; ?>",  new Date("<?php echo $nova_data_ini; ?>"), new Date("<?php echo $data_fim_n; ?>") ],
                        <?php

                    }
                    //
                    //[ '1', 'George Washington', new Date(2017, 12, 04), new Date(2017, 12, 8) ],
                    //[ '2', 'John Adams',        new Date(2017, 12, 11),  new Date(2017, 12, 15) ],
                    //[ '3', 'Thomas Jefferson',  new Date(2017, 12, 13),  new Date(2018,1 , 28) ]
                    ?>  


                    ]);

                            var options = {
                            timeline: { groupByRowLabel: true, colorByRowLabel: true  }
                    };

                    chart.draw(dataTable, options);
                    }

                                    //}
                </script>

            <!-- Line Chart Example -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="portlet portlet-green">
                        <i class="green fa fa-circle-o"></i>Ações Concluídas;  <i class="orange fa fa-circle-o"></i>Ações Pendentes; <i class="black fa fa-circle"></i>Ações Atrasadas
                        <div class="portlet-heading">
                            <div class="portlet-title">
                                <h4>Desempenho das Ações </h4>
                            </div>
                            <div class="portlet-widgets">
                                <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                <span class="divider"></span>
                                <a data-toggle="collapse" data-parent="#accordion" href="#lineChart"><i class="fa fa-chevron-down"></i></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div id="lineChart" class="panel-collapse collapse in">
                            <div class="portlet-body">
                                <div id="morris-chart-line"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Ações por Superintendência  

                     GRÁFICO DE PIZZA
            -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="portlet portlet-default">
                        <div class="portlet-heading">
                            <div class="portlet-title">
                                <h4>Status do Escopo - Fases</h4>
                            </div>
                            <div class="portlet-widgets">
                                <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                <span class="divider"></span>
                                <a data-toggle="collapse" data-parent="#escoporesumido" href="#lineChart"><i class="fa fa-chevron-down"></i></a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div id="escoporesumido" class="panel-collapse collapse in">
                            <div class="portlet-body">

                                <?php
                                $fases = $this->projetos_model->getAllFaseByProjeto();
                                foreach ($fases as $fase) {
                                    $fase_id = $fase->id;

                                    $soma_porc_acoes_concluidas_fase = 0;
                                    $soma_porc_acoes_pendentes_fase = 0;
                                    $soma_porc_acoes_atrasadas_fase = 0;
                                    $cont_qtde_item_fase = 0;
                                    $cont_qtde_item_evento_fase = 0;
                                    $coma_total_acoes_itens = 0;
                                    $coma_total_acoes_concluidas_itens = 0;
                                    $soma_valores_zerado = 0;


                                     $ordem = 'ordem';
                                     $eventos = $this->projetos_model->getAllEventosProjetoByFase($fase_id);
                                     foreach ($eventos as $evento) {


                                           $soma_acoes_evento = 0;
                                           $cont_qtde_item_evento = 0;
                                           $intes_eventos2 = $this->projetos_model->getAllItemEventosProjeto($evento->id,'tipo','asc');
                                           foreach ($intes_eventos2 as $item2) {

                                                    $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item2->id);

                                                    //Qtde de Ações concluídas
                                                    $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item2->id);
                                                    $quantidade_acoes_concluidas_item =  $concluido->quantidade;
                                                    $coma_total_acoes_concluidas_itens += $quantidade_acoes_concluidas_item;

                                                    //Qtde de ações Pendentes
                                                    $item_pendente =    $this->projetos_model->getAcoesPendentesByItemEvento($item2->id);
                                                    $item_avalidacao =  $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item2->id);
                                                    $itens_pendentes =  $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                    $soma_porc_acoes_pendentes_fase += $itens_pendentes;

                                                    $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item2->id);
                                                    $acoes_atrasadas = $atrasadas->quantidade;
                                                    $soma_porc_acoes_atrasadas_fase += $acoes_atrasadas;

                                                    $cont_qtde_item_fase += $quantidade_acoes_item->quantidade;



                                                   /*
                                                     *  SOMA AS PORCENTAGENS DO ÍTEM PARA GERAR A MÉDIA DO EVENTO
                                                     */


                                                    $cont_qtde_item_evento ++;
                                                   // $soma_valores_zerado ++;
                                                   $cont_qtde_item_evento_fase ++;

                                                     if ($quantidade_acoes_item->quantidade == 0){
                                                        //$cont_qtde_item_fase += 1;

                                                    }

                                                 //   echo 'TIPO'.$tipo_evento.' / '.$evento->nome_evento.' / '.$item2->descricao.'; Conc : '.$quantidade_acoes_concluidas_item.'; Pend : '.$itens_pendentes.'; Atra : '.$soma_porc_acoes_atrasadas_fase.'; Total : '.$cont_qtde_item_fase;

                                                   // echo '<br>';

                                           }


                                             if($cont_qtde_item_evento == 0){

                                                 $soma_valores_zerado += 1;


                                            }else{
                                             //   $soma_itens_sem_acao += $cont_qtde_item_evento;
                                            }





                                     }

                                  //   echo 'total : '.$soma_acoes_evento.' Conc :'.$coma_total_acoes_concluidas_itens;

                                     $total_acoes_projeto = $cont_qtde_item_fase + $soma_itens_sem_acao + $soma_valores_zerado;
                                     $total_acoes_pendentes =  $soma_porc_acoes_pendentes_fase + $soma_valores_zerado + $soma_itens_sem_acao;

                                     $porc_concluido = ($coma_total_acoes_concluidas_itens * 100)/$total_acoes_projeto;
                                     $porc_pendente = ($total_acoes_pendentes * 100)/$total_acoes_projeto;
                                     $porc_atrasado = ($soma_porc_acoes_atrasadas_fase * 100)/$total_acoes_projeto;

                                ?>


                                    <table style="width:100%; background-color: gray" id="example-table" class="table table-striped table-bordered table-hover table-striped">

                                            <tr>
                                                <td style="width: 20%; ">
                                                    <div class="portlet-title">
                                                        <h4><?php echo $fase->nome_fase; ?></h4>
                                                    </div>
                                                </td>
                                                <td style="width: 80%; background-color: #a0a0a0;">
                                                    <div class="progress">
                                                      <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido;  ?>%">
                                                       <?php if($porc_concluido != 100){ echo  substr($porc_concluido,0,5); }else{ echo $porc_concluido; } ?> % Concluído
                                                      </div>
                                                      <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente;  ?>%">
                                                       <?php if($porc_pendente != 100){ echo  substr($porc_pendente,0,5); }else{ echo $porc_pendente; } ?>% Pendente
                                                      </div>
                                                      <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php  echo $porc_atrasado;  ?>%">
                                                       <?php if($porc_atrasado != 100){ echo  substr($porc_atrasado,0,5); }else{ echo $porc_atrasado; } ?>% Atrasado
                                                      </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>




                                    <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div  class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div style="width: 100%; height: 430px;" class="portlet portlet-blue">
                                <div class="portlet-heading">
                                    <div class="portlet-title">
                                        <h4>Total de Ações por Área</h4>
                                    </div>
                                    <div class="portlet-widgets">
                                        <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                        <span class="divider"></span>
                                        <a data-toggle="collapse" data-parent="#accordion" href="#donutChart"><i class="fa fa-chevron-down"></i></a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                    <script type="text/javascript">
                                            google.charts.load('current', {'packages':['corechart']});
                                    google.charts.setOnLoadCallback(drawChart);

                                    function drawChart() {

                                                        var data = google.visualization.arrayToDataTable([
                                                        ['Área', 'Porcentagem'],
                                        <?php
                                        foreach ($areas_projeto as $area_pai) {


                                            $id_pai = $area_pai->id_pai;
                                            $superintendencia = $area_pai->descricao;

                                            $quantidade_area = $this->projetos_model->getAcoesSetorPaiByProjeto($id_pai);
                                            $qtde_area2 = $quantidade_area->qtde;
                                            ?>
                                                     ['<?php echo $superintendencia; ?>', <?php echo $qtde_area2; ?>],

                                            <?php
                                        }
                                        ?>
                         ]);
                                   var options = {
                                            title: 'Total de Ações divididas por Áreas'
                                    };

                                    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                                    chart.draw(data, options);
                                    }
                                    </script>


                                    <div id="piechart" style="width: 100%; height: 389px;"></div>
                                </div>
                            </div>

                            <!-- /.col-lg-12 -->

                        </div>
                    </div>
                </div> 

                <!-- /.row -->



                <!-- TIMELINE -->
                <div class="row">
                        <div class="col-lg-12">
                            <div class="portlet portlet-green">
                                <div class="portlet-heading">
                                    <div class="portlet-title">
                                        <h4>AÇÕES POR ÁREA</h4>
                                    </div>
                                    <div class="portlet-widgets">

                                        <span class="divider"></span>
                                        <a data-toggle="collapse"  href="#main_content_wrap2"><i class="fa fa-chevron-down"></i></a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <BR>
                                <div id="main_content_wrap2" class="outer">
                                    <div class="row">

                                      <div class="col-lg-12">
                                            <div class="row">
                                                <!-- Relógio  -->
                                                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                                <script type="text/javascript">
                                              google.charts.load('current', {'packages':['gauge']});
                                              google.charts.setOnLoadCallback(drawChart);

                                              function drawChart() {

                                                var data = google.visualization.arrayToDataTable([
                                                  ['Label', 'Value'],
                                                   <?php

                                                $cont = 1;
                                                foreach ($areas_projeto as $area_projeto) {

                                                    $id_pai = $area_projeto->id_pai;
                                                    $superintendencia = $area_projeto->descricao;


                                                    // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                                    $quantidade_area = $this->projetos_model->getAcoesSetorPaiByProjeto($id_pai);
                                                    $qtde_area = $quantidade_area->qtde;

                                                    $perc_area = ($qtde_area * 100) / $total_acoes;

                                                    if (($perc_area < 1) && ($perc_area > 0)) {
                                                        $perc_area = 1;
                                                    }

                                                    //qtde AÇÕES CONCLUÍDAS POR ÁREA
                                                    $acoes_concluidas_area = $this->projetos_model->getAcoesConcluidasSetorPaiByProjeto($id_pai);
                                                    $concluidas_setor_pai = $acoes_concluidas_area->qtde;
                                                    //qtde AÇÕES ´PENDENTE POR ÁREA
                                                    $acoes_pendente_area = $this->projetos_model->getAcoesPendenteSetorPaiByProjeto($id_pai, 1);
                                                    $pendente_area =  $acoes_pendente_area->qtde;

                                                    //qtde AÇÕES ATRASADAS POR ÁREA
                                                    $acoes_atrasadas_area = $this->projetos_model->getAcoesPendenteSetorPaiByProjeto($id_pai, 2);
                                                    $atrasadas_area =  $acoes_atrasadas_area->qtde;

                                                    //PERCENTUAL DE CONCLUSÃO POR ÁREA
                                                    $perc_conc_area = ($concluidas_setor_pai * 100) / $qtde_area;
                                                    if ($perc_conc_area == 0) {
                                                        $perc_conc_area = 0;
                                                    }
                                                    ?>
                                                     ['<?php echo $superintendencia; ?>', <?php echo substr("$perc_conc_area", 0, 3); ?>],
                                                   <?php 
                                                   }

                                                    ?>


                                                ]);

                                                var options = {
                                                  width: 400, height: 200,

                                                  redFrom: 0, redTo: 60,
                                                  yellowFrom:61, yellowTo: 90,
                                                  greenFrom:91, greenTo: 100,
                                                  minorTicks: 5
                                                };

                                                var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

                                                chart.draw(data, options);

                                              }
                                            </script>
                                                <div class="col-sm-12">
                                                    <div id="chart_div" style="width: 100%; height: 200px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <br><br>
                                        <!-- tabela  -->
                                      <div class="col-lg-12">
                                        <br><br>
                                          <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                         <script type="text/javascript">
                                          google.charts.load('current', {'packages':['table']});
                                          google.charts.setOnLoadCallback(drawTable);

                                          function drawTable() {
                                            var data = new google.visualization.DataTable();


                                            data.addColumn('string', 'Area');
                                            data.addColumn('string', 'Total');
                                            data.addColumn('string', 'Concluídas');
                                            data.addColumn('string', 'Pendentes');
                                            data.addColumn('string', 'Atrasadas');
                                            data.addRows([
                                                 <?php
                                                foreach ($areas_projeto as $area_projeto) {
                                                    $id_pai = $area_projeto->id_pai;
                                                    $superintendencia = $area_projeto->descricao;
                                                    // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                                    $quantidade_area = $this->projetos_model->getAcoesSetorPaiByProjeto($id_pai);
                                                    $qtde_area = $quantidade_area->qtde;

                                                    //qtde AÇÕES CONCLUÍDAS POR ÁREA
                                                    $acoes_concluidas_area = $this->projetos_model->getAcoesConcluidasSetorPaiByProjeto($id_pai);
                                                    $concluidas_setor_pai = $acoes_concluidas_area->qtde;
                                                    //qtde AÇÕES ´PENDENTE POR ÁREA
                                                    $acoes_pendente_area = $this->projetos_model->getAcoesPendenteSetorPaiByProjeto($id_pai, 1);
                                                    $pendente_area =  $acoes_pendente_area->qtde;
                                                    //qtde AÇÕES ATRASADAS POR ÁREA
                                                    $acoes_atrasadas_area = $this->projetos_model->getAcoesPendenteSetorPaiByProjeto($id_pai, 2);
                                                    $atrasadas_area =  $acoes_atrasadas_area->qtde;
                                                    ?>

                                                     ['<?php echo $superintendencia; ?>',  '<?php echo $qtde_area; ?>', '<?php echo $concluidas_setor_pai; ?>', '<?php echo $pendente_area; ?>', '<?php echo $atrasadas_area; ?>'],

                                                    <?php
                                                    //$cont++;
                                                }

                                                ?>


                                            ]);

                                            var table = new google.visualization.Table(document.getElementById('table_div'));

                                            table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
                                          }
                                        </script>
                                        <div class="row">
                                            <div class="col-lg-12">
                                          <div id="table_div"></div>  
                                          </div>
                                        </div>    
                                        <br>
                                    </div>   

                                    </div>
                            </div>

                        </div>
                    </div>


                    <?php
                    function geraTimestamp($data) {
                        $partes = explode('/', $data);
                        return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
                    }
                    ?>
                <!-- 
                AÇÕES POR ÁREA E SETOR
                -->
                <div class="row">
                    <div class="col-lg-12">

                        <div class="portlet portlet-green">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>AÇÕES POR SETOR</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <ul id="myTab" class="nav nav-tabs">

                                    <?php
                                    $cont = 1;
                                    foreach ($areas_projeto as $area_projeto) {

                                       $id_pai = $area_projeto->id_pai;
                                       $superintendencia = $area_projeto->descricao;

                                        // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                        $quantidade_area = $this->projetos_model->getAcoesSetorPaiByProjeto($id_pai);
                                        $qtde_area = $quantidade_area->qtde;
                                        ?>
                                    <li <?php if ($cont == 1) { ?> class="active" <?php } ?> ><a class="  tile <?php if ($cont % 2 == 0) { ?> blue <?php } else { ?> orange <?php } ?>  " href="#<?php echo $id_pai; ?>" data-toggle="tab"><?php echo $superintendencia; ?></a>
                                    </li>

                                    <?php
                                    $cont++;
                                }
                                ?>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <?php
                                    $cont = 1;
                                    foreach ($areas_projeto as $area_projeto) {

                                           $id_pai = $area_projeto->id_pai;
                                           $superintendencia = $area_projeto->descricao;

                                            // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                            $quantidade_area = $this->projetos_model->getAcoesSetorPaiByProjeto($id_pai);
                                            $qtde_area = $quantidade_area->qtde;
                                        ?>
                                        <!-- 
                                            TABELA COM AS AÇÕES DO SETOR
                                        -->
                                        <div class="tab-pane fade in <?php if ($cont == 1) { ?> active <?php } ?>"  id="<?php echo $id_pai; ?>">

                                            <!-- 
                                                AQUI MOSTRA AS CAIXAS COM AS 
                                            QUANTIDADE DE AÇÕES DOS SETORES DA ÁREA SELECIONADA

                                            -->
                                        <?php
                                        $cont_setor_area = 1;
                                        // PEGA OS SETORES POR AREAS E POR PROJETO  $projeto,
                                         if($perfil_atual == 2){
                                             //GESTOR
                                         //   $setores_areas = $this->atas_model->getAllSetorAreaUsuario($projeto, $usuario,$id_superintendente);
                                        }else  if($perfil_atual == 3){
                                            //SUPERINTENDENTE

                                           //         $setores_areas = $this->atas_model->getAllSetorArea($projeto, $id_superintendente);//$this->projetos_model->getAllSetorArea($id_superintendente);                                                           
                                        }else{
                                            //EDP

                                        }

                                        $setores_areas = $this->atas_model->getAllSetorArea($id_pai); 
                                        $cont_caixa = 1;
                                        foreach ($setores_areas as $setor_area) {

                                            $id_setor = $setor_area->id_setor;
                                            $nome_setor = $setor_area->descricao;


                                            // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                            $quantidade_acoes_setor = $this->atas_model->getAllitemPlanosProjetoSetorCont($id_setor);
                                            $qtde_total_acoes_setor = $quantidade_acoes_setor->quantidade;
                                            // PEGA A QTDE DE AÇÕES CONCLUÍDAS POR AREAS E POR PROJETO
                                            $quantidade_acoes_concluida_setor = $this->atas_model->getAllitemPlanosProjetoSetorContConcluido($id_setor);
                                            $qtde_concluido_setor = $quantidade_acoes_concluida_setor->quantidade;
                                            // PEGA A QTDE DE AÇÕES PENDENTES POR AREAS E POR PROJETO
                                            $quantidade_acoes_pendente_setor = $this->atas_model->getAllitemPlanosProjetoSetorContPendente('PENDENTE', $id_setor);
                                            $qtde_pendente_setor = $quantidade_acoes_pendente_setor->quantidade;
                                            // PEGA A QTDE DE AÇÕES PENDENTES POR AREAS E POR PROJETO
                                            $quantidade_acoes_atrasadas_setor = $this->atas_model->getAllitemPlanosProjetoSetorContPendente('ATRASADO',$id_setor);
                                            $qtde_atrasadas_setor = $quantidade_acoes_atrasadas_setor->quantidade;
                                            // PEGA A QTDE DE AÇÕES PENDENTES AGUARDANDO VALIDAÇÃO POR AREAS E POR PROJETO
                                            $quantidade_acoes_avalidacao_setor = $this->atas_model->getAllitemPlanosProjetoSetorContAguardandoValidacao($id_setor);
                                            $qtde_avalidacao_setor = $quantidade_acoes_avalidacao_setor->quantidade;

                                            $total_pendentes = $qtde_pendente_setor + $qtde_avalidacao_setor;

                                            //CALCULA A PERCENTAGEM
                                            $calc_conc = $qtde_concluido_setor * 100;
                                            $perc_concluida_setor = ($calc_conc / $qtde_total_acoes_setor);


                                            ?>
                                                <!-- 
                                            AQUI MOSTRA AS CAIXAS COM AS 
                                        QUANTIDADE DE AÇÕES POR SETOR
                                                -->      

                                                <div style="height: 400px; margin-top: 5px;" class="col-md-2 ">
                                                    <div style="height: 400px;" class="tile <?php if ($cont_setor_area++ % 2 == 0) { ?> gray <?php } else { ?> gray <?php } ?> dash-demo-tile">
                                                        <h4><i class="fa fa-sitemap fa-fw"></i><?php echo $nome_setor; ?> </h4>
                                                        <div id="easy-pie-1" class="easy-pie-chart" data-percent="<?php echo $perc_concluida_setor; ?>">
                                                            <span class="percent"></span>
                                                        </div>
                                                        <div>
                                                        <?php echo 'Ações : ' . $quantidade_acoes_setor->quantidade . '<br> Concluído :' . $qtde_concluido_setor . '<br> Pendentes :' . $total_pendentes. '<br> Atrasado :' . $qtde_atrasadas_setor; ?>   
                                                        </div>
                                                    </div>
                                                </div>



                                        <?php   }

                                        ?>   

                                        <div class="row">
                                                <div class="col-lg-12">

                                                       <div class="portlet-body">
                                                            <ul id="myPills" class="nav nav-pills">
                                                        <?php

                                                            $cont_setor_area2 = 1;
                                                            // PEGA OS SETORES POR AREAS E POR PROJETO  $projeto,

                                                            $setores_areas = $this->atas_model->getAllSetorArea($id_pai);
                                                            foreach ($setores_areas as $setor_area2) {
                                                                $id_setor = $setor_area2->id_setor;
                                                                $nome_setor2 = $setor_area2->descricao;
                                                                // AKI CARREGA O NOME DO SETOR NA ABA
                                                                ?>
                                                                    <li class="<?php if ($cont_setor_area2++ == 1) { ?> active <?php } ?>">
                                                                        <a href="#setor<?php echo $id_setor; ?>" data-toggle="tab"><?php echo $nome_setor2; ?></a>
                                                                    </li>

                                                            <?php } ?>   
                                                            </ul>
                                                        </div>

                                                        <div id="myPillsContent" class="tab-content">
                                                            <?php
                                                            $cont_setor_area3 = 1;
                                                            $setores_areas2 = $this->atas_model->getAllSetorArea($id_pai);

                                                          // print_r($setores_areas2);
                                                            foreach ($setores_areas2 as $setor_area3) {
                                                                    $id_setor2 = $setor_area3->id_setor;
                                                                    //echo $id_setor;
                                                                    $nome_setor3 = $setor_area3->descricao;

                                                                    // }
                                                                    ?>
                                                                    <div class="tab-pane fade in <?php if ($cont_setor_area3 == 1) { ?> active <?php } ?>" id="setor<?php echo $id_setor2; ?>">
                                                                    <!--
                                                                    AQUI CARREGA O NOME DE CASA RESPONSÁVEL PELAS AÇÕES DO SETOR
                                                                    -->
                                                                    <div class="row">
                                                                        <div class="col-lg-12">
                                                                            <div class="portlet portlet-default">
                                                                                <div class="portlet-heading">
                                                                                    <div class="portlet-title">
                                                                                        <h4>SELECIONE O NOME PARA VER AS AÇÕES</h4>
                                                                                    </div>
                                                                                    <div class="portlet-widgets">
                                                                                        <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                                                                        <span class="divider"></span>
                                                                                        <a data-toggle="collapse" data-parent="#accordion" href="#example"><i class="fa fa-chevron-down"></i></a>
                                                                                    </div>
                                                                                    <div class="clearfix"></div>
                                                                                </div>

                                                                                <div class="portlet-body">
                                                                                    <ul id="myTab" class="nav nav-tabs">
                                                                                            <?php
                                                                                            $cont_user_setor_area = 1;
                                                                                            // PEGA OS SETORES POR AREAS E POR PROJETO  $projeto,

                                                                                            $user_setores = $this->atas_model->getAllUserPlanosProjetoSetor($id_setor2); //$this->projetos_model->getAllSetorArea($id_superintendente);
                                                                                           // ECHO $setor_area3->setor_id;
                                                                                            foreach ($user_setores as $user_setor) {

                                                                                               $id = $user_setor->id;
                                                                                                $nome = $user_setor->nome;

                                                                                                ?>
                                                                                                   <li class=""><a href="#nome<?php echo $id.$id_setor2 ?>" data-toggle="tab"><?php echo $nome; ?></a>
                                                                                                   </li>
                                                                                                <?php $cont_user_setor_area++;
                                                                                            } 

                                                                                            ?>

                                                                                    </ul>
                                                                                    <div id="myTabContent" class="tab-content">
                                                                                        <?php

                                                                                        $cont_user_setor_area_acao = 1;
                                                                                        // PEGA OS SETORES POR AREAS E POR PROJETO  $projeto,
                                                                                        $user_setores2 = $this->atas_model->getAllUserPlanosProjetoSetor($id_setor2); //$this->projetos_model->getAllSetorArea($id_superintendente);
                                                                                        foreach ($user_setores2 as $user_setor2) {

                                                                                            $id1 = $user_setor2->id;
                                                                                            $nome = $user_setor2->nome;

                                                                                            ?>
                                                                                            <div class="tab-pane fade " id="nome<?php echo $id1.$id_setor2 ?>">

                                                                                                 <div class="portlet-body">
                                                                                                    <?php
                                                                                                        $wu4[''] = '';
                                                                                                        $cont_presidencia = 0;
                                                                                                        /*
                                                                                                         * CARREGA AS AÇÕES DE UM PROJETO E UM SETOR E USUÁRIO
                                                                                                         * 
                                                                                                         */
                                                                                                        $cont_quantidade_atrasado_acoes = 0;
                                                                                                        $cont_quantidade_pendente_acoes = 0;
                                                                                                        $cont_quantidade_av_acoes = 0;
                                                                                                        $cont_quantidade_concluido_acoes = 0;
                                                                                                        $acoes_setor = $this->atas_model->getAllitemPlanosProjetoSetorUser($id_setor2, $id1);

                                                                                                        foreach ($acoes_setor as $plano_precidencia) {
                                                                                                            $cont_presidencia ++;
                                                                                                            $data_prazo = $plano_precidencia->data_termino;
                                                                                                            $data_entrega = $plano_precidencia->data_retorno_usuario;
                                                                                                            $status = $plano_precidencia->status;
                                                                                                            $descricao = $plano_precidencia->descricao;

                                                                                                            if ($status == 'CONCLUÍDO') {
                                                                                                                $cont_quantidade_concluido_acoes++;
                                                                                                                /*
                                                                                                                 * SE A DATA DE CONLUSÃO FOR <= A DATA DO PRAZO
                                                                                                                 * CONCLUÍDO
                                                                                                                 */

                                                                                                                if ($data_entrega <= $data_prazo) {
                                                                                                                    $novo_status = 'CONCLUÍDO';

                                                                                                                }

                                                                                                                /*
                                                                                                                 * SE A DATA DE CONLUSÃO FOR > A DATA DO PRAZO
                                                                                                                 * CONCLUÍDO FORA DO PRAZO
                                                                                                                 */
                                                                                                                if ($data_entrega > $data_prazo) {
                                                                                                                    $novo_status = 'CONCLUÍDO FORA DO PRAZO';
                                                                                                                }
                                                                                                            } else

                                                                                                            if ($status == 'PENDENTE') {
                                                                                                                $dataHoje = date('Y-m-d H:i:s');
                                                                                                                /*
                                                                                                                 * SE A DATA ATUAL FOR < A DATA DO PRAZO
                                                                                                                 * PENDENTE
                                                                                                                 */
                                                                                                                if ($dataHoje <= $data_prazo) {
                                                                                                                    $novo_status = 'PENDENTE';
                                                                                                                    $cont_quantidade_pendente_acoes++;
                                                                                                                }

                                                                                                                /*
                                                                                                                 * SE A DATA ATUAL FOR > A DATA DO PRAZO
                                                                                                                 * ATRASADO (X DIAS)
                                                                                                                 * +5 DIAS
                                                                                                                 * +10 DIAS
                                                                                                                 * 
                                                                                                                 */
                                                                                                                if ($dataHoje > $data_prazo) {
                                                                                                                    $novo_status = 'ATRASADO';
                                                                                                                    $cont_quantidade_atrasado_acoes ++;

                                                                                                                    // Usa a função criada e pega o timestamp das duas datas:
                                                                                                                    $time_inicial = geraTimestamp($this->sma->hrld($dataHoje));
                                                                                                                    $time_final = geraTimestamp($this->sma->hrld($data_prazo));
                                                                                                                    // Calcula a diferença de segundos entre as duas datas:
                                                                                                                    $diferenca = $time_final - $time_inicial; // 19522800 segundos
                                                                                                                    // Calcula a diferença de dias
                                                                                                                    $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias

                                                                                                                    if ($dias >= '-5') {
                                                                                                                        $qtde_dias = '+5';
                                                                                                                    } else if (($dias < '-5') && ($dias >= '-10')) {
                                                                                                                        $qtde_dias = '+10';
                                                                                                                    } else if ($dias < '-10') {
                                                                                                                        $qtde_dias = '+15';
                                                                                                                    }
                                                                                                                }
                                                                                                            } else 

                                                                                                            if ($status == 'AGUARDANDO VALIDAÇÃO') {
                                                                                                                $novo_status = 'AGUARDANDO VALIDAÇÃO';
                                                                                                                $cont_quantidade_av_acoes++;
                                                                                                            }
                                                                                                            ?>   

                                                                                                    <?php 

                                                                                                        }

                                                                                                    ?>

                                                                                                    <table>
                                                                                                                <tr>
                                                                                                                    <td colspan="9">
                                                                                                                        <h4>Total de Ações: <?php echo $cont_presidencia; ?></h4>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td colspan="9">
                                                                                                                        <h4>Ações Concluídas: <?php echo $cont_quantidade_concluido_acoes; ?></h4>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td colspan="9">
                                                                                                                        <h4>Ações Pendentes: <?php echo $cont_quantidade_pendente_acoes + $cont_quantidade_av_acoes; ?></h4>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td colspan="9">
                                                                                                                        <h4>Ações Atrasadas: <?php echo $cont_quantidade_atrasado_acoes; ?></h4>
                                                                                                                    </td>
                                                                                                                </tr>          
                                                                                                    </table>

                                                                                                    <br>
                                                                                                    <div class="table-responsive">
                                                                                                        <table class="table table-striped table-bordered table-hover table-green" >
                                                                                                            <thead>
                                                                                                                <tr>
                                                                                                                    <th>-</th>
                                                                                                                    <th>Id</th>
                                                                                                                    <th>Evento</th>
                                                                                                                    <th>Descrição</th>
                                                                                                                    <th>Dt Prazo</th>
                                                                                                                    <th>Dt Conclusão</th>
                                                                                                                    <th>Responsável</th>
                                                                                                                    <th>Setor</th>
                                                                                                                    <th>Status</th>

                                                                                                                </tr>
                                                                                                            </thead>
                                                                                                            <tbody>
                                                                                                                        <?php
                                                                                                                        $wu4[''] = '';
                                                                                                                        $cont_presidencia = 0;
                                                                                                                        //$acoes_por_areas = $this->projetos_model->getAllitemPlanosProjetoArea($projeto, $id_superintendente);
                                                                                                                        /*
                                                                                                                         * CARREGA AS AÇÕES DE UM PROJETO E UM SETOR E USUÁRIO
                                                                                                                         * 
                                                                                                                         */

                                                                                                                        $acoes_setor2 = $this->atas_model->getAllitemPlanosProjetoSetorUser($id_setor2, $id1);
                                                                                                                        foreach ($acoes_setor2 as $plano_precidencia) {
                                                                                                                            $cont_presidencia ++;
                                                                                                                            $data_prazo = $plano_precidencia->data_termino;
                                                                                                                            $data_entrega_demanda = $plano_precidencia->data_entrega_demanda;
                                                                                                                            $data_entrega = $plano_precidencia->data_retorno_usuario;
                                                                                                                            $status = $plano_precidencia->status;
                                                                                                                            $descricao = $plano_precidencia->descricao; //

                                                                                                                            if ($status == 'CONCLUÍDO') {

                                                                                                                                /*
                                                                                                                                 * SE A DATA DE CONLUSÃO FOR <= A DATA DO PRAZO
                                                                                                                                 * CONCLUÍDO
                                                                                                                                 */
                                                                                                                                if ($data_entrega <= $data_prazo) {
                                                                                                                                    $novo_status = 'CONCLUÍDO';
                                                                                                                                }

                                                                                                                                /*
                                                                                                                                 * SE A DATA DE CONLUSÃO FOR > A DATA DO PRAZO
                                                                                                                                 * CONCLUÍDO FORA DO PRAZO
                                                                                                                                 */
                                                                                                                                if ($data_entrega > $data_prazo) {
                                                                                                                                    $novo_status = 'CONCLUÍDO FORA DO PRAZO';
                                                                                                                                }
                                                                                                                            } else

                                                                                                                            if ($status == 'PENDENTE') {
                                                                                                                                $dataHoje = date('Y-m-d H:i:s');
                                                                                                                                /*
                                                                                                                                 * SE A DATA ATUAL FOR < A DATA DO PRAZO
                                                                                                                                 * PENDENTE
                                                                                                                                 */
                                                                                                                                if ($dataHoje <= $data_prazo) {
                                                                                                                                    $novo_status = 'PENDENTE';
                                                                                                                                }

                                                                                                                                /*
                                                                                                                                 * SE A DATA ATUAL FOR > A DATA DO PRAZO
                                                                                                                                 * ATRASADO (X DIAS)
                                                                                                                                 * +5 DIAS
                                                                                                                                 * +10 DIAS
                                                                                                                                 * */

                                                                                                                                if ($dataHoje > $data_prazo) {
                                                                                                                                    $novo_status = 'ATRASADO';


                                                                                                                                    // Usa a função criada e pega o timestamp das duas datas:
                                                                                                                                    $time_inicial = geraTimestamp($this->sma->hrld($dataHoje));
                                                                                                                                    $time_final = geraTimestamp($this->sma->hrld($data_prazo));
                                                                                                                                    // Calcula a diferença de segundos entre as duas datas:
                                                                                                                                    $diferenca = $time_final - $time_inicial; // 19522800 segundos
                                                                                                                                    // Calcula a diferença de dias
                                                                                                                                    $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias

                                                                                                                                    if ($dias >= '-5') {
                                                                                                                                        $qtde_dias = '+5';
                                                                                                                                    } else if (($dias < '-5') && ($dias >= '-10')) {
                                                                                                                                        $qtde_dias = '+10';
                                                                                                                                    } else if ($dias < '-10') {
                                                                                                                                        $qtde_dias = '+15';
                                                                                                                                    }
                                                                                                                                }
                                                                                                                            } else

                                                                                                                            if ($status == 'AGUARDANDO VALIDAÇÃO') {
                                                                                                                                $novo_status = 'AGUARDANDO VALIDAÇÃO';
                                                                                                                            }
                                                                                                                            ?>   
                                                                                                                    <tr class="odd gradeX">
                                                                                                                        <td><?php echo $cont_presidencia; ?></td>
                                                                                                                        <td><?php echo $plano_precidencia->idplanos; ?></td>
                                                                                                                        <td class="center"><?php echo $plano_precidencia->eventos.' / '.$plano_precidencia->item; ?> </td>
                                                                                                                        <td class="center"><?php echo $plano_precidencia->descricao; ?> </td>
                                                                                                                        <td class="center"><?php echo 'De : ' .date('d/m/Y', strtotime($data_prazo)). '<br> Até : '. date('d/m/Y', strtotime($data_entrega_demanda)); ?></td>
                                                                                                                        <td class="center"><?php
                                                                                                                    if ($plano_precidencia->status == 'CONCLUÍDO') {
                                                                                                                        echo $this->sma->hrld($plano_precidencia->data_retorno_usuario);
                                                                                                                    }
                                                                                                                    ?></td>
                                                                                                                        <td class="center"><?php echo $plano_precidencia->username; ?></td>
                                                                                                                        <td class="center"><?php echo $plano_precidencia->setor ?> </td>
                                                                                                                    <?php if ($novo_status == 'CONCLUÍDO') { ?>
                                                                                                                            <td style="background-color: #007700;color: #ffffff" class="center"><?php echo $novo_status; ?></td>
                                                                                                                    <?php } else if ($novo_status == 'CONCLUÍDO FORA DO PRAZO') {
                                                                                                                        ?>
                                                                                                                            <td style="background-color: #99ca63" class="center"><?php echo $novo_status; ?></td>
                                                                                                                                <?php } else if ($novo_status == 'PENDENTE') {
                                                                                                                                    ?>
                                                                                                                            <td style="background-color: #CB3500;color: #ffffff" class="center"><?php echo $novo_status; ?></td>

                                                                                                                                <?php
                                                                                                                            } else if ($novo_status == 'ATRASADO') {

                                                                                                                                if ($dias >= '-5') {
                                                                                                                                    ?>
                                                                                                                                <td style=" background-color: #c7254e; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></td>
                                                                                                                            <?php } else if (($dias < '-5') && ($dias >= '-10')) { ?> 
                                                                                                                                <td style=" background-color: #d2322d; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></td>

                                                                                                                            <?php } else if ($dias < '-10') { ?> 
                                                                                                                                <td style=" background-color: #000000; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></td>

                                                                                                                            <?php } ?> 


                                                                                                                        <?php } else if ($novo_status == 'AGUARDANDO VALIDAÇÃO') { ?>
                                                                                                                            <td style=" background-color: orange; color: #ffffff;" class="center"><?php echo $novo_status; ?></td>



                                                                                                                        <?php }else{ ?> 
                                                                                                                            <td style=" background-color: orange; color: #ffffff;" class="center">-</td>
                                                                                                                        <?php } ?>     

                                                                                                                    </tr>
                                                                                                                        <?php
                                                                                                                    }
                                                                                                                    ?>

                                                                                                            </tbody>

                                                                                                        </table>
                                                                                                    </div>
                                                                                                    <!-- /.table-responsive -->
                                                                                                </div>


                                                                                            </div>
                                                                                                <?php $cont_user_setor_area_acao++;
                                                                                            }  ?>

                                                                                        </div>
                                                                                </div>
                                                                                            <!-- /.portlet-body -->
                                                                            </div>
                                                                            <!-- /.portlet -->
                                                                        </div>
                                                                    </div>
                                                                    <!-- FIM AÇÃO POR USUARIO -->



                                                                </div>

                                                               <?php
                                                                $cont_setor_area3++;
                                                                }
                                                              ?>   
                                                            </div>

                                                 </div>
                                            </div>
                                       </div>
                                        <?php
                                        $cont++;
                                    }
                                    ?>       
                                </div>
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>
                    <!-- /.col-lg-12 -->
                </div>


            </div>
            <!-- /.page-content -->

        </div>
        </div>
            
            
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/defaults.js"></script>            
           
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?= $assets ?>dashboard/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <!-- HubSpot Messenger -->
    <script src="<?= $assets ?>dashboard/js/plugins/messenger/messenger.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/messenger/messenger-theme-flat.js"></script>
    <!-- Date Range Picker -->
    <script src="<?= $assets ?>dashboard/js/plugins/daterangepicker/moment.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Morris Charts -->
    <script src="<?= $assets ?>dashboard/js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/morris/morris.js"></script>
    <!-- Flot Charts -->
    <script src="<?= $assets ?>dashboard/js/plugins/flot/jquery.flot.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/flot/jquery.flot.resize.js"></script>
    <!-- Sparkline Charts -->
    <script src="<?= $assets ?>dashboard/js/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- Moment.js -->
    <script src="<?= $assets ?>dashboard/js/plugins/moment/moment.min.js"></script>
    <!-- jQuery Vector Map -->
    <script src="<?= $assets ?>dashboard/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/jvectormap/maps/jquery-jvectormap-world-mill-en.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/map-demo-data.js"></script>
    <!-- Easy Pie Chart -->
    <script src="<?= $assets ?>dashboard/js/plugins/easypiechart/jquery.easypiechart.min.js"></script>
    <!-- DataTables -->
    <script src="<?= $assets ?>dashboard/js/plugins/datatables/jquery.dataTables.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/datatables/datatables-bs3.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/flex.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/dashboard-demo.js"></script>   

        




       <script> 
                            //Date Range Picker
                            $(document).ready(function() {
                                        $('#reportrange').daterangepicker({
                                        startDate: moment().subtract('days', 29),
                                        endDate: moment(),
                                        minDate: '01/01/2012',
                                        maxDate: '31/12/2022',
                        dateLimit: {
                        days: 60
                },
                showDropdowns: true,
                showWeekNumbers: true,
                        timePicker: false,
                        timePickerIncrement: 1,
                                  timePicker12Hour: true,
                                  ranges: {
                                'Today': [moment(), moment()],
                                        'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                                        'Last 7 Days': [moment().subtract('days', 6), moment()],
                                        'Last 30 Days': [moment().subtract('days', 29), moment()],
                                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        },
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
            format: 'MM/DD/YYYY',
                                separator: ' to ',
                                        locale: {
                                applyLabel: 'Submit',
                                fromLabel: 'From',
                                toLabel: 'To',
                                customRangeLabel: 'Custom Range',
                                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                firstDay: 1
                                }
                                },
                                function(start, end) {
                                console.log("Callback has been called!");
                        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                                        }
                                        );
                                    //Set the initial state of the picker label
                            $('#reportrange span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
                });
                
                
                </script> 
        
        <script>             

//Morris Line Chart
Morris.Line({
// ID of the element in which to draw the chart.
element: 'morris-chart-line',
        // Chart data records -- each entry in this array corresponds to a point on
// the chart.
            data: [
    <?php
    foreach ($acoes_tempo as $acao_tempo) {

        $t_projeto = $acao_tempo->projeto;
       // if($t_projeto == $projeto_selecionado){
        $t_data = substr("$acao_tempo->data", 0, 10);
        $n_data = implode("/", array_reverse(explode("-", $t_data)));
        $t_concluido = $acao_tempo->total_concluido;
        $t_fora_prazo = $acao_tempo->total_fora_prazo;
        $t_pendente = $acao_tempo->total_pendentes;
        $t_atrasado = $acao_tempo->total_atrasados;

        $total_concluido = $t_concluido + $t_fora_prazo;
       // }
        ?>


{ d: <?php echo "'" . $t_data . "'"; ?>, concluidos: <?php echo $total_concluido; ?>, pendentes: <?php echo $t_pendente; ?>, atrasadas: <?php echo $t_atrasado; ?> },

        <?php
    }
    ?>
            ],
                // The name of the data record attribute that contains x-visitss.
xkey: 'd',
xLabelFormat: function(date) {
return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
             },
             // A list of names of data record attributes that contain y-visitss.
             ykeys: ['concluidos',  'pendentes', 'atrasadas'],
         // Labels for the ykeys -- will be displayed when you hover over the
// chart. '#99ca63','#16a085','#f39c12' ,'#000000'
lineColors: ['green','orange' ,'#000000'],
    labels: ['Concluídos',  'Pendentes', 'Atrasadas'],
            // Disables line smoothing
                smooth: true,
                resize: true,
        grid: {
                    hoverable: true
   },
dateFormat: function(date) {
                    d = new Date(date);
return d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear();
                },
                tooltip: true
});   

</script> 

            <script> 

              //Easy Pie Charts
              $(function() {
                    $('#easy-pie-1, #easy-pie-2, #easy-pie-3, #easy-pie-4').easyPieChart({
            barColor: "rgba(255,255,255,.5)",
                    trackColor: "rgba(255,255,255,.5)",
                    scaleColor: "rgba(255,255,255,.5)",
                    lineWidth: 20,
                    animate: 1500,
                    size: 175,
                    onStep: function(from, to, percent) {
                    $(this.el).find('.percent').text(Math.round(percent));
            }
            });

                });

            //DataTables Initialization for Map Table Example
               $(document).ready(function() {
                            $('#map-table-example').dataTable();
               });


    //Flot Pie Chart with Tooltips
$(function () {

                            var data = [
                <?php
                foreach ($areas_projeto as $area_projeto) {


                    $id_superintendente = $area_projeto->id_superintendencia;
                    $superintendencia = $area_projeto->superintendencia;

                    $projeto = $area_projeto->projeto;

                    $quantidade_area = $this->projetos_model->getAcoesSuperintendenciaByProjeto($projeto, $id_superintendente);
                    $qtde_area = $quantidade_area->qtde;

                    $perc_area = ($qtde_area * 100) / $total_acoes_areas;



                    if (($perc_area < 1) && ($perc_area > 0)) {
                        $perc_area = 1;
                    }
                    ?>

                                { label: "<?php echo $superintendencia; ?>", data: <?php echo $perc_area; ?> },

                    <?php
                }
                ?>        
            ];

        var plotObj = $.plot($("#flot-chart-pie"), data, {
                                    series: {
                                    pie: {
                                    show: true
            }
               },
               grid: {
                                            hoverable: true
            },
            tooltip: true,
            tooltipOpts: {
                                            content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                                            shifts: {
                                            x: 20,
                                                    y: 0
    },
            defaultTheme: false
            }
                });

            });


</script>


   <!-- CRONOGRAMA. -->




</body>

</html>
