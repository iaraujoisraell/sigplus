

<script>
    $('.nav-link').click(function (e) {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
    });

// verificar via JS:
    const href = [location.pathname, location.search].join('?');
    $('.nav-link[href="' + href + '"]').addClass('active');

</script>     
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->


<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <a href="<?php echo base_url('admin'); ?>" class="brand-link">
        <?php
        $company_name = get_option('companyname');
        $name_reduzido = get_option('nome_reduzido');

        $logoURL = site_url(get_admin_uri() . '/');
        $logo = '<a href="' . $logoURL . '" class="brand-image img-circle elevation-3' . ($href_class != '' ? ' ' . $href_class : '') . '" style="opacity: .8">
        <img src="' . base_url('uploads/company/' . $company_logo) . '" class="img-responsive" alt="' . html_escape($company_name) . '">
        </a>';
        ?>
        <?php echo $name_reduzido; ?>
        <span class="brand-text font-weight-light"><?php echo $company_name; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <a href="<?php echo base_url('gestao_corporativa/intranet/meu_perfil'); ?>"> <?php echo staff_profile_image($current_user->staffid, array('img', 'img-responsive', 'staff-profile-image-small', 'pull-left')); ?></a>

            </div>
            <div class="info">

                <a href="<?php echo base_url('gestao_corporativa/intranet/meu_perfil'); ?>" class="d-block"><?php echo get_staff_full_name(); ?></a>

                <a href="#" onclick="logout();" class="d-block">Sair</a>


            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item ">
                    <a href="<?php echo base_url('admin'); ?>" class="nav-link <?php
                    if ($title == 'INTRANET') {
                        echo 'active';
                    }
                    ?>">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Home
                            <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>
                    
                <?php if (has_permission_intranet('menu_lateral_view', '', 'view_admins') || is_admin()) { ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('gestao_corporativa/intranet_admin'); ?>" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Administrativo
                                <span class="right badge badge-danger"></span>
                            </p>


                        </a>
                    </li>
                <?php } ?> 
                <?php if (has_permission_intranet('menu_lateral_view', '', 'view_menus') || is_admin()) { ?>
                    <?php
                    $this->load->model('Menu_model');
                    $menus_principal = $this->Menu_model->get_menus();
                    //print_r($menus);
                    foreach ($menus_principal as $menu) {
                        $submenus = $this->Menu_model->get_submenus($menu['id']);
                        ?>
                        <li class="nav-item ">
                            <a href="<?php echo $menu['urk']; ?>" class="nav-link <?php
                            if ($menu_id == $menu['id']) {
                                echo 'active';
                            }
                            ?>">
                                <i class="nav-icon <?php echo $menu['icon']; ?>"></i>
                                <p>
                                    <?php echo $menu['nome_menu']; ?>
                                    <span class="right badge badge-danger"></span>
                                </p>


                            </a>
                            <?php if ($submenus) { ?>
                                <ul class="nav nav-treeview" style="padding-left: 5px; padding-right: 5px;">
                                    <?php foreach ($submenus as $submenu): ?>
                                        <li class="nav-item">
                                            <a href="<?php echo $submenu['urk']; ?>" target="_blank" class="nav-link">
                                                <i class="<?php echo $submenu['icon']; ?> nav-icon"></i>
                                                <p><?php echo $submenu['nome_menu']; ?></p>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php } ?>
                        </li>

                    <?php } ?>
                <?php } ?>

                <?php if (has_permission_intranet('menu_lateral_view', '', 'view_contacts') || is_admin()) { ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('gestao_corporativa/intranet/contatos'); ?>" class="nav-link <?php
                        if ($title == 'INTRANET - Contatos') {
                            echo 'active';
                        }
                        ?>" >
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Contatos
                                <span class="right badge badge-danger"></span>
                            </p>


                        </a>
                    </li>

                <?php } ?>
                <?php
                $member = $this->staff_model->get(get_staff_user_id());
                $last_login = $member->last_login;
                $ip = $_SERVER['REMOTE_ADDR'];
                ?>
                <li class="nav-header">Último Acesso</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-warning"></i>
                        <p class="text"><?php echo _d($last_login); ?></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-circle text-danger"></i>
                        <p class="text"><?php echo $ip; ?></p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>