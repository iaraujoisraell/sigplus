<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

     <title>SIG - SISTEMA INTEGRADO DE GESTÃO </title>

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
                                        <a  href="<?= site_url('welcome/home'); ?>" >
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
                                        <?php  if ($qtde_perfis_user > 1) { echo form_submit('add_projeto', lang("Trocar"), 'id="add_projeto" class="btn btn-flickr" style= "height: 50px;"'); }?>
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
                            <a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>" title="<?= lang('Perfil') ?>" class="btn">
                            <span class="fa fa-user"></span>
                        </a>
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
        
        
               