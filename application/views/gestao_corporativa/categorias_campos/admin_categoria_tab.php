<?php
$vowels = array(".");
$tabela = str_replace($vowels, "", $rel_type);
?>

<div class="row">
    <div class="col-md-12">
        <input value="<?php echo $rel_type; ?>" type="hidden" name="rel_type<?php echo $rel_type; ?>">
        <?php
        $permisson = $tabela;
        if ($tabela == 'ra_atendimento_rapido') {
            $permisson = 'atendimento';
        }
        ?>
        <div class="_buttons">
            <?php if (has_permission_intranet($permisson . '_settings', '', 'create_categoria') || $rel_type == 'api') { ?>
                <a onclick="Update_categoria<?php echo $tabela; ?>('');" class="btn btn-info pull-left" ><?php
                    echo ($rel_type != 'api') ? 'Nova Categoria' : 'Nova API';
                ?></a>
            <?php } ?>

            <label class="checkbox-inline pull-right" style="margin-top: 7px;">
                <input type="checkbox" id="show_inactive_chk_<?php echo $tabela; ?>" onchange="$('#show_inactive_<?php echo $tabela; ?>').val(this.checked ? '1' : '0'); reload_categoria<?php echo $tabela; ?>();">
                Mostrar inativos
            </label>
            <input type="hidden" id="show_inactive_<?php echo $tabela; ?>" name="show_inactive_<?php echo $tabela; ?>" value="0">
        </div>
        <div class="clearfix"></div>

        <hr class="hr-panel-heading" />
        <?php
        $table_data = [];

        array_push($table_data, 'Título');
        if ($rel_type == 'atendimento' || $rel_type == 'ra_atendimento_rapido') {
            array_push($table_data, 'Cadastro');
        }
        if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'cdc') {
            array_push($table_data, 'Responsável');
        }

        if ($rel_type == 'workflow') {
            array_push($table_data, 'Fluxos');
        }

        if ($rel_type == 'r.o') {
            array_push($table_data, 'Atuantes');
        }
        if ($rel_type == 'api') {
            array_push($table_data, 'Descrição');
        }

        if ($rel_type == 'autosservico') {
            array_push($table_data, 'Vinculado a');
            array_push($table_data, 'Orientação');
        }
        if ($rel_type != 'autosservico'  && $rel_type != 'links' && $rel_type != 'links_destaque') {
            array_push($table_data, 'Campos');
        }

        array_push($table_data, 'Ativo');
        array_push($table_data, 'Opções');
        //echo "aqui"; exit;
        render_datatable($table_data, 'categorias' . $tabela);
        ?>
    </div>
</div>
<?php init_tail(); ?>
<script>


    function update_active(rowId) {
        const isActive = $("#active_" + rowId).is(":checked") ? 1 : 0; // Convert the checkbox state to 1 (active) or 0 (inactive)
        //alert(isActive);
        //alert(rowId);
        $.ajax({
            url: '<?php echo base_url('gestao_corporativa/Categorias_campos/update_active') ?>', // Replace with the server-side script URL to handle database updates
            method: "POST",
            data: {id: rowId, active: isActive},
            success: function (data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            },
            error: function (xhr, status, error) {
                alert("ERRO AO ATIVAR/INATIVAR", error);
            }
        });
    }
    $(document).ready(function () {
        $('.table-categorias<?php echo $tabela; ?> tbody').sortable({
            update: function (event, ui) {
                //var inputValue = ui.item.find('#categoria_ordem').val();
                var ordemAtualizada = [];
                $('.table-categorias<?php echo $tabela; ?> tbody tr').each(function () {
                    var inputValor = $(this).find('#categoria_ordem').val();
                    ordemAtualizada.push(inputValor);
                });

                // Enviar os dados reordenados para o arquivo PHP
                $.ajax({
                    url: '<?php echo base_url('gestao_corporativa/Categorias_campos/change_order_table') ?>',
                    method: 'POST',
                    data: {ordem: ordemAtualizada},
                    success: function (data) {
                        var obj = JSON.parse(data);
                        alert_float(obj.alert, obj.message);
                    },
                    error: function (xhr, status, error) {
                        alert("Erro ao atualizar a ordem: " + error);
                    }
                });
            }
        });
    });
    $(function () {
        var Params = {};
        Params['rel_type'] = '[name="rel_type<?php echo $rel_type; ?>"]';
        Params['show_inactive'] = '#show_inactive_<?php echo $tabela; ?>';
        if('<?php echo $rel_type; ?>' == 'links' || '<?php echo $rel_type; ?>' == 'links_destaque'){
            var search = 2;
            var sort = 2;
        } else {
            var search = 4;
            var sort = 4;
        }
        initDataTable('.table-categorias<?php echo $tabela; ?>', '<?php echo base_url(); ?>' + 'gestao_corporativa/Categorias_campos/table_categorias', [search], [sort], Params, [1, 'desc']);
    });


    function Update_categoria<?php echo $tabela; ?>(el) {
      //  alert("aqui"); exit;
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Categorias_campos/modal", {
            slug: 'update_categoria',
            id: el,
            type: '<?php echo $rel_type; ?>'
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#edit_categoria').is(':hidden')) {
                $('#edit_categoria').modal({
                    show: true
                });
            }
        });
    }

    function Campos<?php echo $tabela; ?>(el, in_out = '') {

        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Categorias_campos/modal", {
            slug: 'campos_categoria',
            id: el,
            in_out: in_out,
            type: '<?php echo $rel_type; ?>'
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#Campos').is(':hidden')) {
                $('#Campos').modal({
                    show: true
                });
            }
        });
    }

    function Doctos<?php echo $tabela; ?>(el) {

        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Categorias_campos/modal", {
            slug: 'doctos',
            id: el,
            type: '<?php echo $rel_type; ?>'
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#Doctos').is(':hidden')) {
                $('#Doctos').modal({
                    show: true
                });
            }
        });
    }
    
    function Portal<?php echo $tabela; ?>(el) {

        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Categorias_campos/modal", {
            slug: 'portal',
            id: el,
            type: '<?php echo $rel_type; ?>'
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#Portal').is(':hidden')) {
                $('#Portal').modal({
                    show: true
                });
            }
        });
    }
    
    function duplicate_categoria<?php echo $tabela; ?>(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Categorias_campos/duplicate_tipo'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                reload_categoria<?php echo $tabela; ?>();
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }

    function delete_categoria<?php echo $tabela; ?>(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Categorias_campos/delete_tipo'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                reload_categoria<?php echo $tabela; ?>();
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }



    function reload_categoria<?php echo $tabela; ?>() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-categorias<?php echo $tabela; ?>')) {
            $('.table-categorias<?php echo $tabela; ?>').DataTable().destroy();
        }
        Params['rel_type'] = '[name="rel_type<?php echo $rel_type; ?>"]';
        Params['show_inactive'] = '#show_inactive_<?php echo $tabela; ?>';
        if('<?php echo $rel_type; ?>' == 'links' || '<?php echo $rel_type; ?>' == 'links_destaque'){
            var search = 2;
            var sort = 2;
        } else {
            var search = 4;
            var sort = 4;
        }
        initDataTable('.table-categorias<?php echo $tabela; ?>', '<?php echo base_url(); ?>' + 'gestao_corporativa/Categorias_campos/table_categorias', [search], [sort], Params, [1, 'desc']);
    }


</script>