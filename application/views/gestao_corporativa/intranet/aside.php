<!-- Left side column. contains the logo and sidebar -->
    <aside  class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
           
         <?php echo staff_profile_image($current_user->staffid,array('img','img-responsive','staff-profile-image-small','pull-left')); ?>
      
         
        </div>
        <div class="pull-left info">
          <p><?php echo get_staff_full_name(); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        <li >
            <a href="<?php echo base_url('admin'); ?>">
            <i class="fa fa-home"></i> <span>Home</span>
          </a>
        </li>
        
        <?php /*
        <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Layout Options</span>
            <span class="pull-right-container">
              <span class="label label-primary pull-right">4</span>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="../layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
            <li><a href="../layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
            <li><a href="../layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
            <li><a href="../layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
          </ul>
        </li>
        <li>
          <a href="../widgets.html">
            <i class="fa fa-th"></i> <span>Widgets</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span>
          </a>
        </li>
        */ ?>
        
        <?php if(is_admin()){ ?>
            <li>
          <a href="<?php echo base_url('gestao_corporativa/intranet/cadastros'); ?>">
            <i class="fa fa-gears"></i> <span>Adminitrativo</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">new</small>
            </span>
          </a>
        </li>
        <?php }?>
        
        <li><a href="#" onclick="logout(); return false;"><i class="fa fa-sign-out"></i> <span>Sair</span></a></li>
        <li class="header">Informações</li>
        <?php
        $member             = $this->staff_model->get(get_staff_user_id());
        $last_login         = $member->last_login;
        $ip        =  $_SERVER['REMOTE_ADDR'];
        ?>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Seu IP : <?php echo $ip; ?> </span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Último Login : <?php echo _d($last_login); ?> </span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>