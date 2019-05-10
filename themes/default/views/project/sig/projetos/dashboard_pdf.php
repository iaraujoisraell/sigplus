<?php
//$assets = 'themes/default/assets/'

?>

    
<!DOCTYPE html>
<html lang="en">

    <head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    

   
    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/jquery.js"></script>
    <![endif]-->
    <noscript><style type="text/css">#loading { display: none; }</style></noscript>
    <?php if ($Settings->user_rtl) { ?>
        <link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet"/>
        <link href="<?= $assets ?>styles/style-rtl.css" rel="stylesheet"/>
        <script type="text/javascript">
            $(document).ready(function () { $('.pull-right, .pull-left').addClass('flip'); });
        </script>
    <?php } ?>
    <script type="text/javascript">
        $(window).load(function () {
            $("#loading").fadeOut("slow");
        });
        </script>
    <script>
$(document).on('change', "[name='perfil_usario']", function(){
   getState();
});

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
    <link href="<?= $assets ?>dashboard/css/styles.css" rel="stylesheet">
    <link href="<?= $assets ?>dashboard/css/plugins.css" rel="stylesheet">

    <!-- THEME DEMO STYLES - Use these styles for reference if needed. Otherwise they can be deleted. -->
    <link href="<?= $assets ?>dashboard/css/demo.css" rel="stylesheet">
    
        <!-- CRONOGRAMA. -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?= $assets ?>cronograma/stylesheets/stylesheet.css">
   
     <script>
           function imprime() {
            setTimeout(function () { window.print(); }, 500);
                   
           
    }
    </script>
             <script language='JavaScript'>
function e()
{
  setTimeout("window.close()",50)
}
window.onafterprint = e;
</script>
        
</head>

<STYLE media=print>.noprint {
DISPLAY: none
}
</STYLE>
<body onload="imprime();">

    <div id="wrapper">

       
         <!-- begin TOP NAVIGATION  style="background-color: seagreen; position: relative; width: 100%; height: 50px;"-->
         <nav style="width: 100%;"  >
          <p class="text-center"><img width="100%;" height="100%" src="<?= base_url() ?>assets/uploads/logos/cabecalho_unimed.png " ></p>
             </nav> 
                    
                    <?php
                     $usuario = $this->session->userdata('user_id');
                    $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    $perfil_atual = $projetos->group_id;
                    $perfis_user = $this->site->getUserGroupAtual($perfil_atual);

                   $perfis_user = $this->site->getPerfilusuarioByID($usuario);
                   $qtde_perfis_user = 0;
                       foreach ($perfis_user as $item) {
                           $qtde_perfis_user++;
                       }
                        
                    ?>
              
    
        <!-- /.navbar-top -->
        <!-- end TOP NAVIGATION -->
        <!-- begin SIDE NAVIGATION -->
        
        <!-- /.navbar-side -->
        <!-- end SIDE NAVIGATION -->

        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">
                <!-- begin PAGE TITLE AREA -->
                <!-- Use this section for each page's title and breadcrumb layout. In this example a date range picker is included within the breadcrumb. -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title center">
                            <h1>DASHBOARD -<?php  echo $projetos->projeto; ?></h1>
                            
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE AREA -->

                <!-- begin DASHBOARD CIRCLE TILES -->
                <div class="row">
                    
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 16%">
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

                                    </div>
                                </div>
                            </td>
                            <td style="width: 16%">
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

                                    </div>
                                </div>
                            </td>
                            <td style="width: 17%">
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

                                    </div>
                                </div>
                            </td>
                            
                            <td style="width: 17%">
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
                                    </div>
                                </div>
                            </td>
                            
                            <td style="width: 17%">
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
                                    </div>
                                </div>
                            </td>
                            <td style="width: 17%">
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

                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                        
                    
                </div>
                <!-- Line Chart Example -->
                
                
                
                <div class="row">
                    <table style="width: 100%">
                        <tr>
                            <td>
                                <div class="col-lg-10">
                                    <div class="portlet portlet-blue">
                                        <div class="portlet-heading">
                                            <div class="portlet-title">
                                                <h4>TIMELINE DOS EVENTOS</h4>
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                        </div>
                                        <div id="main_content_wrap" class="outer">
                                            <section id="main_content" class="inner">


                                                <div style="width: 100%; min-height: 500px; height: 500px;" id="example" ></div>
                                                

                                        </div>

                                        
                                    </div>

                                </div> 
                            </td>
                        </tr>
                    </table>    
                    
                    </div>
                </div>
                
                
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

            $partes_fim_n = explode("-", $data_ini);
            $dia_fim_n = $partes_fim_n[2];
            $mes_fim_n = $partes_fim_n[1] - 1;
            $ano_fim_n = $partes_fim_n[0];
            $nova_data_fim = $ano_fim_n . ',' . $mes_fim_n . ',' . $dia_fim_n;
            ?>    
                [ "<?php echo $evento->tipo; ?>", "<?php echo $evento->nome_evento; ?>",  new Date("<?php echo $nova_data_ini; ?>"), new Date("<?php echo $data_fim_n; ?>") ],


            <?php
        }
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
                    <div class="col-lg-10">
                        <div class="portlet portlet-green">
                            <i class="green fa fa-circle-o"></i>Açõs Concluídas;  <i class="orange fa fa-circle-o"></i>Açõs Pendentes; <i class="black fa fa-circle"></i>Açõs Atrasadas
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Status das Açõs no tempo do projeto</h4>
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
                
                
                 <!-- Ações por Superintendência  -->
                 <br>   <br>   <br>   <br>   <br> <br>   <br>   <br>   <br>   <br> <br>  
                 <!-- Ações por Superintendência  -->
                              <!-- Ações por Superintendência  
           
                         GRÁFICO DE PIZZA
                -->
                


              
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
                                            foreach ($areas_projeto as $area_projeto2) {


                                                $id_superintendente = $area_projeto2->id_superintendencia;
                                                $superintendencia = $area_projeto2->superintendencia;

                                                $projeto = $area_projeto2->projeto;

                                                $quantidade_area = $this->projetos_model->getAcoesSuperintendenciaByProjeto($projeto, $id_superintendente);
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
    
    
   
    
        
                    <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Ações Concluídas por Área</h4>
                                </div>
                                
                                <div class="clearfix"></div>
                            </div>
                                <br>
                                
                                
                               
            
          
                                
                                <table style="width: 100%">
                                        <tr>
                                         <td>ÁREA </td>
                                         <td>% CONCLUSÃO </td>
                                         <td>TOTAL DE AÇÕES</td>
                                         <td>AÇÕES CONCLUÍDAS </td>
                                         <td>AÇÕES PENDENTES </td>
                                         <td>AÇÕES ATRASADAS </td>
                                        </tr>
                                       
                                    
                            <?php
       
                                                                                    $cont = 1;
                                        foreach ($areas_usuario_projeto as $area_projeto) {

                                            $id_superintendente = $area_projeto->id_superintendencia;
                                            $superintendencia = $area_projeto->superintendencia;
                                            $projeto = $area_projeto->projeto;

                                            // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                            $quantidade_area = $this->projetos_model->getAcoesSuperintendenciaByProjeto($projeto, $id_superintendente);
                                            $qtde_area = $quantidade_area->qtde;

                                            $perc_area = ($qtde_area * 100) / $total_acoes_areas;

                                            if (($perc_area < 1) && ($perc_area > 0)) {
                                                $perc_area = 1;
                                            }

                                            //qtde AÇÕES CONCLUÍDAS POR ÁREA
                                            $acoes_concluidas_area = $this->projetos_model->getAcoesConcluidasSuperintendenciaByProjeto($projeto, $id_superintendente);

                                            //qtde AÇÕES ´PENDENTE POR ÁREA
                                            $acoes_pendente_area = $this->projetos_model->getAcoesPendenteSuperintendenciaByProjeto('PENDENTE', $projeto, $id_superintendente);
                                            $pendente_area = $acoes_pendente_area->qtde;

                                            //qtde AÇÕES ATRASADAS POR ÁREA
                                            $acoes_atrasadas_area = $this->projetos_model->getAcoesPendenteSuperintendenciaByProjeto('ATRASADO', $projeto, $id_superintendente);
                                            $atrasadas_area = $acoes_atrasadas_area->qtde;

                                            //PERCENTUAL DE CONCLUSÃO POR ÁREA
                                            $perc_conc_area = ($acoes_concluidas_area->qtde * 100) / $qtde_area;
                                            if ($perc_conc_area == 0) {
                                                $perc_conc_area = 0;
                                            }
                                            ?>
                                            <tr>
                                                <td ><h3 > <?php echo $superintendencia; ?></h3></td>
                                                <td><h3 > <?php echo substr($perc_conc_area, 0, 4); ?> %</h3>  </td>
                                                <td><h3 > <?php echo $qtde_area; ?> </h3>  </td>
                                                <td><h3 > <?php echo $acoes_concluidas_area->qtde; ?></h3>  </td>
                                                <td><h3 > <?php echo $pendente_area; ?> </h3>  </td>
                                                <td><h3> <?php echo $atrasadas_area; ?></h3>  </td>
                                            </tr>
                                            <?php
                                            $cont++;
                                        }
                                        ?>
                           
                            </table>
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
                                <div  class="portlet-body">
                                   
                                    <div id="myTabContent" class="tab-content">
                                        <table style="width: 100%">
                                                                 <tr>
                                                             <td>SETOR </td>
                                                             <td>% CONCLUSÃO </td>
                                                             <td>TOTAL DE AÇÕES</td>
                                                             <td>AÇÕES CONCLUÍDAS </td>
                                                             <td>AÇÕES PENDENTES </td>
                                                             <td>AÇÕES ATRASADAS </td>
                                                            </tr>
                                        <?php
                                        $cont = 1;
                                        foreach ($areas_projeto as $area_projeto) {

                                            $id_superintendente = $area_projeto->id_superintendencia;
                                            $superintendencia = $area_projeto->superintendencia;
                                            $projeto = $area_projeto->projeto;

                                            // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                            $quantidade_area = $this->projetos_model->getAcoesSuperintendenciaByProjeto($projeto, $id_superintendente);
                                            $qtde_area = $quantidade_area->qtde;
                                            ?>
                                            <!-- 
                                                TABELA COM AS AÇÕES DO SETOR
                                            -->
                                            <div  class="tab-pane fade in active "  id="<?php echo $id_superintendente; ?>">

                                                <!-- 
                                                    AQUI MOSTRA AS CAIXAS COM AS 
                                                QUANTIDADE DE AÇÕES DOS SETORES DA ÁREA SELECIONADA
                                              
                                                -->
                                            <?php
                                            $cont_setor_area = 1;
                                            // PEGA OS SETORES POR AREAS E POR PROJETO  $projeto,
                                            if($perfil_atual == 2){
                                                 //GESTOR
                                                $setores_areas = $this->atas_model->getAllSetorAreaUsuario($projeto, $usuario,$id_superintendente);
                                            }else  if($perfil_atual == 3){
                                                //SUPERINTENDENTE
                                                   $setores_areas = $this->projetos_model->getAreasSetorByUsuarioProjeto($projeto,$usuario); //$this->projetos_model->getAllSetorArea($id_superintendente);                                                           
                                            }else{
                                                //EDP
                                                     $setores_areas = $this->atas_model->getAllSetorArea($projeto, $id_superintendente); //$this->projetos_model->getAllSetorArea($id_superintendente);
                                          
                                            }
                                            ?>
                                                     
                                                            
                                                                
                                                
                                            <?php    
                                            foreach ($setores_areas as $setor_area) {

                                                $id_setor = $setor_area->setor_id;
                                                $nome_setor = $setor_area->setor;


                                                // PEGA A QTDE DE AÇÕES POR AREAS E POR PROJETO
                                                $quantidade_acoes_setor = $this->atas_model->getAllitemPlanosProjetoSetorCont($projeto, $id_setor);
                                                // PEGA A QTDE DE AÇÕES CONCLUÍDAS POR AREAS E POR PROJETO
                                                $quantidade_acoes_concluida_setor = $this->atas_model->getAllitemPlanosProjetoSetorContConcluido($projeto, $id_setor);
                                                $qtde_concluido_setor = $quantidade_acoes_concluida_setor->quantidade;
                                                // PEGA A QTDE DE AÇÕES PENDENTES POR AREAS E POR PROJETO
                                                $quantidade_acoes_pendente_setor = $this->atas_model->getAllitemPlanosProjetoSetorContPendente('PENDENTE',$projeto, $id_setor);
                                                $qtde_pendente_setor = $quantidade_acoes_pendente_setor->quantidade;
                                                // PEGA A QTDE DE AÇÕES PENDENTES POR AREAS E POR PROJETO
                                                $quantidade_acoes_atrasadas_setor = $this->atas_model->getAllitemPlanosProjetoSetorContPendente('ATRASADO',$projeto, $id_setor);
                                                $qtde_atrasadas_setor = $quantidade_acoes_atrasadas_setor->quantidade;
                                                // PEGA A QTDE DE AÇÕES PENDENTES AGUARDANDO VALIDAÇÃO POR AREAS E POR PROJETO
                                                $quantidade_acoes_avalidacao_setor = $this->atas_model->getAllitemPlanosProjetoSetorContAguardandoValidacao($projeto, $id_setor);
                                                $qtde_avalidacao_setor = $quantidade_acoes_avalidacao_setor->quantidade;
                                                
                                                $total_pendentes = $qtde_pendente_setor + $qtde_avalidacao_setor;
                                                
                                                //CALCULA A PERCENTAGEM
                                                $calc_conc = $qtde_concluido_setor * 100;
                                                $perc_concluida_setor = ($calc_conc / $quantidade_acoes_setor->quantidade);
                                                
                                                // }
                                                ?>
                                                    <!-- 
                                                AQUI MOSTRA AS CAIXAS COM AS 
                                            QUANTIDADE DE AÇÕES POR SETOR
                                                    -->      
                                                        
                                                           
                                                             <tr>
                                                                 <td><h4><?php echo $nome_setor; ?> </h4></td> 
                                                                 <td><h4><?php echo substr($perc_concluida_setor, 0, 4); ?> %</h4></td> 
                                                                 <td><h4><?php echo $quantidade_acoes_setor->quantidade; ?> </h4></td>
                                                                 <td><h4><?php echo $qtde_concluido_setor; ?> </h4></td>
                                                                 <td><h4><?php echo $total_pendentes; ?> </h4></td>
                                                                 <td><h4><?php echo $qtde_atrasadas_setor; ?> </h4></td> 
                                                            </tr>       
                                                   
                                                   
    <?php } ?>   
                                                   

                                                
                                            </div>
                                            
                                            
    <?php
    
    /*
     * 
     */
    
    $cont++;
}
?>       
                                            </table>
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
        <!-- /#page-wrapper -->
        <!-- end MAIN PAGE CONTENT -->
   
      
    </div>
   
       
     
    
    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->
    
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
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
    
    
        <!-- Flot Charts -->
    <script src="<?= $assets ?>dashboard/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/flot/jquery.flot.pie.js"></script>
    <!-- Flot Demo/Dummy Data -->
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.dtFilter.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.calculator.min.js"></script>


    
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

            $t_data  =  substr("$acao_tempo->data", 0, 10);
           $n_data =    implode("/", array_reverse(explode("-", $t_data)));
            $t_concluido  = $acao_tempo->total_concluido;
            $t_fora_prazo = $acao_tempo->total_fora_prazo;
            $t_pendente   = $acao_tempo->total_pendentes;
            $t_atrasado   = $acao_tempo->total_atrasados;
           
            $total_concluido = $t_concluido + $t_fora_prazo;
            $total_pendente  = $t_pendente + $t_atrasado;
        ?>
      
        
        { d: <?php echo "'".$t_data."'"; ?>, concluidos: <?php echo $total_concluido; ?> , pendentes: <?php echo $t_pendente; ?> , atrasadas: <?php echo $t_atrasado; ?> },

 <?php  
        } 
 ?>
],
// The name of the data record attribute that contains x-visitss.
xkey: 'd',
xLabelFormat: function(date) {
	return date.getDate() +'/'+(date.getMonth()+1)+'/'+date.getFullYear(); 
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
	return d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear(); 
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

           
            $id_superintendente   = $area_projeto->id_superintendencia;
            $superintendencia   = $area_projeto->superintendencia;
        
            $projeto          =   $area_projeto->projeto;
               
            $quantidade_area =  $this->projetos_model->getAcoesSuperintendenciaByProjeto($projeto, $id_superintendente);
            $qtde_area = $quantidade_area->qtde;
        
             $perc_area = ($qtde_area * 100)/$total_acoes_areas;
       
           
         
            if (($perc_area < 1)&&($perc_area > 0)){
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
       
    <link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/qtip2/2.0.1/jquery.qtip.min.css" />
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/qtip2/2.0.1/jquery.qtip.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?= $assets ?>cronograma/dist/chronoline.css" />
    <script type="text/javascript" src="<?= $assets ?>cronograma/dist/chronoline.js"></script>
    
    
    
    
</body>


</html>
