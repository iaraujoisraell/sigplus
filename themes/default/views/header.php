<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG - SISTEMA INTEGRADO DE GESTÃO </title>
    
    <link href="<?= $assets ?>styles/theme.css" rel="stylesheet"/>
    <link href="<?= $assets ?>styles/style.css" rel="stylesheet"/>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/funcoes.js"></script>
   
    
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
});



</script>


<?php 
 function encrypt($str, $key)
        {
           
            for ($return = $str, $x = 0, $y = 0; $x < strlen($return); $x++)
            {
                $return{$x} = chr(ord($return{$x}) ^ ord($key{$y}));
                $y = ($y >= (strlen($key) - 1)) ? 0 : ++$y;
            }

            return $return;
        }
        
        
        
        function calculaTempo($hora_inicial, $hora_final) {
                     $i = 1;
                     $tempo_total;

                     $tempos = array($hora_final, $hora_inicial);

                     foreach($tempos as $tempo) {
                      $segundos = 0;

                      list($h, $m, $s) = explode(':', $tempo);

                      $segundos += $h * 3600;
                      $segundos += $m * 60;
                      $segundos += $s;

                      $tempo_total[$i] = $segundos;

                      $i++;
                     }
                     $segundos = $tempo_total[1] - $tempo_total[2];

                     $horas = floor($segundos / 3600);
                     $segundos -= $horas * 3600;
                     $minutos = str_pad((floor($segundos / 60)), 2, '0', STR_PAD_LEFT);
                     $segundos -= $minutos * 60;
                     $segundos = str_pad($segundos, 2, '0', STR_PAD_LEFT);

                     return "$horas:$minutos";
                    }
?>

</head>

<body>
<noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of this website.</p>
        </div>
    </div>
</noscript>
<div id="loading"></div>
<div id="app_wrapper" style="background-color: #4cae4c">
    <header id="header" class="navbar"  >
        <div class="container" style="background-color: seagreen; position: fixed;" >
            <a class="navbar-brand" ><span class="logo">
                    <font style="font-size: 14px;">  <?= $Settings->site_name ?> - </font>
                    <?php
                     $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                     $cadastroUsuario = $this->site->getPerfilAtualByID($usuario);
                     $perfilAtualUsuario = $cadastroUsuario->group_id;
                    ?>
                  <font style="font-size: 16px; color: #CCA940 ">  <?php  echo $projetos->projeto; ?></font></span></a>
                    
            <div class="btn-group visible-xs pull-right btn-visible-sm">
                <button class="navbar-toggle btn" type="button" data-toggle="collapse" data-target="#sidebar_menu">
                    <span class="fa fa-bars"></span>
                </button>
                <a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>" class="btn">
                    <span class="fa fa-user"></span>
                </a>
                <a href="<?= site_url('logout'); ?>" class="btn">
                    <span class="fa fa-sign-out"></span>
                </a>
            </div>
            <?php
            //echo $assets.'../../../assets/uploads/avatars/' . $this->session->userdata('avatar') ;
            ?>
            <div class="header-nav" >
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown">
                        <a class="btn account dropdown-toggle" data-toggle="dropdown" href="#">
                            <img alt="" src="<?= $this->session->userdata('avatar') ? $assets.'../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets.'images/' . $this->session->userdata('gender') . '.png'; ?>" class="mini_avatar img-rounded">

                            <div class="user">
                                <span><?= lang('welcome') ?> <?= $this->session->userdata('username'); ?></span>
                            </div>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>">
                                    <i class="fa fa-user"></i> <?= lang('profile'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('users/profile/' . $this->session->userdata('user_id') . '/#cpassword'); ?>"><i class="fa fa-key"></i> <?= lang('change_password'); ?>
                                </a>
                            </li>
                            
                            <li class="divider"></li>
                            <li>
                                <a href="<?= site_url('logout'); ?>">
                                    <i class="fa fa-sign-out"></i> <?= lang('logout'); ?>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                
                <ul class="nav navbar-nav pull-right">
                 
                   <?php
                    $perfil_atual = $projetos->group_id;
                  
                    ?>
                       <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("auth/troca_perfil", $attrib);
                
                $usuario = $this->session->userdata('user_id');
                
                
                $perfis_user = $this->site->getPerfilusuarioByID($usuario);
                
                  $qtde_perfis_user = 0;
                 foreach ($perfis_user as $item){
                    $qtde_perfis_user++;
                }
                
              
              
                if($qtde_perfis_user > 1){
                ?>
                    <li class="dropdown hidden-sm">
                        
                        <div style="width: 200px;" class="fprom-group">
                           <?php
                                    $usuario = $this->session->userdata('user_id');
                                   
                                    /*
                                    $perfis_nao_usuarios = $this->site->getSoPerfilusuarioByID($usuario);
                                    echo '<br><br>';
                                        foreach ($perfis_nao_usuarios as $perfil_not_user) {
                                           
                                            
                                            echo $perfil_not_user->grupo_id;

                                           // $verifica_perfil;
                                        }
                                    exit;
                                    
                                     * 
                                     */
                                    $perfis_user = $this->site->getPerfilusuarioByID($usuario);

                                     foreach ($perfis_user as $item){
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
                                    echo form_dropdown('perfil_usuario', $wu4, (isset($_POST['perfil_usuario']) ? $_POST['perfil_usuario'] : $perfil_atual), '  class="form-control selectpicker  select" style="width:100%;" ' );
                                    ?>
                       </div>
                    </li>
                    <li class="dropdown hidden-xs">
                        <div style="background-color: orange" class="fprom-group btn btn-danger "><?php echo form_submit('add_projeto', lang("Trocar"), 'id="add_projeto" class="btn btn-primary"'); ?>
                               </div>
                       
                        
                    </li>
                <?php } ?>
                    <li class="dropdown hidden-xs">
                        <a class="btn tip" title="<?= lang('calculator') ?>" data-placement="bottom" href="#" data-toggle="dropdown">
                            <i class="fa fa-calculator"></i>
                        </a>
                        <ul class="dropdown-menu pull-right calc">
                            <li class="dropdown-content">
                                <span id="inlineCalc"></span>
                            </li>
                        </ul>
                    </li>
                   
                    <?php
                       $usuario = $this->session->userdata('user_id');
                        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                        $eventos_projetos = $this->projetos_model->getAllMarcosProjetoByProjetoNaoVencido($projetos_usuario->projeto_atual,'start', 'asc');
                    if ($eventos_projetos) { ?>
                        <li class="dropdown hidden-xs">
                            <a class="btn tip" title="<?= lang('calendar') ?>" data-placement="bottom" href="#" data-toggle="dropdown">
                                <i class="fa fa-calendar"></i>
                                <span class="number blightOrange black"><?= sizeof($eventos_projetos) ?></span>
                            </a>
                            <ul class="dropdown-menu pull-right content-scroll">
                                <li class="dropdown-header">
                                <i class="fa fa-calendar"></i> <?= lang('upcoming_events'); ?>
                                </li>
                                <li class="dropdown-content">
                                    <div class="top-menu-scroll">
                                        <ol class="oe">
                                            <?php foreach ($eventos_projetos as $event) {
                                                echo '<li>' . date($dateFormats['php_ldate'], strtotime($event->start)) . ' <strong>' . $event->title . '</strong><br>'.$event->description.'</li>';
                                            } ?>
                                        </ol>
                                    </div>
                                </li>
                                <li class="dropdown-footer">
                                    <a href="<?= site_url('Login_Projetos/marcos_projeto') ?>" class="btn-block link">
                                        <i class="fa fa-calendar"></i> <?= lang('calendar') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php } else { ?>
                    <li class="dropdown hidden-xs">
                        <a class="btn tip" title="<?= lang('calendar') ?>" data-placement="bottom" href="<?= site_url('calendar') ?>">
                            <i class="fa fa-calendar"></i>
                        </a>
                    </li>
                    <?php } ?>
                    
                    <li class="dropdown hidden-sm">
                        <a class="btn tip" title="<?= lang('styles') ?>" data-placement="bottom" data-toggle="dropdown"
                           href="#">
                            <i class="fa fa-css3"></i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li class="bwhite noPadding">
                                <a href="#" id="fixed" class="">
                                    <i class="fa fa-angle-double-left"></i>
                                    <span id="fixedText">Fixed</span>
                                </a>
                                 
                                <a href="#" id="cssBlack" class="black">
                                   <i class="fa fa-stop"></i> Black
                               </a>
                           </li>
                        </ul>
                    </li>
                    
                    
                   
                    <?php if ($perfilAtualUsuario == '5')   { 
                         $quantidadePendente = $this->site->getAllPlanosPendenteUser($usuario);
                        ?>
                        <li class="dropdown hidden-sm">
                            <a class="btn blightOrange tip" title="<?= lang('alerts') ?>" 
                                data-placement="left" data-toggle="dropdown" href="#">
                                <i class="fa fa-exclamation-triangle"></i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="<?= site_url('welcome') ?>" class="">
                                        <span class="label label-danger pull-right" style="margin-top:3px;"><?php echo $quantidadePendente->quantidade; ?></span>
                                        <span style="padding-right: 35px;"><?= lang('Ações Pendentes') ?></span>
                                    </a>
                                </li>
                               
                            </ul>
                        </li>
                    <?php } ?>
                  
               <?php echo form_close(); ?>
                
                </ul>
            </div>
        </div>
    </header>



<div class="container"  id="container" >
        <div class="row"  id="main-con" >
        <table class="lt" >
            <tr>
                <td class="sidebar-con" style="background-color: #4cae4c;  position: relative; " >
            <div id="sidebar-left" >
                
                <div class="sidebar-nav nav-collapse collapse navbar-collapse"   >
                    <ul class="nav main-menu" style=" background-color: seagreen;">
             
                        <?php
                        if ($perfilAtualUsuario != '5')  {
                            ?>
                               
                            <li id="mm_login_projetos_projeto_menu">
                                        <a class="submenu" href="<?= site_url('Login_Projetos/menu'); ?>">
                                            <i class="fa fa-th-large"></i>
                                            <span class="text"> <?= lang('Menu'); ?></span>
                                        </a>
                            </li>
                            
                                <?php
                                        if ($perfilAtualUsuario == '1')  {
                                    ?>    
                                
                                <li class="mm_projetos">
                                <a class="dropmenu" href="#">
                                    <i class="fa  fa-bookmark"></i>
                                     <?php if (($usuario == 2) || ($usuario == 18)){ }?>
                                    <span class="text"> <?= lang('Projetos'); ?> </span> 
                                    
                                    <span class="chevron closed"></span>
                                </a>
                                <ul>
                                     
                                    <li id="projetos_index">
                                        <a class="submenu" href="<?= site_url('Projetos'); ?>">
                                            <i class="fa  fa-bookmark-o"></i>
                                            <span class="text"> <?= lang('Lista de Projetos'); ?></span>
                                        </a>
                                    </li>
                                    
                                    <li id="projetos_gestao_documentacao_index">
                                        <a class="submenu" href="<?= site_url('Projetos/gestao_documentacao_index'); ?>">
                                            <i class="fa  fa-book"></i>
                                            <span class="text"> <?= lang('Gestão de Documentação'); ?></span>
                                        </a>
                                    </li>
                                    
                                  <?php
                                       
                                    }    
                                    ?> 
                                    
                                     <?php
                                        if ($perfilAtualUsuario == '1')  {
                                    ?>
                                   
                                    <li id="projetos_eventos_index">
                                        <a class="submenu" href="<?= site_url('Projetos/Eventos_index'); ?>">
                                            <i class="fa fa-calendar-o"></i>
                                            <span class="text"> <?= lang('Eventos'); ?></span>
                                        </a>
                                    </li>
                                    
                                    <li id="projetos_marcos_eventos_index">
                                        <a class="submenu" href="<?= site_url('Projetos/Marcos_eventos_index'); ?>">
                                            <i class="fa fa-calendar-o"></i>
                                            <span class="text"> <?= lang('Marcos do Projeto'); ?></span>
                                        </a>
                                    </li>
                                     <?php
                                        }
                                    ?>
                                     </ul>
                            </li>
                                     <?php
                        }
                        
                        if ($perfilAtualUsuario == '1')  {
                            ?>
                               
                            
                            <li class="mm_atas">
                                <a class="dropmenu" href="<?= site_url('Atas'); ?>">
                                    <i class="fa fa-book"></i>
                                    <span class="text"> <?= lang('Atas'); ?> </span> 
                                    <span class="chevron closed"></span>
                                </a>
                                <ul>
                                    <li id="atas_index">
                                        <a class="submenu" href="<?= site_url('Atas'); ?>">
                                            <i class="fa fa-file-text-o"></i>
                                            <span class="text"> <?= lang('Lista de Atas'); ?></span>
                                        </a>
                                    </li>
                                    <li id="atas_atasresumido">
                                        <a class="submenu" href="<?= site_url('Atas/AtasResumido'); ?>">
                                            <i class="fa fa-sort-numeric-asc"></i>
                                            <span class="text"> <?= lang('Atas - Resumo'); ?></span>
                                        </a>
                                    </li>
                                    
                                     
                                    </ul>
                            </li>
                                     <?php
                        }
                        
                            ?>
                                   
                         <?php
                       
                        if ($perfilAtualUsuario == '1')  {
                            ?>            
                                
                            
                            <li class="mm_planos">
                                <a class="dropmenu" href="#">
                                    <i class="fa fa-list"></i>
                                    <span class="text"> <?= lang('Ações'); ?> </span> 
                                    <span class="chevron closed"></span>
                                </a>
                                <ul>
                                    <li id="planos_index">
                                        <a class="submenu" href="<?= site_url('Planos'); ?>">
                                            <i class="fa fa-list-ol"></i>
                                            <span class="text"> <?= lang('Lista de Ações'); ?></span>
                                        </a>
                                    </li>
                                    <li id="planos_lista_acao_setor">
                                        <a class="submenu" href="<?= site_url('Planos/lista_acao_setor'); ?>">
                                            <i class="fa fa-sitemap"></i>
                                            <span class="text"> <?= lang('Resumo por Setor'); ?></span>
                                        </a>
                                    </li>
                                     <li id="planos_planosaguardandovalidacao">
                                        <a class="submenu" href="<?= site_url('Planos/planosAguardandoValidacao'); ?>">
                                            <i class="fa fa-clock-o"></i>
                                            <span class="text"> <?= lang('Ações Aguardando Validação'); ?></span>
                                        </a>
                                    </li>
                                    <li id="planos_planospendentes">
                                        <a class="submenu" href="<?= site_url('Planos/planosPendentes'); ?>">
                                            <i class="fa fa-exclamation"></i>
                                            <span class="text"> <?= lang('Ações Pendentes'); ?></span>
                                        </a>
                                    </li>
                                    
                                    <li id="planos_planosconcluidos">
                                        <a class="submenu" href="<?= site_url('Planos/planosConcluidos'); ?>">
                                            <i class="fa fa-check"></i>
                                            <span class="text"> <?= lang('Ações Concluídas'); ?></span>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </li>
                              
                              <?php if ($perfilAtualUsuario == '1') { ?>                      
                          <li class="mm_auth mm_gestores mm_suppliers mm_billers">
                                <a class="dropmenu" href="#">
                                <i class="fa fa-users"></i>
                                <span class="text"> <?= lang('people'); ?> </span> 
                                <span class="chevron closed"></span>
                                </a>
                                <ul>
                                   
                                    <li id="auth_users">
                                        <a class="submenu" href="<?= site_url('users'); ?>">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('list_users'); ?></span>
                                        </a>
                                    </li>
                                    <li id="gestores_index">
                                        <a class="submenu" href="<?= site_url('Gestores/index'); ?>">
                                            <i class="fa fa-user-secret"></i><span class="text"> <?= lang('Lista de Gestores'); ?></span>
                                        </a>
                                    </li>
                                    <li id="gestores_superintendentes">
                                        <a class="submenu" href="<?= site_url('Gestores/superintendentes'); ?>">
                                            <i class="fa fa-user-md"></i><span class="text"> <?= lang('Lista de Superintendentes'); ?></span>
                                        </a>
                                    </li>
                                    <li id="gestores_usuarios_listas">
                                        <a class="submenu" href="<?= site_url('Gestores/usuarios_listas'); ?>">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('Listas de Participantes'); ?></span>
                                        </a>
                                    </li>
                                    
                                    <li id="customers_index">
                                        <a class="submenu" href="<?= site_url('customers'); ?>">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('list_customers'); ?></span>
                                        </a>
                                    </li>
                                    <li id="customers_index">
                                        <a class="submenu" href="<?= site_url('customers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_customer'); ?></span>
                                        </a>
                                    </li>
                                    <li id="suppliers_index">
                                        <a class="submenu" href="<?= site_url('suppliers'); ?>">
                                            <i class="fa fa-users"></i><span class="text"> <?= lang('list_suppliers'); ?></span>
                                        </a>
                                    </li>
                                    <li id="suppliers_index">
                                        <a class="submenu" href="<?= site_url('suppliers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_supplier'); ?></span>
                                        </a>
                                    </li>
                                  
                                </ul>
                            </li>   
                             <?php } ?>
                             <?php if ($perfilAtualUsuario == '1') { ?>
                                <li class="mm_cadastros mm_system_settings">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-folder"></i><span class="text"> <?= lang('Cadastros'); ?> </span> 
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <li id="cadastros_pesquisa_satisfacao">
                                        <a class="submenu" href="<?= site_url('cadastros/pesquisa_satisfacao'); ?>">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('Cadastro de Avaliações'); ?></span>
                                        </a>
                                    </li>
                                    <li id="system_settings_expense_categories">
                                            <a href="<?= site_url('Historico_Acoes') ?>">
                                                <i class="fa fa-list-alt"></i><span class="text"> <?= lang('Categorias Financeira'); ?></span>
                                            </a>
                                        </li>
                                        <li id="system_settings_index">
                                            <a href="<?= site_url('System_settings/expense_categories') ?>">
                                                <i class="fa fa-cog"></i><span class="text"> <?= lang('Setores'); ?></span>
                                            </a>
                                        </li>
                                       
                           
                                                       
                         
                                        </ul>
                                </li>
                                 <?php  }?>           
                            <?php if ($perfilAtualUsuario == '1') { ?>
                                <li class="mm_system_settings2 <?= strtolower($this->router->fetch_method()) != 'settings' ? '' : 'mm_pos' ?>">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-cogs"></i><span class="text"> <?= lang('settings'); ?> </span> 
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <li id="system_settings_index">
                                            <a href="<?= site_url('Historico_Acoes') ?>">
                                                <i class="fa fa-cog"></i><span class="text"> <?= lang('Histórico Ações'); ?></span>
                                            </a>
                                        </li>
                                       
                           
                                                         
                         
                                        </ul>
                                </li>
                                 <?php  }?>
                                
                                
                                
                                
                                <?php if ($perfilAtualUsuario == '1') { ?>
                                <li class="mm_reports ">
                                            <a class="dropmenu" href="#">
                                                <i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('Relatórios'); ?> </span> 
                                                <span class="chevron closed"></span>
                                            </a>
                                            <ul>
                                                <li id="reports_status_report">
                                                    <a class="submenu" href="<?= site_url('Reports/status_report'); ?>">
                                                        <i class="fa fa-tasks"></i>
                                                        <span class="text"> <?= lang('Status Report'); ?></span>
                                                    </a>
                                                </li>

                                                <li id="reports_settings_usuarioscomacoesatrasadas">
                                                    <a href="<?= site_url('Reports/usuariosComAcoesAtrasadas') ?>">
                                                        <i class="fa fa-user"></i><span class="text"> <?= lang('Usuários com Ações Atrasadas'); ?></span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                 <?php  }?>     
                                
                                
                                
                        <?php
                        } else if ($perfilAtualUsuario == '5') { // MENU DO USUÁRIO
                            ?>
                           
                             <li class="mm_acoes mm_welcome">
                                <a  href="<?= site_url('welcome'); ?>">
                                    <i class="fa fa-list"></i>
                                    <span class="text"> <?= lang('Home'); ?> </span> 
                                    
                                </a>
                                
                            </li>
                        
                   <?php } ?>
                    </ul>
                </div>
                <a href="#" id="main-menu-act" class="full visible-md visible-lg">
                    <i class="fa fa-angle-double-left"></i>
                </a>
            </div>
                    <br>
            </td>
            <td class="content-con" >
            <div id="content">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <ul class="breadcrumb">
                            <?php
                            foreach ($bc as $b) {
                                if ($b['link'] === '#') {
                                    echo '<li class="active">' . $b['page'] . '</li>';
                                } else {
                                    echo '<li><a href="' . $b['link'] . '">' . $b['page'] . '</a></li>';
                                }
                            }
                            ?>
                            <li class="right_log hidden-xs">
                                <?= lang('your_ip') . ' ' . $ip_address . " <span class='hidden-sm'>( " . lang('last_login_at') . ": " . date($dateFormats['php_ldate'], $this->session->userdata('old_last_login')) . " " . ($this->session->userdata('last_ip') != $ip_address ? lang('ip:') . ' ' . $this->session->userdata('last_ip') : '') . " )</span>" ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($message) { ?>
                            <div class="alert alert-success">
                                <button data-dismiss="alert" class="close" type="button">�</button>
                                <?= $message; ?>
                            </div>
                        <?php } ?>
                        <?php if ($error) { ?>
                            <div class="alert alert-danger">
                                <button data-dismiss="alert" class="close" type="button">�</button>
                                <?= $error; ?>
                            </div>
                        <?php } ?>
                        <?php if ($warning) { ?>
                            <div class="alert alert-warning">
                                <button data-dismiss="alert" class="close" type="button">�</button>
                                <?= $warning; ?>
                            </div>
                        <?php } ?>
                        <?php
                        if ($info) {
                            foreach ($info as $n) {
                                if (!$this->session->userdata('hidden' . $n->id)) {
                                    ?>
                                    <div class="alert alert-info">
                                        <a href="#" id="<?= $n->id ?>" class="close hideComment external"
                                           data-dismiss="alert">&times;</a>
                                        <?= $n->comment; ?>
                                    </div>
                                <?php }
                            }
                        } ?>
                        
                    </div>
                </div>
                
                        <div class="alerts-con"></div>
