<?php
$name = array(
    'name' => 'name',
    'id' => 'name',
    'value' => $subcategory->name,
    'class' => 'form-control',
);
$code = array(
    'name' => 'code',
    'id' => 'code',
    'value' => $subcategory->code,
    'class' => 'form-control',
    'required' => 'required',
);
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_subcategory'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/edit_subcategory/" . $id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('update_info'); ?></p>

            <div class="form-group">
                <?php echo lang('main_category', 'category'); ?>
                <div class="controls"> <?php
                    $ct[""] = $this->lang->line("select") . " " . $this->lang->line("main_category");
                    foreach ($categories as $category) {
                        $ct[$category->id] = $category->name;
                    }
                    echo form_dropdown('category', $ct, (isset($_POST['category']) ? $_POST['category'] : $subcategory->category_id), 'class="form-control" id="category"');
                    ?> </div>
            </div>
            <div class="form-group">
                <?php echo lang('category_code', 'category_code'); ?>
                <div class="controls">
                    <?php echo form_input($code); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo lang('category_name', 'category_name'); ?>
                <div class="controls">
                    <?php echo form_input($name); ?>
                </div>
            </div>
            <div class="form-group">
                <?= lang("category_image", "image") ?>
                <input id="image" type="file" data-browse-label="<?= lang('browse'); ?>" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>
            <?php echo form_hidden('id', $id); ?>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_subcategory', lang('edit_subcategory'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
