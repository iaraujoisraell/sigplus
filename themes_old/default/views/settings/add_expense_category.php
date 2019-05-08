<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('add_expense_category'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("system_settings/add_expense_category", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <?= lang('category_type', 'code'); ?>
                <?php $pst[''] = '';
                      $pst['DESPESAS'] = lang('expenses');
                      $pst['RECEITAS'] = lang('income');
                         echo form_dropdown('code', $pst, (isset($_POST['code']) ? $_POST['code'] : $Settings->code), 'id="slpayment_status" class="form-control " data-placeholder="' . lang("select") . ' ' . lang("category_type") . '" required="required"   style="width:100%;" '); ?>
                           
                   
              
            </div>

            <div class="form-group">
                <?= lang('category_name', 'name'); ?>
                <?= form_input('name', '', 'class="form-control" id="name" required="required"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?= form_submit('add_expense_category', lang('add_expense_category'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?= form_close(); ?>
</div>
<?= $modal_js ?>