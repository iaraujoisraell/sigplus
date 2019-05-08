<html lang="en">

    <head>
        <meta charset="utf-8">
        <base href="<?= site_url() ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Controle de Projetos - TI UnimedManaus</title>

        <!--[if lt IE 9]>
        <script src="<?= $assets ?>js/jquery.js"></script>
        <![endif]-->
        <noscript><style type="text/css">#loading { display: none; }</style></noscript>
        <?php if ($Settings->user_rtl) { ?>
            <link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet"/>
            <link href="<?= $assets ?>styles/style-rtl.css" rel="stylesheet"/>
            <script type="text/javascript">
                $(document).ready(function () { $('.pull-right, .pull-left').addClass('flip'); });
            </script>
        <?php } ?>
        <script type="text/javascript">
            $(window).load(function () {
            $("#loading").fadeOut("slow");
            });
        </script>
        <script>
        $(document).on('change', "[name='perfil_usario']", function(){
        getState();
        });<!-- PACE LOAD BAR PLUGIN - This creates the subtle load bar effect at the top of the page. -->
            <link href="<?= $assets ?>dashboard/css/plugins/pace/pace.css" rel="style            sheet">
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
            <link href="<?= $assets ?>dashboard/css/styles.css" rel="stylesheet">
            <link href="<?= $assets ?>dashboard/css/plugins.css" rel="stylesheet">

            <!-- THEME DEMO STYLES - Use these styles for reference if needed. Otherwise they can be deleted. -->
            <link href="<?= $assets ?>dashboard/css/demo.css" rel="stylesheet">

            <!-- CRONOGRAMA. -->
            <link rel="stylesheet" type="text/css" media="screen" href="<?= $assets ?>cronograma/stylesheets/stylesheet.css">


        </head>


        <body >

            <div id="wrapper">

<?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("auth/troca_perfil_dashboard/".$projeto_selecionado, $attrib);
                ?>
                <!-- begin TOP NAVIGATION  style="background-color: seagreen; position: relative; width: 100%; height: 50px;"-->
                <nav class="navbar-top"  role="navigation">

                    <div style="width: 420px;"  class="navbar-header">
                        
                        <ul  class="nav navbar-left">
                            <li >
                                <a style="height:50px; width: 90px; color: #ffffff;"  class="gray dropdown-toggle" href="<?= site_url('Login_Projetos/menu'); ?>">
                                   <div style=" margin-top: 10px;"> <i class="fa fa-chevron-left"></i> <?= lang('Voltar'); ?></div>
                                </a>


                            </li>
                        </ul>
                        <ul   class="nav navbar-left">
                            
                          <li style="width: 200px; " >
                                <a style="margin-left: -20px; margin-top: -6px; "  href="<?= site_url('Login_Projetos/menu'); ?>" class=" dropdown-toggle" >
                                    <img width="200px;" height="50px" src="<?= base_url() ?>assets/uploads/logos/LogoUnimed1.png " >
                                </a>  
                            </li>
                        </ul>
                        


                    
                    <?php
                    $usuario = $this->session->userdata('user_id');
                    $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    $perfil_atual = $projetos->group_id;
                    $perfis_user = $this->site->getUserGroupAtual($perfil_atual);

                   $perfis_user = $this->site->getPerfilusuarioByID($usuario);
                   $qtde_perfis_user = 0;
                       foreach ($perfis_user as $item) {
                           $qtde_perfis_user++;
                       }
                    ?>
                    
                        
                       
                    </div>
                    
                    <div class="nav-top">
                        <ul class="nav navbar-right">
                            
                            <li >
                                      <img style="width: 50px; height: 50px; margin-top: -5px;" alt="" src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="mini_avatar img-rounded">
                            
                            </li>
                          <li style="text-decoration: none" style="width: 300px;  " >
                                    <span style="text-decoration: none" >
                                        <p style="font-size: 14px; color: #ffffff;">Olá, <?= $this->session->userdata('username'); ?>, Bem Vindo. </p>
                                        <p style="font-size: 14px; color: #ffffff;"><?php if($qtde_perfis_user > 1){ ?> Você está usando o perfil de: <?php } ?></p> </span>
                              
                            </li>
                             
                           
                            <li class="dropdown">
                                       <?php
                                       
                                       if ($qtde_perfis_user > 1) {
                                           $usuario = $this->session->userdata('user_id');
                                           $perfis_user = $this->site->getPerfilusuarioByID($usuario);
                                           
                                           
                                           
                                         
                                           foreach ($perfis_user as $item) {
                                               
                                               if($item->grupo_id == 1){
                                                 $wu4[$item->grupo_id] = $item->name;
                                               }
                                               if($item->grupo_id == 2){
                                                   // Verifica se ele ta na tabela de gestores
                                                   $perfis_user = $this->site->getPerfilGestorByIDandProjeto($usuario, $projeto_selecionado);
                                                   
                                                   $qtde_gestor = $perfis_user->quantidade;
                                                   
                                                  if($qtde_gestor > 0){
                                                    $wu4[$item->grupo_id] = $item->name;
                                                  }
                                               }
                                               if($item->grupo_id == 3){
                                                   // Verifica se ele ta na tabela de gestores
                                                   $perfis_user = $this->site->getPerfilSuperintendenteByIDandProjeto($usuario, $projeto_selecionado);
                                                   
                                                   $qtde_gestor = $perfis_user->quantidade;
                                                   
                                                  if($qtde_gestor > 0){
                                                    $wu4[$item->grupo_id] = $item->name;
                                                  }
                                               }
                                                if($item->grupo_id == 5){
                                                    $wu4[$item->grupo_id] = $item->name;
                                                }
                                                
                                           }
                                           
                                           echo form_dropdown('perfil_usuario', $wu4, (isset($_POST['perfil_usuario']) ? $_POST['perfil_usuario'] : $perfil_atual), '  class="form-control selectpicker  select" style="width:100%; height: 50px;" ');
                                       
                                       ?>
                               
                                    <li>
                                        <?php echo form_submit('add_projeto', lang("Mudar Perfil"), 'id="add_projeto" class="btn btn-orange " style= "height: 50px;"'); ?>
                                    </li>
                                    <?php
                                    
                                    }
                                    ?>
                               
                            </li>
                           
                            <li class="dropdown">
                                <a class="blue dropdown-toggle" target="_blank" onclick="window.open" href="<?= site_url('projetos/dashboard_pdf/'.$projeto_selecionado); ?>">
                                    <i class="fa fa-print"></i> <?= lang('Imprimir'); ?>
                                </a>
                                

                            </li>
                            <li class="dropdown">
                                <a class="red dropdown-toggle" href="<?= site_url('Auth/logout'); ?>">
                                    <i class="fa fa-sign-out"></i> <?= lang('logout'); ?>
                                </a>
                                

                            </li>
                            
                        </ul>
                    </div>


                </nav>
                <?php echo form_close(); ?>
                    
                <!-- begin MAIN PAGE CONTENT -->
                <div id="page-wrapper">

                    <div class="page-content">

                        <!-- begin PAGE TITLE AREA -->
                        <!-- Use this section for each page's title and breadcrumb layout. In this example a date range picker is included within the breadcrumb. -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="page-title">
                                    
                                    <ol class="breadcrumb">
                                        <li >
                                            <h1>
                                                <small>Dashboard HELPDESK-</small> <?php echo $projetos->projeto; ?>
                                            </h1>
                                        </li>
                            
                                        <li class="pull-right">
                                            <div  class="btn btn-green btn-square date-picker">
                                                <i class="fa fa-calendar"></i> <?php echo  date('d/m/Y', strtotime($data_hoje)); ?>
                                            </div>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        <!-- /.row -->
                        <?php 
                        $soma_acoes_sem_atendimento = 0;
                        //print_r($tickets);
                         foreach ($tickets_semchamado as $ticket2) {
                           $id = $ticket2->id;
                         
                           $status = $ticket2->status;
                          
           
                           if($status == 2){
                               $soma_acoes_sem_atendimento++;
                           }
                         }
                        ?>
                       
                        <!-- RESUMO INICIAL -->
                        <div class="row">
                            <div class="col-lg-2 col-sm-6">
                                <div class="circle-tile">
                                    <a >
                                        <div class="circle-tile-heading dark-blue">
                                            <i class="fa fa-clock-o fa-fw fa-3x"></i>
                                        </div>
                                    </a>
                                    <div class="circle-tile-content dark-blue">
                                        <div class="circle-tile-description text-faded">
                                            Sem Atendimento 
                                        </div>
                                        <div class="circle-tile-number text-faded">
                                            <?php echo $soma_acoes_sem_atendimento; ?>
                                        </div>
                                 
                                    </div>
                                </div>
                            </div>
                            <?php 
                        $soma_acoes_em_execucao = 0;
                        $soma_acoes_canceladas = 0;
                        $soma_acoes_solucionado = 0;
                        $soma_acoes_fechado = 0;
                        //print_r($tickets);
                         foreach ($tickets as $ticket) {
                           $id = $ticket->id;
                         
                           $status = $ticket->status;
                          
           
                           if($status == 3){
                               $soma_acoes_em_execucao++;
                           }
                           
                           if($status == 4){
                               $soma_acoes_canceladas++;
                           }
                           
                           if($status == 5){
                               $soma_acoes_solucionado++;
                           }
                           
                           if($status == 6){
                               $soma_acoes_fechado++;
                           }
                         }
                        ?>

                            <div class="col-lg-2 col-sm-6">
                                <div class="circle-tile">
                                    <a >
                                        <div class="circle-tile-heading blue">
                                            <i class="fa fa-flash fa-fw fa-3x"></i>
                                        </div>
                                    </a>
                                    <div class="circle-tile-content blue">
                                        <div class="circle-tile-description text-faded">
                                            Em execução
                                        </div>
                                        <div class="circle-tile-number text-faded">
                                            <?php echo $soma_acoes_em_execucao; ?>

                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                                <div class="circle-tile">
                                    <a >
                                        <div class="circle-tile-heading gray">
                                            <i class="fa fa-exclamation fa-fw fa-3x"></i>
                                        </div>
                                    </a>
                                    <div class="circle-tile-content gray">
                                        <div class="circle-tile-description text-faded">
                                            Solucionado
                                        </div>
                                        <div class="circle-tile-number text-faded">
                                            <?php echo $soma_acoes_solucionado; ?>
                                        </div>
                                 
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
                                            Fechado
                                        </div>
                                        <div class="circle-tile-number text-faded">
                                            <?php echo $soma_acoes_fechado; ?>

                                        </div>
                                
                                    </div>
                                </div>
                            </div>

                            

                            <div class="col-lg-2 col-sm-6">
                                <div class="circle-tile">
                                    <a >
                                        <div class="circle-tile-heading red">
                                            <i class="fa fa-ban fa-fw fa-3x"></i>
                                        </div>
                                    </a>
                                    <div class="circle-tile-content red">
                                        <div class="circle-tile-description text-faded">
                                            CANCELADO
                                        </div>
                                        <div class="circle-tile-number text-faded">
                                            <?php echo $soma_acoes_canceladas; ?>

                                        </div>
                                   
                                    </div>
                                </div>
                            </div>

                        </div>
                       
                        
                        <!-- TIMELINE DOS EVENTOS -->
                        <div  class="row">
                            <div class="col-lg-12">
                                <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>CHAMADO POR CATEGORIA</h4>
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
                                   <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr style="background-color: orange;">
                                                <th>ID</th>
                                                <th>CATEGORIA</th>
                                                <th>AGUARDANDO</th>
                                                <th>EXECUÇÃO</th>
                                                <th>CANCELADO</th>
                                                <th>SOLUCIONADO</th>
                                                <th>FECHADO</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                                         
                                            <?php
                                            $cont = 0;
                                                foreach ($categorias as $categoria) {
                                                    
                                                    $tickets_aguardando = $this->reports_model->getAllTicketByCategoriaAndStatus($categoria->id, 2);
                                                    $aguardando_atendimento = $tickets_aguardando->quantidade;
                                                    
                                                    $tickets_execucao = $this->reports_model->getAllTicketByCategoriaAndStatus($categoria->id, 3);
                                                    $executando = $tickets_execucao->quantidade;
                                                    
                                                    $tickets_cancelado = $this->reports_model->getAllTicketByCategoriaAndStatus($categoria->id, 4);
                                                    $cancelado = $tickets_cancelado->quantidade;
                                                    
                                                    $tickets_solucionado = $this->reports_model->getAllTicketByCategoriaAndStatus($categoria->id, 5);
                                                    $solucionado = $tickets_solucionado->quantidade;
                                                    
                                                    $tickets_fechado = $this->reports_model->getAllTicketByCategoriaAndStatus($categoria->id, 6);
                                                    $fechado = $tickets_fechado->quantidade;
                                                     
                                                    
                                                //}
                                               
                                              
                                                ?>   
                                           
                                            <tr   class="odd gradeX">
                                                        <td><?php echo $cont++; ?>   </td> 
                                                        <td><?php echo $categoria->completename; ?></td> 
                                                        <td><?php echo $aguardando_atendimento; ?></td>     
                                                        <td><?php echo $executando; ?></td> 
                                                        <td><?php echo $cancelado; ?></td>
                                                         <td><?php echo $solucionado; ?> </td>     
                                                         <td><?php echo $fechado; ?> </td>     
                                                         
                                            </tr>
                                                <?php
                                                
                                                }
                                                ?>
                                            
                                            
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                                </div>
                            </div>
                         </div>

                            </div>
                        </div>
                    </div>

                    
                     <!-- Line Chart Example -->
                




              
                

                    <!-- /.row -->


                    
                                <?php

                                function geraTimestamp($data) {
                                    $partes = explode('/', $data);
                                    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
                                }
                                ?>
                   
                </div>
                <!-- /.page-content -->

            </div>
            <!-- /#page-wrapper -->
            <!-- end MAIN PAGE CONTENT -->


        </div>




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



              
   <!-- CRONOGRAMA. -->

    <link rel="stylesheet" type="text/css" href="http://cdnjs.cloudflare.com/ajax/libs/qtip2/2.0.1/jquery.qtip.min.css" />
                                    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/qtip2/2.0.1/jquery.qtip.min.js"></script>

                                    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

                                    <link rel="stylesheet" type="text/css" href="<?= $assets ?>cronograma/dist/chronoline.css" />
                                    <script type="text/javascript" src="<?= $assets ?>cronograma/dist/chronoline.js"></script>




                                </body>

                            </html>
