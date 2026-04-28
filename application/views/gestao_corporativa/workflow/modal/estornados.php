<div class="modal fade" id="estornados" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-lg" role="document">
        <?php //echo form_open("gestao_corporativa/Workflow/cancel/" . $id . '?fluxo_andamento_id=' . $fluxo_andamento_id, array("id" => "workflow-form")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">Informações dos Processos Estornados</span>
                </h4>
            </div>

            <div class="modal-body">

           

            <?php
            $andamento = $this->Workflow_model->get_fluxos_andamento_estornados($workflow->id);
           // print_r($andamento); exit;
            
            if ($workflow->cancel_id == 0 || $workflow->reopen == 1) { ?>
                        <?php
                    //    print_r($andamento); exit;
                        $i = 0;
                        for ($i = 0; $i < count($andamento); $i++) {
                            $classe = 'success';
                            $classe_label = 'success';
                            $info = 'CONCLUÍDO NO PRAZO';
                            if (strtotime($andamento[$i]['data_concluido']) > strtotime($andamento[$i]['data_prazo'])) {
                                $info = 'CONCLUÍDO FORA DO PRAZO';
                                $classe = 'danger';
                                $classe_label = 'danger';
                            }
                            if (!$andamento[$i]['data_concluido']) {
                                $info = 'EM ANDAMENTO';
                                $classe = 'info';
                                $classe_label = 'info';
                                if (strtotime(date('Y-m-d')) > strtotime($andamento[$i]['data_prazo'])) {
                                    $info = 'ATRASADO';
                                    $classe = 'danger';
                                    $classe_label = 'danger';
                                }
                            }
                            $backs = $this->Workflow_model->get_workflow_back($andamento[$i]['id']);
                        ?>
                            <div class="panel_s">
                                <div class="panel-heading">
                                    <?php echo $andamento[$i]['fluxo_sequencia']; ?>° - <?php echo get_departamento_nome($andamento[$i]['department_id']); ?> (<?php echo get_staff_full_name($andamento[$i]['atribuido_a']); ?>)

                                    <?php
                                    if ($fluxo_atual->id == $andamento[$i]['id']) {
                                        echo '<span class="label label-default ">ATUAL</span>';
                                    }
                                    ?>

                                    <?php
                                    if ($fluxo_atual->id == $andamento[$i]['id'] && $atual == true && count($andamento) > 1) {
                                        echo '<a target="_blank" data-toggle="tooltip" data-title="Estornar para fluxo anterior" class="' . (is_mobile() ? ' ' : ' mleft15 ') . ' pull-right single-ticket-status-label" onclick="Back(' . "'" . $workflow->id . "', " . "'" . $andamento[$i]['id'] . "', " . "'" . $andamento[$i - 1]['id'] . "'" . ')">'
                                            . '<i class="fa fa-arrow-circle-up" aria-hidden="true"></i>'
                                            . '</a>';
                                    }
                                    ?>
                                    <?php
                                    if ($andamento[$i]['atribuido_a'] == 0 && $i < count($andamento) - 1) {
                                      /*  echo '<a href="' . base_url('gestao_corporativa/workflow/delete_fluxo/' . $andamento[$i]['id']) . '?workflow_id=' . $andamento[$i]['workflow_id'] . '" data-toggle="tooltip" data-title="Cancelar Duplicado" class="' . (is_mobile() ? ' ' : ' mleft15 ') . ' pull-right single-ticket-status-label delete _delete">'
                                            . '<i class="fa fa-trash" aria-hidden="true"></i>'
                                            . '</a>';*/
                                    }
                                    ?>
                                </div>

                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12 mbot20 before-ticket-message">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-<?php echo $classe; ?> alert-dismissible">
                                                        <h5><i class="icon fa fa-info-circle"></i> <?php
                                                                                                    if ($andamento[$i]['objetivo']) {
                                                                                                        echo $andamento[$i]['objetivo'];
                                                                                                    } else {
                                                                                                        echo 'Objetivo não cadastrado.';
                                                                                                    }
                                                                                                    ?> <span class="ticket-label label label-<?php echo $classe_label; ?> inline-block">
                                                                <?php echo $info; ?>
                                                            </span></h5>
                                                    </div>
                                                    <!--<a href="#" class="btn btn-default btn-xs" onclick="convert_ticket_to_task(14,'ticket'); return false;">Converter para ação</a>-->
                                                    <?php
                                                    if (count($backs) > 0) {
                                                        echo '<hr />';
                                                        echo '<p style="color: red;">ESSE PROCESSO FOI ESTORNADO!!</p>';
                                                        $param = "'" . $andamento[$i]['id'] . "'";
                                                        echo '<button onclick="View_backs(' . $param . ');" style="margin-bottom: 5px;" class="btn btn-default">Visualizar Processos Estornados <i class="fa fa-eye"></i></button>';
                                                    }
                                                    ?>


                                                </div>
                                                <div class="col-md-12">
                                                    <?php
                                                    $campos = [];
                                                    $values_info['rel_type'] = 'workflow';
                                                    $values_info['campos'] = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', $andamento[$i]['fluxo_id']);
                                                    $this->load->view('gestao_corporativa/categorias_campos/values_info', $values_info);
                                                    ?>
                                                </div>
                                                <div class="col-md-12">
                                                    <hr>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="col-md-3">
                                                        <p class="text-uppercase text-muted">Recebido data</p>
                                                        <p class="bold font-medium"><?php echo date("d/m/Y  H:i:s", strtotime($andamento[$i]['date_created'])); ?></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="text-uppercase text-info">Previsto data</p>
                                                        <p class="bold font-medium"><?php echo date("d/m/Y", strtotime($andamento[$i]['data_prazo'])); ?></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="text-uppercase text-success">Assumido data</p>
                                                        <p class="bold font-medium"><?php echo date("d/m/Y  H:i:s", strtotime($andamento[$i]['data_assumido'])); ?></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <p class="text-uppercase text-danger">Concluido data</p>
                                                        <p class="bold font-medium"><?php
                                                                                    if ($andamento[$i]['data_concluido'] && $andamento[$i]['data_concluido'] != '0000-00-00 00:00:00') {
                                                                                        echo date("d/m/Y  H:i:s", strtotime($andamento[$i]['data_concluido']));
                                                                                    } else {
                                                                                        echo 'Não Concluído';
                                                                                    }
                                                                                    ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>
                        <?php } ?>
                    <?php } ?>
            </div>

        </div>
        <?php //echo form_close(); ?>
    </div>
</div>