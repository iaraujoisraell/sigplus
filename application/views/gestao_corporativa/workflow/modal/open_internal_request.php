
<div class="modal fade" id="open_internal_request" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="this.disabled = true; toggleDiv();"<!--data-dismiss="modal" aria-label="Close" --><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">SOLICITAÇÃO DE PARECER #<?php echo $request_id; ?></span>
                </h4>
            </div>


            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6 border-right">
                        
                        
                        <span class="bold">SUA SOLICITAÇÃO:</span><br><br>
                        
                        <p class="text-muted" style="margin-top: 5px;">
                            <strong style="text-transform: uppercase;  font-weight: bold;">De: </strong> <?php echo get_staff_full_name($request->user_created); ?> - <?php echo date('d/m/Y H:i:s', strtotime($request->date_created)); ?>
                        </p>
                        <p class="text-muted" style="margin-top: 5px;">
                            <strong style="text-transform: uppercase;  font-weight: bold;">Para:</strong> <?php echo get_staff_full_name($request->staffid); ?> <?php
                            if ($request->status == 1) {
                                echo date('d/m/Y H:i:s', strtotime($request->date));
                            }
                            ?>
                        </p><!-- comment --><p class="text-muted" style="margin-top: 5px;">
                            <strong style="text-transform: uppercase;  font-weight: bold;">Descrição:</strong> <?php echo $request->description; ?>
                        </p><!-- comment --><p class="text-muted" style="margin-top: 5px;">
                            <strong style="text-transform: uppercase;  font-weight: bold;">Situação:</strong><?php if ($request->status == 0) { ?>
                                <span class="label label-success">AGUARDANDO</span>
                            <?php } else { ?>
                                <span class="label label-warning">RESPONDIDO</span>
                            <?php } ?><br>
                        </p><br>
                        <?php if ($request->status == 1) { ?>
                            <span class="bold">SUA RESPOSTA: <?php echo date('d/m/Y H:i:s', strtotime($request->date));?></span>
                            <br><br>
                            <?php
                            $campos = [];
                            $values_info['rel_type'] = 'workflow';
                            $values_info['col_md'] = '12';
                            $values_info['campos'] = $this->Categorias_campos_model->get_values($request_id, 'internal_request_workflow');
                            $this->load->view('gestao_corporativa/categorias_campos/values_info3', $values_info);
                            ?>
                        <?php } else { ?>
                            <br>
                            <?php echo form_open_multipart("gestao_corporativa/Workflow/finish_internal_request/" . $workflow->id, array("id" => "workflow-form")); ?>
                            <span class="bold">REPONDER SOLICITAÇÃO:</span>
                            <input name="request_id" value="<?php echo $request_id; ?>" type="hidden">
                            <div class="row" style="padding: 10px;">
                                <div class="col-md-12">
                                    <?php
                                    $campos = $this->Categorias_campos_model->get_categoria_campos($request_id, '0', true, 'internal_request_workflow');

                                    $data['campos'] = $campos;
                                    $data['just_campos'] = true;
                                    $this->load->view('gestao_corporativa/categorias_campos/retorno_categoria', $data);
                                    ?>

                                </div>
                                <button  class="btn btn-success w-100"  type="submit" id="disabled"  data-toggle="tooltip" <?php echo $disable; ?>>
                                    Responder 

                                </button>
                            </div>
                            <?php echo form_close(); ?>
                            
                        <?php } ?>
                            <hr>
                            <?php if (is_array($info_client)) { ?>
                    
                                <div class="col-md-12 before-ticket-message" style="">
                                    <p class=""> 
                                        <?php //print_r($info_client); ?>

                                        <span class="bold">CLIENTE/CARETEIRINHA: </span><?php echo $info_client['NOME_ABREV'] . ' - ' . $info_client['NUMERO_CARTEIRINHA']; ?> <br>
                                        <span class="bold">EMAIL/TELEFONE </span><?php echo $info_client['EMAIL'] . ' - ' . $info_client['TELEFONE']; ?> <br>
                                        <span class="bold">CONTRATANTE:</span> <?php echo $info_client['CONTRATANTE']; ?> <br>
                                        <span class="bold">CPF/CNPJ CONTRATANTE:</span><?php echo $info_client['CPF_CONTRATANTE']; ?> ( <?php echo $info_client['TIPOCONTRATANTE']; ?> ) <br>
                                        <span class="bold">CONTRATO:</span> <?php echo $info_client['CONTRATO']; ?> - <?php echo $info_client['CONTRATACAO']; ?><br>
                                        <span class="bold">CPF:</span> <?php echo $info_client['CPF']; ?><br>
                                        <span class="bold">DATA DE NASCIMENTO:</span> <?php echo $info_client['DATADENASCIMENTO']; ?><br>
                                        <span class="bold">DATA DE ADESÃO:</span> <?php echo $info_client['DATAADESAO']; ?><br>
                                        <span class="bold">DATA DE VALIDADE:</span> <?php echo $info_client['VALIDADE']; ?><br>
                                        <span class="bold">PRODUTO:</span> <?php echo $info_client['PRODUTO']; ?><br>
                                        <span class="bold">ABRANGENCIA:</span> <?php echo $info_client['ABRANGENCIA']; ?><br>
                                        <span class="bold">ACOMODAÇÃO:</span> <?php echo $info_client['ACOMODACAO']; ?><br>
                                        <span class="bold">TITULAR:</span> <?php echo $info_client['TITULAR']; ?><br>
                                        <span class="bold">REDE:</span> <?php echo $info_client['REDE']; ?><br>
                                        <span class="bold">CNS:</span> <?php echo $info_client['CNS']; ?><br>
                                        <span class="bold">SITUAÇÃO:</span> <?php echo $info_client['SITUACAO']; ?><br>


                                    </p>



                                </div>
                         
                <?php } ?>



                    </div>
                    <div class="col-md-6 ">
                        <span class="bold">DETALHES DO WORKFLOW:</span><br>
                        <div class="alert alert-warning alert-dismissible">
                            POR: <?php echo strtoupper(get_staff_full_name($workflow->user_created)); ?> - <?php echo date("d/m/Y", strtotime($workflow->date_created)); ?>
                        </div>

                        <?php
                        $andamento = $this->Workflow_model->get_fluxos_andamento($workflow->id);
                        $campos = [];
                        $values_info['rel_type'] = 'workflow';
                        $values_info['campos'] = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', '0');
                        $this->load->view('gestao_corporativa/categorias_campos/values_info5', $values_info);
                        ?>
                        <br>
                        <?php
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



                                </div>

                                <?php
                                $campos = [];
                                $values_info['rel_type'] = 'workflow';
                                $values_info['campos'] = $this->Categorias_campos_model->get_values($workflow->id, 'workflow', $andamento[$i]['fluxo_id']);
                                if (count($values_info['campos']) > 0) {
                                    ?>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <?php
                                                $this->load->view('gestao_corporativa/categorias_campos/values_info5', $values_info);
                                                ?>
                                            </div>
                                        </div>

                                    </div>
                                <?php } ?>


                            </div>
                        <?php } ?>



                    </div>



                </div>

            </div>
            <div class="modal-footer" id="">

                <button type="button" class="btn btn-default" onclick="this.disabled = true; toggleDiv();">Fechar</button>

            </div>

        </div>

    </div>
</div>
<script>
    $(document).ready(function () {
        init_selectpicker();
    });
</script>