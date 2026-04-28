
 <link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/AdminLTE.min.css">
 <link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">

  <link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/jvectormap/jquery-jvectormap.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
   
   
<div class="row">
   
    <div id="conteudo"> 
        <!-- Main content -->
        <section class="content">
        <!-- Info boxes -->
        <div class="row">      
                
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-hospital-o"></i></span>
                    <?php 
                     
                    ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Total Atendidos no Período</span>
                      <span class="info-box-number"><?php echo $total_atendidos->total; ?></span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-user-md"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Em Atendimento</span>
                      <span class="info-box-number"><?php echo $total_atendimento->total; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
            </div>

            <div class="col-md-4">
                 <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-close"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Total de Falta</span>
                      <span class="info-box-number"><?php echo $total_falta->total; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
             </div>
            
          </div>
         <!-- GRÁFICO PIZZA -->   
          <div class="row">
            <!--POR TIPO DE ATENDIMENTO -->
            <div class="col-md-6">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Tipo de Atendimento</h3>
                </div>
                <div class="box-body">
                 
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                      google.charts.load("current", {packages:["corechart"]});
                      google.charts.setOnLoadCallback(drawChart);
                      function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                          ['Tipo de Atendimento', 'Quantidade'],
                          <?php
                          $cont = 0;
                          foreach($total_tipo_atendimento as $forma){
                            $paymentmode= $forma['type'];
                            $valor = $forma['total'];
                            ?>
                            ['<?php echo $paymentmode; ?>', <?php echo $valor ?>],
                        <?php
                        $cont++;
                        }
                         ?>  
                        
                        ]);

                        var options = {
                        //  title: 'My Daily Activities',
                         // pieHole: 0.4,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('tipo_atendimento'));
                        chart.draw(data, options);
                      }
                    </script>
                     <div id="tipo_atendimento" style="width: 90%; height: 400px;"></div>
                </div>
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
            
            <!--POR TIPO DE SEXO -->
            <div class="col-md-6">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Tipo de Sexo</h3>
                </div>
                <div class="box-body">
                 
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                      google.charts.load("current", {packages:["corechart"]});
                      google.charts.setOnLoadCallback(drawChart);
                      function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                          ['Sexo', 'Quantidade'],
                          <?php
                          $cont = 0;
                          foreach($total_tipo_sexo as $forma){
                            $paymentmode= $forma['sexo'];
                            $valor = $forma['total'];
                            ?>
                            ['<?php echo $paymentmode; ?>', <?php echo $valor ?>],
                        <?php
                        $cont++;
                        }
                         ?>  
                        
                        ]);

                        var options = {
                        //  title: 'My Daily Activities',
                         // pieHole: 0.4,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('tipo_sexo'));
                        chart.draw(data, options);
                      }
                    </script>
                     <div id="tipo_sexo" style="width: 90%; height: 400px;"></div>
                </div>
              </div>
            </div>
            <!-- /.row -->
          </div>
          
          <!-- GRÁFICO BARRA -->
          
          <!-- ATENDIMENTO POR MÉDICO -->  
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Atendimento por Médico</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                        <script> 
                        google.charts.load('current', {'packages':['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                              ['Data', 'Total'],
                              <?php
                              $cont = 0;
                              foreach($total_atendimento_medico as $tesouraria){
                                $nome_profissional = $tesouraria['nome_profissional'];
                                $total = $tesouraria['total'];
                                ?>
                                ['<?php echo $nome_profissional; ?>', <?php echo $total ?>],
                            <?php
                            $cont++;
                            }
                             ?>  

                            ]);

                            var options = {
                              chart: {
                               // title: 'Company Performance',
                                //subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                              }
                            };

                            var chart = new google.charts.Bar(document.getElementById('atendimentos_medico'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                          }
                      </script>
                        
                        <div id="atendimentos_medico" style="width: 100%; height: 400px;"></div>
                  
                        </div>
                   
                  </div>
                  <!-- /.row -->
                </div>
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
          </div>
          
          <!-- ATENDIMENTO POR DIA -->  
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Atendimento por Horário</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                        <script> 
                        google.charts.load('current', {'packages':['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                              ['Data', 'Total'],
                              <?php
                              $cont = 0;
                              foreach($total_atendimento_horario as $tesouraria){
                                $start_hour = $tesouraria['start_hour'];
                                $total = $tesouraria['total'];
                                ?>
                                ['<?php echo $start_hour; ?>', <?php echo $total ?>],
                            <?php
                            $cont++;
                            }
                             ?>  

                            ]);

                            var options = {
                              chart: {
                               // title: 'Company Performance',
                                //subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                              }
                            };

                            var chart = new google.charts.Bar(document.getElementById('atendimentos_horario'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                          }
                      </script>
                        
                        <div id="atendimentos_horario" style="width: 100%; height: 400px;"></div>
                  
                        </div>
                   
                  </div>
                  <!-- /.row -->
                </div>
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
          </div>
        
          <!-- ATENDIMENTO POR CONVENIO -->  
          <div class="row">
            <div class="col-md-6">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Atendimento por Convênio</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                        <script> 
                        google.charts.load('current', {'packages':['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                              ['Convenio', 'Total'],
                              <?php
                              $cont = 0;
                              foreach($total_atendimento_convenio as $tesouraria){
                                $convenio = $tesouraria['convenio'];
                                $total = $tesouraria['total'];
                                ?>
                                ['<?php echo $convenio; ?>', <?php echo $total ?>],
                            <?php
                            $cont++;
                            }
                             ?>  

                            ]);

                            var options = {
                              chart: {
                               // title: 'Company Performance',
                                //subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                              }
                            };

                            var chart = new google.charts.Bar(document.getElementById('atendimento_convenio'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                          }
                      </script>
                        
                        <div id="atendimento_convenio" style="width: 100%; height: 400px;"></div>
                  
                        </div>
                   
                  </div>
                  <!-- /.row -->
                </div>    
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
            
            
            <div class="col-md-6">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Atendimento por Data</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                        <script> 
                        google.charts.load('current', {'packages':['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                              ['Data', 'Total'],
                              <?php
                              $cont = 0;
                              foreach($total_atendimento_data as $tesouraria){
                                $data = $tesouraria['date'];
                                $total = $tesouraria['total'];
                                ?>
                                ['<?php echo $data; ?>', <?php echo $total ?>],
                            <?php
                            $cont++;
                            }
                             ?>  

                            ]);

                            var options = {
                              chart: {
                               // title: 'Company Performance',
                                //subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                              }
                            };

                            var chart = new google.charts.Bar(document.getElementById('atendimentos_data'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                          }
                      </script>
                        
                        <div id="atendimentos_data" style="width: 100%; height: 400px;"></div>
                  
                        </div>
                   
                  </div>
                  <!-- /.row -->
                </div>
              </div>
              <!-- /.box -->
            </div>
          </div>
          
          
          <!-- AGENDAMENTO POR ATENDENTE -->  
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Agendamento por Atendente</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-12">
                        <script> 
                        google.charts.load('current', {'packages':['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                              ['Data', 'Total'],
                              <?php
                              $cont = 0;
                              foreach($total_agendamento_atendente as $tesouraria){
                                $firstname = $tesouraria['firstname'];
                                $total = $tesouraria['total'];
                                ?>
                                ['<?php echo $firstname; ?>', <?php echo $total ?>],
                            <?php
                            $cont++;
                            }
                             ?>  

                            ]);

                            var options = {
                              chart: {
                               // title: 'Company Performance',
                                //subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                              }
                            };

                            var chart = new google.charts.Bar(document.getElementById('agendamento_atendente'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                          }
                      </script>
                        
                        <div id="agendamento_atendente" style="width: 100%; height: 400px;"></div>
                  
                        </div>
                   
                  </div>
                  <!-- /.row -->
                </div>
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
          </div>
         
          
        </section>
        <!-- /.content -->
        </div>
    
    <?php //hooks()->do_action('after_dashboard_top_container'); ?>
</div>



<script src="<?php echo base_url() ?>assets/menu/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url() ?>assets/menu/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/bower_components/fastclick/lib/fastclick.js"></script>
<script src="<?php echo base_url() ?>assets/menu/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url() ?>assets/menu/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url() ?>assets/menu/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo base_url() ?>assets/menu/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url() ?>assets/menu/bower_components/chart.js/Chart.js"></script>
<script src="<?php echo base_url() ?>assets/menu/dist/js/pages/dashboard2.js"></script>
<script src="<?php echo base_url() ?>assets/menu/dist/js/demo.js"></script>

<script>
  $(function () {
    $('#example3').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      "aaSorting": [[0, "desc"]]
    })  
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      "aaSorting": [[0, "desc"]]
    })
    
    
    $('#example1').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      "aaSorting": [[0, "desc"]]
    })
    
    
     
  })
</script>