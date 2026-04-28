
<!DOCTYPE html>
<html lang="en" translate="no">
    <?php $this->load->view('portal/includes/head'); ?>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <body class="g-sidenav-show  bg-gray-200">


        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

        <?php $this->load->view('portal/includes/menu'); ?>
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">


            <?php $this->load->view('portal/includes/navbar'); ?>

            <div class="container-fluid py-2">
                <div class="row">
                    <div class="col-lg-11 mx-auto">

                        <div class="card mb-4 p-3">
                            <div class="row">
                                <div class="col-lg-12 mx-auto">
                                    <h3 class="mt-lg-0 mt-4">Protocolo: <?php echo $at->protocolo;?> <span class="badge badge-dark">Atendimento #<?php echo $at->id;?></span></h3>
                                    <!--<div class="rating">
                                        <i class="material-icons text-lg">grade</i>
                                        <i class="material-icons text-lg">grade</i>
                                        <i class="material-icons text-lg">grade</i>
                                        <i class="material-icons text-lg">grade</i>
                                        <i class="material-icons text-lg">star_outline</i>
                                    </div>-->
                                    
                                    <label class="mt-2">Informações</label>
                                    <ul>
                                        <?php if(!$at->user_created){?>
                                        <li>Atendimento gerado mediante ao acesso no portal pelo cliente</li>
                                        <?php }?>
                                        <?php if($at->user_created){?>
                                        <li>Atendimento feito por: <?php echo get_staff_full_name($at->user_created);?></li>
                                        <?php }?>
                                        <li>Data de abertura: <?php echo date('d/m/Y H:i:s', strtotime($at->date_created)); ?></li>
                                        <?php if($at->titulo){?>
                                        <li><?php echo $at->titulo;?></li>
                                        <?php }?>
                                    </ul>
                                    
                                </div>
                            </div>
                            <?php if(count($workflow_ra) > 0){?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5 class="ms-3">Solicitações nesse atendimento</h5>
                                    <div class="table table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Solicitação</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Data de Cadastro</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Data de Prazo</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acompanhar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($workflow_ra as $workflow) { ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm"><?php echo $workflow['titulo_abreviado']; ?></h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm text-secondary mb-0"><?php echo date("d/m/Y H:i:s", strtotime($workflow['date_created'])); ?></p>
                                                    </td>
                                                    <td>
                                                        <?php echo date("d/m/Y", strtotime($workflow['date_prazo_client'])); ?>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <?php if($workflow['date_end_client'] == ''){?>
                                                <?php $status = get_ro_status($workflow['status']); ?>
                                                <span class="badge badge-<?php echo $status['class']; ?>"><?php echo $status['label']; ?></span>
                                                <?php } else {?>
                                                    <span class="badge badge-success">Finalizado</span>
                                                <?php }?>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="text-secondary text-sm"><a class="btn btn-sm btn-warning mb-0 mx-auto" href="<?php echo base_url(); ?>portal/workflow/workflow/<?php echo $workflow['id']; ?>">Acompanhar</a></span>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                            <?php if(count($ros_ra) > 0){?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5 class="ms-3">Registros de Ocorrência nesse atendimento</h5>
                                    <div class="table table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Assunto</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Data ocorrido</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Prioridade</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Data de Cadastro</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acompanhar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($ros_ra as $ro) { ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm"><?php echo $ro['titulo']; ?></h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm text-secondary mb-0"><?php echo date("d/m/Y H:i:s", strtotime($ro['date'])); ?></p>
                                                    </td>
                                                    <td>
                                                        <?php
                                                if ($ro['priority'] == 1) {
                                                    echo 'Baixa';
                                                } elseif ($ro['priority'] == 2) {
                                                    echo 'Média';
                                                } else {
                                                    echo 'Alta';
                                                }
                                                ?>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <?php echo date("d/m/Y H:i:s", strtotime($ro['date_created'])); ?>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="text-secondary text-sm"><a class="btn btn-sm btn-warning mb-0 mx-auto" href="<?php echo base_url(); ?>portal/registro_ocorrencia/registro/<?php echo $ro['id']; ?>">Acompanhar</a></span>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>

                <footer class="footer py-4  ">
                    <div class="container-fluid">
                        <div class="row align-items-center justify-content-lg-between">
                            <div class="col-lg-6 mb-lg-0 mb-4">
                                <div class="copyright text-center text-sm text-muted text-lg-start">
                                    © <script>
                                        document.write(new Date().getFullYear())
                                    </script>,
                                    made with <i class="fa fa-heart"></i> by
                                    <a href="" class="font-weight-bold" target="_blank">Sigplus</a>/<a href="" class="font-weight-bold" target="_blank">Unimed Manaus</a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                    <li class="nav-item">
                                        <a href="" class="nav-link pe-0 text-muted" target="_blank">Dúvidas Frequentes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="nav-link pe-0 text-muted" target="_blank">Sobre Nós</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <script src="<?php echo base_url(); ?>assets/portal/js/core/popper.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/portal/js/core/bootstrap.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/portal/js/plugins/perfect-scrollbar.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/portal/js/plugins/smooth-scrollbar.min.js"></script>

            <script src="<?php echo base_url(); ?>assets/portal/js/plugins/dragula/dragula.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/portal/js/plugins/jkanban/jkanban.js"></script>
            <script>
                                        var win = navigator.platform.indexOf('Win') > -1;
                                        if (win && document.querySelector('#sidenav-scrollbar')) {
                                            var options = {
                                                damping: '0.5'
                                            }
                                            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
                                        }
            </script>

            <script async defer src="https://buttons.github.io/buttons.js"></script>

            <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.min.js?v=3.0.6"></script>
            <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v2b4487d741ca48dcbadcaf954e159fc61680799950996" integrity="sha512-D/jdE0CypeVxFadTejKGTzmwyV10c1pxZk/AqjJuZbaJwGMyNHY3q/mTPWqMUnFACfCTunhZUVcd4cV78dK1pQ==" data-cf-beacon='{"rayId":"7b6c143ec9631af3","version":"2023.3.0","r":1,"token":"1b7cbb72744b40c580f8633c6b62637e","si":100}' crossorigin="anonymous"></script>


        </main>

    </body>
</html>


<style>
    /* Timeline */
    .timeline,
    .timeline-horizontal {
        list-style: none;
        padding: 5px;
        position: relative;
    }
    .timeline:before {
        top: 40px;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 3px;
        background-color: #eeeeee;
        left: 50%;
        margin-left: -1.5px;
    }
    .timeline .timeline-item {
        margin-bottom: 20px;
        position: relative;
    }
    .timeline .timeline-item:before,
    .timeline .timeline-item:after {
        content: "";
        display: table;
    }
    .timeline .timeline-item:after {
        clear: both;
    }
    .timeline .timeline-item .timeline-badge {
        color: #fff;
        width: 54px;
        height: 54px;
        line-height: 52px;
        font-size: 22px;
        text-align: center;
        position: absolute;
        top: 18px;
        left: 50%;
        margin-left: -25px;
        background-color: #7c7c7c;
        border: 3px solid #ffffff;
        z-index: 100;
        border-top-right-radius: 50%;
        border-top-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-bottom-left-radius: 50%;
    }
    .timeline .timeline-item .timeline-badge i,
    .timeline .timeline-item .timeline-badge .fa,
    .timeline .timeline-item .timeline-badge .glyphicon {
        top: 2px;
        left: 0px;
    }
    .timeline .timeline-item .timeline-badge.primary {
        background-color: #1f9eba;
    }
    .timeline .timeline-item .timeline-badge.info {
        background-color: #5bc0de;
    }
    .timeline .timeline-item .timeline-badge.success {
        background-color: #59ba1f;
    }
    .timeline .timeline-item .timeline-badge.warning {
        background-color: #d1bd10;
    }
    .timeline .timeline-item .timeline-badge.danger {
        background-color: #ba1f1f;
    }
    .timeline .timeline-item .timeline-panel {
        position: relative;
        width: 46%;
        float: left;
        right: 16px;
        border: 1px solid #c0c0c0;
        background: #ffffff;
        border-radius: 2px;
        padding: 20px;
        -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    }
    .timeline .timeline-item .timeline-panel:before {
        position: absolute;
        top: 26px;
        right: -16px;
        display: inline-block;
        border-top: 16px solid transparent;
        border-left: 16px solid #c0c0c0;
        border-right: 0 solid #c0c0c0;
        border-bottom: 16px solid transparent;
        content: " ";
    }
    .timeline .timeline-item .timeline-panel .timeline-title {
        margin-top: 0;
        color: inherit;
    }
    .timeline .timeline-item .timeline-panel .timeline-body > p,
    .timeline .timeline-item .timeline-panel .timeline-body > ul {
        margin-bottom: 0;
    }
    .timeline .timeline-item .timeline-panel .timeline-body > p + p {
        margin-top: 5px;
    }
    .timeline .timeline-item:last-child:nth-child(even) {
        float: right;
    }
    .timeline .timeline-item:nth-child(even) .timeline-panel {
        float: right;
        left: 16px;
    }
    .timeline .timeline-item:nth-child(even) .timeline-panel:before {
        border-left-width: 0;
        border-right-width: 14px;
        left: -14px;
        right: auto;
    }
    .timeline-horizontal {
        list-style: none;
        position: relative;
        display: inline-block;
    }
    .timeline-horizontal:before {
        height: 3px;
        top: auto;
        bottom: 26px;
        left: 56px;
        right: 0;
        width: 100%;
        margin-bottom: 20px;
    }
    .timeline-horizontal .timeline-item {
        display: table-cell;
        height: 250px;
        min-width: 20px;
        max-width: 400px;
        float: none !important;
        padding-left: 0px;
        padding-right: 20px;
        vertical-align: bottom;
    }
    .timeline-horizontal .timeline-item .timeline-panel {
        top: auto;
        bottom: 64px;
        display: inline-block;
        float: none !important;
        left: 0 !important;
        right: 0 !important;
        width: 100%;
    }
    .timeline-horizontal .timeline-item .timeline-panel:before {
        top: auto;
        bottom: -16px;
        left: 28px !important;
        right: auto;
        border-right: 16px solid transparent !important;
        border-top: 16px solid #c0c0c0 !important;
        border-bottom: 0 solid #c0c0c0 !important;
        border-left: 16px solid transparent !important;
    }
    .timeline-horizontal .timeline-item:before,
    .timeline-horizontal .timeline-item:after {
        display: none;
    }
    .timeline-horizontal .timeline-item .timeline-badge {
        top: auto;
        bottom: 0px;
        left: 43px;
    }
</style>

<!--
<div class="timeline-block mb-3">
<span class="timeline-step">
<i class="material-icons text-secondary text-white">notifications</i>
</span>
<div class="timeline-content">
<h6 class="text-dark text-sm font-weight-bold mb-0">Order received</h6>
<p class="text-secondary font-weight-normal text-xs mt-1 mb-0">22 DEC 7:20 AM</p>
</div>
</div>-->