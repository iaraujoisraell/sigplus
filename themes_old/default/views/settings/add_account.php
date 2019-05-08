<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_account'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("system_settings/add_account", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label" for="code"><?php echo $this->lang->line("name_account"); ?></label>

                <div
                    class="controls"> <?php echo form_input('name', '', 'class="form-control" id="name" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="name"><?php echo $this->lang->line("description_account"); ?></label>

                <div
                    class="controls"> <?php echo form_input('description', '', 'class="form-control" id="description" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="rate"><?php echo $this->lang->line("opening_balance"); ?></label>

                <div
                    class="controls"> <?php echo form_input('balance', '', 'class="form-control" id="balance" required="required"'); ?> </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_account', lang('add_account'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
