<div class="modal fade" id="view_request" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">Solicitação #<?php echo $request->id; ?></span>
                </h4>
            </div>

            <div class="modal-body">

                <?php echo render_input('departmentid_version', 'Setor Responsável', get_departamento_nome($request->responsible_response), 'text', array('required' => 'true', 'readonly' => 'true')); ?>
                <?php echo render_textarea('description_version', 'Descrição da Solicitação', $request->description, array('disabled' => true), array(), '', 'tinymce'); ?>
                <?php if ($request->created == 0) { ?> 
                    <div class="alert alert-danger alert-dismissible">
                        <p> <i class="icon fa fa-exclamation-circle"></i>  Solicitação  NÃO respondida.</p>
                    </div>
                    <?php
                } else {

                    $id = $request->created;
//echo $id; exit;
                    $documento = $this->Cdc_model->get_documento($id);
                    $total = $this->Cdc_model->get_fluxos_by_docid($id);
                    $processo = $this->Cdc_model->get_fluxo_atual($id);
                    ?>
                    <?php
                    if ($documento->publicado == 1) {
                        $resultado = 100;
                    } else {
                        if ($total) {
                            $ok = 0;
                            $not = 0;
                            foreach ($total as $um) {
                                if ($um['status'] == 1) {
                                    $ok++;
                                } else {
                                    $not++;
                                }
                                $count = count($total);
                                $resultado = ($ok / $count) * 100;
                                $resultado = intval($resultado);
                            }
                        }
                    }
                    ?>
                    <div class="alert alert-success alert-dismissible">
                        <p> <i class="icon fa fa-check"></i> Solicitação respondida.</p>
                    </div>
                    <div class="panel_s" >
                        <div class="panel-heading" >
                            INFORMAÇÕES DO DOCUMENTO SOLICITADO

                        </div>
                        <div class="panel-body">


                            <span class="bold"> Código:</span> <?php echo $documento->codigo;?><br>
                            <span class="bold"> Versão:</span> <?php echo $documento->numero_versao;?><br>
                            <span class="bold"> Usuário Cadastro:</span> <?php echo get_staff_full_name($documento->user_cadastro);?><br>
                            <span id="esconder" class="text-muted"> <?php echo $documento->descricao; ?></span>

                            <br>
                            <?php
                            $campos = [];
                            $values_info['rel_type'] = 'cdc';
                            //print_r($processo); exit;
                            $values_info['campos'] = $this->Categorias_campos_model->get_values($documento->id, 'cdc', '0');
                            $this->load->view('gestao_corporativa/categorias_campos/values_info', $values_info);
                            ?>

                        </div>


                    </div>


                    <div class="row">
                        <div class="col-md-4 text-center project-percent-col mtop10"  >

                            <div class="row">

                                <div class="col-md-12" style="display: flex; justify-content: center; align-items: center;">
                                    <div class="progress-bar relative mtop15" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                        <div class="percentage"><?php
                                            if ($resultado) {
                                                echo $resultado;
                                            } else {
                                                echo '0';
                                            }
                                            ?>%</div>
                                    </div>
                                </div>

                            </div>



                        </div>
                        <div class="col-md-8">

                                <div class="activity-feed">
                                    <?php foreach ($total as $um) { ?>

                                        <div class="feed-item row col-md-12" data-sale-activity-id="">
                                            <div class="date">
                                                <span class="text-has-action" data-toggle="tooltip" data-title="">
                                                    <?php echo $um['fluxo_nome']; ?> (<span class="bold"><?php echo get_staff_full_name($um['staff_id']); ?></span>): 
                                                    <?php
                                                    if ($processo->id == $um['id']) {
                                                        echo '<span class="text-muted" style="color: #ff7070;">PROCESSO ATUAL</span>';
                                                    } elseif ($um['status'] == 1) {
                                                        echo '<span class="text-muted" style="color: #70ffa5" >DOCUMENTO AVALIDADO</span>' . '<br><span class="posttime">' . _d($um['dt_aprovacao']) . '</span><br>';
                                                        $campos = [];
                                                        $values_info['rel_type'] = 'cdc';
                                                        //print_r($processo); exit;
                                                        $values_info['campos'] = $this->Categorias_campos_model->get_values($documento->id, 'cdc', $um['fluxo_id']);
                                                        $this->load->view('gestao_corporativa/categorias_campos/values_info5', $values_info);
                                                    } else {
                                                        echo '<span class="text-muted";">AGUARDANDO PARA AVALIAÇÃO</span>';
                                                    }

                                                    //DT_APROVACAO
                                                    ?>

                                                </span> 
                                            </div>

                                        </div>

                                        <?php
                                    }
                                    ?>
                                </div>
                            <?php }
                            ?>
                        </div>
                    </div>

            </div>

        </div>
    </div>
</div>