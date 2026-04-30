<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
$empresa_id      = $this->session->userdata('empresa_id');
$company_name    = get_option('companyname');
$nome_reduzido   = get_option('nome_reduzido');
$company_logo    = get_option('company_logo');
$current_user    = $this->staff_model->get(get_staff_user_id());

$brand_name      = !empty($nome_reduzido) ? $nome_reduzido : $company_name;
$brand_initial   = strtoupper(substr($brand_name, 0, 1));

$count_approbations = isset($count_approbations) ? (int) $count_approbations : 0;
$total_notify       = isset($current_user->total_unread_notifications) ? (int) $current_user->total_unread_notifications : 0;
$cont_msg_n_lidas   = isset($cont_msg_n_lidas) ? (int) $cont_msg_n_lidas : 0;
$total_tarefas      = isset($tarefas) && is_array($tarefas) ? count($tarefas) : 0;

$foto_usuario  = staff_profile_image_caminho($current_user->staffid);
$cargo_usuario = !empty($current_user->cargo) ? $current_user->cargo : 'Colaborador';

$menus_principal = [];
if (!isset($without_permission) || !$without_permission) {
    if (has_permission_intranet('menu_top_view', '', 'view_menus') || is_admin()) {
        $this->load->model('Menu_model');
        $menus_principal = $this->Menu_model->get_menus();
    }
}
?>

<style>
    .sig-navbar-wrap{
        position: sticky;
        top: 0;
        z-index: 1050;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 1px 8px rgba(0,0,0,.05);
    }

    .sig-topbar{
        background: #44546a;
    }

    .sig-topbar-inner{
        max-width: 1280px;
        margin: 0 auto;
        padding: 6px 14px;
        min-height: 58px;
        display: grid;
        grid-template-columns: 260px 1fr auto;
        align-items: center;
        gap: 14px;
    }

    .sig-brand{
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none !important;
        min-width: 0;
    }

    .sig-brand-logo{
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,.10);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .sig-brand-logo img{
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 5px;
        background: #fff;
    }

    .sig-brand-logo span{
        font-size: 20px;
        font-weight: 700;
        color: #0a66c2;
    }

    .sig-brand-text{
        line-height: 1.05;
    }

    .sig-brand-title{
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        margin: 0;
    }

    .sig-brand-subtitle{
        color: rgba(255,255,255,.78);
        font-size: 11px;
        margin-top: 2px;
    }

    .sig-top-menu{
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 6px;
        min-width: 0;
        overflow-x: auto;
    }

    .sig-top-menu::-webkit-scrollbar{
        height: 4px;
    }

    .sig-menu-item,
    .sig-menu-toggle{
        border: none;
        background: transparent;
        color: #fff !important;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
        cursor: pointer;
        transition: .18s ease;
        line-height: 1;
    }

    .sig-menu-item:hover,
    .sig-menu-toggle:hover,
    .sig-top-dropdown.open .sig-menu-toggle{
        background: rgba(255,255,255,.10);
    }

    .sig-top-dropdown,
    .sig-user-dropdown{
        position: relative;
    }

    .sig-dropdown-menu,
    .sig-user-menu{
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        min-width: 290px;
        background: #fff;
        border: 1px solid #e6e6e6;
        border-radius: 12px;
        box-shadow: 0 12px 26px rgba(0,0,0,.16);
        padding: 10px 0;
    }

    .sig-user-menu{
        left: auto;
        right: 0;
        min-width: 250px;
    }

    .sig-top-dropdown.open .sig-dropdown-menu,
    .sig-user-dropdown.open .sig-user-menu{
        display: block;
    }

    .sig-dropdown-menu a,
    .sig-user-menu a{
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 18px;
        color: #5f6673 !important;
        text-decoration: none !important;
        font-size: 15px;
        line-height: 1.35;
        transition: .15s;
    }

    .sig-dropdown-menu a:hover,
    .sig-user-menu a:hover{
        background: #f5f7fa;
        color: #334155 !important;
    }

    .sig-top-actions{
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
    }

    .sig-icon-btn{
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid rgba(0,0,0,.08);
        color: #44546a;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: .18s ease;
        font-size: 14px;
        flex-shrink: 0;
    }

    .sig-icon-btn:hover{
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0,0,0,.08);
    }

    .sig-badge{
        position: absolute;
        top: -5px;
        right: -5px;
        min-width: 17px;
        height: 17px;
        border-radius: 999px;
        background: #d90429;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 700;
        padding: 0 4px;
        line-height: 1;
    }

    .sig-user-box{
        display: flex;
        align-items: center;
        gap: 9px;
        padding-left: 12px;
        margin-left: 4px;
        border-left: 1px solid rgba(255,255,255,.16);
        cursor: pointer;
    }

    .sig-user-box img{
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,.40);
        flex-shrink: 0;
    }

    .sig-user-text{
        line-height: 1.03;
    }

    .sig-user-name{
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .sig-user-role{
        color: rgba(255,255,255,.78);
        font-size: 10px;
        margin-top: 3px;
        white-space: nowrap;
    }

    .sig-user-chevron{
        color: #fff;
        font-size: 13px;
    }

    .sig-modulebar{
        background: #f5f6f8;
        border-top: 1px solid rgba(0,0,0,.03);
        border-bottom: 1px solid #e5e7eb;
    }

    .sig-modulebar-inner{
        max-width: 1280px;
        margin: 0 auto;
        padding: 8px 14px 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .sig-module{
        width: 112px;
        height: 68px;
        background: #fff;
        border: 1px solid #d9dee5;
        border-radius: 14px;
        text-decoration: none !important;
        color: #425466;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        position: relative;
        transition: .18s ease;
        box-shadow: 0 1px 4px rgba(0,0,0,.03);
    }

    .sig-module:hover{
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(0,0,0,.06);
        border-color: #c5d4e7;
    }

    .sig-module.active{
        border-color: #2f80ed;
        box-shadow: 0 6px 14px rgba(47,128,237,.10);
    }

    .sig-module-ico{
        font-size: 15px;
        line-height: 1;
        color: #43556a;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 15px;
    }

    .sig-module-title{
        font-size: 11px;
        font-weight: 700;
        line-height: 1.1;
        text-align: center;
        color: #425466;
    }

    .sig-module-count{
        position: absolute;
        top: -5px;
        right: -5px;
        min-width: 17px;
        height: 17px;
        border-radius: 999px;
        background: #17b26a;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        line-height: 1;
    }

    @media (max-width: 1100px){
        .sig-topbar-inner{
            grid-template-columns: 230px 1fr auto;
        }
    }

    @media (max-width: 992px){
        .sig-topbar-inner{
            grid-template-columns: 1fr;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .sig-top-actions{
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .sig-modulebar-inner{
            justify-content: flex-start;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .sig-module{
            flex: 0 0 auto;
        }
    }

    @media (max-width: 768px){
        .sig-user-text{
            display: none;
        }

        .sig-dropdown-menu,
        .sig-user-menu{
            min-width: 230px;
        }
    }


    /* MÓDULOS NO TOPO (INLINE) */
    .sig-modulebar-inline{
        display:flex;
        align-items:center;
        justify-content:center;
        gap:18px;
        flex:1;
    }

    /* BOTÃO DOS MÓDULOS */
    .sig-module-inline{
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        gap:4px;
        padding:6px 10px;
        border-radius:10px;
        text-decoration:none !important;
        color:#fff;
        font-size:12px;
        min-width:70px;
        transition:.2s;
    }

    .sig-module-inline i{
        font-size:16px;
    }

    /* HOVER estilo LinkedIn */
    .sig-module-inline:hover{
        background:rgba(255,255,255,.12);
    }

    /* ATIVO */
    .sig-module-inline.active{
        background:rgba(255,255,255,.18);
    }
</style>

<div class="sig-navbar-wrap">
    <div class="sig-topbar">
        <div class="sig-topbar-inner">

            <a href="<?php echo base_url('admin'); ?>" class="sig-brand">
                <div class="sig-brand-logo">
                    <?php if (!empty($company_logo)) { ?>
                        <img src="<?php echo base_url('uploads/company/' . $company_logo); ?>" alt="Logo">
                    <?php } else { ?>
                        <span><?php echo $brand_initial; ?></span>
                    <?php } ?>
                </div>
                <div class="sig-brand-text">
                    <div class="sig-brand-title"><?php echo html_escape($brand_name); ?></div>
                    <div class="sig-brand-subtitle">Intranet corporativa</div>
                </div>
            </a>

             <!-- MÓDULOS NO MEIO -->
            <div class="sig-modulebar-inline">
                <?php
                $docs_url = $empresa_id == 2
                    ? base_url('gestao_corporativa/intra/documentos/list_')
                    : base_url('gestao_corporativa/cdc/list_');

                $modules = [
                    ['perm' => 'view_docs',         'href' => $docs_url,                                            'icon' => 'far fa-folder-open',    'label' => 'Documentos'],
                    ['perm' => 'view_cis',          'href' => base_url('gestao_corporativa/intra/comunicado'),      'icon' => 'fas fa-bullhorn',       'label' => 'CI'],
                    ['perm' => 'view_atas',         'href' => base_url('gestao_corporativa/Ata'),                   'icon' => 'far fa-file-alt',       'label' => 'Atas'],
                    ['perm' => 'view_planos_acao',  'href' => base_url('gestao_corporativa/Plano_acao'),            'icon' => 'fas fa-clipboard-list', 'label' => 'Planos'],
                    ['perm' => 'view_ros',          'href' => base_url('gestao_corporativa/Registro_ocorrencia'),   'icon' => 'far fa-flag',           'label' => 'R.O'],
                    ['perm' => 'view_ras',          'href' => base_url('gestao_corporativa/Atendimento/index'),     'icon' => 'fas fa-headset',        'label' => 'Atendimentos'],
                    ['perm' => 'view_workflows',    'href' => base_url('gestao_corporativa/Workflow/index'),         'icon' => 'fas fa-project-diagram','label' => 'Workflow'],
                    ['perm' => 'view_forms',        'href' => base_url('gestao_corporativa/Formularios'),            'icon' => 'far fa-file',           'label' => 'Formulários'],
                    ['perm' => 'view_geds',         'href' => base_url('gestao_corporativa/Ged/index'),              'icon' => 'fas fa-database',       'label' => 'GED'],
                    ['perm' => 'view_projects',     'href' => base_url('admin/projects'),                            'icon' => 'far fa-folder',         'label' => 'Projetos'],
                ];

                foreach ($modules as $m) {
                    if (has_permission_intranet('modules', '', $m['perm']) || is_admin()) {
                        echo '<a href="' . $m['href'] . '" class="sig-module-inline" title="' . $m['label'] . '">'
                           . '<i class="' . $m['icon'] . '"></i>'
                           . '<span>' . $m['label'] . '</span>'
                           . '</a>';
                    }
                }
                ?>
            </div>

            <div class="sig-top-actions">
                <?php if (!isset($without_permission) || !$without_permission) { ?>

                <?php if (has_permission_intranet('menu_top_view', '', 'view_chat') || is_admin()) { ?>
                    <a class="sig-icon-btn" title="Chat" href="<?php echo base_url('admin/prchat/Prchat_Controller_intranet/chat_full_view'); ?>">
                        <i class="far fa-comments"></i>
                        <?php if ($cont_msg_n_lidas > 0) { ?>
                            <span class="sig-badge"><?php echo $cont_msg_n_lidas; ?></span>
                        <?php } ?>
                    </a>
                <?php } ?>

                <a class="sig-icon-btn" title="Gerador de Arquivos" href="<?php echo base_url('gestao_corporativa/gerador_arquivos'); ?>">
                    <i class="fas fa-file-excel"></i>
                </a>

            

                <?php } ?>

                <div class="sig-user-dropdown js-user-dropdown">
                    <div class="sig-user-box js-user-toggle">
                        <img src="<?php echo $foto_usuario; ?>" alt="Usuário">
                        <div class="sig-user-text">
                            <div class="sig-user-name"><?php echo html_escape(get_staff_full_name()); ?></div>
                            <div class="sig-user-role"><?php echo html_escape($cargo_usuario); ?></div>
                        </div>
                        <i class="fa fa-caret-down sig-user-chevron"></i>
                    </div>

                    <div class="sig-user-menu">
                        <a href="<?php echo base_url('gestao_corporativa/intranet/meu_perfil'); ?>">
                            <i class="fa fa-user"></i> Meu Perfil
                        </a>

                        <?php if (has_permission_intranet('menu_top_view', '', 'view_notify') || is_admin()) { ?>
                            <a href="<?php echo base_url('gestao_corporativa/intranet/meu_perfil?notifications=true'); ?>">
                                <i class="far fa-bell"></i> Alertas
                                <?php if ($total_notify > 0) { ?>
                                    <span class="sig-badge" style="position:static;margin-left:auto;"><?php echo $total_notify; ?></span>
                                <?php } ?>
                            </a>
                        <?php } ?>

                        <a  href="<?php echo base_url('gestao_corporativa/Approbation'); ?>">
                                <i class="fa fa-exclamation-circle"></i> Aprovações Pendentes
                                <?php if ($count_approbations > 0) { ?>
                                <span class="sig-badge" style="position:static;margin-left:auto;"><?php echo $count_approbations; ?></span>
                                
                                <?php } ?>
                            </a>

                        <a href="<?php echo base_url('gestao_corporativa/intranet_admin'); ?>">
                            <i class="fas fa-cogs"></i> Configurações
                        </a>

                        <a href="javascript:void(0);" onclick="logout();">
                            <i class="fa fa-sign-out"></i> Sair
                        </a>
                    </div>
                </div>
            </div>

         

        </div>
    </div>

  
</div>

<script>
    document.addEventListener('click', function(e) {
        document.querySelectorAll('.js-top-dropdown').forEach(function(item) {
            if (!item.contains(e.target)) {
                item.classList.remove('open');
            }
        });

        document.querySelectorAll('.js-user-dropdown').forEach(function(item) {
            if (!item.contains(e.target)) {
                item.classList.remove('open');
            }
        });
    });

    document.querySelectorAll('.js-top-toggle').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var parent = this.closest('.js-top-dropdown');
            document.querySelectorAll('.js-top-dropdown').forEach(function(item) {
                if (item !== parent) {
                    item.classList.remove('open');
                }
            });
            parent.classList.toggle('open');
        });
    });

    document.querySelectorAll('.js-user-toggle').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var parent = this.closest('.js-user-dropdown');
            document.querySelectorAll('.js-user-dropdown').forEach(function(item) {
                if (item !== parent) {
                    item.classList.remove('open');
                }
            });
            parent.classList.toggle('open');
        });
    });
</script>