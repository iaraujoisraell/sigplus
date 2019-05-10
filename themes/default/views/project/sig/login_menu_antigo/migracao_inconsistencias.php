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
                                    <h4>INCONSISTÊNCIAS MIGRAÇÃO - LOTE 1</h4>
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
                                    <div style="height: 700px;" class="row">
                                    
                                        <div class="col-lg-12">
                                                <div class="row">

                                                    

                                                        <div class="col-lg-3 col-sm-6">
                                                            <div style="height: 330px;" class="tile blue  dash-demo-tile">
                                                                <h4><i class="fa fa-sitemap fa-fw"></i> CPF Inválido </h4>
                                                                
                                                                <?PHP
                                                                $afazer = 606;//330;  69
                                                                $concluido = 0;// 623 + 64  =684
                                                                $total = $afazer + $concluido;
                                                                $porc =  ($concluido * 100)/$total;
                                                                ?>
                                                                
                                                                <div id="easy-pie-1" class="easy-pie-chart" data-percent="<?php if($porc != 100){ echo substr($porc, 0, 2); }else{ echo $porc; } ?>">
                                                                    <span class="percent"></span>
                                                                </div>
                                                                <div>
                                                                    <?php echo 'Total de Itens : '.$total.'<br>   ' ?>  
                                                                    <?php echo 'Concluído : '.$concluido.' <br>   ' ?>   
                                                                    <?php echo 'Pendente : '.$afazer.' <br>   ' ?>   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    
                                                    

                                                        <div class="col-lg-3 col-sm-6">
                                                            <div style="height: 330px;" class="tile orange  dash-demo-tile">
                                                                <h4><i class="fa fa-sitemap fa-fw"></i> Materiais Hospitalares </h4>
                                                                <?PHP
                                                                $afazer_mh = 62;//850
                                                                $concluido_mh = 825; //744 + 81
                                                                $total_mh = $afazer_mh + $concluido_mh;
                                                                $porc_mh =  ($concluido_mh * 100)/$total_mh;
                                                                ?>
                                                                <div id="easy-pie-1" class="easy-pie-chart" data-percent="<?php if($porc_mh != 100){ echo substr($porc_mh, 0, 2); }else{ echo $porc_mh; } ?>">
                                                                    <span class="percent"></span>
                                                                </div>
                                                                <div>
                                                                    <?php echo 'Total de Itens : '.$total_mh.'<br>   ' ?>  
                                                                    <?php echo 'Concluído : '.$concluido_mh.' <br>   ' ?>   
                                                                    <?php echo 'Pendente: '.$afazer_mh.' <br>   ' ?>   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    
                                                    <div class="col-lg-3 col-sm-6">
                                                            <div style="height: 330px;" class="tile blue  dash-demo-tile">
                                                                <h4><i class="fa fa-sitemap fa-fw"></i> Generos Alimentícios </h4>
                                                                
                                                                <?PHP
                                                                $afazer_ga = 77;//330;  69
                                                                $concluido_ga = 102;// 620; 64  =684
                                                                $total_ga = $afazer_ga + $concluido_ga;
                                                                $porc_ga =  ($concluido_ga * 100)/$total_ga;
                                                                ?>
                                                                
                                                                <div id="easy-pie-1" class="easy-pie-chart" data-percent="<?php if($porc_ga != 100){ echo substr($porc_ga, 0, 2); }else{ echo $porc_ga; } ?>">
                                                                    <span class="percent"></span>
                                                                </div>
                                                                <div>
                                                                    <?php echo 'Total de Itens : '.$total_ga.'<br>   ' ?>  
                                                                    <?php echo 'Concluído : '.$concluido_ga.' <br>   ' ?>   
                                                                    <?php echo 'Pendente : '.$afazer_ga.' <br>   ' ?>   
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                     <div class="col-lg-3 col-sm-6">
                                                            <div style="height: 330px;" class="tile orange  dash-demo-tile">
                                                                <h4><i class="fa fa-sitemap fa-fw"></i> Material de Limpeza e Higiene </h4>
                                                                <?PHP
                                                                $afazer_lh = 90;//850
                                                                $concluido_lh = 7;
                                                                $total_lh = $afazer_lh + $concluido_lh;
                                                                $porc_lh =  ($concluido_lh * 100)/$total_lh;
                                                                ?>
                                                                <div id="easy-pie-1" class="easy-pie-chart" data-percent="<?php if($porc_lh != 100){ echo substr($porc_lh, 0, 2); }else{ echo $porc_lh; } ?>">
                                                                    <span class="percent"></span>
                                                                </div>
                                                                <div>
                                                                    <?php echo 'Total de Itens : '.$total_lh.'<br>   ' ?>  
                                                                    <?php echo 'Concluído : '.$concluido_lh.' <br>   ' ?>   
                                                                    <?php echo 'Pendente: '.$afazer_lh.' <br>   ' ?>   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    
                                                    <div class="col-lg-3 col-sm-6">
                                                            <div style="height: 330px;" class="tile blue  dash-demo-tile">
                                                                <h4><i class="fa fa-sitemap fa-fw"></i> Material de Manutenção e Conservação </h4>
                                                                
                                                                <?PHP
                                                                $afazer_mc = 1747;//330;  69
                                                                $concluido_mc = 71;// 620; 64  =684
                                                                $total_mc = $afazer_mc + $concluido_mc;
                                                                $porc_mc =  ($concluido_mc * 100)/$total_mc;
                                                                ?>
                                                                
                                                                <div id="easy-pie-1" class="easy-pie-chart" data-percent="<?php if($porc_mc != 100){ echo substr($porc_mc, 0, 2); }else{ echo $porc_mc; } ?>">
                                                                    <span class="percent"></span>
                                                                </div>
                                                                <div>
                                                                    <?php echo 'Total de Itens : '.$total_mc.'<br>   ' ?>  
                                                                    <?php echo 'Concluído : '.$concluido_mc.' <br>   ' ?>   
                                                                    <?php echo 'Pendente : '.$afazer_mc.' <br>   ' ?>   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    
                                                    <div class="col-lg-3 col-sm-6">
                                                            <div style="height: 330px;" class="tile orange  dash-demo-tile">
                                                                <h4><i class="fa fa-sitemap fa-fw"></i> Imobilizado </h4>
                                                                <?PHP
                                                                $afazer_im = 462;//850
                                                                $concluido_im = 2;
                                                                $total_im = $afazer_im + $concluido_im;
                                                                $porc_im =  ($concluido_im * 100)/$total_im;
                                                                ?>
                                                                <div id="easy-pie-1" class="easy-pie-chart" data-percent="<?php if($porc_im != 100){ echo substr($porc_im, 0, 2); }else{ echo $porc_im; } ?>">
                                                                    <span class="percent"></span>
                                                                </div>
                                                                <div>
                                                                    <?php echo 'Total de Itens : '.$total_im.'<br>   ' ?>  
                                                                    <?php echo 'Concluído : '.$concluido_im.' <br>   ' ?>   
                                                                    <?php echo 'Pendente: '.$afazer_im.' <br>   ' ?>   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    
                                                    
                                                </div>
                                            
                                            <table style="width:100%; background-color: gray" id="example-table" class="table table-striped table-bordered table-hover table-striped">
                                                   
                                                    <tr>
                                                        <td style="width: 20%; ">
                                                            <div class="portlet-title">
                                                                <h4>Total Concluído MAT/MED</h4>
                                                            </div>
                                                        </td>
                                                        <?php
                                                        $total_item = $total_mh + $total ; //+ $total_ga+$total_lh + $total_mc +$total_im
                                                        $total_concluido = $concluido_mh + $concluido;//+$concluido_ga+$concluido_lh + $concluido_mc + $concluido_im
                                                        $porc_total_conc = ($total_concluido * 100)/$total_item;
                                                        
                                                        $porc_pendente = 100 - $porc_total_conc ;
                                                        //echo $porc_pendente;
                                                        ?>
                                                        <td style="width: 80%; background-color: #a0a0a0;">
                                                            <div class="progress">
                                                              <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_total_conc;  ?>%">
                                                               <?php if($porc_total_conc != 100){ echo  substr($porc_total_conc,0,2); }else{ echo $porc_total_conc; } ?> % Concluído
                                                              </div>
                                                              <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente;  ?>%">
                                                               <?php if($porc_pendente != 100){ echo  substr($porc_pendente,0,2); }else{ echo $porc_pendente; } ?>% Pendente
                                                              </div>
                                                              
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            
                                        </div>
                                    
                                    
                                </div>
                            </div>
                         </div>
                     </div>
                 </div>   
                 
                
                
               
    <!-- /#wrapper -->

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
</body>

</html>
