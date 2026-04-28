<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (isset($client)) { ?>
    <h4 class="customer-profile-group-heading">Workflow's</h4>
    <div class="row">
        <div class="col-md-12">


            <div class="clearfix"></div>
            <div class="mtop15">
                <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc" style="table-layout: fixed;
                       width: 100%;">
                    <thead>
                        <tr>
                            <th >
                                ID
                            </th>
                            <th>
                                CATEGORIA
                            </th>
                            <th>
                                PROTOCOLO VINCULADO
                            </th>
                            <th>
                                PRAZO CLEINTE
                            </th>
                            <th>
                                STATUS
                            </th>
                            <th>
                                DATA
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($workflows as $workflow) { ?>
                            <tr>
                                <td>
                                    <a target="_blank"  href="<?php echo base_url('gestao_corporativa/Workflow/workflow/' . $workflow['id']); ?>">#<?php echo $workflow['id']; ?></a>

                                </td>
                                <td>
                                    <?php
                                    if ($workflow['titulo']) {
                                        echo $workflow['titulo'];
                                    } else {
                                        echo 'Portal do Cliente';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a target="_blank" href="<?php echo base_url('gestao_corporativa/Atendimento/view/' . $workflow['atendimento_id']); ?>"><?php echo $workflow['protocolo']; ?></a>
                                </td>
                                <td>
                                    <?php
                                    if ($workflow['date_prazo_client']) {
                                        echo date('d/m/Y', strtotime($workflow['date_prazo_client']));
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    $status = get_ro_status($workflow['status']);
                                    ?>
                                    <span class="label inline-block" style="border:1px solid <?php echo $status['color'] ?>; color: <?php echo $status['color']; ?>"><?php echo ($status['label']); ?></span>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($workflow['date_created'])); ?> - <?php echo get_staff_full_name($workflow['user_created']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" style="">
                                    <?php
                                    $this->load->model('Workflow_model');
                                    $andamento = $this->Workflow_model->get_fluxos_andamento($workflow['id'], $workflow['date_end_client']);
                                    ?>
                                    <div class="row" style="max-width: 100%;">


                                        <div style="display:inline-block; width:100%; overflow-y:auto; text-align: center; ">
                                            <ul class="timeline timeline-horizontal" >

                                                <li class="timeline-item">
                                                    <div class="timeline-badge default"><i class="fa fa-file-text-o" aria-hidden="true"></i></div>
                                                    <div class="timeline-panel">
                                                        <div class="timeline-body">
                                                            <h6 class="text-dark text-sm font-weight-bold mb-0">Solicitação</h6>
                                                            <p class="text-secondary font-weight-normal text-xs mt-1 mb-0">Criada: <?php echo date("d/m/Y H:i:s", strtotime($workflow['date_created'])); ?></p>
                                                            <span class="badge badge-sm bg-gradient-light">Enviada</span>

                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                                foreach ($andamento as $item) {
                                                    ?>
                                                    <li class="timeline-item">
                                                        <?php if ($item['concluido'] == 1) { ?>
                                                            <div class="timeline-badge success"><i class="fa fa-check-square-o" aria-hidden="true"></i></div>
                                                        <?php } else { ?>
                                                            <div class="timeline-badge info"><i class="fa fa-spinner" aria-hidden="true"></i></div>
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
                                                <?php if ($workflow['status'] == 3) { ?>
                                                    <li class="timeline-item">
                                                        <div class="timeline-badge success"><i class="fa fa-check-square" aria-hidden="true"></i></div>
                                                        <div class="timeline-panel">
                                                            <div class="timeline-body">
                                                                <h6 class="text-dark text-sm font-weight-bold mb-0">Finalizado</h6>
                                                                <p class="text-secondary font-weight-normal text-xs mt-1 mb-0"><?php echo date("d/m/Y H:i:s", strtotime($workflow['date_end'])); ?></p>

                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php } else { ?>
                                                    <li class="timeline-item">
                                                        <div class="timeline-badge warning"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
                                                        <div class="timeline-panel">
                                                            <div class="timeline-body">
                                                                <h6 class="text-dark text-sm font-weight-bold mb-0">Prazo Estimado</h6>
                                                                <p class="text-secondary font-weight-normal text-xs mt-1 mb-0"><?php
                                                                    if ($workflow['date_prazo_client'] and $workflow['date_prazo_client'] != '0000-00-00 00:00:00' and $workflow['date_prazo_client'] != '1969-12-31') {
                                                                        echo date("d/m/Y", strtotime($workflow['date_prazo_client']));
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
                                    </div>   


                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <style>
        /* Timeline */
        .timeline,
        .timeline-horizontal {
            list-style: none;
            padding: 5px;
            position: relative;
        }
        .timeline:before {
            top: 60px;
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
            height: 280px;
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
<?php } ?>


