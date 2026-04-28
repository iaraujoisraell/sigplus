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
                                        Para: NOTIFICANTE
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
                                <?php
                                $is_atuante = $this->Registro_ocorrencia_model->get_is_atuante($ro->id, $note['for_id']);
                                if ($is_atuante == true) {
                                    ?>
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