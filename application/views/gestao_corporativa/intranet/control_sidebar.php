<aside class="control-sidebar control-sidebar-light">
    <a href="<?php echo base_url('gestao_corporativa/intranet/meu_perfil'); ?>" class="brand-link">
        <img src="<?php echo staff_profile_image_caminho($current_user->staffid); ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8"><span class="brand-text font-weight-light"><?php echo get_staff_full_name(); ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item ">
                    <a href="<?php echo base_url('gestao_corporativa/intranet/meu_perfil'); ?>" class="nav-link ">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <p>
                            Meu Perfil
                            <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>

                <?php if (has_permission_intranet('menu_top_view', '', 'view_chat') || is_admin()) { ?>

                <li class="nav-item ">
                    <a title="Chat" href="<?php echo base_url('admin/prchat/Prchat_Controller_intranet/chat_full_view'); ?>" class="nav-link ">
                        <i class="far fa-comments"></i>
                        <p>
                            Chat
                        </p>


                    </a>
                </li>
                <?php }?>
                <?php if (has_permission_intranet('menu_lateral_view', '', 'view_admin') || is_admin()) { ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('gestao_corporativa/intranet_admin'); ?>" class="nav-link ">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Administrativo
                                <span class="right badge badge-danger"></span>
                            </p>


                        </a>
                    </li>
                <?php } ?> 




                <?php if (has_permission_intranet('menu_top_view', '', 'view_contacts') || is_admin()) { ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('gestao_corporativa/intranet/contatos'); ?>" class="nav-link " >
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
                <li class="nav-item">
                    <a onclick="logout();" class="nav-link" style="background-color: gainsboro;">
                        <i class="nav-icon 	fas fa-sign-out-alt"></i>
                        <p class="text">Terminar Sessão</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>