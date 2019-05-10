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
    <script>
        !function ($) {
    
    // Le left-menu sign
    /* for older jquery version
    $('#left ul.nav li.parent > a > span.sign').click(function () {
        $(this).find('i:first').toggleClass("icon-minus");
    }); */
    
    $(document).on("click","#left ul.nav li.parent > a > span.sign", function(){          
        $(this).find('i:first').toggleClass("fa fa-minus");      
    }); 
    
    // Open Le current menu
    $("#left ul.nav li.parent.active > a > span.sign").find('i:first').addClass("fa fa-minus");
    $("#left ul.nav li.current").parents('ul.children').addClass("in");

}(window.jQuery);










$.fn.extend({
    treed: function (o) {
      
      var openedClass = 'glyphicon-minus-sign';
      var closedClass = 'glyphicon-plus-sign';
      
      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };
      
        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
      tree.find('.branch .indicator').each(function(){
        $(this).on('click', function () {
            $(this).closest('li').click();
        });
      });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});

//Initialization of treeviews

$('#tree1').treed();

$('#tree2').treed({openedClass:'glyphicon-folder-open', closedClass:'glyphicon-folder-close'});

$('#tree3').treed({openedClass:'glyphicon-chevron-right', closedClass:'glyphicon-chevron-down'});
    </script>    
    
    <style>
    .tree, .tree ul {
    margin:0;
    padding:0;
    list-style:none
}
.tree ul {
    margin-left:1em;
    position:relative
}
.tree ul ul {
    margin-left:.5em
}
.tree ul:before {
    content:"";
    display:block;
    width:0;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    border-left:1px solid
}
.tree li {
    margin:0;
    padding:0 1em;
    line-height:2em;
    color:#369;
    font-weight:700;
    position:relative
}
.tree ul li:before {
    content:"";
    display:block;
    width:10px;
    height:0;
    border-top:1px solid;
    margin-top:-1px;
    position:absolute;
    top:1em;
    left:0
}
.tree ul li:last-child:before {
    background:#fff;
    height:auto;
    top:1em;
    bottom:0
}
.indicator {
    margin-right:5px;
}
.tree li a {
    text-decoration: none;
    color:#369;
}
.tree li button, .tree li button:active, .tree li button:focus {
    text-decoration: none;
    color:#369;
    border:none;
    background:transparent;
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
    outline: 0;
}
    </style>
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
                            <h1> Escopo -  <?php echo $projetos->projeto. ' '; ?>
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
                                <a title="MENU" href="<?= site_url('Login_Projetos/menu') ?>" class="btn btn-social-icon orange"><i class="fa fa-qrcode"></i></a>
                            <?php } ?>
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
                    <div class="col-lg-12">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Escopo do Projeto</h4>
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
                                    
                                    
                                    <div class="container">
	<div class="row">
		<div id="left" class="col-md-3">
            <ul id="menu-group-1" class="nav menu">  
                <?php
                    foreach ($tipos as $tipo) {
                        $tipo_evento = $tipo->tipo;

                ?>
                <li class="item-1 deeper parent active">
                    <a class="" href="#">
                        <span data-toggle="collapse" data-parent="#menu-group-1" href="#sub-item-1" class="sign"><i class="fa fa-plus icon-white"> </i></span>
                        <span class="lbl"><?php echo $tipo_evento; ?></span>                      
                    </a>
                    <ul class="children nav-child unstyled small collapse" id="sub-item-1">
                        <li class="item-2 deeper parent active">
                            <a class="" href="#">
                                <span data-toggle="collapse" data-parent="#menu-group-1" href="#sub-item-2" class="sign"><i class="icon-plus icon-white"></i></span>
                                <span class="lbl"><?php echo $tipo_evento; ?></span> 
                            </a>
                            <ul class="children nav-child unstyled small collapse" id="sub-item-2">
                                <li class="item-3 current active">
                                    <a class="" href="#">
                                        <span class="sign"><i class="icon-play"></i></span>
                                        <span class="lbl">Menu 1.1</span> (current menu)
                                    </a>
                                </li>
                                <li class="item-4">
                                    <a class="" href="#">
                                        <span class="sign"><i class="icon-play"></i></span>
                                        <span class="lbl">Menu 1.2</span> 
                                    </a>
                                </li>                                
                            </ul>
                        </li>
                       
                    </ul>
                </li>
                 <?php
                    }
                 ?>
            </ul>          
	</div>
	</div>
</div>
                                    
                                    <div class="container">
                                        <div class="row">
                                            <div id="left" class="col-md-12">
                                                <ul id="menu-group-1" class="nav menu">  
                                                <?php
                                                    foreach ($tipos as $tipo) {
                                                        $tipo_evento = $tipo->tipo;
                                                    
                                                ?>
                                                <li class="item-1<?php echo $tipo->id; ?> deeper parent ">
                                                    <a  class="" href="#<?php echo $tipo->id; ?>">
                                                        <span data-toggle="collapse" data-parent="#menu-group-1" href="#<?php echo $tipo->id; ?>" style="width: 2%;" class="sign"><i class="fa fa-plus icon-white"> </i></span>
                                                        <span class="lbl"><?php echo $tipo_evento; ?></span>                      
                                                    </a>
                                                    <ul class="children nav-child unstyled small collapse" id="<?php echo $tipo->id; ?>">
                                                        <?php
                                                         $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento,'ordem','asc');
                                                         
                                                         foreach ($eventos as $evento) {
                                                        ?>
                                                        <li class="item-1<?php echo $tipo->id.$evento->id; ?> deeper parent active">
                                                            <a class="" >
                                                                <span data-toggle="collapse" data-parent="#menu-group-1" href="#<?php echo $evento->id ?>" style="width: 2%;" class="sign"><i class="fa fa-plus icon-white"></i></span>
                                                                <span class="lbl"><?php echo $evento->id.' - '.$evento->nome_evento; ?></span> 
                                                            </a>
                                                            <ul class="children nav-child unstyled small collapse" id="<?php echo $evento->id ?>">
                                                                <?php
                                                                $intes_eventos = $this->projetos_model->getAllItemEventosProjeto($evento->id,'tipo','asc');
                                                                $cont = 1;
                                                                foreach ($intes_eventos as $item) {
                                                                    
                                                                    
                                                         
                                                                ?>
                                                                    <li style="width: 60%;  text-align: justify;">
                                                                        <a class="" >
                                                                            <span class="sign"><i class="icon-play"></i></span>
                                                                            <span class="lbl"> <p style="text-align: justify;"> <i class="fa fa-circle"></i> <?php echo $cont++ .' - '; ?>  <?php echo $item->descricao; ?><p></span> 
                                                                        </a>
                                                                    </li>
                                                                <?php } 
                                                                
                                                                ?>   
                                                                                              
                                                            </ul>
                                                        </li>
                                                         <?php } ?>
                                                        
                                                        
                                                    </ul>
                                                </li>
                                                <?php
                                                }
                                                ?>
                                                   			
                                            </ul>     
                                  </div>
                    <!-- /LADO ESQUERDO -->

                </div>
                <!-- /.row -->

            </div>
                                    
                                    <div class="container" style="margin-top:30px;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul id="tree1">
                                                    <?php
                                                    foreach ($tipos as $tipo) {
                                                        $tipo_evento = $tipo->tipo;
                                                    
                                                    ?>
                                                    <li>
                                                        <a href="#"><?php echo $tipo->tipo; ?></a>

                                                        <?php
                                                         $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento,'ordem','asc');
                                                         
                                                         foreach ($eventos as $evento) {
                                                        ?>
                                                        <ul>
                                                            
                                                            <li><?php echo $evento->nome_evento; ?>
                                                                <ul>
                                                                    <?php
                                                                $intes_eventos = $this->projetos_model->getAllItemEventosProjeto($evento->id,'tipo','asc');
                                                                $cont = 1;
                                                                foreach ($intes_eventos as $item) {
                                                                    
                                                                    
                                                         
                                                                ?>
                                                                    <li><?php echo $item->descricao; ?></li>
                                                                     <?php } ?>
                                                                </ul>
                                                            </li>
                                                            
                                                        </ul>
                                                         <?php } ?>
                                                    </li>
                                                    <?php } ?>
                                                   
                                                </ul>
                                            </div>

                                        </div>
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
