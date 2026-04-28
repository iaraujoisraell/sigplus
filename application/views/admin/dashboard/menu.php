<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
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
    
<script>
function Mudarestado(el) {
    var display = document.getElementById(el).style.display;
    if(display == "none")
        document.getElementById(el).style.display = 'block';
    else
        document.getElementById(el).style.display = 'none';
}      
</script>
    
    
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
                <?php if($mod_sistema == 1){ ?>
                <div class="col-lg-3 col-xs-6">
                  <!-- Comercial -->
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3><sup style="font-size: 20px">Sistema</sup></h3>
                      <p>Módulos</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-bag"></i>
                    </div>
                    <a href="<?php echo admin_url('dashboard/index'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                   <!-- <a href="#tab_comercial" data-toggle="tab" class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  -->
                  </div>
                </div>
                <?php } ?>
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
                <?php if($mod_indicadores == 1){ ?>
                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3><sup style="font-size: 20px">Indicadores</sup></h3>
                      <p>Gestão</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" onclick="Mudarestado('indicadoresDiv')" class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <?php } ?>
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
                <?php if($mod_faturamento == 1){ ?>
                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-yellow">
                    <div class="inner">
                      <h3><sup style="font-size: 20px">Faturamento</sup></h3>

                      <p>Gestão</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-cash"></i>
                    </div>
                    <a href="#" onclick="Mudarestado('faturamentoDiv')"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <?php } ?>
                
                <?php
                $mod_financeiro = 0;
                    foreach ($modulos as $mod){
                        $modulo_id = $mod['modulo_id'];
                        if($modulo_id == 4){
                            $mod_financeiro = 1;
                        }
                    }
                ?>
                <?php if($mod_financeiro == 1){ ?>
                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-green">
                    <div class="inner">
                      <h3><sup style="font-size: 20px">Financeiro</sup></h3>

                      <p>Gestão</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-cash"></i>
                    </div>
                    <a href="<?php echo admin_url('dashboard/menu_financeiro'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>   
                  <!--  <a href="#" onclick="Mudarestado('financeiroDiv')"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a> -->
                  </div>
                </div>
                
               
                <?php } ?>
                <?php
                $mod_plantao = 0;
                    foreach ($modulos as $mod){
                        $modulo_id = $mod['modulo_id'];
                        if($modulo_id == 5){
                            $mod_plantao = 1;
                        }
                    }
                ?>
                <?php if($mod_plantao == 1){ ?>
                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-primary">
                    <div class="inner">
                      <h3><sup style="font-size: 20px">Gestão de plantões</sup></h3>

                      <p>Gestão</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-calendar"></i>
                    </div>
                    <a href="#" onclick="Mudarestado('gestao_plantaoDiv')"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <?php } ?>
              </div>
                
                <!-- SUB MENUS - FINANCEIRO -->
                <div style="display: none" id="financeiroDiv">
                    <div class="panel box box-danger">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a >
                                    Controle Financeiro
                                </a>
                            </h4>
                        </div>
                        <div class="box-body">
                            <div class="col-sm-3 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/financeiro/cadastros') ?>">  
                                    <div class="info-box">
                                        <span class="info-box-icon bg-aqua"><i class="fa fa-pencil"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Cadastros</span>
                                            <span class="info-box-number"><small>Cadastros Financeiros</small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>    
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/financeiro') ?>">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-yellow"><i class="fa fa-heartbeat"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Contas a Pagar</span>
                                            <span class="info-box-number"><small>Títulos/Contrato</small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>        
                                <!-- /.info-box -->
                            </div>
                            
                            <div class="col-sm-3 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/financeiro/list_conta_pagar_parcelas') ?>">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-gray"><i class="fa fa-list"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Parcelas a Pagar</span>
                                            <span class="info-box-number"><small>Parcelas dos Títulos</small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>        
                                <!-- /.info-box -->
                            </div>

                            <div class="col-sm-3 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/financeiro_invoices') ?>">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-green"><i class="fa fa-smile-o"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Contas a Receber</span>
                                            <span class="info-box-number"><small>Add Lançamentos</small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>        
                                <!-- /.info-box -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel box box-danger">
                          <div class="box-header with-border">
                            <h4 class="box-title">
                              <a >
                                  Relatórios / Dashboard
                              </a>
                            </h4>
                          </div>
                          <div class="box-body">
                           <div class="row">
                            
                            <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/Financeiro_report') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-dashboard"></i></span>
                                <div class="info-box-content">
                                  <span class="info-box-text">Indicadores / Dashboard</span>
                                  <span class="info-box-number"><small></small></span>
                                </div>

                              </div>
                              </a>    
                            </div>     
                               
                            <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/Financeiro_report/entradas') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa  fa-arrow-circle-left"></i></span>
                                <div class="info-box-content">
                                  <span class="info-box-text">Entradas</span>
                                  <span class="info-box-number"><small></small></span>
                                </div>

                              </div>
                              </a>    
                            </div>   
                               
                            <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/Financeiro_report/saidas') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-yellow "><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="info-box-content">
                                  <span class="info-box-text">Saídas</span>
                                  <span class="info-box-number"><small></small></span>
                                </div>

                              </div>
                              </a>    
                            </div>
                               
                            <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/producao_auditoria') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-exchange"></i></span>
                                <div class="info-box-content">
                                  <span class="info-box-text">Fluxo Financeiro</span>
                                  <span class="info-box-number"><small>Pagamentos/ Holerites Médico</small></span>
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              </a>    
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->


                          </div>
                        </div>
                </div>
                </div>     
                <!-- FIM SUB MENUS - FINANCEIRO -->   
                
                
                <?php
                $mod_menu_med = 0;
                    foreach ($modulos as $mod){
                        $modulo_id = $mod['modulo_id'];
                        if($modulo_id == 6){
                            $mod_menu_med = 1;
                        }
                    }
                ?>
                <?php if($mod_menu_med == 1){ ?>
                <h3>Menu Médico</h3>
                <div class="row">
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
                        <a href="<?php echo admin_url('medicos/agenda_medica'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
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
                          <i class="fa fa-user-md"></i>
                        </div>
                        <a href="<?php echo admin_url('medicos/producao_medica'); ?>"  class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>  
                      </div>
                    </div>
                </div>
                <?php } ?>
                
                <!-- SUB MENUS - GESTÃO DE PLANTÕES -->
                <div style="display: none" id="gestao_plantaoDiv">
                    
                    <div class="panel box box-danger">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a >
                                    Controle de Plantões
                                </a>
                            </h4>
                        </div>
                        <div class="box-body">
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/Gestao_titular') ?>">  
                                    <div class="info-box">
                                        <span class="info-box-icon bg-yellow"><i class="fa fa-user-md"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Escala Fixa</span>
                                            <span class="info-box-number"><small>Contrato </small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>    
                                <!-- /.info-box -->
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/gestao_plantao') ?>">  
                                    <div class="info-box">
                                        <span class="info-box-icon bg-green"><i class="fa fa-hospital-o"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Escalas Unidades Hospitalares</span>
                                            <span class="info-box-number"><small>Escalas </small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>    
                                <!-- /.info-box -->
                            </div>
                            
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/gestao_plantao') ?>">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-purple"><i class="fa fa-user-md"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Escalas Médicos</span>
                                            <span class="info-box-number"><small>Escala/Plantão</small></span>
                                        </div>
                                       
                                    </div>
                                </a>        
                                
                            </div>
                            
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/gestao_plantao/listagem_escala') ?>">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-gray"><i class="fa fa-calendar"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Gestão de Escala</span>
                                            <span class="info-box-number"><small>Cadastros</small></span>
                                        </div>
                                        
                                    </div>
                                </a>        
                               
                            </div>
                            
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/Gestao_titular/escala_fixa_resumo') ?>">  
                                    <div class="info-box">
                                        <span class="info-box-icon bg-yellow"><i class="fa fa-list"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Relatório Escala Fixa</span>
                                            <span class="info-box-number"><small>Resumo </small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>    
                                <!-- /.info-box -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="panel box box-danger">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a >
                                    Cadastros Principais
                                </a>
                            </h4>
                        </div>
                        <div class="box-body">
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/medico_substituto') ?>">  
                                    <div class="info-box">
                                        <span class="info-box-icon bg-green"><i class="fa fa-user-md"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Substitutos</span>
                                            <span class="info-box-number"><small>Cadastro</small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>    
                                <!-- /.info-box -->
                            </div><!-- comment -->
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/medicos') ?>">  
                                    <div class="info-box">
                                        <span class="info-box-icon bg-aqua"><i class="fa fa-pencil"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Médicos</span>
                                            <span class="info-box-number"><small>Profissionais</small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>    
                                <!-- /.info-box -->
                            </div>
                            
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/Unidades_hospitalares') ?>">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-yellow"><i class="fa fa-heartbeat"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Unidades Hospitalares</span>
                                            <span class="info-box-number"><small>Hospitais</small></span>
                                        </div>
                                       
                                    </div>
                                </a>        
                                
                            </div>
                            
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/horario_plantao') ?>">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-red"><i class="fa fa-clock-o"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Horários de Plantões</span>
                                            <span class="info-box-number"><small>Cadastros</small></span>
                                        </div>
                                        
                                    </div>
                                </a>        
                               
                            </div>
                        </div>
                    </div>
                    <div class="panel box box-danger">
                          <div class="box-header with-border">
                            <h4 class="box-title">
                              <a >
                                  Relatórios
                              </a>
                            </h4>
                          </div>
                         <!-- 
                         @autor: Larissa
                         @data: 04/07/22
                         @desc: Mudnça apenas da tag span para 'ESCALA DE PLANTÕES'
                         e na base url para chamada do controller-->
                         
                          <div class="box-body">
                           <div class="row">
                          <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/Escala_plantoes');?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-yellow aqua"><i class="fa fa-file"></i></span>
                                <div class="info-box-content">
                                  <span class="info-box-text">ESCALA DE PLANTÕES </span>
                                  <span class="info-box-number"><small>Setor/Hospital</small></span>
                                </div>
         
                              </div>
                              </a>    
                            
                            </div>   
                         <!-- 
                         @autor: Larissa
                         @data: 04/07/22
                         @desc: Mudnça apenas da tag span para 'LISTA DE FREQUENCIA'
                         e na base url para chamada do controller-->
                         
                            <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/Lista_frequencia') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-file-text"></i></span>

                                <div class="info-box-content">
                                  <span class="info-box-text">LISTA DE FREQUÊNCIA</span>
                                  <span class="info-box-number"><small>Médicos/Hospital</small></span>
                                </div>
                               
                              </div>
                              </a>    
                             
                            </div>
                       

                          </div>
                        </div>
                </div>
               </div>     
                
                <!-- SUB MENUS - FATURAMENTO -->
                <div style="display: none" id="faturamentoDiv">
                    <div class="panel box box-danger">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a >
                                    Controle Faturamento
                                </a>
                            </h4>
                        </div>
                        <div class="box-body">
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/producao_auditoria') ?>">  
                                    <div class="info-box">
                                        <span class="info-box-icon bg-aqua"><i class="fa fa-pencil"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Auditoria Produção</span>
                                            <span class="info-box-number"><small>Procedimentos aguardando auditoria</small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>    
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/producao_auditoria/repasse_medico') ?>">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-yellow"><i class="fa fa-check-square-o"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">CONFIRMAR REPASSE MÉDICO</span>
                                            <span class="info-box-number"><small>Add Repasse para produção confirmada</small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>        
                                <!-- /.info-box -->
                            </div>
                        </div>
                    </div>

                    <div class="panel box box-danger">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a >
                                    Produção via Carga (Excel / Xml)
                                </a>
                            </h4>
                        </div>
                        <div class="box-body">
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/faturamento_carga/add_carga') ?>">  
                                    <div class="info-box">
                                        <span class="info-box-icon bg-aqua"><i class="fa fa-arrow-up"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Carga</span>
                                            <span class="info-box-number"><small>Fazer Upload Arquivo</small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>
                                <!-- /.info-box -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 col-xs-12">
                                <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/faturamento_carga') ?>">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-yellow"><i class="fa fa-check-square-o"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Produção</span>
                                            <span class="info-box-number"><small>Produção Profissional/Convênio</small></span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </a>        
                                <!-- /.info-box -->
                            </div>
                        </div>
                    </div>

                    <div class="panel box box-danger">
                          <div class="box-header with-border">
                            <h4 class="box-title">
                              <a >
                                  Relatórios
                              </a>
                            </h4>
                          </div>
                          <div class="box-body">
                           <div class="row">
                          <!--  <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/dashboard/dashboard_gestao_producao') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-yellow aqua"><i class="fa fa-bar-chart-o"></i></span>
                                <div class="info-box-content">
                                  <span class="info-box-text">PRODUÇÃO MÉDICA</span>
                                  <span class="info-box-number"><small></small></span>
                                </div>

                              </div>
                              </a>    
                            /.info-box -->
                            </div>   
                            <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/producao_auditoria') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-exchange"></i></span>

                                <div class="info-box-content">
                                  <span class="info-box-text">Repasses Médicos</span>
                                  <span class="info-box-number"><small>Pagamentos/ Holerites Médico</small></span>
                                </div>
                                <!-- /.info-box-content -->
                              </div>
                              </a>    
                              <!-- /.info-box -->
                            </div>
                            <!-- /.col -->


                          </div>
                        </div>
                </div>
                    

                <!-- SUB MENUS - INDICADORES -->
                <div style="display: none" id="indicadoresDiv">
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
                            <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/dashboard/dashboard_producao/') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-yellow"><i class="fa fa-bar-chart-o"></i></span>
                                <div class="info-box-content">
                                  <span class="info-box-text">PRODUÇÃO MÉDICA</span>
                                  <span class="info-box-number"><small></small></span>
                                </div>

                              </div>
                              </a>    
                           <!-- /.info-box -->
                            </div>   
                             <div class="col-sm-4 col-xs-12">
                              <a style="text-decoration: none; color: inherit" href="<?php echo base_url('admin/Financeiro_report/dashboard') ?>">  
                              <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                                <div class="info-box-content">
                                  <span class="info-box-text">FINANCEIRO</span>
                                  <span class="info-box-number"><small></small></span>
                                </div>

                              </div>
                              </a>    
                           <!-- /.info-box -->
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
            </div>

                <!-- FIM SUB-MENUS COMERCIAL -->

                <!-- SUB-MENUS SAME -->



                <!-- FIM SUB-MENUS SAME -->
               
            </div>
        </div>
   </div>
</div>    
        
     
  
    

<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php $this->load->view('admin/dashboard/dashboard_js'); ?>

   </div>
   </body>
</html>
