
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
                        <?php
                        foreach ($requests as $request) {
                            if ($request['status'] == 0) {
                                ?>
                                <div class="alert alert-danger alert-dismissible text-white fade show" style="width: 100%; position: relative;" role="alert">
                                    <span class="alert-icon align-middle">
                                        <span class="material-icons text-md">
                                            info
                                        </span>
                                    </span>
                                    <span class="alert-text"><strong>Mais informações!</strong> Os responsáveis por essa solicitação precisam de mais informações...</span>
                                    <button data-bs-toggle="modal" data-bs-target="#resquest_<?php echo $request['id']; ?>" type="button" class="btn btn-danger btn-sm" style="position: absolute;  right: 0;  margin-right: 10px; ">ENVIAR<span class='material-icons'>file_upload</span></button>
                                    <div class="modal fade" id="resquest_<?php echo $request['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalMessageTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title font-weight-normal" id="exampleModalLabel">Informações Necessárias:</h6>
                                                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <?php echo form_open_multipart(base_url('portal/workflow/finish_external_request/' . $workflow->id), array('onsubmit' => "document.getElementById('disabled').disabled = true;")); ?>
                                                <div class="modal-body">
                                                    <p class="text-sm mb-0" style="color: black;"><?php echo $request['description']; ?></p>

                                                    <input name="request_id" value="<?php echo $request['id']; ?>" type="hidden">

                                                    <?php
                                                    $this->load->model('Categorias_campos_model');
                                                    $campos = $this->Categorias_campos_model->get_categoria_campos($request['id'], '', true, 'external_request_workflow');
                                                    $this->load->view('portal/categorias_campos/campos', array('campos' => $campos));
                                                    ?>

                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn bg-gradient-success" id="disabled">Enviar</button>
                                                </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        <div class="card mb-4">
                            <div class="card-header p-3 pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="w-50">
                                        <h6>SOLICITAÇÃO #<?php echo $workflow->id; ?> <?php echo strtoupper($workflow->titulo); ?></h6>

                                        <p class="text-sm mb-0">
                                            Solicitante <b><?php echo $info_perfil->company; ?></b> 
                                        </p>
                                        <p class="text-sm mb-0">
                                            Data: <b><?php echo date("d/m/Y H:i:s", strtotime($workflow->date_created)); ?></b>
                                        </p>
                                        <p class="text-sm mb-0">
                                            Setor Responsável: <b><?php echo $workflow->name; ?></b> 
                                        </p>
                                        <?php if ($workflow->status != 4) { ?>
                                            <p class="text-sm mb-0">
                                                Prazo Estimado: <b><?php echo date("d/m/Y", strtotime($workflow->date_prazo)); ?></b> 
                                            </p>
                                        <?php } ?>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12 my-auto text-end">
                                        <?php
                                        if ($workflow->date_end_client == '') {

                                            $status = get_ro_status($workflow->status);
                                            ?>
                                            <span class="badge badge-<?php echo $status['class']; ?>  ms-auto mb-0"><?php echo $status['label']; ?></span>


                                            <?php
                                            $data_inicial = date("Y-m-d");
                                            $data_final = date("Y-m-d", strtotime($workflow->date_prazo_client));

                                            $diferenca = strtotime($data_final) - strtotime($data_inicial);

                                            $dias = floor($diferenca / (60 * 60 * 24));
                                            if ($dias >= 0) {
                                                ?>
                                                <p class="text-sm mt-2 mb-0">Faltam <?php echo $dias; ?> dias para o prazo Final.</p>
                                            <?php }
                                        } else {
                                            ?>
                                            <span class="badge badge-success  ms-auto mb-0">Finalizado</span>
                                            <p class="text-sm mt-2 mb-0"><?php echo date('d/m/Y H:i:s', strtotime($workflow->date_end_client))?></p>
                                        <?php } ?>


                                    </div>

                                </div>
                            </div>

                            <div class="card-body p-3 pt-0">
                                <?php if ($workflow->status != 4) { ?>
                                    <div style="display:inline-block;width:100%;overflow-y:auto;">
                                        <ul class="timeline timeline-horizontal" style="">

                                            <li class="timeline-item">
                                                <div class="timeline-badge default"><i class="material-icons text-secondary text-white">receipt_long</i></div>
                                                <div class="timeline-panel">
                                                    <div class="timeline-body">
                                                        <h6 class="text-dark text-sm font-weight-bold mb-0">Sua solicitação</h6>
                                                        <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Criada: <?php echo date("d/m/Y H:i:s", strtotime($workflow->date_created)); ?></p>
                                                        <span class="badge badge-sm bg-gradient-light">Enviada</span>

                                                    </div>
                                                </div>
                                            </li>
                                            <?php
                                            $andamento = $this->Workflow_model->get_fluxos_andamento($workflow->id, $workflow->date_end_client);
                                            foreach ($andamento as $item) {
                                                ?>
                                                <li class="timeline-item">
                                                    <?php if ($item['concluido'] == 1) { ?>
                                                        <div class="timeline-badge success"><i class="material-icons text-secondary text-white">check</i></div>
                                                    <?php } else { ?>
                                                        <div class="timeline-badge info"><i class="material-icons text-secondary text-white">pending</i></div>
                                                    <?php } ?>
                                                    <div class="timeline-panel">
                                                        <div class="timeline-body">
                                                            <h6 class="text-dark text-sm font-weight-bold mb-0"><?php echo $item['setor_name']; ?></h6>
                                                            <?php if (!$item['data_assumido'] && !$item['data_concluido']) { ?>
                                                                <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Recebida: <?php echo date("d/m/Y H:i:s", strtotime($item['date_created'])); ?></p>
                                                            <?php } else { ?>
                                                                <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Assumida: <?php echo date("d/m/Y H:i:s", strtotime($item['data_assumido'])); ?></p>
                                                                <?php if ($item['data_concluido']) { ?>
                                                                    <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Concluída: <?php echo date("d/m/Y H:i:s", strtotime($item['data_concluido'])); ?></p>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            <?php if ($item['concluido'] == 1) { ?>
                                                                <span class="badge badge-sm bg-gradient-success">CONCLUÍDA</span>
                                                            <?php } else { ?>
                                                                <span class="badge badge-sm bg-gradient-info">EM ANDAMENTO</span>
                                                            <?php } ?>


                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                            <?php if ($workflow->date_end_client != '') { ?>
                                                <li class="timeline-item">
                                                    <div class="timeline-badge success"><i class="material-icons text-secondary text-white">verified</i></div>
                                                    <div class="timeline-panel">
                                                        <div class="timeline-body">
                                                            <h6 class="text-dark text-sm font-weight-bold mb-0">Finalizado</h6>
                                                            <p class="text-secondary font-weight-normal text-xs mt-1 mb-0"><?php echo date("d/m/Y H:i:s", strtotime($workflow->date_end_client)); ?></p>

                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } else { ?>
                                                <li class="timeline-item">
                                                    <div class="timeline-badge warning"><i class="material-icons text-secondary text-white">hourglass_empty</i></div>
                                                    <div class="timeline-panel">
                                                        <div class="timeline-body">
                                                            <h6 class="text-dark text-sm font-weight-bold mb-0">Prazo Estimado</h6>
                                                            <p class="text-secondary font-weight-normal text-xs mt-1 mb-0"><?php
                                                                if ($workflow->date_prazo_client and $workflow->date_prazo_client != '0000-00-00 00:00:00' and $workflow->date_prazo_client != '1969-12-31') {
                                                                    echo date("d/m/Y", strtotime($workflow->date_prazo_client));
                                                                } else {
                                                                    echo 'Sem Prazo';
                                                                }
                                                                ?></p>
                                                            <span class="badge badge-sm bg-gradient-warning">EM PROGRESSO</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>


                                        </ul>
                                    </div>
                                <?php } ?>
                                <hr class="horizontal dark mb-4">
                                <div class="row">

                                    <div class="col-lg-4 col-md-6 col-12">
                                        <h6 class="mb-3">Detalhes da Solicitação</h6>
                                        <ul class="list-group">
                                            <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                                <div class="d-flex flex-column">
                                                    <h6 class="mb-3 text-sm">Solicitação #<?php echo $workflow->id; ?></h6>
                                                    <span class="mb-2 text-xs">Tipo: <span class="text-dark font-weight-bold ms-2"><?php echo $workflow->titulo ?></span></span>
                                                    <span class="mb-2 text-xs">Setor Responsável: <span class="text-dark font-weight-bold ms-2"><?php echo $workflow->name ?></span></span>
                                                    <span class="mb-2 text-xs">Data de Início: <span class="text-dark ms-2 font-weight-bold"><?php echo date("d/m/Y H:i:s", strtotime($workflow->date_start)); ?></span></span>
                                                    <span class="text-xs">Data de Finalização: <span class="text-dark ms-2 font-weight-bold"><?php
                                                            if ($workflow->date_end) {
                                                                echo date("d/m/Y H:i:s", strtotime($workflow->date_end));
                                                            } else {
                                                                echo 'AGUARDANDO';
                                                            }
                                                            ?></span></span>
                                                </div>
                                            </li>
                                            <?php if ($workflow->status != 4) { ?>


                                                <?php if (count($requests) > 0) { ?>
                                                    <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="mb-3 text-sm">Dados Complementares</h6>
                                                            <?php
                                                            foreach ($requests as $request) {
                                                                $exist = false;
                                                                ?>
                                                                <?php
                                                                $values = $this->Categorias_campos_model->get_values($request['id'], 'external_request_workflow');
                                                                foreach ($values as $campo) {
                                                                    $exist = true;
                                                                    ?>
                                                                    <span class="mb-2 text-xs"><?php echo $value['nome_campo']; ?>: <span class="text-dark font-weight-bold ms-2"><?php echo get_value('workflow', $campo['value'], $campo['tipo_campo']); ?></span></span>
                                                                <?php } ?>

                                                            <?php } ?>
                                                            <?php if ($exist == false) { ?>
                                                                <span class="mb-2 text-xs">Nada informado...</span>
                                                            <?php } ?>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                                <li class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-3 text-sm">Informações para você:</h6>
                                                        <?php
                                                        foreach ($andamento as $item) {
                                                            $exist = false;
                                                            ?>
                                                            <?php
                                                            $values = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', $item['fluxo_id'], false, true);
                                                            foreach ($values as $value) {
                                                                $exist = true;
                                                                ?>
                                                        <span class="mb-2 text-xs"><?php echo $value['nome_campo']; ?>: <span class="text-dark font-weight-bold ms-2"><?php echo get_value('workflow', $value['value'], $value['tipo_campo']); ?></span></span>
                                                            <?php } ?>

                                                        <?php } ?>
                                                        <?php if ($exist == false) { ?>
                                                            <span class="mb-2 text-xs">Sem dados para você...</span>
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                            <?php } ?>

                                        </ul>
                                    </div>
                                    <div class="col-lg-8 col-12 ms-auto mt-5">

                                        <?php
                                        $campos = [];
                                        $values_info['rel_type'] = 'workflow';
                                        $values_info['campos'] = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', true, false, false);
                                        $this->load->view('portal/categorias_campos/values_info', $values_info);
                                        ?>
                                        <?php
                                        if ($workflow->status == 4) {
                                            $this->load->model('Company_model');
                                            $cancel = $this->Company_model->get_cancel_workflow($workflow->cancel_id);
                                            ?>
                                            <div class="alert alert-secondary text-white" role="alert">
                                                <strong>SOLICITAÇÃO CANCELADA!</strong> <?php
                                                if ($cancel->cancellation) {
                                                    echo $cancel->cancellation;
                                                }
                                                ?>
                                                <?php if ($workflow->obs) { ?>
                                                    <br>Observações: <?php echo $workflow->obs; ?>
                                                <?php } ?>
                                                <?php if ($workflow->date_end) { ?>
                                                    <br>Data: <?php echo date('d/m/Y H:i:s', strtotime($workflow->date_end)); ?>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
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