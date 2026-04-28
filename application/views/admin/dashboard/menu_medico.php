<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_menu_admin(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/AdminLTE.min.css">
 <link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">
  
    <style>
    .color-palette {
      height: 35px;
      line-height: 35px;
      text-align: center;
    }

    .color-palette-set {
      margin-bottom: 15px;
    }

    .color-palette span {
      display: none;
      font-size: 12px;
    }

    .color-palette:hover span {
      display: block;
    }

    .color-palette-box h4 {
      position: absolute;
      top: 100%;
      left: 25px;
      margin-top: -40px;
      color: rgba(255, 255, 255, 0.8);
      font-size: 12px;
      display: block;
      z-index: 7;
    }
  </style>
 
    <style>
         body {
         font-family:'Open Sans';
         background:#f1f1f1;
         }
         h3 {
         margin-top: 7px;
         font-size: 18px;
         }
        
         .install-row.install-steps {
         margin-bottom:15px;
         box-shadow: 0px 0px 1px #d6d6d6;
         }
        
         .control-label {
         font-size:13px;
         font-weight:600;
         }
         .padding-10 {
         padding:10px;
         }
         .mbot15 {
         margin-bottom:15px;
         }
         .bg-default {
         background: #03a9f4;
         border:1px solid #03a9f4;
         color:#fff;
         }
         .bg-success {
         border: 1px solid #dff0d8;
         }
         .bg-not-passed {
         border:1px solid #f1f1f1;
         border-radius:2px;
         }
         .bg-not-passed {
         border-right:0px;
         }
         .bg-not-passed.finish {
         border-right:1px solid #f1f1f1 !important;
         }
         .bg-not-passed h5 {
         font-weight:normal;
         color:#6b6b6b;
         }
         .form-control {
         box-shadow:none;
         }
         .bold {
         font-weight:600;
         }
         .col-xs-5ths,
         .col-sm-5ths,
         .col-md-5ths,
         .col-lg-5ths {
         position: relative;
         min-height: 1px;
         padding-right: 15px;
         padding-left: 15px;
         }
         .col-xs-5ths {
         width: 20%;
         float: left;
         }
         b {
         font-weight:600;
         }
         .bootstrap-select .btn-default {
         background: #fff !important;
         border: 1px solid #d6d6d6 !important;
         box-shadow: none;
         color: #494949 !important;
         padding: 6px 12px;
         }
      </style>
   
    <div class="content">
        <div class="col-md-12 ">
        <div class="row">

            <?php $this->load->view('admin/includes/alerts'); ?>

            <?php hooks()->do_action( 'before_start_render_dashboard_content' ); ?>

            <div class="clearfix"></div>
            <?php
            $empresa_id = $this->session->userdata('empresa_id');
            //echo 'Aqui : '.$empresa_id;
            
            ?>
            
            
            <div class="col-md-12 mtop30" data-container="top-12">
                <div class="box-group" id="accordion">
                
                    
                <h3>Menu Médico</h3>
                <div class="row">
                    
                    <div class="col-lg-3 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-aqua">
                        <div class="inner">
                          <h3>Atendimento<sup style="font-size: 20px"></sup></h3>

                          <p>Ver Fila Ambulatorial</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-user-md"></i>
                        </div>
                        <a href="<?php echo admin_url('appointly/appointments/agenda_medica'); ?>" class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                    </div>
                    
                    <div class="col-lg-3 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-red">
                        <div class="inner">
                          <h3>Agenda<sup style="font-size: 20px"></sup></h3>

                          <p>Médica</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('medicos/agenda_medica_individual/'.$medico_id); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                    
                    <div class="col-lg-3 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-yellow">
                        <div class="inner">
                          <h3>Produção<sup style="font-size: 20px"></sup></h3>

                          <p>Médica</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-coffee"></i>
                        </div>
                        <a href="<?php echo admin_url('medicos/producao_medica_individual/'.$medico_id); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                    
                    <div class="col-lg-3 col-xs-6">
                      <!-- Comercial -->
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3>Repasses</h3>

                          <p>Financeiro</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-money"></i>
                        </div>
                        <a   class="small-box-footer">Em breve <i class="fa fa-arrow-circle-right"></i></a>  
                       <!-- <a href="#tab_comercial" data-toggle="tab" class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  -->
                      </div>
                    </div>
                    
                </div>    
                
                    
                  
                    
                    <div class="tab-content">
                    <!-- SUB-MENUS COMERCIAL -->
                   
                      <div class="tab-pane " id="tab_comercial">
                        <div class="panel box box-danger">
                          <div class="box-header with-border">
                            <h4 class="box-title">
                              <a >

                              </a>
                            </h4>
                          </div>
                          <div class="box-body">
                           <div class="row">
                            <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/dashboard') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-dashboard"></i></span>

                                <div class="info-box-content">
                                  <span class="info-box-text">FATURAS</span>
                                  <span class="info-box-number"><small></small></span>
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              </a>    
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 col-xs-12">
                              <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

                                <div class="info-box-content">
                                  <span class="info-box-text">ORÇAMENTOS</span>
                                  <span class="info-box-number"></span>
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              <!-- /.info-box -->
                            </div>


                          </div>
                        </div>
                        </div>    
                      </div>
                   
                      <div class="tab-pane" id="tab_indicadores">
                        <div class="panel box box-danger">
                          <div class="box-header with-border">
                            <h4 class="box-title">
                              <a >
                                  Sub-menu Indicadores
                              </a>
                            </h4>
                          </div>
                          <div class="box-body">
                           <div class="row">
                          <!--  <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/dashboard/dashboard_gestao_producao') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-bar-chart-o"></i></span>
                                <div class="info-box-content">
                                  <span class="info-box-text">PRODUÇÃO MÉDICA</span>
                                  <span class="info-box-number"><small></small></span>
                                </div>
                                
                              </div>
                              </a>    
                            /.info-box -->
                            </div>   
                            <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/dashboard/dashboard_agendamento') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-dashboard"></i></span>

                                <div class="info-box-content">
                                  <span class="info-box-text">AGENDA</span>
                                  <span class="info-box-number"><small></small></span>
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              </a>    
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/dashboard/dashboard_gestao') ?>">
                              <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

                                <div class="info-box-content">
                                  <span class="info-box-text">TESOURARIA</span>
                                  <span class="info-box-number"></span>
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              </a>        
                              <!-- /.info-box -->
                            </div>
                            
                            <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/dashboard/dashboard_emprestimo') ?>">
                              <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fa fa-money"></i></span>

                                <div class="info-box-content">
                                  <span class="info-box-text">EMPRÉSTIMOS</span>
                                  <span class="info-box-number"></span>
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              </a>        
                              <!-- /.info-box -->
                            </div>

                          </div>
                        </div>
                        </div>    
                      </div>
                  
                    <!-- FIM SUB-MENUS COMERCIAL -->

                    <!-- SUB-MENUS SAME -->
                 
                    
                    
                    <!-- FIM SUB-MENUS SAME -->
                    </div>  
                </div>
            </div>
            
          </div>
              
        </div>    
        
        
       

     
    </div>
  
    

<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php $this->load->view('admin/dashboard/dashboard_js'); ?>

</body>
</html>
