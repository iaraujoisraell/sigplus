
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
                    <div class="col-lg-10 mx-auto">
                        <div class="card mb-4">
                            <div class="card-header p-3 pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-lg-8 col-md-8 col-12">
                                        <h6>REGISTRO #<?php echo $registro->id; ?> - <?php echo strtoupper($registro->titulo); ?></h6>

                                        <p class="text-sm mb-0">
                                            Solicitante: <b><?php echo $info_perfil->company; ?> - <?php echo _d($registro->date_created); ?></b>
                                        </p>
                                        <p class="text-sm mb-0">
                                            Protocolo: <b><?php echo $atendimento->protocolo; ?> - <?php echo _d($atendimento->date_created); ?></b>
                                        </p>
                                        <p class="text-sm mb-0">
                                            Setor Responsável: <b><?php echo $registro->name; ?> <?php echo (get_staff_full_name($registro->atribuido_a)) ? '(' . get_staff_full_name($registro->atribuido_a) . ')' : ''; ?></b> 
                                        </p>
                                        <p class="text-sm mb-0">
                                            Validade: <b><?php echo _d($registro->validade) ? _d($registro->validade) : 'SEM VALIDADE'; ?></b> 
                                        </p>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-12 my-auto text-end">
                                        <?php
                                        if ($registro->date_end_client == '') {

                                            $status = get_ro_status($registro->status);
                                            ?>
                                            <span class="badge badge-<?php echo $status['class']; ?>  ms-auto mb-0"><?php echo $status['label']; ?></span>
                                            <p class="text-sm mt-2 mb-0"><?php echo _d($registro->date_end); ?></p>
                                        <?php } ?>


                                    </div>

                                </div>
                            </div>

                            <div class="card-body p-3 pt-0">
                                <?php if ($registro->status != 4) { ?>
                                    <div style="display:inline-block;width:100%;overflow-y:auto;">
                                        <ul class="timeline timeline-horizontal" style="">

                                            <li class="timeline-item">
                                                <div class="timeline-badge default"><i class="material-icons text-secondary text-white">send</i></div>
                                                <div class="timeline-panel">
                                                    <div class="timeline-body">
                                                        <h6 class="text-dark text-sm font-weight-bold mb-0">REGISTRO</h6>
                                                        <p class="text-secondary font-weight-normal text-xs mt-1 mb-0"><?php echo _d($registro->date_created); ?></p>
                                                        <span class="badge badge-sm bg-gradient-light">Enviado</span>

                                                    </div>
                                                </div>
                                            </li>
                                            <li class="timeline-item">
                                                <div class="timeline-badge success"><i class="material-icons text-secondary text-white">list_alt</i></div>
                                                <div class="timeline-panel">
                                                    <div class="timeline-body">
                                                        <h6 class="text-dark text-sm font-weight-bold mb-0">RECEBIDO</h6>
                                                        <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Setor: <?php echo ($registro->name); ?></p>
                                                        <p class="text-secondary font-weight-normal text-xs mt-1 mb-0"><?php echo _d($registro->date_created); ?></p>
                                                        <span class="badge badge-sm bg-gradient-success">Recebido</span>

                                                    </div>
                                                </div>
                                            </li>
                                            <li class="timeline-item">
                                                <?php
                                                if ($registro->atribuido_a != '' && $registro->atribuido_a != '0') {
                                                    $theme = 'success';
                                                    $title = _d($registro->date_atribuido_a);
                                                    $responsability = get_staff_full_name($registro->atribuido_a);
                                                    $status_ = 'Tratativa iniciada';
                                                } else {
                                                    $theme = 'info';
                                                    $title = '';
                                                    $responsability = 'AGUARDANDO';
                                                    $status_ = 'Aguardando tratativa';
                                                }
                                                ?>
                                                <div class="timeline-badge <?php echo $theme; ?>"><i class="material-icons text-secondary text-white">assignment_ind</i></div>
                                                <div class="timeline-panel">
                                                    <div class="timeline-body">
                                                        <h6 class="text-dark text-sm font-weight-bold mb-0">TRATATIVA</h6>
                                                        <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Responsável: <?php echo $responsability; ?></p>
                                                        <p class="text-secondary font-weight-normal text-xs mt-1 mb-0"><?php echo $title; ?></p>
                                                        <span class="badge badge-sm bg-gradient-<?php echo $theme; ?>"><?php echo $status_ ?></span>

                                                    </div>
                                                </div>
                                            </li>
                                            <li class="timeline-item">
                                                <?php
                                                if ($registro->status == '2' || $registro->status == '1') {
                                                    $icon = 'pending';
                                                } elseif ($registro->status == '3') {
                                                    $icon = 'done_all';
                                                } else {
                                                    $icon = 'close';
                                                }
                                                ?>
                                                <div class="timeline-badge <?php echo $theme; ?>"><i class="material-icons text-secondary text-white"><?php echo $icon; ?></i></div>
                                                <div class="timeline-panel">
                                                    <div class="timeline-body">
                                                        <h6 class="text-dark text-sm font-weight-bold mb-0">SITUAÇÃO</h6>
                                                        <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Validade: <?php echo _d($registro->validade) ? _d($registro->validade) : 'SEM VALIDADE'; ?></p>
                                                        <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Fim: <?php echo _d($registro->date_end) ? _d($registro->date_end) : 'AGUARDANDO'; ?></p>
                                                        <span class="badge badge-sm bg-gradient-<?php echo $status['class']; ?>"><?php echo $status['label']; ?></span>

                                                    </div>
                                                </div>
                                            </li>


                                        </ul>
                                    </div>
                                <?php } ?>
                                <hr class="horizontal ">
                                <div class="row">


                                    <div class="col-lg-6 col-12" style="padding-left: 40px;">
                                        <h6 class="mb-3 ms-3">Detalhes do registro:</h6>

                                        <h8 class="mb-3 text-sm ">Assunto: <?php echo $registro->subject; ?></h8><br>
                                        <h8 class="mb-3 text-sm ">Relato detalhado: <?php echo $registro->report; ?></h8><br>
                                        <h8 class="mb-3 text-sm ">Data Ocorrdo: <?php echo _d($registro->date); ?></h8><br>
                                        <h8 class="mb-3 text-sm ">Anexo: 
                                            <a target="_blank" href="<?php echo base_url() . "assets/intranet/arquivos/ro_arquivos/" . $registro->arquivos; ?>">
                                               <i class="material-icons text-info">file_open</i>
                                                <?php
                                                $path = base_url() . "assets/intranet/arquivos/ro_arquivos/" . $registro->arquivos;
                                                $is_image = is_image($path);
                                                if ($is_image) {
                                                    ?>
                                                    <img class="mt-5" style="max-width: 100%;" src="<?php echo base_url() . "assets/intranet/arquivos/ro_arquivos/" . $registro->arquivos; ?>">
                                                <?php } ?>
                                            </a>
                                        </h8>


                                        <br>
                                        <br>
                                        <?php
                                        $campos = [];
                                        $values_info['rel_type'] = 'r.o';
                                        $values_info['campos'] = $this->Categorias_campos_model->get_values($registro->id, 'r.o', 0, false, false);
                                        $this->load->view('portal/categorias_campos/values_info2', $values_info);
                                        ?>

                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12" style="padding-left: 40px;">

                                        <h6 class="mb-3 ms-3">Respostas</h6>
                                        <?php foreach ($notes as $ans) { ?>
                                            <div class="d-flex flex-column bg-gray-100 p-2 bg-gray-100 p-2">
                                                <span class="text-dark font-weight-bold ms-2 text-xs"><?php echo $ans['note']; ?></span><br>
                                                <span class="mb-2 text-xs text-end"> <?php echo get_staff_full_name($ans['user_created']); ?>- <?php echo _d($ans['data_created']); ?></span>

                                            </div>
                                        <?php } ?>
                                        <br>
                                        <div class="d-flex flex-column bg-gray-100 p-2">
                                            <h6 class="mb-3 text-sm">Informações para você</h6>
                                            <?php
                                            $exist = false;
                                            ?>
                                            <?php
                                            $values = $this->Categorias_campos_model->get_values($registro->id, 'r.o', '', false, true);
                                            foreach ($values as $value) {
                                                $exist = true;
                                                ?>
                                                <span class="mb-2 text-xs"><?php echo $value['nome_campo']; ?>: <span class="text-dark font-weight-bold ms-2"><?php echo get_value('r.o', $value['value'], $value['tipo_campo']); ?></span></span>
                                            <?php } ?>

                                            <?php if ($exist == false) { ?>
                                                <span class="mb-2 text-xs">Sem dados para você...</span>
                                            <?php } ?>
                                        </div>

                                    </div>
                                </div>
                            </div>

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