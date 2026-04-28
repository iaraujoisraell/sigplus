<?php
$logo = get_company_option('', 'company_logo');
//echo $this->session->userdata('categoria_id'); exit;

$company = get_company_option('', 'companyname');
?>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main" translate="no">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-xl-none" aria-hidden="true" id="iconSidenav"></i>

        <a class="navbar-brand m-0" href="<?php echo base_url(); ?>portal/dashboard/index/2">

            <img src="<?php echo base_url(); ?>uploads/company/<?php echo $logo; ?>" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold text-white">Portal</span>

        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item mb-2 mt-0">
                <a data-bs-toggle="collapse" href="#ProfileNav" class="nav-link text-white" aria-controls="ProfileNav" role="button" aria-expanded="false">
                    <!--<img src="<?php echo base_url(); ?>assets/portal/img/team-3.jpg" class="avatar">-->
                    <i class="material-icons-round opacity-10">person</i>
                    <span class="nav-link-text ms-2 ps-1"><?php
                                                            $nome_array = explode(' ', $info_perfil->company);
                                                            echo $nome_array[0] . ' ' . $nome_array[count($nome_array) - 1];
                                                            ?></span>
                </a>
                <div class="collapse" id="ProfileNav" style="">
                    <ul class="nav ">
                        <!--<li class="nav-item">
                            <a class="nav-link text-white" href="../../pages/pages/profile/overview.html">
                                <span class="sidenav-mini-icon"> MP </span>
                                <span class="sidenav-normal  ms-3  ps-1"> My Profile </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white " href="../../pages/pages/account/settings.html">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal  ms-3  ps-1"> Settings </span>
                            </a>
                        </li>-->
                        <?php if ($this->session->userdata('categoria_id') == '9') { ?>
                            <?php if ($master != '2') { ?>
                                <li class="nav-item">
                                    <a class="nav-link text-white " href="<?php echo base_url("portal/Profile/index/"); ?>">
                                        <span class="sidenav-mini-icon"> P </span>
                                        <span class="sidenav-normal  ms-3  ps-1"> Perfil </span>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <li class="nav-item">
                            <a class="nav-link text-white " href="<?php echo base_url(); ?>portal/signin/finish">
                                <span class="sidenav-mini-icon"> S </span>
                                <span class="sidenav-normal  ms-3  ps-1"> Sair </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <hr class="horizontal light mt-0">
            <?php //if ($master != '2') { ?>
                <li class="nav-item">
                    <a href="<?php echo base_url(); ?>portal/dashboard/index/2" class="nav-link text-white active">
                        <i class="material-icons-round opacity-10">dashboard</i>
                        <span class="nav-link-text ms-2 ps-1">Home</span>
                    </a>
                </li>
            <?php // } ?>
            <?php if ($this->session->userdata('categoria_id') == '9') { ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('portal/financeiro/index/' . $master) ?> ">
                        <span class="material-icons-round opacity-10">
                            receipt_long
                        </span>
                        <span class="nav-link-text ms-2 ps-1">Boletos</span>
                    </a>
                </li>
                <?php if ($master != '2') { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>portal/workflow">
                            <i class="material-icons-round opacity-10">pan_tool_alt</i>
                            <span class="nav-link-text ms-2 ps-1">Solicitações</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url(); ?>portal/Appointments">
                            <span class="material-icons-round opacity-10">
                                calendar_month
                            </span>
                            <span class="nav-link-text ms-2 ps-1">Agendamentos</span>
                        </a>
                    </li>

                    <?php
                    $this->load->model('Categorias_campos_model');

                    $categorias_registro_ocorrencia = $this->Categorias_campos_model->get_categorias_with_ra('r.o', 'portal');
                    ?>

                    <li class="nav-item">
                        <a class="nav-link text-white <?php echo $active_fin; ?> " style="<?php echo $backgroud_fin; ?>" data-bs-toggle="collapse" aria-expanded="false" href="#profileExample">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">payments</i>
                            </div>
                            <span class="nav-link-text ms-1">Autosserviços</span>
                        </a>
                        <div class="collapse " id="profileExample">
                            <ul class="nav nav-sm flex-column">
                                <?php if ($this->session->userdata('pf') == true) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="<?php echo base_url('portal/financeiro/index'); ?>">
                                            <span class="sidenav-mini-icon"> B </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Boletos </span>
                                        </a>
                                    </li>
                                <?php } ?>

                                <?php if ($this->session->userdata('pf') == true) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="<?php echo base_url('portal/irpf/index'); ?>">
                                            <span class="sidenav-mini-icon"> I </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Relatório IRPF </span>
                                        </a>
                                    </li>
                                <?php } ?>

                                <?php if ($this->session->userdata('pf') == true) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="<?php echo base_url('portal/financeiro/history'); ?>">
                                            <span class="sidenav-mini-icon"> P </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Histórico Pagamentos </span>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($this->session->userdata('coparticipation') == true) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-white " href="<?php echo base_url('portal/coparticipation/index'); ?>">
                                            <span class="sidenav-mini-icon"> C </span>
                                            <span class="sidenav-normal  ms-2  ps-1"> Relatório Coparticipação </span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php foreach ($categorias_registro_ocorrencia as $categoria) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url(); ?>portal/registro_ocorrencia/index/<?php echo $categoria['id']; ?>">
                                <i class="material-icons-round opacity-10">wysiwyg</i>
                                <span class="nav-link-text ms-2 ps-1"><?php echo $categoria['titulo_abreviado']; ?></span>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url(); ?>portal/dashboard/docs">
                        <i class="material-icons-round opacity-10">folder</i>
                        <span class="nav-link-text ms-2 ps-1">Documentos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url(); ?>portal/dashboard/financial">
                        <i class="material-icons-round opacity-10">attach_money</i>
                        <span class="nav-link-text ms-2 ps-1">Ficha Financeira</span>
                    </a>
                </li>
            <?php } ?>






        </ul>
    </div>
</aside>