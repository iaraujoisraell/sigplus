
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
           
          </div>
     
        <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Entradas Tesouraria</h3>
               </div>
                 <!-- RECEBIMENTO PARTICULAR E VSOCIAL -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-7">
                      <!-- RECEBIMENTO PARTICULAR E VSOCIAL -->
                        <script> 
                        google.charts.load('current', {'packages':['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                              ['Data', 'Entradas Tesouraria'],
                              <?php
                              $cont = 0;
                              foreach($recebimentos_tesouraria as $tesouraria){
                                $mes = $tesouraria['MONTH'];
                                $dia = $tesouraria['DAY'];
                                $valor = $tesouraria['amount'];
                                ?>
                                ['<?php echo $dia.'/'.$mes; ?>', <?php echo $valor ?>],
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

                            var chart = new google.charts.Bar(document.getElementById('columnchart_tesouraria'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                          }
                      </script>
                        
                        <div id="columnchart_tesouraria" style="width: 100%; height: 400px;"></div>
                  
                        </div>
                    <!-- /.col -->
                    
                    <div class="col-md-5">
                      <p class="text-center">
                        <strong>Formas de Pagamento</strong>
                      </p>
                     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                      google.charts.load("current", {packages:["corechart"]});
                      google.charts.setOnLoadCallback(drawChart);
                      function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                          ['Forma de Pagamento', 'Valor'],
                          <?php
                          $cont = 0;
                          foreach($recebimentos_formas_pagamento as $forma){
                            $paymentmode= $forma['paymentmode'];
                            $valor = $forma['amount'];
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

                        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
                        chart.draw(data, options);
                      }
                    </script>
                     <div id="donutchart" style="width: 100%; height: 400px;"></div>
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->
                </div>
               
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
          
          <!-- Main row -->
          <div class="row">
            <!-- Performance Tesouraria X Saídas  -->
            <div class="col-md-8">
              <!-- Performance Tesouraria X Saídas  -->
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Performance Tesouraria X Saídas (<?php echo date('Y'); ?>)</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <!-- RECEBIMENTO PARTICULAR E VSOCIAL -->
                    <script> 
                    google.charts.load('current', {'packages':['bar']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                          ['Mês', 'Particular/Vsocial', 'Despesas'],
                          <?php
                          $cont = 0;
                          foreach($chart_expenses_vs_income_values as $chart){
                            $mes = $chart['mes'];
                            $entrada = $chart['entrada'];
                            $saida = $chart['saida'];
                            ?>
                            ['<?php echo $mes; ?>', <?php echo $entrada ?>, <?php echo $saida ?>],
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

                        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                        chart.draw(data, google.charts.Bar.convertOptions(options));
                      }
                  </script>
                  
                  <div id="columnchart_material" style="width: 100%; height: 400px;"></div>
                  
                  <!-- /.row -->
                </div>
                <!-- /.box-body -->
              </div>
            </div>
            <!-- /.col -->
            
            <div class="col-md-4">
                
             
              <!-- <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">CPU Traffic</span>
                  <span class="info-box-number">90<small>%</small></span>
                </div>
               
              </div>
           -->
           
            <!-- /.col -->
            
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-user-md"></i></span>
                <?php 
                 $total_empresa_repasse = 0;
                  foreach($recebimentos_vision as $contas_s){
                    $total_empresa_repasse += $contas_s['amount'];
                  }
                  

                 $soma_total_recebido = 0;
                foreach($recebimentos_formas_pagamento as $forma){
                    $valor = $forma['amount'];
                    $soma_total_recebido += $valor;
                }
                
                $total_repasse = $soma_total_recebido - $total_empresa_repasse;
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Total Repasse</span>
                  <span class="info-box-number"><?php echo app_format_money($total_repasse, 'R$'); ?></span>
                </div>
              </div>
            
            
            
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-hospital-o"></i></span>

                <?php 
               
                 
                 
                 $total_empresa = 0;
                  foreach($recebimentos_vision as $contas_s){
                    $total_empresa += $contas_s['amount'];
                  }

                
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Total Vision</span>
                  <span class="info-box-number"><?php echo app_format_money($total_empresa, 'R$'); ?></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
          
            <!-- /.col -->
            <!-- fix for small devices only -->
            
            <!-- /.col -->
           
              
              <!-- /.info-box -->
          
            <!-- /.col -->
            <div class="clearfix visible-sm-block"></div>
            
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
                <?php 
                $soma_total_recebido = 0;
                foreach($recebimentos_formas_pagamento as $forma){
                    $valor = $forma['amount'];
                    $soma_total_recebido += $valor;
                }
                ?>
                <div class="info-box-content">
                  <span class="info-box-text">Recebimentos Tesouraria</span>
                  <span class="info-box-number"><?php echo app_format_money($soma_total_recebido, 'R$'); ?></span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
          
                
              <!-- Info Boxes Style 2 
              <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Inventory</span>
                  <span class="info-box-number">5,200</span>

                  <div class="progress">
                    <div class="progress-bar" style="width: 50%"></div>
                  </div>
                  <span class="progress-description">
                        50% Increase in 30 Days
                      </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box 
              <div class="info-box bg-green">
                <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Mentions</span>
                  <span class="info-box-number">92,050</span>

                  <div class="progress">
                    <div class="progress-bar" style="width: 20%"></div>
                  </div>
                  <span class="progress-description">
                        20% Increase in 30 Days
                      </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box 
              <div class="info-box bg-red">
                <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Downloads</span>
                  <span class="info-box-number">114,381</span>

                  <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                  </div>
                  <span class="progress-description">
                        70% Increase in 30 Days
                      </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box 
              <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">Direct Messages</span>
                  <span class="info-box-number">163,921</span>

                  <div class="progress">
                    <div class="progress-bar" style="width: 40%"></div>
                  </div>
                  <span class="progress-description">
                        40% Increase in 30 Days
                      </span>
                </div>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>
           </div>
          <!-- /.row -->
          
          <div class="row">
             <!-- PRODUÇÃO POR CATEGORIA --> 
              <div class="col-md-3">
                  <div class="box box-info">
                    <div class="box-header with-border">
                      <h3 class="box-title">Produção Faturada por Categoria</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <!-- PRODUÇÃO POR CATEGORIA -->
                     <div class="table-responsive">
                        <table  id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>Quantidade</th>
                              <th>Categoria</th>
                            </tr>
                          </thead>
                          <tbody>
                           <?php
                            $this->load->model('Invoices_model');
                             $total_quantidade = 0;
                            foreach ($producao_categoria as $dados_medico) {
                                $quantidade = $dados_medico['quantidade'];
                                $total_quantidade += $quantidade;
                            }

                            $soma_quantidade = 0;
                            $soma_total_valor = 0;
                            $soma_total_empresa = 0;
                            $soma_total_medico = 0;
                            $total_faturado = 0;
                            $total_empresa = 0;
                            $total_medico = 0;

                            foreach ($producao_categoria as $dados_medico) {
                                  $categoria= $dados_medico['categoria'];
                                  $quantidade = $dados_medico['quantidade'];
                                  $total_faturado = $dados_medico['total_faturado'];
                                  $total_empresa = $dados_medico['valor_tesouraria'];
                                  $total_medico = $dados_medico['valor_medico'];

                                  $soma_quantidade += $quantidade;
                                  $soma_total_valor += $total_faturado;
                                  $soma_total_empresa += $total_empresa;
                                  $soma_total_medico += $total_medico;

                                  $porc_categoria = ($quantidade * 100)/$total_quantidade;
                                  $porc_categoria = substr($porc_categoria,0 ,5);
                                  $cont  = 4;
                                  if($cont == 1){
                                     $bar = "aqua";
                                 }else if($cont == 2){
                                     $bar = "red";
                                 }else if($cont == 3){
                                     $bar = "green";
                                 }else if($cont == 4){
                                     $bar = "yellow";
                                 }else if ($cont % 2 == 0){
                                     $bar = "red";
                                 }else{
                                      $bar = "green";
                                 }
                                  ?>

                                  <tr>
                                      <td><?php echo $quantidade; ?></td>
                                      <td>
                                          <div class="progress-group">
                                            <span class="progress-text"><?php echo $categoria.' ('.$porc_categoria.'% )'; ?></span>
                                            <span class="progress-number"><b><?php echo app_format_money($total_faturado, 'R$'); ?></b></span>

                                            <div class="progress sm">
                                                <div class="progress-bar progress-bar-<?php echo $bar; ?>" style="width: <?php echo $porc_categoria; ?>%"></div>
                                            </div>
                                          </div>
                                       </td>    
                                  </tr>

                                  <?php

                              }
                              ?>


                          </tbody>
                          <tfoot>
                            <tr>

                              <td><b><?php echo $soma_quantidade; ?></b></td>
                              <td><?php echo app_format_money($soma_total_valor, 'R$'); ?></td>
                            </tr>    
                            <tr>
                              <th>Quantidade</th>  
                              <th>Categoria</th>
                            </tr>
                            </tfoot>
                        </table>
                      </div>
                      <!-- /.table-responsive -->
                    </div>       
                  </div>
              </div>
             <!-- PRODUÇÃO POR PROCEDIMENTO --> 
              <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                      <h3 class="box-title">Produção Faturada por Procedimento</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <!-- PRODUÇÃO POR CATEGORIA -->
                     <div class="table-responsive">
                        <table  id="example2" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>Quantidade</th>
                              <th>Procedimento</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                           <?php
                            $this->load->model('Invoices_model');
                             $total_quantidade = 0;
                            foreach ($producao_procedimentos as $dados_medico) {
                                $quantidade = $dados_medico['quantidade'];
                                $total_quantidade += $quantidade;
                            }

                            $soma_quantidade = 0;
                            $soma_total_valor = 0;
                            $soma_total_empresa = 0;
                            $soma_total_medico = 0;
                            $total_faturado = 0;
                            $total_empresa = 0;
                            $total_medico = 0;

                            foreach ($producao_procedimentos as $dados_medico) {
                                  $procedimento= $dados_medico['procedimento'];
                                  $categoria= $dados_medico['categoria'];
                                  $quantidade = $dados_medico['quantidade'];
                                  $total_faturado = $dados_medico['total_faturado'];
                                  $total_empresa = $dados_medico['valor_tesouraria'];
                                  $total_medico = $dados_medico['valor_medico'];

                                  $soma_quantidade += $quantidade;
                                  $soma_total_valor += $total_faturado;
                                  $soma_total_empresa += $total_empresa;
                                  $soma_total_medico += $total_medico;

                                  $porc_categoria = ($quantidade * 100)/$total_quantidade;
                                  $porc_categoria = substr($porc_categoria,0 ,5);
                                  $cont  = 1;
                                  if($cont == 1){
                                     $bar = "aqua";
                                     }else if($cont == 2){
                                         $bar = "red";
                                     }else if($cont == 3){
                                         $bar = "green";
                                     }else if($cont == 4){
                                         $bar = "yellow";
                                     }else if ($cont % 2 == 0){
                                         $bar = "red";
                                     }else{
                                          $bar = "green";
                                     }
                                  ?>
                                  <tr>
                                      <td><?php echo $quantidade; ?></td>
                                      <td>
                                          <div class="progress-group">
                                            <span class="progress-text"><?php echo $procedimento.' ['.$categoria.'] ' .' ('.$porc_categoria.'% )'; ?></span>
                                            <span class="progress-number"><b><?php echo app_format_money($total_medico, 'R$'); ?></b> / <?php echo app_format_money($total_faturado, 'R$'); ?></span>
                                           

                                            <div class="progress sm">
                                                <div class="progress-bar progress-bar-<?php echo $bar; ?>" style="width: <?php echo $porc_categoria; ?>%"></div>
                                            </div>
                                          </div>
                                       </td>
                                </tr>
                                  <?php
                              }
                              ?>
                          </tbody>
                          <tfoot>
                            <tr>

                              <td><b><?php echo $soma_quantidade; ?></b></td>
                              <td><?php echo app_format_money($soma_total_medico, 'R$'); ?> / <?php echo app_format_money($soma_total_valor, 'R$'); ?></td>

                            </tr>    
                            <tr>
                              <th>Quantidade</th>  
                              <th>Procedimento</th>
                            
                            </tr>
                            </tfoot>
                        </table>
                      </div>
                      <!-- /.table-responsive -->
                    </div>       
                  </div>
              </div> 
             <!-- faturamento conta médica -->
              <div class="col-md-3">
                <div class="box box-info">
                    <div class="box-header with-border">
                      <h3 class="box-title">Receita Faturada por Conta Médica</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <!-- PRODUÇÃO POR CATEGORIA -->
                     <div class="table-responsive">
                        <table  id="example3" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th style="display:none">Quantidade</th>
                              <th>Conta Médica</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                              <?php 
                              $total = 0;
                              foreach($recebimentos_contas as $contas_s){
                                $total += $contas_s['amount'];
                              }

                             $cont = 0;
                             foreach($recebimentos_contas as $contas){
                               $nome_conta = $contas['conta_financeira'];
                               $amount = $contas['amount'];

                               $porc_medico = ($amount * 100)/$total;
                               $porc_medico = substr($porc_medico,0 ,5);

                                 if($cont == 1){
                                     $bar = "aqua";
                                 }else if($cont == 2){
                                     $bar = "red";
                                 }else if($cont == 3){
                                     $bar = "green";
                                 }else if($cont == 4){
                                     $bar = "yellow";
                                 }else if ($cont % 2 == 0){
                                     $bar = "red";
                                 }else{
                                      $bar = "green";
                                 }


                             ?>
                                  <tr>
                                      <td style="display:none"><?php echo $amount; ?></td>
                                      <td>
                                          <div class="progress-group">
                                            <span class="progress-text"><?php echo $nome_conta.' ('.$porc_medico.'% )'; ?></span>
                                            <span class="progress-number"><b><?php echo app_format_money($amount, 'R$'); ?></b> / <?php echo app_format_money($total, 'R$'); ?></span>

                                            <div class="progress sm">
                                                <div class="progress-bar progress-bar-<?php echo $bar; ?>" title="<?php echo $porc_medico; ?> %" style="width: <?php echo $porc_medico; ?>%"></div>
                                            </div>
                                          </div>
                                       </td>
                                </tr>
                                 <?php
                                  $cont++;
                                    } ?>
                          </tbody>
                         
                        </table>
                      </div>
                      <!-- /.table-responsive -->
                    </div>       
                  </div>
              </div>
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