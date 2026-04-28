
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
                  <h3 class="box-title">Produção Total por Competência</h3>
               </div>
                 <!-- RECEBIMENTO PARTICULAR E VSOCIAL -->
                <div class="box-body">
                 
                    <div class="col-md-12">
                      <!-- PRODUÇÃO TOTAL -->
                        <script> 
                        google.charts.load('current', {'packages':['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                              ['Competência', 'Valor Produzido'],
                              <?php
                              $cont = 0;
                              $mes_nome = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
                              foreach($producao_total as $tesouraria){
                                $ano = $tesouraria['ano'];
                                $mes_n =  $tesouraria['mes'];
                                $mes = $mes_nome[$mes_n];
                                $valor = $tesouraria['valor_total'];
                                ?>
                                ['<?php echo $mes.'/'.$ano; ?>', <?php echo $valor ?>],
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
                  
                  <!-- /.row -->
                </div>
               
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
         
          <!-- /.row -->
          
          <!-- Main row -->
          </div> 
            
        <div class="row">
            <!-- Performance Tesouraria X Saídas  -->
            <div class="col-md-7">
              <!-- Performance Tesouraria X Saídas  -->
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Performance Produção por convênio </h3>
                  </div>
                  <div class="box-body">
                  <div class="row">
                      
                       <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                         <script type="text/javascript">
                        google.charts.load("current", {packages:["corechart"]});
                        google.charts.setOnLoadCallback(drawChart);
                        function drawChart() {
                          var data = google.visualization.arrayToDataTable([
                            ["Convênio", "Repasse Médico", { role: "style" } ],
                             <?php
                              foreach($producao_resumo_convenio as $tesouraria){
                                $convenio = $tesouraria['convenio_nome'];
                                $valor = $tesouraria['valor_total'];
                                $color = $tesouraria['color'];
                                ?>
                                ['<?php echo $convenio; ?>', <?php echo $valor ?>, '<?php echo $color ?>'],
                            <?php } ?>  
                          ]);

                          var view = new google.visualization.DataView(data);
                          view.setColumns([0, 1,
                                           { calc: "stringify",
                                             sourceColumn: 1,
                                             type: "string",
                                             role: "annotation" },
                                           2]);

                          var options = {
                           // title: "Density of Precious Metals, in g/cm^3",
                            width: 900,
                            height: 900,
                            bar: {groupWidth: "90%"},
                            legend: { position: "none" },
                          };
                          var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
                          chart.draw(view, options);
                      }
                      </script>
                      
                     <div id="barchart_values" ></div>
                  </div>
               </div>       
               
              </div>
            </div>
            
            
            <!-- /.col -->
        </div>     
           
       
    </div>
             
          
          <div class="row">
            
              
              <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                      <h3 class="box-title">Produção detalhada por Convênio</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <!-- PRODUÇÃO POR CATEGORIA -->
                     <div class="table-responsive">
                        <table  id="example3" class="table table-bordered table-striped">
                          <thead >
                              <tr >
                              <th style="background-color: #E6E6E6"></th>
                              <th style="background-color: #E6E6E6" colspan="3" class="text-center">Consultas</th>
                              <th style="background-color: #E6E6E6" colspan="3" class="text-center">Exames</th>
                              <th style="background-color: #E6E6E6" colspan="3" class="text-center">Cirurgias</th>
                              <th style="background-color: #E6E6E6" colspan="3"> -</th>
                              
                            </tr>
                          </thead>  
                          <thead>
                            <tr>
                              <th>Convênio</th>
                              <th style="background-color: #81BEF7" class="text-center">Quantidade</th>
                              <th style="background-color: #81BEF7" class="text-center">Valor</th>
                              <th style="background-color: #81BEF7" class="text-center">Ticket</th>
                              <th style="background-color: #81F781" class="text-center">Quantidade</th>
                              <th style="background-color: #81F781" class="text-center">Valor</th>
                              <th style="background-color: #81F781" class="text-center">Ticket</th>
                              <th style="background-color: #F5A9F2" class="text-center">Quantidade</th>
                              <th style="background-color: #F5A9F2" class="text-center">Valor</th>
                              <th style="background-color: #F5A9F2" class="text-center">Ticket</th>
                              <th>Repasse</th>
                              <th>Produção</th>
                              <th>%</th>
                            </tr>
                          </thead>
                          <tbody>
                           <?php
                          
                            $qtde_consulta = 0;
                            $valor_consulta = 0;
                            $qtde_exames = 0;
                            $valor_exames = 0;
                            $qtde_cirurgia = 0;
                            $valor_cirurgia = 0;
                            $soma_total_valor = 0;
                            
                            $soma_qtde_consulta = 0;
                            $soma_valor_consulta = 0;
                            $soma_qtde_exames = 0;
                            $soma_valor_exames = 0;
                            $soma_qtde_cirurgia = 0;
                            $soma_valor_cirurgia = 0;
                            
                            $soma_total_faturado = 0;
                            $soma_total_repasse = 0;
                            $total_medico = 0;

                            foreach ($producao_detalhes_resumo_convenio as $dados_medico) {
                                  $convenio_nome        = $dados_medico['convenio_nome'];
                                  $valor_total_faturado = $dados_medico['valor_total'];
                                  $total_repasse = $dados_medico['total_repasse'];
                                  
                                  $qtde_consulta        = $dados_medico['qtde_consulta'];
                                  $valor_consulta       = $dados_medico['valor_consulta'];
                                  $tk_medio_consulta    = $valor_consulta/$qtde_consulta;
                                  $tk_medio_consulta    = substr($tk_medio_consulta, 0, 5);
                                  $qtde_exames_estimado = $qtde_consulta * 3;
                                  //$vl_exames_estimado = $qtde_consulta * 3;
                                  
                                  $qtde_exames = $dados_medico['qtde_exames'];
                                  $valor_exames = $dados_medico['valor_exames'];
                                  $tk_medio_exames    = $valor_exames/$qtde_exames;
                                  $tk_medio_exames    = substr($tk_medio_exames, 0, 5);
                                  
                                  $qtde_cirurgia = $dados_medico['qtde_cirurgia'];
                                  $valor_cirurgia = $dados_medico['valor_cirurgia'];
                                  $tk_medio_cirurgia    = $valor_cirurgia/$qtde_cirurgia;
                                  $tk_medio_cirurgia    = substr($tk_medio_cirurgia, 0, 5);
                                  $color        = $dados_medico['color'];
                                  

                                  $soma_qtde_consulta       += $qtde_consulta;
                                  $soma_valor_consulta      += $valor_consulta;
                                  $soma_qtde_exames         += $qtde_exames;
                                  $soma_valor_exames        += $valor_exames;
                                  $soma_qtde_cirurgia       += $qtde_cirurgia;
                                  $soma_valor_cirurgia      += $valor_cirurgia;
                                  $soma_total_faturado      += $valor_total_faturado;
                                  $soma_total_repasse       += $total_repasse;
                                  $soma_tk_medio_consulta   += $tk_medio_consulta;
                                  $soma_tk_medio_exames     += $tk_medio_exames;
                                  $soma_tk_medio_cirurgia   += $tk_medio_cirurgia;
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
                                     
                                     $per_re = ($total_repasse * 100)/ $valor_total_faturado;
                                  ?>
                                  <tr>
                                       <td class="text-center" ><?php echo $convenio_nome; ?></td>
                                      
                                       <td class="text-center"><?php echo $qtde_consulta; ?></td>
                                       <td><?php echo app_format_money($valor_consulta, 'R$'); ?></td>
                                       <td><?php echo app_format_money($tk_medio_consulta, 'R$'); ?></td>
                                       
                                        <td class="text-center"><b><?php echo $qtde_exames; ?></b> / <?php echo $qtde_exames_estimado; ?> </td>
                                        <td><?php echo app_format_money($valor_exames, 'R$'); ?></td>
                                        <td><?php echo app_format_money($tk_medio_exames, 'R$'); ?></td>
                                        
                                        <td class="text-center"><?php echo $qtde_cirurgia; ?></td>
                                        <td><?php echo app_format_money($valor_cirurgia, 'R$'); ?></td>
                                        <td><?php echo app_format_money($tk_medio_cirurgia, 'R$'); ?></td>
                                        
                                        <td><?php echo app_format_money($total_repasse, 'R$'); ?></td>
                                        <td><?php echo $valor_total_faturado;// app_format_money(, 'R$'); ?></td>
                                        <td><?php echo substr("$per_re", 0, 5).' %'; ?></td>
                                        
                                </tr>
                                  <?php
                              }
                              ?>
                          </tbody>
                          <tfoot>
                              <tr style="background-color: #151515; color: #ffffff;">
                              <td>TOTAL</td> 
                              <td class="text-center"><b><?php echo $soma_qtde_consulta; ?></b></td>
                              <td><?php echo app_format_money($soma_valor_consulta, 'R$'); ?></td>
                              <?php
                              $tkt_medio_tt_cst = $soma_valor_consulta/ $soma_qtde_consulta;
                              $tkt_medio_tt_cst    = substr($tkt_medio_tt_cst, 0, 5);
                              ?>
                              <td><?php echo app_format_money($tkt_medio_tt_cst, 'R$'); ?></td>
                              
                              
                              <td class="text-center"><b><?php echo $soma_qtde_exames; ?></b></td>
                              <td><?php echo app_format_money($soma_valor_exames, 'R$'); ?></td>
                              <?php
                              $tkt_medio_tt_ex = $soma_valor_exames/ $soma_qtde_exames;
                              $tkt_medio_tt_ex    = substr($tkt_medio_tt_ex, 0, 5);
                              ?>
                              <td><?php echo app_format_money($tkt_medio_tt_ex, 'R$'); ?></td>
                              
                              
                              <td class="text-center"><b><?php echo $soma_qtde_cirurgia; ?></b></td>
                              <td><?php echo app_format_money($soma_valor_cirurgia, 'R$'); ?></td>
                              <?php
                              $tkt_medio_tt_cir = $soma_valor_cirurgia/ $soma_qtde_cirurgia;
                              $tkt_medio_tt_cir    = substr($tkt_medio_tt_cir, 0, 5);
                              ?>
                              <td><?php echo app_format_money($tkt_medio_tt_cir, 'R$'); ?></td>
                              <td><?php echo app_format_money($soma_total_repasse, 'R$'); ?></td>
                              <td><?php echo app_format_money($soma_total_faturado, 'R$'); ?></td>
                              
                              <td>%</td>
                            </tr>    
                            <tr>
                              <th>Médico</th>
                              <th class="text-center">Qte Cons.</th>
                              <th>VL Cons.</th>
                              <th>Tick. Cons.</th>
                              <th class="text-center">Qte Ex.</th>
                              <th>VL Exames</th>
                              <th>Tick. Ex.</th>
                              <th class="text-center">Qte Cir;</th>
                              <th>VL Cir.</th>
                              <th>Tick. Cirur.</th>
                              <th>Repasse</th>
                              <th>Produção</th>
                              <th>%</th>
                            </tr>
                            </tfoot>
                        </table>
                      </div>
                      <!-- /.table-responsive -->
                    </div>       
                  </div>
              </div>
              
             <!-- PRODUÇÃO POR PROCEDIMENTO --> 
              <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                      <h3 class="box-title">Produção Resumida por Mês</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <!-- PRODUÇÃO POR CATEGORIA -->
                     <div class="table-responsive">
                        <table  id="example2" class="table table-bordered table-striped">
                          <thead >
                              <tr >
                              <th style="background-color: #E6E6E6"></th>
                              <th style="background-color: #E6E6E6" colspan="3" class="text-center">Consultas</th>
                              <th style="background-color: #E6E6E6" colspan="3" class="text-center">Exames</th>
                              <th style="background-color: #E6E6E6" colspan="3" class="text-center">Cirurgias</th>
                              <th style="background-color: #E6E6E6" colspan="3"> -</th>
                              
                            </tr>
                          </thead>  
                          <thead>
                            <tr>
                              <th>Médico</th>
                              <th style="background-color: #81BEF7" class="text-center">Quantidade</th>
                              <th style="background-color: #81BEF7" class="text-center">Valor</th>
                              <th style="background-color: #81BEF7" class="text-center">Ticket</th>
                              <th style="background-color: #81F781" class="text-center">Quantidade</th>
                              <th style="background-color: #81F781" class="text-center">Valor</th>
                              <th style="background-color: #81F781" class="text-center">Ticket</th>
                              <th style="background-color: #F5A9F2" class="text-center">Quantidade</th>
                              <th style="background-color: #F5A9F2" class="text-center">Valor</th>
                              <th style="background-color: #F5A9F2" class="text-center">Ticket</th>
                              <th>Repasse</th>
                              <th>Produção</th>
                              <th>%</th>
                            </tr>
                          </thead>
                          <tbody>
                           <?php
                          
                            $qtde_consulta = 0;
                            $valor_consulta = 0;
                            $qtde_exames = 0;
                            $valor_exames = 0;
                            $qtde_cirurgia = 0;
                            $valor_cirurgia = 0;
                            $soma_total_valor = 0;
                            
                            $soma_qtde_consulta = 0;
                            $soma_valor_consulta = 0;
                            $soma_qtde_exames = 0;
                            $soma_valor_exames = 0;
                            $soma_qtde_cirurgia = 0;
                            $soma_valor_cirurgia = 0;
                            
                            $soma_total_faturado = 0;
                            $soma_total_repasse = 0;
                            $total_medico = 0;
                            $soma_perc_medio = 0;
                            $cont_mes = 1;
                            foreach ($producao_detalhes_resumo_medico as $dados_medico) {
                                  $mes        = $dados_medico['mes'];
                                  $ano        = $dados_medico['ano'];
                                  $valor_total_faturado = $dados_medico['valor_total'];
                                  $total_repasse = $dados_medico['total_repasse'];
                                  
                                  $qtde_consulta        = $dados_medico['qtde_consulta'];
                                  $valor_consulta       = $dados_medico['valor_consulta'];
                                  $tk_medio_consulta    = $valor_consulta/$qtde_consulta;
                                  $tk_medio_consulta    = substr($tk_medio_consulta, 0, 5);
                                  $qtde_exames_estimado = $qtde_consulta * 3;
                                  //$vl_exames_estimado = $qtde_consulta * 3;
                                  
                                  $qtde_exames = $dados_medico['qtde_exames'];
                                  $valor_exames = $dados_medico['valor_exames'];
                                  $tk_medio_exames    = $valor_exames/$qtde_exames;
                                  $tk_medio_exames    = substr($tk_medio_exames, 0, 5);
                                  
                                  $qtde_cirurgia = $dados_medico['qtde_cirurgia'];
                                  $valor_cirurgia = $dados_medico['valor_cirurgia'];
                                  $tk_medio_cirurgia    = $valor_cirurgia/$qtde_cirurgia;
                                  $tk_medio_cirurgia    = substr($tk_medio_cirurgia, 0, 5);
                                  $color        = $dados_medico['color'];
                                  

                                  $soma_qtde_consulta       += $qtde_consulta;
                                  $soma_valor_consulta      += $valor_consulta;
                                  $soma_qtde_exames         += $qtde_exames;
                                  $soma_valor_exames        += $valor_exames;
                                  $soma_qtde_cirurgia       += $qtde_cirurgia;
                                  $soma_valor_cirurgia      += $valor_cirurgia;
                                  $soma_total_faturado      += $valor_total_faturado;
                                  $soma_total_repasse       += $total_repasse;
                                  $soma_tk_medio_consulta   += $tk_medio_consulta;
                                  $soma_tk_medio_exames     += $tk_medio_exames;
                                  $soma_tk_medio_cirurgia   += $tk_medio_cirurgia;
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
                                     
                                     $per_re = ($total_repasse * 100)/ $valor_total_faturado;
                                     $soma_perc_medio += $per_re; 
                                     $cont_mes ++;
                                  ?>
                                  <tr>
                                       <td class="text-center" ><?php echo $mes.'/'.$ano; ?></td>
                                      
                                       <td class="text-center"><?php echo $qtde_consulta; ?></td>
                                       <td><?php echo app_format_money($valor_consulta, 'R$'); ?></td>
                                       <td><?php echo app_format_money($tk_medio_consulta, 'R$'); ?></td>
                                       
                                        <td class="text-center"><b><?php echo $qtde_exames; ?></b> / <?php echo $qtde_exames_estimado; ?> </td>
                                        <td><?php echo app_format_money($valor_exames, 'R$'); ?></td>
                                        <td><?php echo app_format_money($tk_medio_exames, 'R$'); ?></td>
                                        
                                        <td class="text-center"><?php echo $qtde_cirurgia; ?></td>
                                        <td><?php echo app_format_money($valor_cirurgia, 'R$'); ?></td>
                                        <td><?php echo app_format_money($tk_medio_cirurgia, 'R$'); ?></td>
                                        
                                        <td><?php echo app_format_money($total_repasse, 'R$'); ?></td>
                                        <td><?php echo $valor_total_faturado;// app_format_money(, 'R$'); ?></td>
                                        <td><?php echo substr("$per_re", 0, 5).' %'; ?></td>
                                        
                                </tr>
                                  <?php
                              }
                              ?>
                          </tbody>
                          <tfoot>
                              <tr style="background-color: #151515; color: #ffffff;">
                              <td>TOTAL</td> 
                              <td class="text-center"><b><?php echo $soma_qtde_consulta; ?></b></td>
                              <td><?php echo app_format_money($soma_valor_consulta, 'R$'); ?></td>
                              <?php
                              $tkt_medio_tt_cst2 = $soma_valor_consulta/ $soma_qtde_consulta;
                              $tkt_medio_tt_cst2    = substr($tkt_medio_tt_cst2, 0, 5);
                              ?>
                              <td><?php echo app_format_money($tkt_medio_tt_cst2, 'R$'); ?></td>
                              
                              <td class="text-center"><b><?php echo $soma_qtde_exames; ?></b></td>
                              <td><?php echo app_format_money($soma_valor_exames, 'R$'); ?></td>
                              <?php
                              $tkt_medio_tt_ex2 = $soma_valor_exames/ $soma_qtde_exames;
                              $tkt_medio_tt_ex2   = substr($tkt_medio_tt_ex2, 0, 5);
                              ?>
                              <td><?php echo app_format_money($tkt_medio_tt_ex2, 'R$'); ?></td>
                              
                              <td class="text-center"><b><?php echo $soma_qtde_cirurgia; ?></b></td>
                              <td><?php echo app_format_money($soma_valor_cirurgia, 'R$'); ?></td>
                              <?php
                              $tkt_medio_tt_cir2 = $soma_valor_cirurgia/ $soma_qtde_cirurgia;
                              $tkt_medio_tt_cir2    = substr($tkt_medio_tt_cir2, 0, 5);
                              ?>
                              <td><?php echo app_format_money($tkt_medio_tt_cir2, 'R$'); ?></td>
                              <td><?php echo app_format_money($soma_total_repasse, 'R$'); ?></td>
                              <td><?php echo app_format_money($soma_total_faturado, 'R$'); ?></td>
                              
                              <td>
                              <?php  
                                $per_total_soma = $soma_perc_medio/ $cont_mes;
                              ?>
                              <?php echo substr("$per_total_soma", 0, 5).' %'; ?></td>
                            </tr>    
                            <tr>
                              <th>Médico</th>
                              <th class="text-center">Qte Cons.</th>
                              <th>VL Cons.</th>
                              <th>Tick. Cons.</th>
                              <th class="text-center">Qte Ex.</th>
                              <th>VL Exames</th>
                              <th>Tick. Ex.</th>
                              <th class="text-center">Qte Cir;</th>
                              <th>VL Cir.</th>
                              <th>Tick. Cirur.</th>
                              <th>Repasse</th>
                              <th>Produção</th>
                              <th>%</th>
                            </tr>
                            </tfoot>
                        </table>
                      </div>
                      <!-- /.table-responsive -->
                    </div>       
                  </div>
              </div> 
             <!-- faturamento conta médica -->
              
          </div>
          
       
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
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      "aaSorting": [[0, "desc"]]
    });  
    
    
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      "aaSorting": [[11, "desc"]]
    })
    
    /*
    $('#example1').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      "aaSorting": [[0, "desc"]]
    })
    */
    
     
  })
</script>