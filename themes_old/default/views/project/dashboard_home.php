

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
    
<div class="row">
<!-- /navbar -->

<!-- /subnavbar -->
      <div class="col-md-12">
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
        <!-- /span6 -->
        
        <!-- /span6 --> 
      
      <!-- /row --> 
    <!-- /container --> 

<!-- /main -->
</div>
<!-- /footer --> 
<!-- Le javascript
================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
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