<div class="modal fade" id="Log" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <input type="hidden" id="departmentid" value="<?php echo $id; ?>" />
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    LOG DE ENTRADA/SAÍDA DE COLABORADORES (<?php echo $department->name; ?>)
                </h4>
            </div>
            <div class="modal-body">
                <?php
                $table_data = array(
                    'COLABORADOR',
                    'AÇÃO',
                    'DATA',
                    'IP',
                    
                );
                render_datatable($table_data, 'log');
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
        initDataTable('.table-log', '<?php echo base_url(); ?>' + 'admin/Departments/table_log', [0], [0], Params);
    });
</script>