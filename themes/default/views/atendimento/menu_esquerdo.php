<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="img-circle" alt="User Image">
        </div>
          <?php
                $usuario = $this->session->userdata('user_id');
                $dados_user = $this->site->getUser($usuario);
                ?>
        <div class="pull-left info">
          <p><?php echo $dados_user->first_name; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        <li class="  <?php if($menu == "dashboard"){ ?> active <?php } ?>">
          <a href="pages/mailbox/mailbox.html">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="  <?php if($menu == "lista"){ ?> active <?php } ?>  ">
          <a href="<?= site_url('atendimento/lista_espera'); ?>">
            <i class="fa fa-clock-o"></i> <span>Lista Espera</span>
          </a>
        </li>
         <li class="  <?php if($menu == "prestador"){ ?> active <?php } ?>  ">
          <a href="<?= site_url('atendimento/prestadores'); ?>">
            <i class="fa fa-users"></i> <span>Prestadores</span>
            
          </a>
        </li>
        <li class="  <?php if($menu == "especialidades"){ ?> active <?php } ?>  ">
          <a href="<?= site_url('atendimento/especialidades'); ?>">
            <i class="fa fa-search"></i> <span>Especialidades</span>
            
          </a>
        </li>
         <li class="  <?php if($menu == "consulta"){ ?> active <?php } ?>  ">
          <a href="<?= site_url('atendimento/servicos_ativos'); ?>">
            <i class="fa fa-search"></i> <span>Serviços Ativos</span>
            
          </a>
        </li>
       
        
       
        
        
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>