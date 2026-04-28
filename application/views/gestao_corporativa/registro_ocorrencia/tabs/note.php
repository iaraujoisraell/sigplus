<div role="tabpanel" class="tab-pane" id="note">
    <hr class="no-mtop" />
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="note_description"><?php echo _l('ticket_single_note_heading'); ?></label>
                <textarea class="form-control" name="note_description_ro" id="note_description_ro" rows="5"></textarea>
            </div>
            <a class="btn btn-info pull-right" onclick="add_note();"><?php echo _l('ticket_single_add_note'); ?></a>

        </div>
    </div>



    <div class="row" id="refresh_notes">
        <?php
        $this->load->model('Registro_ocorrencia_model');
        $notes = $this->Registro_ocorrencia_model->get_notes($ro->id, 'note');
        if (sizeof($notes) > 0) {
            ?>
            <div class="col-md-12 mbot15">
                <h4 class="bold">Notas Adicionadas</h4>
                
                   
                                <?php foreach ($notes as $note) { ?>
                <div class="ticketstaffnotes">
                     <div class="table-responsive">
                        <table >
                            <tbody>
                                    <tr>
                                        <td>
                                            <span class="bold">
                                                <?php echo staff_profile_image($note['user_created'], array('staff-profile-xs-image')); ?> <a href="<?php echo admin_url('staff/profile/' . $note['user_created']); ?>"><?php echo 'NOTA #' . $note['id'] . ' - ' . get_staff_full_name($note['user_created']); ?>
                                                </a>
                                            </span>
                                            <?php if ($note['user_created'] == get_staff_user_id() || is_admin()) { ?>
                                                <div class="pull-right">
                                                    <a href="#" class="btn btn-default btn-icon" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><i class="fa fa-pencil-square-o"></i></a>
                                                    <a href="<?php echo base_url('gestao_corporativa/Registro_ocorrencia/delete_note/' . $note["id"] . '.' . $ro->id); ?>" class="mright10 _delete btn btn-danger btn-icon">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </div>
                                            <?php } ?>
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
                    </div>
                                <?php } ?>
                            
                
            </div>
        <?php } else { ?>

            <div class="col-md-12 mbot15" style="margin-top: 20px;">
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fa fa-info-circle"></i> Lista de notas do registro!</h5>
                    Não existem notas para este registro.
                </div>
            </div>
        <?php } ?>
    </div>

</div>



