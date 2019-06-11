<?php
 $usuario = $this->session->userdata('user_id');                     
 $users_dados = $this->site->geUserByID($usuario);
 $modulo_atual = $users_dados->modulo_atual;

 $modulo_dados = $this->owner_model->getModuloById($modulo_atual);
 $controle = $modulo_dados->controle;
 $pagina_home = $modulo_dados->home;
 $logo_modulo = $modulo_dados->logo;
 
 if($logo_modulo){
     $logo_mod = $logo_modulo;
 }else{
  $logo_mod = 'sig.png';   
 }
 
?>


<header style="background-color: #323c50; position: fixed; width: 100%;  " class="main-header">
    
    <!-- Logo -->
    <a style="background-color: #323c50; position: fixed; " href="<?= site_url($pagina_home); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
         <span class="logo-mini"><img width="50px" height="50px" src="<?= base_url() ?>assets/uploads/logos/logo_sig.jpeg " ></span>
      <!-- logo for regular state and mobile devices -->            
      <!-- logo for regular state and mobile devices -->
      <span style="width: 100%;" class="logo-lg"><img width="220px" height="50px" src="<?= base_url() ?>assets/uploads/logos/<?php echo $logo_mod; ?>" ></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav style="background-color: #323c50" class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      
      <?php
      $usuario = $this->session->userdata('user_id');
      $users_dados = $this->site->geUserByID($usuario);
      $modulo_atual_id = $users_dados->modulo_atual;
      $empresa = $users_dados->empresa_id;
      
      $modulos = $this->owner_model->getAllModulosByEmpresa($empresa);
      $cont_modulos = 0;
        foreach ($modulos as $modulo) {
            $cont_modulos++;
        }
        //echo $cont_modulos;
      ?>
     
      <div  class="navbar-custom-menu">
        <?php  if($cont_modulos > 1) { ?>
            <ul class="nav navbar-nav">
           <!-- <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
            <li><a href="#">Link</a></li> -->
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Módulos <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                 <?php
                 $cont_mod = 1;
                 foreach ($modulos as $modulo) {
                    
                 $dados_modulo = $this->owner_model->getModuloById($modulo->sig_modulos_id);
                 $nome_modulo = $dados_modulo->descricao;
                 $cor_modulo = $dados_modulo->cor;
                 $home = $dados_modulo->home;
                 
                 if ($modulo_atual_id == $modulo->sig_modulos_id){
                $ativo = "active";
                }else{
                    $ativo = "";
                }
                 ?>
                
                  <li class="<?php echo $ativo; ?>"><a class="active"  href="<?= site_url($home); ?>"> <?php if ($modulo_atual_id == $modulo->sig_modulos_id){ ?> <i class="fa fa-circle text-success"></i> <?php } echo $nome_modulo; ?></a></li>
                <?php if($cont_mod < $cont_modulos){ ?>
                <li class="divider"></li>
                <?php
                }
                $cont_mod++;
                }
                ?>
                
              </ul>
            </li>
          </ul>
        <?php  } ?>
          <?php
          // MENSAGENS
          $qtde_mensagem = $this->networking_model->getQtdeMensagensNaoLidasByUsuario();
          $quantidade_msg = $qtde_mensagem->quantidade;
          ?>
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->
            <li class="dropdown messages-menu">
              <!-- Menu toggle button -->
              <a title="Visualizar Mensagens" href="<?= site_url('welcome/mensagens/0/93'); ?>" class="dropdown-toggle" >
                <i class="fa fa-envelope-o"></i>
                <span class="label label-success"><?php echo $quantidade_msg; ?></span>
              </a>
              <?php 
              /*
              ?>
              <ul class="dropdown-menu">
                <li class="header">Você tem <?php echo $quantidade_msg; ?> Mensagem</li>
                <li>
                  <!-- inner menu: contains the messages -->
                  <ul class="menu">
                    <?php 
                     $mensagens = $this->networking_model->getAllMensagensNaoLidas();
                     foreach ($mensagens as $mensagem) {
                        $titulo = $mensagem->title;
                        $mensagem = $mensagem->text;
                        $users_dados = $this->site->geUserByID($mensagem->id_from);
                        $remetente = $users_dados->first_name; 
                     
                     ?>
                    <li><!-- start message -->
                      <a href="#">
                        <div class="pull-left">
                          <!-- User Image -->
                          <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                        </div>
                        <!-- Message title and timestamp -->
                        <h4>
                          <?php echo $titulo; ?>
                          <small><i class="fa fa-clock-o"></i> 5 mins</small>
                        </h4>
                        <!-- The message -->
                        <p>Why not buy a new awesome theme?</p>
                      </a>
                    </li>
                    <?php
                    } 
                    ?>
                    <!-- end message -->
                  </ul>
                  <!-- /.menu -->
                </li>
                <li class="footer"><a href="<?= site_url('welcome/mensagens/0/93'); ?>">Ver Todas Mensagens</a></li>
              </ul>
          <?php */ ?>
            </li>
            <!-- /.messages-menu -->
            <?php
              //CONVITES
              $conv = $this->owner_model->getQtdeConvitesByUser();
              $conv_total = $conv->quantidade;              
              
              
              $soma_total_notificacoes = $conv_total;
              ?>
            <!-- Notifications Menu -->
            <li class="dropdown notifications-menu">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning"><?php if($soma_total_notificacoes > 0){ echo $soma_total_notificacoes; } ?></span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">Você tem <?php echo $soma_total_notificacoes; ?> Notificações</li>
                <li>
                  <!-- Inner Menu: contains the notifications -->
                  <ul class="menu">
                      <?php 
                      if($conv_total > 0){
                      ?>                      
                    <li><!-- Convites -->
                      <a href="<?= site_url('welcome/convites/107/88'); ?>">
                        <i class="fa fa-send text-purple"></i> <?php echo $conv_total ?> convite 
                      </a>
                    </li>
                    <?php
                      }
                    ?>
                    <!-- end convites -->
                    <li><!-- Ações -->
                      <a href="#">
                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                      </a>
                    </li>
                    <!-- end Ações -->
                  </ul>
                </li>
                
              </ul>
            </li>
            <!-- Tasks Menu -->
            <li class="dropdown tasks-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-calendar"></i>
                <span class="label label-danger">9</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have 9 tasks</li>
                <li>
                  <!-- Inner menu: contains the tasks -->
                  <ul class="menu">
                    <li><!-- Task item -->
                      <a href="#">
                        <!-- Task title and progress text -->
                        <h3>
                          Design some buttons
                          <small class="pull-right">20%</small>
                        </h3>
                        <!-- The progress bar -->
                        <div class="progress xs">
                          <!-- Change the css width attribute to simulate progress -->
                          <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                            <span class="sr-only">20% Complete</span>
                          </div>
                        </div>
                      </a>
                    </li>
                    <!-- end task item -->
                  </ul>
                </li>
                <li class="footer">
                  <a href="#">View all tasks</a>
                </li>
              </ul>
            </li>
          </ul>
       
        <ul class="nav navbar-nav">
          
        
                            
                           
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php
                $usuario = $this->session->userdata('user_id');
                $dados_user = $this->site->getUser($usuario);
                ?>
              <img src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $dados_user->first_name; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $dados_user->first_name.' - '.$dados_user->cargo; ?>
                  <small><?php echo $dados_user->email; ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?= site_url('welcome/profile/' . $this->session->userdata('user_id')); ?>" class="btn btn-primary btn-flat">Perfil </a>
                </div>
                <div class="pull-left" >
                  <a href="<?= site_url('welcome/colaboradores'); ?>" class="btn bg-teal-active btn-flat">Colaboradores </a>
                </div>  
                <div class="pull-right">
                    
                  <a href="<?= site_url('Auth/logout'); ?>" class="btn btn-danger btn-flat">Sair</a>
                </div>
              </li>
            </ul>
              
            
              
          </li>
          
         
          <li class="hold-transition skin-blue sidebar-mini active">
           <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a> 
          </li>
         
            <!-- -->
          
        </ul>
        
          
          
      </div>
     
    </nav>
  </header>

<br><br>

     

        
      