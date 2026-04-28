<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet(false); ?>


<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">
    
 
    
 
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <?php $_announcements = get_announcements_for_user();
                    if(sizeof($_announcements) > 0  && is_staff_member()){ ?>
                    <div class="col-lg-12">
                            <div class="panel_s">
                                    <?php foreach($_announcements as $__announcement){ ?>
                                    <div class="panel-body announcement mbot15 tc-content">
                                            <div class="text-info alert-dismissible" role="alert">
                                                    <h4 class="no-margin pull-left">
                                                            <?php echo _l('announcement'); ?>! <?php if($__announcement['showname'] == 1){ echo '<br /><small class="font-medium-xs">'._l('announcement_from').' '. $__announcement['userid']; } ?></small><br />
                                                            <small><?php echo _l('announcement_date',_dt($__announcement['dateadded'])); ?></small>
                                                    </h4>
                                                    <a href="<?php echo admin_url('misc/dismiss_announcement/'.$__announcement['announcementid']); ?>" class="close">
                                                            <span aria-hidden="true">&times;</span>
                                                    </a>
                                                    <?php if(is_admin()){ ?>
                                                    <a href="<?php echo admin_url('announcements/announcement/'.$__announcement['announcementid']); ?>">
                                                            <i class="fa fa-pencil-square-o pull-right"></i>
                                                    </a>
                                                    <?php } ?>
                                                    <div class="clearfix"></div>
                                            </div>
                                            <hr class="hr-panel-heading" />
                                            <h4 class="bold"><?php echo $__announcement['name']; ?></h4>
                                            <?php echo check_for_links($__announcement['message']); ?>
                                    </div>
                                    <?php } ?>
                            </div>
                    </div>
                    <?php } ?>
                    <?php hooks()->do_action('before_start_render_content'); ?>

                <h1>INTRANET</h1>
                <!-- WIDGETS -->
                <div class="col-lg-12 position-relative ">
                    <div class="mb-0 ">
                        <div class="p-3">
                            <div class="row">
                                 <div class="col-md-12 mtop30" data-container="top-12">
                                <?php render_intranet_widgets('banner'); ?>
                                      </div>
                            </div>
                        </div>
                    </div>    
                    <div class="mb-0 ">
                        <div class="p-3">        
                            <div class="row">
                                <div class="col-md-12 mtop30" data-container="top-12">
                                    <?php render_intranet_widgets('destaques'); ?>

                            </div>
                        </div>
                    </div>
                    </div>    

                    <div class="mb-0 ">
                        <div class="p-3">        
                            <div class="row">
                                <div class="col-md-12 mtop30" data-container="top-12">
                                    <?php render_intranet_widgets('links'); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0 ">
                        <div class="p-3">        
                            <div class="row">
                                <div class="col-md-12 mtop30" data-container="top-12">
                                    <?php render_intranet_widgets('noticias'); ?>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="mb-0 ">
                        <div class="p-3">        
                            <div class="row">

                                <div class="col-md-12 mtop30" data-container="top-12">
                                    <div class="col-md-7 mtop30" data-container="top-7">
                                        <?php render_intranet_widgets('calendario'); ?>
                                        <?php render_intranet_widgets('arquivos'); ?>
                                    </div>
                                    <div class="col-md-5 mtop30" data-container="top-5">
                                        <?php render_intranet_widgets('aniversariantes'); ?>
                                    </div>

                                </div>



                            </div>
                        </div>
                    </div>


                    </div>

            </div>
        </section>    
    </div>    
    
    <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2019-2022 <a href="https://sigpus.site">Sigplus.Online</a>.</strong> All rights
    reserved.
  </footer>
    
    <!-- MENU LATERAL ESQUERDO -->
    <?php $this->load->view('gestao_corporativa/intranet/aside.php'); ?>
   
   
</div>

</div>
<?php init_tail(); ?>
<script type="text/javascript">
    $(window).on('load', function () {
        // $('#modal_new').modal('show');
    });
</script>


<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>


<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/menu/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/menu/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="<?php echo base_url(); ?>assets/menu/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url(); ?>assets/menu/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url(); ?>assets/menu/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url(); ?>assets/menu/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>assets/menu/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/menu/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url(); ?>assets/menu/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>assets/menu/dist/js/demo.js"></script>

<script src="<?php echo base_url(); ?>assets/menu/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

<!-- AFETA O BANNER -->
<script src="<?php echo base_url(); ?>assets/intranet/js/core/bootstrap.min.js"></script>

<script>
    $(function () {
        $("#tabs").tabs();
    });
</script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<script src="<?php echo base_url(); ?>assets/intranet/js/plugins/fullcalendar.min.js"></script>

<script>
    var calendar = new FullCalendar.Calendar(document.getElementById("calendar_intranet"), {
        contentHeight: 'auto',
        initialView: "dayGridMonth",
        headerToolbar: {
            start: 'title', // will normally be on the left. if RTL, will be on the right
            center: '',
            end: 'today prev,next' // will normally be on the right. if RTL, will be on the left
        },
        selectable: true,
        editable: true,
        initialDate: '<?php echo date('Y-m-d'); ?>',
        events: [
<?php foreach ($date as $data): ?>
                {
                    title: '<?PHP echo $data->titulo ?>',
                    start: '<?PHP echo $data->inicio ?>',
                    end: '<?PHP echo $data->fim ?>',
                    color: '<?PHP echo $data->cor ?>'
                },
<?php endforeach; ?>
        ],
        views: {
            month: {
                titleFormat: {
                    month: "long",
                    year: "numeric"
                }
            },
            agendaWeek: {
                titleFormat: {
                    month: "long",
                    year: "numeric",
                    day: "numeric"
                }
            },
            agendaDay: {
                titleFormat: {
                    month: "short",
                    year: "numeric",
                    day: "numeric"
                }
            }
        },
    });

    calendar.render();

    var ctx1 = document.getElementById("chart-line-1").getContext("2d");

    new Chart(ctx1, {
        type: "line",
        data: {
            labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                    label: "Visitors",
                    tension: 0.5,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#fff",
                    borderWidth: 2,
                    backgroundColor: "transparent",
                    data: [50, 45, 60, 60, 80, 65, 90, 80, 100],
                    maxBarThickness: 6,
                    fill: true
                }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                    },
                    ticks: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                    },
                    ticks: {
                        display: false
                    }
                },
            },
        },
    });
</script>


</body>
</html>