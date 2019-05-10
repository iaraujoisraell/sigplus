<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

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

    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- begin TOP NAVIGATION -->
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
                     $permissao_setores               = $permissoes->setores_index;
                     $permissao_perfil_acesso         = $permissoes->perfil_acesso;
                     /*
                      * RELATÓRIO
                      */
                     $permissao_relatorios             = $permissoes->relatorios;
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
                     $lista_participantes          = $permissoes->lista_participantes;
                     
                     
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
                                                if($item->grupo_id == 1){
                                                 $wu4[$item->grupo_id] = $item->name;
                                               }
                                               if($item->grupo_id == 2){
                                                   // Verifica se ele ta na tabela de gestores
                                                   $perfis_user = $this->site->getPerfilGestorByIDandProjeto($usuario, $projetos->projeto_atual);
                                                   
                                                   $qtde_gestor = $perfis_user->quantidade;
                                                   
                                                  if($qtde_gestor > 0){
                                                    $wu4[$item->grupo_id] = $item->name;
                                                  }
                                               }
                                               if($item->grupo_id == 3){
                                                   // Verifica se ele ta na tabela de gestores
                                                   $perfis_user = $this->site->getPerfilSuperintendenteByIDandProjeto($usuario, $projetos->projeto_atual);
                                                   
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
                                       }
                                       ?>
                               
                                    <li>
                                        <?php echo form_submit('add_projeto', lang("Trocar"), 'id="add_projeto" class="btn btn-flickr" style= "height: 50px;"'); ?>
                                    </li>
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
                            <li class="dropdown">
                                <a class="red dropdown-toggle" href="<?= site_url('Auth/logout'); ?>">
                                    <i class="fa fa-sign-out"></i> 
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                
                
                <?php echo form_close(); ?>
        <!-- /.navbar-top -->
        <!-- end TOP NAVIGATION -->

       
        <!-- end SIDE NAVIGATION -->

        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            <h1> <?php echo $projetos->projeto. ' '; ?>
                                <small></small>
                            </h1>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-user"></i>  Gestor do Projeto:    <?php echo $projetos->gerente_area; ?>
                                </li>
                                <li class="active"><i class="fa fa-calendar"></i>Início do Projeto: <?php echo date("d/m/Y", strtotime($projetos->dt_inicio)); ?></li>
                            </ol>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- end PAGE TITLE ROW -->
                <!-- ATALHOS RÁPIDO -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <?php if ($permissao_dashboard) { ?>    
                                <a title="DASHBOARD" href="<?= site_url('projetos/dashboard/' . $projetos->projeto_atual) ?>" class="btn btn-social-icon btn-google-plus"><i class="fa fa-dashboard"></i></a>
                            <?php } ?> 
                            <?php if ($permissao_atas) { ?>
                                <a title="LISTA DE ATAS" href="<?= site_url('atas/add') ?>" class="btn btn-social-icon btn-bitbucket"><i class="fa fa-book"></i></a>
                            <?php } ?>
                            <?php if ($permissao_avalidacao) { ?> 
                                <a title="AÇÕES AGUARDANDO VALIDAÇÃO"  href="<?= site_url('planos/planosAguardandoValidacao') ?>" class="btn btn-social-icon btn-pinterest"><i class="fa fa-clock-o"></i></a>
                            <?php } ?>     
                            <?php if ($permissao_acoes) { ?>     
                                <a title="LISTA DE AÇÕES" href="<?= site_url('planos') ?>" class="btn btn-social-icon btn-dropbox"><i class="fa fa-list"></i></a>
                            <?php } ?>
                            <?php if ($permissao_apendentes) { ?>     
                                <a title="AÇÕES PENDENTES" href="<?= site_url('Planos/planosPendentes') ?>" class="btn btn-social-icon btn-orange"><i class="fa fa-exclamation"></i></a>
                            <?php } ?>
                            <?php if ($permissao_projetos) { ?>
                                <a title="LISTA DE PROJETOS" href="<?= site_url('projetos/add') ?>" class="btn btn-social-icon btn-tumblr"><i class="fa fa-folder"></i></a>
                            <?php } ?>     
                             <?php if ($permissao_eventos) { ?>
                                <a title="LISTA DE EVENTOS" href="<?= site_url('projetos/add_evento') ?>" class="btn btn-social-icon btn-facebook"><i class="fa fa-calendar-o"></i></a>
                            <?php } ?>
                            <?php if ($permissao_participantes) { ?>    
                                <a title="LISTA DE PARTICIPANTES" href="<?= site_url('Atas/index_participantes') ?>" class="btn btn-social-icon btn-github"><i class="fa fa-users"></i></a>
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
                        </div>
                    </div>
                </div>
                <br><br>
                <!-- /ATALHOS RÁPIDO -->
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
                                            <?php echo $projeto->projeto; ?>  
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
                                        <?php if ($permissao_dashboard){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('projetos/dashboard/' . $projetos->projeto_atual) ?>">
                                                    <div class="circle-tile-heading btn-google-plus">
                                                        <i class="fa fa-dashboard fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-google-plus">
                                                    <div class="circle-tile-description text-faded">
                                                        Dashboard
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('projetos/dashboard/' . $projetos->projeto_atual) ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <!-- PROJETO -->
                                        <?php if ($permissao_projetos){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('projetos') ?>">
                                                    <div class="circle-tile-heading btn-tumblr">
                                                        <i class="fa fa-folder fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-tumblr">
                                                    <div class="circle-tile-description text-faded">
                                                        Projetos
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                        <span id="sparklineA"></span>
                                                    </div>
                                                    <a href="<?= site_url('projetos') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                         <?php } ?>
                                        <!-- /PROJETO -->
                                        <?php if ($permissao_atas){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('atas') ?>">
                                                    <div class="circle-tile-heading btn-bitbucket">
                                                        <i class="fa fa-book fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-bitbucket">
                                                    <div class="circle-tile-description text-faded">
                                                        Atas
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                        <span id="sparklineA"></span>
                                                    </div>
                                                    <a href="<?= site_url('atas') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_eventos){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('atas') ?>">
                                                    <div class="circle-tile-heading btn-facebook">
                                                        <i class="fa fa-calendar-o fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-facebook">
                                                    <div class="circle-tile-description text-faded">
                                                       Eventos
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('atas') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                    </div>
                                    <hr>
                                    <?php if ($permissao_acoes){ ?>
                                   <h3>Ações</h3>
                                    <div class="row">
                                         <?php if ($permissao_acoes){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('planos') ?>">
                                                    <div class="circle-tile-heading btn-dropbox">
                                                        <i class="fa fa-list fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-dropbox">
                                                    <div class="circle-tile-description text-faded">
                                                       Lista de Ações
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('planos') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_avalidacao){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('atas') ?>">
                                                    <div class="circle-tile-heading btn-pinterest">
                                                        <i class="fa fa-clock-o fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-pinterest">
                                                    <div class="circle-tile-description text-faded">
                                                        A. Validação
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('atas') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_apendentes){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('atas') ?>">
                                                    <div class="circle-tile-heading orange">
                                                        <i class="fa fa-exclamation fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content orange">
                                                    <div class="circle-tile-description text-faded">
                                                        Pendentes
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                    </div>
                                                    <a href="<?= site_url('atas') ?>" class="circle-tile-footer"> Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('atas') ?>">
                                                    <div class="circle-tile-heading green">
                                                        <i class="fa fa-check fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content green">
                                                    <div class="circle-tile-description text-faded">
                                                        Concluídas
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                    </div>
                                                    <a href="<?= site_url('atas') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <hr>
                                    <?php } ?>
                                    <?php if ($permissao_cadastro_pessoas){ ?>
                                    <h3>Pessoas</h3>
                                    <div class="row">
                                      <?php if ($permissao_participantes){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('atas/index_participantes') ?>">
                                                    <div class="circle-tile-heading btn-vk">
                                                        <i class="fa fa-users fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-vk">
                                                    <div class="circle-tile-description text-faded">
                                                       Participantes
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('atas/index_participantes') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>  
                                    <?php if ($permissao_usuarios){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('users') ?>">
                                                    <div class="circle-tile-heading btn-dropbox">
                                                        <i class="fa fa-user fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-dropbox">
                                                    <div class="circle-tile-description text-faded">
                                                       Usuários
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('users') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    
                                    <?php if ($permissao_gestores){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Gestores/index') ?>">
                                                    <div class="circle-tile-heading btn-facebook">
                                                        <i class="fa fa-user fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-facebook">
                                                    <div class="circle-tile-description text-faded">
                                                       Gestores
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Gestores/index') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    <?php if ($permissao_suporintendentes){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Gestores/superintendentes') ?>">
                                                    <div class="circle-tile-heading btn-tumblr">
                                                        <i class="fa fa-user fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-tumblr">
                                                    <div class="circle-tile-description text-faded">
                                                       Superintendentes
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Gestores/superintendentes') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($lista_participantes){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Gestores/usuarios_listas') ?>">
                                                    <div class="circle-tile-heading btn-tumblr">
                                                        <i class="fa fa-list-alt fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-tumblr">
                                                    <div class="circle-tile-description text-faded">
                                                       Listas 
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Gestores/usuarios_listas') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        </div>
                                    
                                    <hr>
                                    <?php } ?>
                                    <?php if ($permissao_cadastro){ ?>
                                    <h3>Cadastro</h3>
                                    <div class="row">
                                     <?php if ($permissao_pesquisa_satisfacao){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>">
                                                    <div class="circle-tile-heading btn-google-plus">
                                                        <i class="fa fa-bar-chart-o fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-google-plus">
                                                    <div class="circle-tile-description text-faded">
                                                       P.Satisfação
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_setores){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>">
                                                    <div class="circle-tile-heading btn-github">
                                                        <i class="fa fa-crosshairs fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-github">
                                                    <div class="circle-tile-description text-faded">
                                                       Setores
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_setores){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>">
                                                    <div class="circle-tile-heading btn-github">
                                                        <i class="fa fa-puzzle-piece fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-github">
                                                    <div class="circle-tile-description text-faded">
                                                       Módulos
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                         <?php if ($permissao_perfil_acesso){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>">
                                                    <div class="circle-tile-heading btn-github">
                                                        <i class="fa fa-unlock-alt fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-github">
                                                    <div class="circle-tile-description text-faded">
                                                       Perfil/Acesso
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                    </div>
                                    <hr>
                                    <?php } ?>
                                    <?php if ($permissao_relatorios){ ?>
                                    <h3>Relatórios</h3>
                                    <?php if ($permissao_status_report){ ?>
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Reports/status_report') ?>">
                                                    <div class="circle-tile-heading orange">
                                                        <i class="fa fa-signal fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content orange">
                                                    <div class="circle-tile-description text-faded">
                                                       Status Report
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Reports/status_report') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                     <?php if ($permissao_users_acoes_atrasadas){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a title="Usuários com Ações Atrasadas" href="<?= site_url('Reports/usuariosComAcoesAtrasadas') ?>">
                                                    <div class="circle-tile-heading btn-pinterest">
                                                        <i class="fa fa-warning fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-pinterest">
                                                    <div class="circle-tile-description text-faded">
                                                       Ações Atrasadas
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Reports/usuariosComAcoesAtrasadas') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                      </div>  
                                    <hr>
                                    <?php } ?>
                                   

                                </div>
                            </div>
                        </div>
                        <!-- /MENU DE ACESSO -->
                    </div>
                    <!-- /LADO ESQUERDO -->

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
                                                <h4>Eventos do Mês</h4>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="portlet-body">
                                            <div id='external-events'>
                                                <div class='external-event'>Em Breve</div>
                                               
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col-lg-4 -->

                            </div>
                       
                            
                         <?php if ($permissao_gestao_custo){ ?>
                        <div class="portlet portlet-green">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Gestão de Custo</h4>
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

                                    
                                    <h3>Controle</h3>
                                    <div class="row">
                                     <?php if ($permissao_categoria_financeira){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>">
                                                    <div class="circle-tile-heading btn-github">
                                                        <i class="fa fa-list-alt fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-github">
                                                    <div class="circle-tile-description text-faded">
                                                       Categorias
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_contas_pagar){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('financeiro') ?>">
                                                    <div class="circle-tile-heading btn-google-plus">
                                                        <i class="fa fa-money fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-google-plus">
                                                    <div class="circle-tile-description text-faded">
                                                       Despesas
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('financeiro') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                         <?php if ($permissao_fornecedor){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('suppliers') ?>">
                                                    <div class="circle-tile-heading btn-facebook">
                                                        <i class="fa fa-users fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-facebook">
                                                    <div class="circle-tile-description text-faded">
                                                       Fornecedores
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('suppliers') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                    </div>
                                    <hr>
                                   
                              
                                </div>
                            </div>
                        </div>
                             <?php } ?>

                    </div>
                    <!-- /.col-lg-6 -->

                </div>
                    <!-- /.col-lg-6 -->

                </div>
                <!-- /.row -->

            </div>
            <!-- /.page-content -->

        </div>
        <!-- /#page-wrapper -->
        <!-- end MAIN PAGE CONTENT -->

    </div>
    <!-- /#wrapper -->

    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->
    <div id="logout">
        <div class="logout-message">
            <img class="img-circle img-logout" src="img/profile-pic.jpg" alt="">
            <h3>
                <i class="fa fa-sign-out text-green"></i> Ready to go?
            </h3>
            <p>Select "Logout" below if you are ready<br> to end your current session.</p>
            <ul class="list-inline">
                <li>
                    <a href="login.html" class="btn btn-green">
                        <strong>Logout</strong>
                    </a>
                </li>
                <li>
                    <button class="logout_close btn btn-green">Cancel</button>
                </li>
            </ul>
        </div>
    </div>
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?= $assets ?>dashboard/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/spin.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/ladda.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap-multiselect/bootstrap-multiselect.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/flex.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/buttons-demo.js"></script>

    <script src="<?= $assets ?>dashboard/js/demo/calendar-demo.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/fullcalendar.min.js"></script>
</body>

</html>
