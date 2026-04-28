
<div class="modal fade" id="list_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">Opções da lista (<?php echo $list->list; ?>)</span>
                </h4>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" name="option" id="option" class="form-control" placeholder="Digite o título da opção">
                            <span class="input-group-btn">
                                <button class="btn btn-info p7" onclick="save_list_option();" id="new-categoria-insert"><?php echo 'Add Opção'; ?></button>
                            </span>
                        </div>

                        <div class="clearfix"></div>
                        <input name="list_id" type="hidden" value="<?php echo $list->id; ?>">
                        <br>

                        <?php
                        $table_data = [];
                        array_push($table_data, 'Opção');
                        array_push($table_data, 'Cadastro');
                        array_push($table_data, 'Excluir');
                        render_datatable($table_data, 'option');
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>

            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        var Params = {};
        Params['list_id'] = '[name="list_id"]';

        initDataTable('.table-option', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_list_options', [0], [0], Params, [1, 'desc']);
    });

    function save_list_option() {
        var option = document.getElementById("option").value;

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/add_list_option'); ?>",
            data: {
                option: option,
                list_id: '<?php echo $list->id; ?>'
            },
            success: function (data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
                reload_list_options();
                document.getElementById("option").value = '';
            }
        });
    }

    function reload_list_options() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-option')) {
            $('.table-option').DataTable().destroy();
        }
        Params['list_id'] = '[name="list_id"]';

        initDataTable('.table-option', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_list_options', [0], [0], Params, [1, 'desc']);
    }
    
    function delete_list_option(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/delete_list_option'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                reload_list_options();
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }
</script>