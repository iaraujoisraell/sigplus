<?php $count = 0; ?>
<div class="col-md-12 mbot15 row">
    <?php foreach ($notes as $note) { ?>
        <div class="col-md-6"  >
            <div class="ticketstaffnotes" >
                <div class="table-responsive">
                    <table >
                        <tbody>
                            <tr>
                                <td>
                                    <span class="bold">
                                        <?php echo staff_profile_image($note['user_created'], array('staff-profile-xs-image')); ?> <a href="<?php echo admin_url('staff/profile/' . $note['user_created']); ?>"><?php echo 'NOTA #' . $note['id'] . ' - ' . get_staff_full_name($note['user_created']); ?>
                                        </a>
                                    </span>
                                    <?php if ($note['user_created'] == get_staff_user_id()) { ?>
                                        <div class="pull-right">
                                            <a href="#" class="btn btn-default btn-icon" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><i class="fa fa-pencil-square-o"></i></a>
                                            <a onclick="delete_note('<?php echo $note['id']; ?>', '<?php echo $rel_type; ?>', '<?php echo $rel_id; ?>');" class="mright10 _delete btn btn-danger btn-icon">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <hr class="hr-10" />
                                    <div data-note-description="<?php echo $note['id']; ?>">
                                        <?php echo check_for_links($note['note']); ?>
                                        <?php 
                                        $this->load->model('Intranet_general_model');
                                        $files = $this->Intranet_general_model->get_files('note', $note['id']);
                                        if(count($files) > 0){
                                        ?>
                                        <div class=" w-100">
                                        <?php foreach($files as $file){?>
                                            <a target="_blank" href="<?php echo $file['url'];?>" class="col-md-12display-block mbot5">
                                                <i class="fa fa-file-o"></i> <?php echo $file['file'];?>                                                            
                                            </a>
                                            <br>
                                        <?php }?>
                                        </div>
                                        <?php }?>
                                        
                                    </div>
                                    <div data-note-edit-textarea="<?php echo $note['id']; ?>" class="hide inline-block full-width">
                                        <?php echo form_open('', array('id' => 'form_note' . $note['id'])); ?>
                                        <textarea name="note" class="form-control" rows="4"><?php echo clear_textarea_breaks($note['note']); ?></textarea>
                                        <input name="id" value="<?php echo $note["id"]; ?>" type="hidden">
                                        <input name="rel_type" value="<?php echo $rel_type; ?>" type="hidden">
                                        <input name="rel_id" value="<?php echo $rel_id; ?>" type="hidden">
                                        <div class="text-right mtop15">
                                            <button type="button" class="btn btn-default" onclick="toggle_edit_note(<?php echo $note['id']; ?>);return false;"><?php echo _l('cancel'); ?></button>
                                            <button type="button" class="btn btn-info" onclick="update_note('<?php echo $note['id'] ?>', '<?php echo $rel_type; ?>', '<?php echo $rel_id; ?>')"><?php echo _l('update_note'); ?></button>
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
        </div>
    <?php
        $count++;
        if ($count % 2 == 0) {
            echo '<div class="clearfix"></div>';
        }
        ?>
    <?php } ?>


</div>