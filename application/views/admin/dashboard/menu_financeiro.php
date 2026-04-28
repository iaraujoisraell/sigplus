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
       <section class="content-header">
          <h1>
            Módulo Financeiro
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo admin_url('#'); ?>"><i class="fa fa-home"></i> Home </a></li>
            <li class="active"><a href="<?php echo admin_url('dashboard/menu_financeiro'); ?>">Menu Financeiro</a></li>
          </ol>
        </section>
    <div class="row">
        <?php $this->load->view('admin/includes/alerts'); ?>
        <?php hooks()->do_action( 'before_start_render_dashboard_content' ); ?>
        <div class="clearfix"></div>
        <?php
        $empresa_id = $this->session->userdata('empresa_id');
        //echo 'Aqui : '.$empresa_id;
        ?>
           <div class="col-md-12 mtop30" data-container="top-12">
            <div class="panel box box-danger">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a >
                            Controle e Rotina
                        </a>
                    </h4>
                </div>
                <div class="row">
                <?php
                $mod_sistema = 0;
                    foreach ($modulos as $mod){
                        $modulo_id = $mod['modulo_id'];
                        if($modulo_id == 1){
                            $mod_sistema = 1;
                        }
                    }
                ?>
                <?php //if($mod_sistema == 1){ ?>
                <div class="col-lg-3 col-xs-6">
                  <!-- Comercial -->
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3><sup style="font-size: 20px">Cadastros</sup></h3>
                      <p>Módulos</p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-list"></i>
                    </div>
                    <a href="<?php echo admin_url('financeiro/cadastros'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                   <!-- <a href="#tab_comercial" data-toggle="tab" class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  -->
                  </div>
                </div>
                <?php //} ?>
                <!-- ./col -->
                
                 <?php
                $mod_indicadores = 0;
                    foreach ($modulos as $mod){
                        $modulo_id = $mod['modulo_id'];
                        if($modulo_id == 2){
                            $mod_indicadores = 1;
                        }
                    }
                ?>
                <?php //if($mod_indicadores == 1){ ?>
                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3><sup style="font-size: 20px">Títulos/ Contratos a Pagar</sup></h3>
                      <p>Saídas</p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-heartbeat"></i>
                    </div>
                    <a href="<?php echo admin_url('financeiro'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <?php //} ?>
                <!-- ./col -->
                <?php
                $mod_faturamento = 0;
                    foreach ($modulos as $mod){
                        $modulo_id = $mod['modulo_id'];
                        if($modulo_id == 3){
                            $mod_faturamento = 1;
                        }
                    }
                ?>
                <?php //if($mod_faturamento == 1){ ?>
                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-yellow">
                    <div class="inner">
                      <h3><sup style="font-size: 20px">Parcelas a pagar</sup></h3>

                      <p>Gestão</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-cash"></i>
                    </div>
                    <a href="<?php echo admin_url('financeiro/list_conta_pagar_parcelas'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <?php //} ?>
                
                <?php
                $mod_financeiro = 0;
                    foreach ($modulos as $mod){
                        $modulo_id = $mod['modulo_id'];
                        if($modulo_id == 4){
                            $mod_financeiro = 1;
                        }
                    }
                ?>
                <?php //if($mod_financeiro == 1){ ?>
                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-green">
                    <div class="inner">
                      <h3><sup style="font-size: 20px">Títulos/ Contratos a Receber</sup></h3>

                      <p>Gestão</p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-smile-o"></i>
                    </div>
                    <a href="<?php echo admin_url('financeiro_invoices'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                
               
                <?php //} ?>
                <?php
                $mod_plantao = 0;
                    foreach ($modulos as $mod){
                        $modulo_id = $mod['modulo_id'];
                        if($modulo_id == 5){
                            $mod_plantao = 1;
                        }
                    }
                ?>
                <?php //if($mod_plantao == 1){ ?>
                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-primary">
                    <div class="inner">
                      <h3><sup style="font-size: 20px">Indicadores / Dashboard</sup></h3>

                      <p>Gestão</p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-dashboard"></i>
                    </div>
                    <a href="<?php echo admin_url('Financeiro_report/dashboard'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <?php //} ?>
                
                <div class="col-lg-3 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Movimentos Bancários</sup></h3>
                          <p>Lançamentos</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-list"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro/movimentacao_bancaria'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                
              </div>
            </div>  
               
               <?php
                $mod_menu_med = 0;
                    foreach ($modulos as $mod){
                        $modulo_id = $mod['modulo_id'];
                        if($modulo_id == 6){
                            $mod_menu_med = 1;
                        }
                    }
                ?>
                <?php //if($mod_menu_med == 1){ ?>
                
                <?php //} ?>
               
               
               <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a >
                                Lançamentos
                            </a>
                        </h4>
                    </div>
                   <div class="row">
                    <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Janeiro</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                          <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/1' ); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>

                    <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Fevereiro</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/2'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                       
                   <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Março</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/3'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                       
                    <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Abril</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/4'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                       
                   <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Maio</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/5'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                    
                    <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Junho</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/6'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>   
                       
                       
                    
                       
                </div>
                   
                   <div class="row">
                    <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Julho</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/7'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>

                    <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Agosto</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/8'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                       
                   <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Setembro</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/9'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                       
                    <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Outubro</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/10'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                       
                   <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Novembro</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/11'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                    
                    <div class="col-lg-2 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-primary">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Dezembro</sup></h3>
                        </div>
                        <div class="icon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/fluxo_lancamentos/12'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>   
                       
                       
                    
                       
                </div>
                </div>   
                
                <?php
                $mod_menu_med = 0;
                    foreach ($modulos as $mod){
                        $modulo_id = $mod['modulo_id'];
                        if($modulo_id == 6){
                            $mod_menu_med = 1;
                        }
                    }
                ?>
                <?php //if($mod_menu_med == 1){ ?>
                <div class="panel box box-danger">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a >
                                Relatórios
                            </a>
                        </h4>
                    </div>
                   <div class="row">
                    <div class="col-lg-3 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-green">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Entradas</sup></h3>
                          <p>Previsto/ Realizado</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-arrow-circle-left"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/entradas'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                      <!-- small box -->
                      <div class="small-box bg-yellow">
                        <div class="inner">
                          <h3><sup style="font-size: 20px">Saídas</sup></h3>
                          <p>Previsto/ Realizado</p>
                        </div>
                        <div class="icon">
                          <i class="fa fa-arrow-circle-right"></i>
                        </div>
                        <a href="<?php echo admin_url('Financeiro_report/saidas'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                       
                    
                       
                </div>
                </div>    
                <?php //} ?>
                
          
               
            
        </div>
   </div>
</div>    
        
     
</div>
    

<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php $this->load->view('admin/dashboard/dashboard_js'); ?>

