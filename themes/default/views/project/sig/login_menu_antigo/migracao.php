<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html lang="en">

<?php  $this->load->view($this->theme . 'menu_head'); ?>

<body>

    <div id="wrapper">

        <!-- begin TOP NAVIGATION -->
          <?php  $this->load->view($this->theme . 'top'); ?>
        <!-- end TOP NAVIGATION -->

       
        <!-- end SIDE NAVIGATION -->

        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                
                <!-- ATALHOS RÁPIDO -->
                <?php  $this->load->view($this->theme . 'atalhos'); ?>
                
                
                
                <?php
                     $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    
                  ?>
                <br><br>
                <!-- /ATALHOS RÁPIDO -->
                 <?php 
                $usuario = $this->session->userdata('user_id');
                $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                $id_projeto = $projetos->projeto_atual;
                //$projeto = $this->atas_model->getProjetoByID($id_projeto);

                ?>
                <div class="row">
                        
                   <!-- LADO ESQUERDO -->
                    <div class="col-lg-12">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>MIGRAÇÃO</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#buttons"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="buttons" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div style="height: 500px;" class="row">
                                        
                                        
                                                 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                              <script type="text/javascript">
                                                google.charts.load("current", {packages:['corechart']});
                                                google.charts.setOnLoadCallback(drawChart);
                                                function drawChart() {
                                                  var data1 = google.visualization.arrayToDataTable([
                                                    ["Element", "Density", { role: "style" } ],
                                                    ["Nacion. não informado", 0, "#b87333"],
                                                    ["CPF Inválido", 606, "#b87333"],
                                                    ["CPF Duplicad0", 46832, "silver"],
                                                    ["Nome > 60 caracteres", 1, "color: #e5e4e2"],
                                                    ["E.Civil não informado", 94829, "gold"],
                                                    ["Pai > 60 caracteres", 2, "color: #e5e4e2"]
                                                    ["1o Nome Inválido", 112, "color: #e5e4e2"],
                                                    ["Dec de Nascido Vivo não informado", 21064, "color: #e5e4e2"],
                                                    ["CPF não informado (18 anos ou mais)", 42034, "color: #e5e4e2"],
                                                    ["Nome da Mãe não informado", 41265, "color: #e5e4e2"],
                                                    ["CNS não informado", 100358, "color: #e5e4e2"],
                                                    ["Nome Mãe > 60 caracteres", 0, "color: #e5e4e2"]
                                                  ]);

                                                  var view1 = new google.visualization.DataView(data1);
                                                  view.setColumns([0, 1,
                                                                   { calc: "stringify",
                                                                     sourceColumn: 1,
                                                                     type: "string",
                                                                     role: "annotation" },
                                                                   2]);

                                                  var options1 = {
                                                    title: "PESSOAS FÍSICAS INCONSISTENTES - 14/05/2018",
                                                    width: 1400,
                                                    height: 400,
                                                    bar: {groupWidth: "95%"},
                                                    legend: { position: "none" },
                                                  };
                                                  var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values1"));
                                                  chart.draw(view1, options1);
                                              }
                                              </script>
                                            <div id="columnchart_values1" style="width: 100%; height: 300px;"></div>
                                               
                                            
                                        </div>
                                    
                                    
                                </div>
                            </div>
                         </div>
                     </div>
                 </div>   
                
                
                 <div class="row">
                        
                   <!-- LADO ESQUERDO -->
                    <div class="col-lg-12">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>MIGRAÇÃO</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#buttons"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="buttons" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div style="height: 500px;" class="row">
                                        
                                        
                                                 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                              <script type="text/javascript">
                                                google.charts.load("current", {packages:['corechart']});
                                                google.charts.setOnLoadCallback(drawChart);
                                                function drawChart() {
                                                  var data1 = google.visualization.arrayToDataTable([
                                                    ["Element", "Density", { role: "style" } ],
                                                    ["Nacion. não informado", 0, "#b87333"],
                                                    ["CPF Duplicad", 19713, "silver"],
                                                    ["E.Civil não informado", 15829, "gold"],
                                                    ["Dec de Nascido Vivo não informado", 12691, "color: #e5e4e2"],
                                                    ["CNS não informado", 3292, "color: #e5e4e2"],
                                                    ["CPF não informado (18 anos ou mais)", 26, "color: #e5e4e2"],
                                                    ["1o Nome da Pessoa Inválido", 19, "color: #e5e4e2"],
                                                    ["Nome da Mãe não informado", 8, "color: #e5e4e2"],
                                                    ["Nome > 60 caracteres", 2, "color: #e5e4e2"],
                                                    ["Nome Pai > 60 caracteres", 2, "color: #e5e4e2"],
                                                    ["Nome Mãe > 60 caracteres", 2, "color: #e5e4e2"]
                                                  ]);

                                                  var view = new google.visualization.DataView(data1);
                                                  view.setColumns([0, 1,
                                                                   { calc: "stringify",
                                                                     sourceColumn: 1,
                                                                     type: "string",
                                                                     role: "annotation" },
                                                                   2]);

                                                  var options2 = {
                                                    title: "ENDEREÇO PESSOAS FÍSICAS - 14/05/2018",
                                                    width: 800,
                                                    height: 400,
                                                    bar: {groupWidth: "95%"},
                                                    legend: { position: "none" },
                                                  };
                                                  var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values2"));
                                                  chart.draw(view, options2);
                                              }
                                              </script>
                                            <div id="columnchart_values2" style="width: 800px; height: 300px;"></div>
                                               
                                            
                                        </div>
                                    
                                    
                                </div>
                            </div>
                         </div>
                     </div>
                 </div>
                
                
                
                <div class="row">
                        
                   <!-- LADO ESQUERDO -->
                    <div class="col-lg-12">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>ANDAMENTO DOS SEGUIMENTS MIGRAÇÃO</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#buttons"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="buttons" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div style="height: 1000px;" class="row">
                                        
                                        
                                                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                                <script type="text/javascript">
                                                  google.charts.load('current', {'packages':['bar']});
                                                  google.charts.setOnLoadCallback(drawChart);

                                                  function drawChart() {
                                                    var data = google.visualization.arrayToDataTable([
                                                      ['SEGUIMENTOS',        '03/2018', '04/2018',    '04/05/2018',     '17/05/2018' ],
                                                      
                                                      ['DADOS PF',         500,             950,              1200,         1300],
                                                      ['END. PF',          350,             850,              1025,         1300],
                                                      ['DADOS PJ',         350,             750,               750,          800 ],
                                                      ['END. PJ',          350,             750,               750,          800],
                                                      ['DADOS CONTRATOS',  200,             650,               650,          1200],
                                                      ['TAB. PREÇO',       100,             600,                600,         1040],
                                                      ['PRODUTOS',         100,             650,                650,         1140],
                                                      ['PAGADORES',        100,             200,                200,          200],
                                                      ['SEGURADOS',         0,              0,                     0,         1040],
                                                      ['HISTÓTICO SEGURADOS',         0,              0,                     0,         1040],
                                                      ['ALVO',            2200,               0,                   0,           0]
                                                    ]);

                                                    /*
                                                     * ['Entrega', 'Dados PF', 'End. PF', 'Dados PJ', 'End. PJ', 'Dados Contratos', 'Tab. Preços', 'Produtos do Contrato', 'Pagadores', 'Segurados', 'ALVO' ],
                                                      
                                                      ['042018',    950,       850,        750,       750,           650,                 600,            650,                 200,           10,       2200],
                                                      
                                                      ['032018',    500,       350,        350,       350,           200,                 100,            100,                 100,           10,       2200],
                                                     */

                                                    var options = {
                                                      chart: {
                                                        title: 'Controle do andamento da Migração TASY - HQS',
                                                        subtitle: 'Andamento das Fases da Migração. Início: 05/03/2018. Térm Prev: 04/07/2018',
                                                      },
                                                      bars: 'horizontal' // Required for Material Bar Charts.
                                                    };

                                                    var chart = new google.charts.Bar(document.getElementById('barchart_material'));

                                                    chart.draw(data, google.charts.Bar.convertOptions(options));
                                                  }
                                                </script>
                                            <div id="barchart_material" style="width: 1200px; height: 800px;"></div>
                                               
                                            
                                        </div>
                                    
                                    
                                </div>
                            </div>
                         </div>
                     </div>
                 </div>   
    <!-- /#wrapper -->

    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/spin.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/ladda.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap-multiselect/bootstrap-multiselect.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/flex.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/buttons-demo.js"></script>

    <script src="<?= $assets ?>dashboard/js/demo/calendar-demo.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/fullcalendar.min.js"></script>
</body>

</html>
