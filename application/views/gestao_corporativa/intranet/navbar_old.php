<?php $empresa_id = $empresa_id = $this->session->userdata('empresa_id'); ?>
<nav class="main-header navbar navbar-expand-md  btn<?php if ($empresa_id == 2) { ?>  btn-primary <?php } ?>" <?php if ($empresa_id == 4) { ?> style="background-color: #415165" <?php } ?>>
    <div class="container">
        <a href="<?php echo base_url('admin'); ?>" class="navbar-brand">
            <?php
            $company_name = get_option('companyname');
            $name_reduzido = get_option('nome_reduzido');
            $company_logo = get_option('company_logo');

            $logoURL = site_url(get_admin_uri() . '/');
            if ($company_logo) {
                ?>
                <img src="<?php echo base_url('uploads/company/' . $company_logo);
                ?>" alt="LOGO" class="brand-image elevation-3" >
                 <?php } ?>
    <!--<span class="brand-text font-weight-light"><?php echo $company_name; ?></span>-->
        </a>

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php if (!$without_permission == true) { ?>
            <div class="collapse navbar-collapse order-3" id="navbarCollapse">

                <ul class="navbar-nav" style="color: white;">



                    <?php if (has_permission_intranet('menu_top_view', '', 'view_menus') || is_admin()) { ?>
                        <?php
                        $this->load->model('Menu_model');
                        $menus_principal = $this->Menu_model->get_menus();
                        //print_r($menus);
                        foreach ($menus_principal as $menu) {
                            $submenus = $this->Menu_model->get_submenus($menu['id']);
                            if (count($submenus) > 0) {
                                ?>
                                <li  class="nav-item <?php if (count($submenus) > 0) { ?> dropdown"<?php } ?>>
                                    <a style="color: white;" href="<?php echo $submenu['urk']; ?>" <?php if (count($submenus) > 0) { ?> data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" <?php } ?> class="nav-link <?php if (count($submenus) > 0) { ?> dropdown-toggle <?php } ?> "><?php echo $menu['nome_menu']; ?></a>

                                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                        <?php foreach ($submenus as $submenu): ?>

                                            <li ><a href="<?php echo $submenu['urk']; ?>" class="dropdown-item" style="color: gray;">
                                                    <i class="<?php echo $submenu['icon']; ?> fa-xs" style=""></i> 
                                                    <?php echo $submenu['nome_menu']; ?></a>

                                            </li>





                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php } else { ?>
                                <li class="nav-item">
                                    <a style="color: white;" href="<?php echo $submenu['urk']; ?>" class="nav-link"><?php echo $menu['nome_menu']; ?></a>
                                </li>

                                <?php
                            }
                        }
                        ?>
                    <?php } ?>


                </ul>

            </div>
        <?php } ?>

        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">



            <?php if (!$without_permission == true) { ?>
                <?php if (has_permission_intranet('menu_top_view', '', 'view_chat') || is_admin()) { ?>
                    <li class="nav-item">
                        <a style="margin-right: 10px; " class="btn btn-default "  title="Chat" href="<?php echo base_url('admin/prchat/Prchat_Controller_intranet/chat_full_view'); ?>" role="bottom">
                            <i class="far fa-comments"></i>

                        </a>
                    </li>
                <?php } ?>
                <?php if (has_permission_intranet('menu_top_view', '', 'view_contacts') || is_admin()) { ?>
                    <li class="nav-item">
                        <a style="margin-right: 10px; " class="btn btn-default " title="Contatos"  href="<?php echo base_url('gestao_corporativa/intranet/contatos'); ?>" role="bottom">
                            <i class="nav-icon fas fa-users"></i>

                        </a>
                    </li>
                <?php } ?>

                <li class="nav-item">
                    <a style="margin-right: 10px; " class="btn btn-default "  title="Aprovações Pendentes" href="<?php echo base_url('gestao_corporativa/Approbation'); ?>" role="bottom">
                        <i class="fa fa-exclamation-circle"></i>
                        <span class="badge badge-success navbar-badge"><?php echo $count_approbations; ?></span>
                    </a>
                </li>
                <?php if (has_permission_intranet('menu_top_view', '', 'view_itens_task') || is_admin()) { ?>
                    <li class="nav-item ">
                        <a style="margin-right: 10px; " class="btn btn-default "  title="<?php echo _l('nav_todo_items'); ?>"  href="<?php echo base_url('admin/todo'); ?>" role="bottom">
                            <i class="far fa-check-square"></i>
                        </a>
                    </li>
                <?php } ?>
                <?php if (has_permission_intranet('menu_top_view', '', 'view_notify') || is_admin()) { ?>
                    <li class="nav-item dropdown">
                        <a style="margin-right: 10px; " class="btn btn-default" class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-bell"></i>
                            <span class="badge badge-warning navbar-badge"><?php echo $current_user->total_unread_notifications; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <?php
                            $this->load->model('Intranet_model');

                            $notifications = $this->Intranet_model->get_notification();

                            for ($i = 0; $i < 6; $i++) {
                                ?>
                                <a  href="<?php echo $notifications[$i]['link']; ?>" onclick="visualizar(<?php echo $notifications[$i]['id']; ?>);" target="_blanck" class="dropdown-item" 
                                    <?php if ($notifications[$i]['isread'] == 1) { ?> style="background-color: #F5F5F5" <?php } ?>>

                                    <div class="media">
                                        <img src="<?php echo staff_profile_image_caminho($notifications[$i]['fromuserid']); ?>" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                        <div class="media-body">
                                            <h3 class="dropdown-item-title">
                                                <?php echo $notifications[$i]['firstname'] . ' ' . $notifications[$i]['lastname']; ?>
                                                <?php if ($notifications[$i]['isread'] == 0) { ?>
                                                    <span class="float-right text-sm text-danger"><i class="far fa-bell"></i></span>
                                                <?php } ?>
                                            </h3>
                                            <p class="text-sm">
                                                <?php echo _l($notifications[$i]['description']); ?>
                                            </p>
                                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> <?php echo $notifications[$i]['date']; ?></p>
                                        </div>
                                    </div>

                                </a>
                                <div class="dropdown-divider"></div>
                            <?php } ?>
                            <a onclick="visualizar_all();" href="<?php echo base_url('gestao_corporativa/intranet/meu_perfil?notifications=true'); ?>" class="dropdown-item dropdown-footer">Ver Todas...</a>
                        </div>
                    </li> 
                <?php } ?>
                <li class="nav-item">
                    <a  class="btn btn-default" class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-cogs"></i>
                    </a>
                </li>
            <?php } ?>

            <li class=" nav-item dropdown" >
                <a  href="#" class=" dropdown-toggle  nav-link " data-toggle="dropdown"  >
                    <img src="<?php echo staff_profile_image_caminho($current_user->staffid); ?>" alt="Logo" class="brand-image img-circle " ><font style="color: #ffffff"><?php echo get_staff_full_name(); ?></font>

                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <div class="dropdown-divider"></div>
                    <?php if (!$without_permission == true) { ?>
                        <a class="dropdown-item" href="<?php echo base_url('gestao_corporativa/intranet/meu_perfil'); ?>"><?php echo _l('nav_my_profile'); ?></a>
                    <?php } ?>
                    <a onclick="logout();" class="dropdown-item">

                        <p class="text">Sair</p>
                    </a>



                </div>   
            </li>    

        </ul>
    </div>
</nav>
<script>
    function visualizar(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Comunicacao/lido'); ?>",
            data: {
                id: id
            },
            success: function (data) {

            }
        });
    }
    function visualizar_all() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Comunicacao/lido_all'); ?>",
            data: {
                id: 'all'
            },
            success: function (data) {

            }
        });
    }
</script>