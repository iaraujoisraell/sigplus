<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<header class="main-header">
    <!-- Logo -->
    <a href="<?= site_url('Welcome'); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
         <span class="logo-mini"><img width="40px" height="30px" src="<?= base_url() ?>assets/uploads/logos/logo2.png " ></span>
      <!-- logo for regular state and mobile devices -->            
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img width="170px" height="50px" src="<?= base_url() ?>assets/uploads/logos/LogoUnimed1.png " ></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
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
      
      <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("auth/troca_perfil_dashboard/".$projeto_selecionado, $attrib);
                ?>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
        
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
                                       
                                       ?>
                               
                                    
                                    
                                    
          <li>
                                        <?php echo form_submit('add_projeto', lang("Trocar"), 'id="add_projeto" class="btn btn-flickr" style= "height: 50px;"'); ?>
                                    </li>
                                    
                                    <?php
                                    }
                                    
                                    ?>
                            </li>
                            
                           
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php
                $usuario = $this->session->userdata('user_id');
                $dados_user = $this->site->getUser($usuario);
                ?>
              <img src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $dados_user->matricula.' - '.$dados_user->first_name; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $dados_user->first_name.' - '.$dados_user->cargo; ?>
                  <small><?php echo $dados_user->matricula.' - '.$dados_user->email; ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>" class="btn btn-default btn-flat">Profile </a>
                </div>
                <div class="pull-right">
                    
                  <a href="<?= site_url('Auth/logout'); ?>" class="btn btn-default btn-flat">Sair</a>
                </div>
              </li>
            </ul>
          </li>
          
          <?php if($this->session->userdata('user_id') == 2){ ?>
          <li class="hold-transition skin-blue sidebar-mini active">
           <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a> 
          </li>
          <?php } ?>
            <!-- -->
          
        </ul>
      </div>
       <?php echo form_close(); ?>
    </nav>
  </header>