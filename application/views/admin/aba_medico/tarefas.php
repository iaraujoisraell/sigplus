<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/aba_medico/head');?> 
<script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/pace/pace.js"></script>
    <!-- PAGE LEVEL PLUGIN STYLES -->
<link href="<?php echo base_url() ?>assets/ITOAM/template/css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">

<head>
    <br>
     <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <section class="content-header">
                        <h1>
                           Calendário
                        </h1>
                        <ol class="breadcrumb">
                          <li><a href="<?php echo admin_url(''); ?>"><i class="fa fa-dashboard"></i> Agenda de Médicos</a></li>
                           <li class="active">Calendário</li>
                        </ol>
                    </section>
                
                </div>
            </div>
     </div>   
</head> 
<body>

 
    <div class="content" id="trocar">

               
                <div class="row">
                   
                    <div class="col-lg-8">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Calendário</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-8 -->

                    <div class="col-lg-4">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Eventos</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div id='external-events'>
                                    <div class='external-event'>Lunch</div>
                                    <div class='external-event'>Meeting</div>
                                    <div class='external-event'>Break</div>
                                    <div class='external-event'>Client</div>
                                    <div class='external-event'>Interview</div>
                                    <p>
                                        <input type='checkbox' id='drop-remove' />
                                        <label for='drop-remove'>Remove After Drop</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-4 --> 
                </div>
                <!-- /.row -->

</div>

    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- CUSTOM JQUERY UI FOR FULL CALENDAR -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/fullcalendar/jquery-ui.custom.min.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->
    <div id="logout">
      
    </div>
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/plugins/fullcalendar/fullcalendar.min.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/flex.js"></script>
    <script src="<?php echo base_url() ?>assets/ITOAM/template/js/demo/calendar-demo.js"></script>  
    
</body>

</html>
