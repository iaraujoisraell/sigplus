

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <base href="<?= site_url() ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Controle de Projetos - TI UnimedManaus</title>

           
            <!-- GLOBAL STYLES - Include these on every page. -->
            <link href="<?= $assets ?>dashboard/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
            <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel="stylesheet" type="text/css">
            <link href="<?= $assets ?>dashboard/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">

            
             <!-- PAGE LEVEL PLUGIN STYLES -->
            <link href="<?= $assets ?>dashboard/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
            <link href="<?= $assets ?>dashboard/css/plugins/bootstrap-social/bootstrap-social.css" rel="stylesheet">
            <link href="<?= $assets ?>dashboard/css/plugins/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet">

            <!-- THEME STYLES - Include these on every page. -->
            <link href="<?= $assets ?>dashboard/css/styles.css" rel="stylesheet">
            <link href="<?= $assets ?>dashboard/css/plugins.css" rel="stylesheet">

            <!-- THEME DEMO STYLES - Use these styles for reference if needed. Otherwise they can be deleted. -->
            <link href="<?= $assets ?>dashboard/css/demo.css" rel="stylesheet">
              
            <!-- CALENDAR. -->
            <link href="<?= $assets ?>dashboard/css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
                <!-- PAGE LEVEL PLUGIN STYLES -->

                    <!-- PAGE LEVEL PLUGIN STYLES -->
           

        </head>


        <body style="background: #ffffff;" >

            <div id="wrapper">

            <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("auth/troca_perfil_dashboard/".$projeto_selecionado, $attrib);
                ?>
                <!-- begin TOP NAVIGATION  style="background-color: seagreen; position: relative; width: 100%; height: 50px;"-->
                <nav class="navbar-top"  role="navigation">

                    <div style="width: 420px;"  class="navbar-header">
                        
                        <ul  class="nav navbar-left">
                            <li style="margin-top: -10px; width: 200px; " >
                                        <a  href="<?= site_url('Login_Projetos/menu'); ?>" >
                                            <img width="170px" height="50px" src="<?= base_url() ?>assets/uploads/logos/LogoUnimed1.png " >
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
                    
                    
                     <?php
                     $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    
                     /*
                      * VERIFICA SE O USUÁRIO TEM PERMISSAO PARA ACESSAR O MENU EXIBIDO
                      */
                     $permissoes           = $this->projetos_model->getPermissoesByPerfil($projetos->group_id);   
                     $permissao_projetos   = $permissoes->projetos_index;
                     $permissao_atas       = $permissoes->atas_index;
                     $permissao_participantes   = $permissoes->participantes_index;
                     $permissao_eventos    = $permissoes->eventos_index;
                     
                     $permissao_acoes      = $permissoes->acoes_index;
                     $permissao_avalidacao = $permissoes->acoes_aguardando_validacao_index;
                     $permissao_apendentes = $permissoes->acoes_pendentes_index;
                     
                     
                     $permissao_dashboard   = $permissoes->dashboard_index;
                     
                     /*
                      * CADASTRO
                      */
                     $permissao_cadastro              = $permissoes->cadastro;
                     $permissao_pesquisa_satisfacao   = $permissoes->pesquisa_satisfacao_index;
                     $permissao_categoria_financeira  = $permissoes->categoria_financeira_index	;
                     /*
                      * RELATÓRIO
                      */
                     $permissao_relatorios             = $permissoes->relarorios;
                     $permissao_status_report          = $permissoes->status_report;
                     $permissao_users_acoes_atrasadas  = $permissoes->users_acoes_atrasadas;
                     /*
                      * PESSOAS
                      */
                     $permissao_cadastro_pessoas    = $permissoes->cadastro_pessoas;
                     $permissao_usuarios            = $permissoes->users_index;
                     $permissao_gestores            = $permissoes->lista_gestores;
                     $permissao_suporintendentes    = $permissoes->lista_superintendente;
                     $permissao_fornecedor          = $permissoes->fornecedores_index;
                     
                     
                     /*
                      * GESTAO DE CUSTO
                      */
                     $permissao_gestao_custo          = $permissoes->gestao_custo;
                     $permissao_contas_pagar          = $permissoes->contas_pagar;
                     
                     /*
                      * CALENDÁRIO
                      */
                     $permissao_calendario          = $permissoes->calendario;
                    ?>
                    
                    <div class="nav-top">
                        <ul class="nav navbar-right">
                            
                            <li>
                                <img style="width: 50px; height: 50px; margin-top: -5px;" alt="" src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="mini_avatar img-rounded">
                            </li>
                          <li style="text-decoration: none"  >
                                    <span style="text-decoration: none" >
                                        <p style="font-size: 14px; color: #ffffff;">Olá <?= $this->session->userdata('username'); ?>, Bem Vindo </p>
                                        <p style="font-size: 14px; color: #ffffff;"><?php if($qtde_perfis_user > 1){ ?> :seu perfil atual <?php } ?></p> </span>
                            </li>
                            <li class="dropdown">
                                       <?php
                                       
                                       if ($qtde_perfis_user > 1) {
                                           $usuario = $this->session->userdata('user_id');
                                           $perfis_user = $this->site->getPerfilusuarioByID($usuario);

                                           foreach ($perfis_user as $item) {
                                               $wu4[$item->grupo_id] = $item->name;
                                           }
                                           echo form_dropdown('perfil_usuario', $wu4, (isset($_POST['perfil_usuario']) ? $_POST['perfil_usuario'] : $perfil_atual), '  class="form-control selectpicker  select" style="width:100%; height: 50px;" ');
                                       }
                                       ?>
                               
                                    <li>
                                        <?php echo form_submit('add_projeto', lang("Trocar"), 'id="add_projeto" class="btn btn-flickr" style= "height: 50px;"'); ?>
                                    </li>
                            </li>
                            
                            <li class="dropdown ">
                            <a class="btn tip btn-twitter" title="<?= lang('calendar') ?>" data-placement="bottom" href="#" data-toggle="dropdown">
                                <i class="fa fa-calendar"></i>
                                <span class="number blightOrange black"><?= sizeof($events) ?></span>
                            </a>
                            <ul class="dropdown-menu pull-right content-scroll">
                                <li class="dropdown-header">
                                <i class="fa fa-calendar"></i> <?= lang('upcoming_events'); ?>
                                </li>
                                <li class="dropdown-content">
                                    <div class="top-menu-scroll">
                                        <ol class="oe">
                                            <?php foreach ($events as $event) {
                                                echo '<li>' . date($dateFormats['php_ldate'], strtotime($event->start)) . ' <strong>' . $event->title . '</strong><br>'.$event->description.'</li>';
                                            } ?>
                                        </ol>
                                    </div>
                                </li>
                                <li class="dropdown-footer">
                                    <a href="<?= site_url('calendar') ?>" class="btn-block link">
                                        <i class="fa fa-calendar"></i> <?= lang('calendar') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                            <?php
                         $quantidadePendente = $this->site->getAllPlanosPendenteUser($usuario);
                         $quantidadeAvalidacao = $this->site->getAllPlanosAguardandoValidacao($projetos->id);
                         
                         $acoes_pendentes_alerta = $quantidadePendente->quantidade;
                         if ($permissao_avalidacao )   { 
                         $acoes_aguardando_validacao = $quantidadeAvalidacao->quantidade;
                         }else{
                             $acoes_aguardando_validacao = 0;
                         }
                         
                        ?>
                          <li class="dropdown ">
                            <a class="btn btn-orange tip" title="<?= lang('alerts') ?>" 
                                data-placement="left" data-toggle="dropdown" href="#">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="number blightOrange black"><?php echo $acoes_pendentes_alerta + $acoes_aguardando_validacao; ?></span>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="<?= site_url('welcome') ?>" class="">
                                        <span class="label label-danger pull-right" style="margin-top:3px;"><?php echo $quantidadePendente->quantidade; ?></span>
                                        <span style="padding-right: 35px;"><?= lang('Suas ações Pendentes') ?></span>
                                    </a>
                                </li>
                                <?php if ($permissao_avalidacao )   {  ?>
                                <li>
                                    <a href="<?= site_url('Planos/planosAguardandoValidacao') ?>" class="">
                                        <span class="label label-danger pull-right" style="margin-top:3px;"><?php echo $quantidadeAvalidacao->quantidade; ?></span>
                                        <span style="padding-right: 35px;"><?= lang('Ações Aguardando Validação') ?></span>
                                    </a>
                                </li>
                                <?php } ?>
                               
                            </ul>
                        </li>
                            <li class="dropdown">
                                <a class="red dropdown-toggle" href="<?= site_url('Auth/logout'); ?>">
                                    <i class="fa fa-sign-out"></i> 
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                
                
                <?php echo form_close(); ?>
            
                  
                     
                <!-- /.page-content -->
                   <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
                <br><br>
               
                 <!-- EXIBE PROJETO ATUAL -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <ul class="breadcrumb">
                             
                              
                                    <li>
                                        <li>
                                        <a style="  text-decoration: mouse;"class="btn btn-lg  btn-social ">
                                            Início do Projeto: <?php echo date("d/m/Y", strtotime($projetos->dt_inicio)); ?>
                                         </a>
                                        </li>
                                        
                                    <li>
                                        <a style="text-decoration: none;"  class="btn btn-lg  btn-social ">
                                            Gestor do Projeto:    <?php echo $projetos->gerente_area; ?>
                                         </a>
                                        </li>
                                    <li >
                                        <a style=" text-decoration: none;" class="btn btn-lg  btn-social ">
                                            <?php echo $projetos->projeto. ' '; ?>
                                             
                                         </a>
                                    </li>
                                    
                                    
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- / EXIBE PROJETO ATUAL -->
                
                <!-- ATALHOS RÁPIDO -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <?php if ($permissao_acoes) { ?>     
                                <a title="LISTA DE AÇÕES" href="<?= site_url('planos') ?>" class="btn btn-social-icon btn-dropbox"><i class="fa fa-list"></i></a>
                            <?php } ?>
                            <?php if ($permissao_apendentes) { ?>     
                                <a title="AÇÕES PENDENTES" href="<?= site_url('Planos/planosPendentes') ?>" class="btn btn-social-icon btn-dropbox"><i class="fa fa-exclamation"></i></a>
                            <?php } ?>
                            <?php if ($permissao_avalidacao) { ?> 
                                <a title="AÇÕES AGUARDANDO VALIDAÇÃO"  href="<?= site_url('planos/planosAguardandoValidacao') ?>" class="btn btn-social-icon btn-pinterest"><i class="fa fa-clock-o"></i></a>
                            <?php } ?>    
                            <?php if ($permissao_usuarios) { ?>     
                                <a title="LISTA DE USUÁRIOS" href="<?= site_url('users') ?>" class="btn btn-social-icon btn-vk"><i class="fa fa-user"></i></a>
                            <?php } ?>
                            <?php if ($permissao_calendario) { ?>    
                                <a title="CALENDÁRIO" href="<?= site_url('calendar') ?>" class="btn btn-social-icon btn-instagram"><i class="fa fa-calendar"></i></a>
                            <?php } ?>
                            <?php if ($permissao_contas_pagar) { ?>    
                                <a title="CUSTOS" href="<?= site_url('financeiro') ?>" class="btn btn-social-icon btn-green"><i class="fa fa-money"></i></a>
                            <?php } ?>
                            <?php if ($permissao_eventos) { ?>
                                <a title="LISTA DE EVENTOS" href="<?= site_url('projetos/eventos_index') ?>" class="btn btn-social-icon btn-facebook"><i class="fa fa-calendar-o"></i></a>
                            <?php } ?>
                            <?php if ($permissao_participantes) { ?>    
                                <a title="LISTA DE PARTICIPANTES" href="<?= site_url('Atas/index_participantes') ?>" class="btn btn-social-icon btn-github"><i class="fa fa-users"></i></a>
                            <?php } ?>
                            <?php if ($permissao_atas) { ?>
                                <a title="LISTA DE ATAS" href="<?= site_url('atas') ?>" class="btn btn-social-icon btn-bitbucket"><i class="fa fa-book"></i></a>
                            <?php } ?>
                            <?php if ($permissao_projetos) { ?>
                                <a title="LISTA DE PROJETOS" href="<?= site_url('projetos') ?>" class="btn btn-social-icon btn-tumblr"><i class="fa fa-folder"></i></a>
                            <?php } ?>    
                            <?php if ($permissao_dashboard) { ?>    
                                <a title="DASHBOARD" href="<?= site_url('projetos/dashboard/' . $projetos->projeto_atual) ?>" class="btn btn-social-icon btn-google-plus"><i class="fa fa-dashboard"></i></a>
                            <?php } ?>       





                        </div>
                    </div>

                </div>
                <!-- /ATALHOS RÁPIDO -->
                <br><br>
                <div class="row">
                    <!-- LADO ESQUERDO -->
                    <div class="col-lg-6">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Meus Projetos</h4>
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
                                    <div class="social-sizes">
                                    <?php
                                    $usuario = $this->session->userdata('user_id');
                                    $projetos_user = $this->site->getAllProjetosUsers($usuario);
                                    $cont = 1;
                                    $qtde_perfis_user = 0;
                                    foreach ($projetos_user as $item) {
                                        $id_projeto = $item->projeto;
                                        $wu3[''] = '';
                                        $projeto = $this->atas_model->getProjetoByID($id_projeto);
                                        // foreach ($projetos as $projeto) {
                                        // $wu3[$projeto->id] = $projeto->projeto;
                                        ?>
                                        <a href="<?= site_url('Login_Projetos/projeto_ata/'.$projeto->id); ?>" class="btn btn-block btn-social btn-lg btn-<?php echo $projeto->botao; ?>">
                                            <i class="fa fa-tasks fa-fw fa-3x"></i>
                                            <?php echo $projeto->projeto; ?>   <?php echo '-  Resp :' ?>   <?php echo $projetos->gerente_area; ?>
                                        </a>
                                        <hr>
                                        <?php
                                        $cont++;
                                    }
                                    //  }
                                    ?>   
                                        </div>
                                </div>
                            </div>
                        </div>
                        <!-- /MEUS PROJETOS -->
                        <!-- MENU DE ACESSO -->
                        <div class="portlet portlet-green">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Meus Acessos</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#buttonGroups"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="buttonGroups" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <h3>Controle</h3>
                                    <div class="row">
                                        <!-- PROJETO -->
                                         <?php if ($permissao_projetos){ ?>
                                        <div class="col-lg-2 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="#">
                                                    <div class="circle-tile-heading dark-blue">
                                                        <i class="fa fa-folder fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content dark-blue">
                                                    <div class="circle-tile-description text-faded">
                                                        Projetos
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                        <span id="sparklineA"></span>
                                                    </div>
                                                    <a href="#" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                         <?php } ?>
                                        <!-- /PROJETO -->
                                        <div class="col-lg-2 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="#">
                                                    <div class="circle-tile-heading green">
                                                        <i class="fa fa-money fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content green">
                                                    <div class="circle-tile-description text-faded">
                                                        Revenue
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        $32,384
                                                    </div>
                                                    <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="#">
                                                    <div class="circle-tile-heading orange">
                                                        <i class="fa fa-bell fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content orange">
                                                    <div class="circle-tile-description text-faded">
                                                        Alerts
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        9 New
                                                    </div>
                                                    <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    
                                    <hr>

                                    <h3>Ações</h3>
                                    <div class="row">
                                        <div class="col-lg-2 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="#">
                                                    <div class="circle-tile-heading blue">
                                                        <i class="fa fa-tasks fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content blue">
                                                    <div class="circle-tile-description text-faded">
                                                        Tasks
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        10
                                                        <span id="sparklineB"></span>
                                                    </div>
                                                    <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="#">
                                                    <div class="circle-tile-heading red">
                                                        <i class="fa fa-shopping-cart fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content red">
                                                    <div class="circle-tile-description text-faded">
                                                        Orders
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        24
                                                        <span id="sparklineC"></span>
                                                    </div>
                                                    <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="#">
                                                    <div class="circle-tile-heading purple">
                                                        <i class="fa fa-comments fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content purple">
                                                    <div class="circle-tile-description text-faded">
                                                        Mentions
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        96
                                                        <span id="sparklineD"></span>
                                                    </div>
                                                    <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <h3>Pessoas</h3>
                                    
                                    <hr>

                                    <h3>Cadastro</h3>
                                   
                                    <hr>

                                    <h3>Relatórios</h3>
                                   
                                    <hr>

                                   

                                </div>
                            </div>
                        </div>
                        <!-- /MENU DE ACESSO -->
                    </div>
                    <!-- /LADO ESQUERDO -->

                    <!-- LADO ESQUERDO -->
                    <!-- CALENDÁRIO DE ATIVIDADES -->
                    <div class="col-lg-6">
                          
                        <div class="portlet portlet-orange">
                            <div class="row">

                                <div class="col-lg-8">
                                    <div class="portlet portlet-default">
                                        <div class="portlet-heading">
                                            <div class="portlet-title">
                                                <h4>Calendar</h4>
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
                                                <h4>Draggable Events</h4>
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
                       

                       
                        <div class="portlet portlet-blue">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Button Dropdowns</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#buttonDropdowns"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="buttonDropdowns" class="panel-collapse collapse in">
                                <div class="portlet-body">

                                    <h3>Single Button Dropdowns</h3>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Default
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-green dropdown-toggle" data-toggle="dropdown">Green
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-blue dropdown-toggle" data-toggle="dropdown">Blue
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-orange dropdown-toggle" data-toggle="dropdown">Orange
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-red dropdown-toggle" data-toggle="dropdown">Red
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-purple dropdown-toggle" data-toggle="dropdown">Purple
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown">White
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <hr>

                                    <h3>Split Button Dropdowns</h3>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default">Default</button>
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-green">Green</button>
                                        <button type="button" class="btn btn-green dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-blue">Blue</button>
                                        <button type="button" class="btn btn-blue dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-orange">Orange</button>
                                        <button type="button" class="btn btn-orange dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-red">Red</button>
                                        <button type="button" class="btn btn-red dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-purple">Purple</button>
                                        <button type="button" class="btn btn-purple dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-white">White</button>
                                        <button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <hr>

                                    <h3>Sizing</h3>
                                    <div class="btn-group">
                                        <button class="btn btn-default btn-lg dropdown-toggle" type="button" data-toggle="dropdown">
                                            Large button
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <br>
                                    <br>
                                    <div class="btn-group">
                                        <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                            Small button
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <br>
                                    <br>
                                    <div class="btn-group">
                                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                            Extra small button
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <hr>

                                    <h3>Dropup Variation</h3>
                                    <div class="btn-group dropup">
                                        <button type="button" class="btn btn-default">Dropup</button>
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->
                                    <div class="btn-group dropup">
                                        <button type="button" class="btn btn-green">Right dropup</button>
                                        <button type="button" class="btn btn-green dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li><a href="#">Action</a>
                                            </li>
                                            <li><a href="#">Another action</a>
                                            </li>
                                            <li><a href="#">Something else here</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li><a href="#">Separated link</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /btn-group -->

                                </div>
                            </div>
                        </div>

                        <div class="portlet portlet-red">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Advanced Multi Selects</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#multiSelect"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="multiSelect" class="panel-collapse collapse in">
                                <div class="portlet-body">

                                    <h3>Radio Dropdown</h3>
                                    <p>
                                        <select id="multiselect1">
                                            <option value="cheese" selected>Cheese</option>
                                            <option value="tomatoes">Tomatoes</option>
                                            <option value="mozarella">Mozzarella</option>
                                            <option value="mushrooms">Mushrooms</option>
                                            <option value="pepperoni">Pepperoni</option>
                                            <option value="onions">Onions</option>
                                        </select>
                                    </p>
                                    <hr>

                                    <h3>Checklist Dropdown</h3>
                                    <p>
                                        <select id="multiselect2" multiple="multiple">
                                            <option value="cheese" selected>Cheese</option>
                                            <option value="tomatoes" selected>Tomatoes</option>
                                            <option value="mozarella" selected>Mozzarella</option>
                                            <option value="mushrooms">Mushrooms</option>
                                            <option value="pepperoni">Pepperoni</option>
                                            <option value="onions">Onions</option>
                                        </select>
                                    </p>
                                    <hr>

                                    <h3>Checklist Dropdown with Select All</h3>
                                    <p>
                                        <select id="multiselect3" multiple="multiple">
                                            <option value="cheese">Cheese</option>
                                            <option value="tomatoes">Tomatoes</option>
                                            <option value="mozarella">Mozzarella</option>
                                            <option value="mushrooms">Mushrooms</option>
                                            <option value="pepperoni">Pepperoni</option>
                                            <option value="onions">Onions</option>
                                        </select>
                                    </p>
                                    <hr>

                                    <h3>Checklist Dropdown with Search</h3>
                                    <p>
                                        <select id="multiselect4" multiple="multiple"></select>
                                    </p>

                                </div>
                            </div>
                        </div>

                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Button Loading States</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#buttonStates"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="buttonStates" class="panel-collapse collapse in">
                                <div class="portlet-body">

                                    <h3>Expanding Spinner</h3>
                                    <button class="btn btn-default ladda-button" data-style="expand-left">
                                        <span class="ladda-label">Expand Left</span>
                                    </button>
                                    <button class="btn btn-default ladda-button" data-style="expand-right">
                                        <span class="ladda-label">Expand Right</span>
                                    </button>
                                    <button class="btn btn-default ladda-button" data-style="expand-up">
                                        <span class="ladda-label">Expand Up</span>
                                    </button>
                                    <button class="btn btn-default ladda-button" data-style="expand-down">
                                        <span class="ladda-label">Expand Down</span>
                                    </button>
                                    <hr>

                                    <h3>Zooming Spinner</h3>
                                    <button class="btn btn-green ladda-button" data-style="zoom-in">
                                        <span class="ladda-label">Zoom In</span>
                                    </button>
                                    <button class="btn btn-green ladda-button" data-style="zoom-out">
                                        <span class="ladda-label">Zoom Out</span>
                                    </button>
                                    <hr>

                                    <h3>Sliding Spinner</h3>
                                    <button class="btn btn-orange ladda-button" data-style="slide-left">
                                        <span class="ladda-label">Slide Left</span>
                                    </button>
                                    <button class="btn btn-orange ladda-button" data-style="slide-right">
                                        <span class="ladda-label">Slide Right</span>
                                    </button>
                                    <button class="btn btn-orange ladda-button" data-style="slide-up">
                                        <span class="ladda-label">Slide Up</span>
                                    </button>
                                    <button class="btn btn-orange ladda-button" data-style="slide-down">
                                        <span class="ladda-label">Slide Down</span>
                                    </button>
                                    <hr>

                                    <h3>Loading Bar</h3>
                                    <div class="progress-demo">
                                        <button class="btn btn-purple ladda-button" data-style="expand-right">
                                            <span class="ladda-label">Expand Right</span>
                                        </button>
                                        <button class="btn btn-purple ladda-button" data-style="expand-left">
                                            <span class="ladda-label">Expand Left</span>
                                        </button>
                                        <button class="btn btn-purple ladda-button" data-style="contract">
                                            <span class="ladda-label">Contract</span>
                                        </button>
                                    </div>
                                    <hr>

                                    <h3>Sizes</h3>
                                    <button class="btn btn-red btn-xs ladda-button" data-style="expand-right" data-size="xs">
                                        <span class="ladda-label">Extra Small</span>
                                    </button>
                                    <button class="btn btn-red btn-sm ladda-button" data-style="expand-right" data-size="s">
                                        <span class="ladda-label">Small</span>
                                    </button>
                                    <button class="btn btn-red btn-lg ladda-button" data-style="expand-right" data-size="l">
                                        <span class="ladda-label">Large</span>
                                    </button>

                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.col-lg-6 -->

                </div>
                <!-- /.row -->

           </div>
            </div>
            <!-- /#page-wrapper -->
            <!-- end MAIN PAGE CONTENT -->
                
           
            
        </body>
</html>


        <!-- GLOBAL SCRIPTS -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
      
        <!-- /#logout -->
        <!-- Logout Notification jQuery -->
        <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/logout.js"></script>
        <!-- HISRC Retina Images -->
        <script src="<?= $assets ?>dashboard/js/plugins/hisrc/hisrc.js"></script>
       
        <script src="<?= $assets ?>dashboard/js/demo/calendar-demo.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/fullcalendar.min.js"></script>
        <!-- THEME SCRIPTS -->
        <script src="<?= $assets ?>dashboard/js/flex.js"></script>
        <script src="<?= $assets ?>dashboard/js/demo/buttons-demo.js"></script>
        
         <!-- CUSTOM JQUERY UI FOR FULL CALENDAR -->
        <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/jquery-ui.custom.min.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/bootstrap/bootstrap.min.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/defaults.js"></script>

    