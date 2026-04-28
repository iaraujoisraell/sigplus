<div class="modal fade" id="Colaboradores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <input type="hidden" id="departmentid" value="<?php echo $id; ?>" />
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    Colaboradores de: <?php echo $department->name; ?>
                </h4>
            </div>
            <div class="modal-body">
                <?php
                $table_data = array(
                    _l('staff_dt_name'),
                    _l('staff_dt_email'),
                    'Cargo',
                    _l('staff_dt_last_Login'),
                    _l('staff_dt_active'),
                );
                $custom_fields = get_custom_fields('staff', array('show_on_table' => 1));
                foreach ($custom_fields as $field) {
                    array_push($table_data, $field['name']);
                }
                render_datatable($table_data, 'staff');
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        var Params = {};
        Params['departmentid'] = '[id="departmentid"]';

        initDataTable('.table-staff', '<?php echo base_url(); ?>' + 'admin/Staff', [0], [0], Params);
    });
</script>