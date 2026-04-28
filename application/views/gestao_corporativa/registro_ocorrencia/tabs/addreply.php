<div role="tabpanel" class="tab-pane" id="addreply">
    <hr class="no-mtop" />


    <div class="row">

        <div class="mtop15 col-md-5">
            <?php
            $use_knowledge_base = get_option('use_knowledge_base');
            ?>
            <div class="row mbot15">
                <div class="col-md-12">
                    <select data-width="100%" id="for_id" data-live-search="true" class="selectpicker">
                        <option value="todos" selected>TODOS</option>
                        <option value="solicitante" selected>NOTIFICANTE</option>
                        <option value="setor_responsavel" selected>SETOR RESPONSÁVEL</option>
                        <?php
                        $this->load->model('registro_ocorrencia_model');
                        $atuais = $this->registro_ocorrencia_model->get_atuantes_preenchidos_all($ro->id);
                        foreach ($atuais as $destino) {
                            ?>
                            <option value="<?php echo $destino['id_atuante'] ?>"><?php echo $destino['titulo']; ?></option>
                        <?php } ?>

                    </select>
                </div>

            </div>
            <div class="form-group" app-field-wrapper=""><textarea id="answer_nao_duplicado" name="answer_nao_duplicado" class="form-control" rows="5"></textarea></div>
            <div class="row" id="lista3">
                <div class="col-md-12 text-center">
                    <a href="#" class="btn btn-info" onclick="add_answer('3');">
                        <?php echo _l('submit'); ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-7 mbot15">
            <h4 class="bold">Respostas do Registro</h4>
            <div id="refresh_answers">
                <?php
                $this->load->model('Registro_ocorrencia_model');
                $notes = $this->Registro_ocorrencia_model->get_notes($ro->id, 'answer');

                $setor_responsavel = false;
                $this->load->model('Departments_model');
                $staff_department = $this->Departments_model->get_staff_departments(false, true);
                if (in_array($ro->responsavel, $staff_department)) {
                    $setor_responsavel = true;
                }
                if (count($notes) > 0) {
                    ?>
                    <div class="ticketstaffnotes">

                        <?php
                        foreach ($notes as $note) {
                            $is_atuante = false;
                            if ($note['for_id'] != 'todos' && $setor_responsavel == false && $note['user_created'] != get_staff_user_id()) {
                                if ($note['for_id'] == 'solicitante' && $note['user_created'] != get_staff_user_id()) {
                                    continue;
                                } else {
                                    $is_atuante = $this->Registro_ocorrencia_model->get_is_atuante($ro->id, $note['for_id']);
                                    if ($is_atuante == false) {
                                        continue;
                                    }
                                }
                            }
                            ?>
                            <div class="table-responsive">
                                <table >
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="bold">
                                                    <?php echo staff_profile_image($note['user_created'], array('staff-profile-xs-image')); ?> <a href="<?php echo admin_url('staff/profile/' . $note['user_created']); ?>"><?php echo _l(get_staff_full_name($note['user_created'])); ?>


                                                    </a>
                                                    <?php if ($note['for_id'] == 'todos') { ?>
                                                        <span class="ticket-label label label-default inline-block">
                                                            Para: Todos
                                                        </span>
                                                    <?php } elseif ($note['for_id'] == 'solicitante') { ?>
                                                        <span class="ticket-label label label-default inline-block">
                                                            Para: Solicitante
                                                        </span>
                                                    <?php } elseif ($note['for_id'] == 'setor_responsavel') { ?>
                                                        <span class="ticket-label label label-default inline-block">
                                                            Para: Setor Responsável
                                                        </span>
                                                    <?php } else { ?>
                                                        <span class="ticket-label label label-default inline-block">
                                                            Para: <?php echo $note['titulo']; ?>
                                                        </span>
                                                    <?php } ?>
                                                    <?php $is_atuante = $this->Registro_ocorrencia_model->get_is_atuante($ro->id, $note['for_id']); if ($is_atuante == true) { ?>
                                                        <span class="ticket-label label label-info inline-block">
                                                            PARA VOCÊ
                                                        </span>
                                                    <?php } ?>

                                                </span>
                                                <hr class="hr-10" />
                                                <div data-note-description="<?php echo $note['id']; ?>">
                                                    <?php echo check_for_links($note['note']); ?>
                                                </div>
                                                <div data-note-edit-textarea="<?php echo $note['id']; ?>" class="hide inline-block full-width">
                                                    <?php echo form_open(base_url('gestao_corporativa/Registro_ocorrencia/edit_note_ro/' . $ro->id), array('id' => 'new_ro_form')); ?>
                                                    <textarea name="note" class="form-control" rows="4"><?php echo clear_textarea_breaks($note['note']); ?></textarea>
                                                    <input name="note_id" value="<?php echo $note["id"]; ?>" type="hidden">
                                                    <div class="text-right mtop15">
                                                        <button type="button" class="btn btn-default" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><?php echo _l('cancel'); ?></button>
                                                        <button type="submit" class="btn btn-info" ><?php echo _l('update_note'); ?></button>
                                                    </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                                <small class="bold">
                                                    <?php echo _l('ticket_single_note_added', _dt($note['data_created'])); ?>
                                                </small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>

                    </div>
                <?php } else { ?>
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fa fa-info-circle"></i> Nenhuma resposta encontrada!</h5>

                    </div>

                <?php } ?>
            </div>
        </div><!-- </div> -->
    </div>

    <div>






    </div>
</div>
<script>

    function add_answer() {
        //alert('chegou');
        //exit;
        var answer = document.getElementById("answer_nao_duplicado").value;
        var select = document.getElementById("for_id");
        var for_id = select.options[select.selectedIndex].value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/add_note_ro'); ?>",
            data: {
                note: answer,
                registro_id: '<?php echo $ro->id; ?>',
                for_id: for_id,
                rel_type: 'answer'
            },
            success: function (data) {
                $('#refresh_answers').html(data);
                alert_float('success', 'Resposta adicionada!');
            }
        });
        document.getElementById('answer').value = ''; // Limpa o campo
    }

</script>