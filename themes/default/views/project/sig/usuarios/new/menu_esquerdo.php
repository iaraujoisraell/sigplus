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
        <li>
          <a href="<?= site_url('welcome/home'); ?>">
            <i class="fa fa-dashboard"></i> <span>Home</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green"></small>
            </span>
          </a>
        </li>
        <li>
          <a href="<?= site_url('welcome/controleAtividades'); ?>">
            <i class="fa fa-sliders"></i> <span>Controle de Atividades</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green"></small>
            </span>
          </a>
        </li>
        
        <li  class="<?php if($menu == "acoes"){ ?> active <?php } ?> treeview">
          <a href="#">
            <i class="fa fa-th"></i>
            <span>Ações</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($ativo == "pendentes"){ ?> class="active" <?php } ?>><a href="<?= site_url('welcome/acoesPendentes'); ?>"><i class="fa fa-exclamation"></i>Pendentes</a></li>
              <li <?php if($ativo == "concluidas"){ ?> class="active" <?php } ?>> <a href="<?= site_url('welcome/acoesConcluidas'); ?>"><i class="fa fa-check"></i> Concluídas</a></li>
          </ul>
        </li>
       <?php
            $equipes_projeto = $this->atas_model->getMembroEquipeDistinct(4, $usuario);
           // if($usuario == 2){
        ?>        
             <li class="<?php if($menu == "requisicao"){ ?> active <?php } ?>" >
              <a href="<?= site_url('welcome/requisicaoHoras'); ?>">
                <i class="fa fa-clock-o"></i> <span>Requisição de Horas</span>
                
              </a>
            </li>
        <?php
        /*
            }else{
        ?>
            <li class="<?php if($menu == "requisicao"){ ?> active <?php } ?>" >
              <a href="#">
                <i class="fa fa-clock-o"></i> <span>Em manutenção</span>
                <span class="pull-right-container">
                  <small class="label pull-right bg-green">EM BREVE</small>
                </span>
              </a>
            </li>
          <?php
            }
         * 
         */
          //  $equipes_projeto = $this->atas_model->getMembroEquipeDistinct(4, $usuario);
           // if($usuario == 2){
        ?>    
            <li class="<?php if($menu == "requisicao"){ ?> active <?php } ?>" >
              <a href="<?= site_url('welcome/arquivosProjeto'); ?>">
                <i class="fa fa-archive"></i> <span>Arquivos</span>
           
              </a>
            </li>
        <?php
            //}
        ?>    
            <li>
          <a href="<?= site_url('welcome/treinamentos'); ?>">
            <i class="fa fa-book"></i> <span>Facilitadores Treinamento</span>
            <span class="pull-right-container">
              
            </span>
          </a>
        </li>
        <li>
          <a href="<?= site_url('welcome/documentosProjeto'); ?>">
            <i class="fa fa-folder-open-o"></i> <span>Documentos</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">Novo</small>
            </span>
          </a>
        </li>
        <li>
          <a href="<?= site_url('welcome/lista_rat'); ?>">
            <i class="fa fa-pencil"></i> <span>RAT'S</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">Novo</small>
            </span>
          </a>
        </li>
        
        
       <!--   <li>
          <a href="#" data-toggle="modal" data-target="#modal-farmacia_hmu" >
            <i class="fa fa-envelope-o"></i> <span>Contato</span>
            <span class="pull-right-container">
              
            </span>
          </a>
        </li> -->
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>