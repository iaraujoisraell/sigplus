
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
            
        <div class="row">      
                
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-folder-open"></i></span>
                    <?php 
                     
                    ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Total Empréstimos</span>
                      <span class="info-box-number"><?php echo $total_emprestimo->total; ?></span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Parcelas Pagas</span>
                      <span class="info-box-number"><?php echo $total_parcelas_pagas->qtde_parcelas; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
            </div>

            <div class="col-md-4">
                 <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Valor Pago</span>
                      <span class="info-box-number"><?php echo app_format_money($total_parcelas_pagas->valor_pago, 'R$'); ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
             </div>
            
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-sort"></i></span>
                    <?php 
                     $vl_aberto = $total_parcelas_abertas->valor_aberto;
                     $parcelas_abertas = $total_parcelas_abertas->qtde_parcelas;
                     
                     $ticket_aberto = $vl_aberto/$parcelas_abertas;
                    ?>
                    <div class="info-box-content">
                      <span class="info-box-text">Ticket médio parcelas abertas</span>
                      <span class="info-box-number"><?php echo app_format_money($ticket_aberto, 'R$'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-clock-o"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Parcelas Abertas</span>
                      <span class="info-box-number"><?php echo $total_parcelas_abertas->qtde_parcelas; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
            </div>

            <div class="col-md-4">
                 <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-smile-o"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Valor à Pagar</span>
                      <span class="info-box-number"><?php echo app_format_money($total_parcelas_abertas->valor_aberto, 'R$'); ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
             </div>
            
          </div>    
            
          
            
         <!-- EMPRÉSTIMO POR VALOR CONTRATADO -->  
        <div class="row">
          <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Empréstimos / Valor Contratado</h3>
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
                              foreach($total_liberado_emprestimo as $liberado){
                                $titulo = $liberado['banco_operacao'];
                                $total = $liberado['vl_contratado'];
                                ?>
                                ['<?php echo $titulo; ?>', <?php echo $total ?>],
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

                            var chart = new google.charts.Bar(document.getElementById('emprestimo_liberado'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                          }
                      </script>
                        
                        <div id="emprestimo_liberado" style="width: 100%; height: 400px;"></div>
                  
                        </div>
                   
                  </div>
                  <!-- /.row -->
                </div>
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>   
         
        <!-- EMPRÉSTIMO POR VALOR DE PARCELA -->  
        <div class="row">
          <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Empréstimos / Valor Parcela</h3>
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
                              foreach($total_liberado_emprestimo as $liberado){
                                $titulo = $liberado['banco_operacao'];
                                $total = $liberado['vl_parcela'];
                                ?>
                                ['<?php echo $titulo; ?>', <?php echo $total ?>],
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

                            var chart = new google.charts.Bar(document.getElementById('emprestimo_parcela'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                          }
                      </script>
                        
                        <div id="emprestimo_parcela" style="width: 100%; height: 400px;"></div>
                  
                        </div>
                   
                  </div>
                  <!-- /.row -->
                </div>
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>   
        
         <!-- PARCELA POR DATA DE VENCIMENTO -->  
        <div class="row">
          <div class="col-md-6">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Valor parcela por data de vencimento</h3>
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
                              foreach($total_emprestimo_data as $tesouraria){
                                $data = $tesouraria['data_vcto'];
                                $total = $tesouraria['vl_total'];
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
            
            
            <div class="col-md-6">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Valor total empréstimo por mês</h3>
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
                              foreach($total_emprestimo_mes as $tesouraria){
                                $mes = $tesouraria['mes'];
                                $ano = $tesouraria['ano'];
                                $total = $tesouraria['vl_total'];
                                ?>
                                ['<?php echo $mes.'/'.$ano; ?>', <?php echo $total ?>],
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

                            var chart = new google.charts.Bar(document.getElementById('atendimentos_mes'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                          }
                      </script>
                        
                        <div id="atendimentos_mes" style="width: 100%; height: 400px;"></div>
                  
                        </div>
                   
                  </div>
                  <!-- /.row -->
                </div>
              </div>
              <!-- /.box -->
            </div>
            
        </div> 
            
        <div class="row">
            <?php
            foreach($total_liberado_emprestimo as $liberado){
                $id = $liberado['id'];
                $titulo = $liberado['titulo'];
                $numero = $liberado['numero'];
                $vl_parcela = $liberado['vl_parcela'];
                $banco_operacao = $liberado['banco_operacao'];
                $vl_contratado = $liberado['vl_contratado'];
                $vl_liberado = $liberado['vl_liberado'];
                $color = $liberado['color'];
                $logo = $liberado['logo'];
                $tx_juros_mes = $liberado['tx_juros_mes'];
                $tx_juros_ano = $liberado['tx_juros_ano'];
                
                // dados totais
                $dados_parcelas_emprestimo = $this->Indicadores_model->get_total_parcela_by_emprestimo($id);
                $valor_devido = $dados_parcelas_emprestimo->valor_devido;
                $total_parcelas = $dados_parcelas_emprestimo->qtde_parcelas_total;
                
                // parcelas pagas
                $dados_parcelas_pagas = $this->Indicadores_model->get_total_parcela_pagas_by_emprestimo($id);
                $valor_parcela_pago = $dados_parcelas_pagas->valor_pago;
                $total_parcelas_paga = $dados_parcelas_pagas->qtde_parcelas;
                
                // parcelas abertas
                $dados_parcelas_abertas = $this->Indicadores_model->get_total_parcela_abertas_by_emprestimo($id);
                $valor_parcela_aberta = $dados_parcelas_abertas->valor_pago;
                $total_parcelas_aberta = $dados_parcelas_abertas->qtde_parcelas;
                
               // $total_parcelas_pagas = $this->Indicadores_model->get_atendimento_tipo_sexo($data_de, $data_ate);
                
                $porcent_pago = ($total_parcelas_paga * 100)/$total_parcelas;
                $porcent_pago = substr($porcent_pago, 0, 5);
                
            ?>
            <div class="col-md-6">
              <div class="box ">
                <div class="info-box bg-<?php echo $color; ?>">
                    <span  <?php if($logo){ ?> style="background-color: #ffffff"  <?php } ?> class="info-box-icon">
                        <?php if($logo){ ?>
                        <img src="<?php echo base_url('logo_bancos/'.$logo); ?>" class="img-responsive" >  
                        <?php }else{ ?>
                        <i class="fa fa-bookmark-o"></i> 
                        <?php } ?>
                    </span>

                    <div class="info-box-content">
                      <span class="info-box-text"><?php echo $titulo.' ['.$numero.'] '; ?></span>
                      <span class="info-box-number"><?php echo app_format_money($vl_contratado, 'R$'); ?></span>

                      <div class="progress">
                        <div class="progress-bar" style="width: <?php echo $porcent_pago; ?>%"></div>
                      </div>
                          <span class="progress-description">
                            <?php echo $porcent_pago; ?>% Quitado
                          </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                <!-- /.box-header -->
                <div class="box-body">
                 <div class="box-body">
                 
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                      google.charts.load("current", {packages:["corechart"]});
                      google.charts.setOnLoadCallback(drawChart);
                      function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                          ['Tipo ', 'Quantidade'],
                          
                          ['Parcelas Pagas', <?php echo $total_parcelas_paga ?>],
                          ['Parcelas Abertas', <?php echo $total_parcelas_aberta ?>],
                       
                        
                        ]);

                        var options = {
                        //  title: 'My Daily Activities',
                         // pieHole: 0.4,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById(<?php echo $id; ?>));
                        chart.draw(data, options);
                      }
                    </script>
                     <div id="<?php echo $id; ?>" style="width: 90%; height: 400px;"></div>
                </div>
                    
                    
                 <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-files-o"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">VL Contratado</span>
                      <span class="info-box-number"><?php echo app_format_money($vl_contratado, 'R$'); ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-flag-o"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Vl. Parcela</span>
                      <span class="info-box-number"><?php echo app_format_money($vl_parcela, 'R$'); ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                    
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">VALOR PAGO</span>
                      <span class="info-box-number"><?php echo app_format_money($valor_parcela_pago, 'R$'); ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>    
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-clock-o"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Parc. à Pagar</span>
                      <span class="info-box-number"><?php echo app_format_money($valor_devido, 'R$'); ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                
                
                
                
                
                
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-flag-o"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Total Parcelas</span>
                      <span class="info-box-number"><?php echo $total_parcelas; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                
                
                
                
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-flag-o"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Parcelas Pagas</span>
                      <span class="info-box-number"><?php echo $total_parcelas_paga; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                
                
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-meh-o"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Juros A.M.</span>
                      <span class="info-box-number"><?php echo $tx_juros_mes; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-files-o"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Juros A.A.</span>
                      <span class="info-box-number"><?php echo $tx_juros_ano; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                
                
                </div>
               </div>
            </div>
           <?php
            }
            
            ?>
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
