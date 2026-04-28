<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade" id="Doctos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php
                    if ($tipo->rel_type == 'cdc') {
                        echo 'Documento Matriz de: ' . strtoupper($tipo->titulo);
                        ?>
                        <?php
                    } else {
                        echo 'Documentos de: ' . strtoupper($tipo->titulo);
                    }
                    ?>
                </h4>
            </div>
            <div class="modal-body">



                <div class="row">

                    <div class="col-md-12">
                        <a href="#" class="btn btn-info mtop15 mbot10" id="teste" onclick="slideToggle('.usernote<?php echo $tipo->id; ?>');"><?php echo 'ADD Documento' ?></a>
                        <div class="clearfix"></div>
                        <input type="hidden" id="categoria_id" value="<?php echo $tipo->id; ?>" />
                        <input type="hidden" id="rel_type" value="<?php echo $tipo->rel_type; ?>" />

                        <div class="usernote<?php echo $tipo->id; ?> hide row" id="form">
                            <form id="uploadForm" enctype="multipart/form-data">
                                <input type="hidden" name="cat_id" value="<?php echo $tipo->id; ?>" />
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">


                                <div class="form-group col-md-6">
                                    <label for="fileToUpload" class="control-label">Título</label>

                                    <input type="text"  class="form-control" name="titulo" id="titulo">

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="file" class="control-label">Arquivo</label>
                                    <div class="input-group">
                                        <input type="file" data-name_value="DOC-<?php echo $tipo->rel_type; ?>-<?php echo $tipo->id; ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="file" id="file" data-target="assets/intranet/arquivos/categorias_arquivos/">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info  p8-half" type="button"><i class="fa fa-file"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <?php if ($tipo->rel_type != 'cdc') { ?>
                                    <div class="icheck-primary d-inline col-md-12">
                                        <input type="checkbox" id="intranet" name="intranet">
                                        <label for="intranet" class="control-label">
                                            Intranet
                                        </label>
                                        <input type="checkbox" id="portal" name="portal" style="margin-left: 5px;">
                                        <label for="portal" class="control-label">
                                            Portal
                                        </label>

                                    </div>
                                <?php } ?>

                                <div class="col-md-12">
                                    <input class="btn btn-info pull-right mbot15 w-100"  id="btn_salvar" value="Salvar">


                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="container-fluid">

                        <?php
                        $table_data = [];
                        array_push($table_data, 'Titulo');

                        array_push($table_data, 'File');
                        array_push($table_data, 'Cadastro');
                        if ($tipo->rel_type == 'workflow') {
                            array_push($table_data, 'Portal');
                            array_push($table_data, 'Intranet');
                            array_push($table_data, 'Deletar');
                        } else {
                            array_push($table_data, 'Ativo');
                        }

                        render_datatable($table_data, 'doctos');
                        ?>



                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).ready(function () {
    $('#btn_salvar').on('click', function () {
    var uploadForm = $('#uploadForm');
            var formData = new FormData($('#uploadForm')[0]);
            // Inicialize um objeto que irá conter o mapeamento de campo de entrada para destino
            var fieldToTargetMap = {};
            var fieldValueMap = {};
            // Percorra cada campo de entrada de arquivo
            uploadForm.find('input[type="file"]').each(function () {
    var field = $(this);
            var fieldName = field.attr('name');
            var target = field.data('target');
            var value = field.data('name_value');
            fieldToTargetMap[fieldName] = target;
            fieldValueMap[fieldName] = value;
    });
            // Adicione o mapeamento ao FormData
            formData.append('fieldToTargetMap', JSON.stringify(fieldToTargetMap));
            formData.append('fieldValueMap', JSON.stringify(fieldValueMap));
            formData.append('target', '1');
            $.ajax({
            type: 'POST',
                    url: '<?php echo base_url('controle_upload/All.php'); ?>', // Substitua com o URL do seu script PHP
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                    var obj = JSON.parse(response);
                            var titulo = document.getElementById("titulo").value;
<?php if ($tipo->rel_type != 'cdc') { ?>
                        let intranet = document.getElementById('intranet');
                                if (intranet.checked) {
                        var _intranet = 1;
                        } else {
                        var _intranet = 0;
                        }

                        let portal = document.getElementById('portal');
                                if (portal.checked) {
                        var _portal = 1;
                        } else {
                        var _portal = 0;
                        }
<?php } ?>
                    $.ajax({
                    type: 'POST',
                            url: '<?php echo base_url('gestao_corporativa/Categorias_campos/add_docto'); ?>',
                            data: {
                            file: obj,
                                    titulo: titulo,
<?php if ($tipo->rel_type != 'cdc') { ?>
                                portal: _portal,
                                        intranet: _intranet,
<?php } ?>
                            cat_id: '<?php echo $tipo->id; ?>'
                            },
                            success: function (response) {
                            $('#Doctos').modal('hide');
                                    var obj = JSON.parse(response);
                                    alert_float(obj.alert, obj.message);
                            },
                            error: function () {
                            $('#Doctos').modal('hide');
                                    alert('Ocorreu um erro ao enviar o arquivo.');
                                    $('#progress').text('');
                            }
                    });
                    }
            });
    });
    }
    );

    function uploadFile() {
        var fileInput = document.getElementById('fileToUpload');
        var file = fileInput.files[0];
        var formData = new FormData();
        formData.append('fileToUpload', file);
        formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', "<?php echo $this->security->get_csrf_hash(); ?>");
        var cat_id = '<?php echo $tipo->id; ?>';
        formData.append('cat_id', cat_id);
<?php if ($tipo->rel_type != 'cdc') { ?>
            var titulo = document.getElementById('titulo').value;
            formData.append('titulo', titulo);



            let intranet = document.getElementById('intranet');
            if (intranet.checked) {
                var _intranet = 1;
            } else {
                var _intranet = 0;
            }

            let portal = document.getElementById('portal');
            if (portal.checked) {
                var _portal = 1;
            } else {
                var _portal = 0;
            }
            //alert(_portal);

            formData.append('portal', _portal);
            formData.append('intranet', _intranet);
<?php } ?>

        $.ajax({
            url: '<?php echo base_url('gestao_corporativa/Categorias_campos/add_docto'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable) {
                        var percent = Math.round((e.loaded / e.total) * 100);
                        $('#progress').text(percent + '%');
                    }
                });
                return xhr;
            },
            success: function (response) {
                $('#Doctos').modal('hide');
                var obj = JSON.parse(response);
                alert_float(obj.alert, obj.message);
            },
            error: function () {
                $('#Doctos').modal('hide');
                alert('Ocorreu um erro ao enviar o arquivo.');
                $('#progress').text('');
            }
        });
    }
    $(function () {
        var Params = {};
        Params['categoria_id'] = '[id="categoria_id"]';
        Params['rel_type'] = '[id="rel_type"]';
        initDataTable('.table-doctos', '<?php echo base_url(); ?>' + 'gestao_corporativa/Categorias_campos/table_doctos', [0], [0], Params, [1, 'desc']);
    });

    function reload() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-doctos')) {
            $('.table-doctos').DataTable().destroy();
        }
        Params['categoria_id'] = '[id="categoria_id"]';
        Params['rel_type'] = '[id="rel_type"]';
        initDataTable('.table-doctos', '<?php echo base_url(); ?>' + 'gestao_corporativa/Categorias_campos/table_doctos', [0], [0], Params, [1, 'desc']);
    }

    function delete_docto(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Categorias_campos/delete_docto'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                reload();
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }
    function update_active_docto(rowId) {
        const isActive = $("#active_docto" + rowId).is(":checked") ? 1 : 0; // Convert the checkbox state to 1 (active) or 0 (inactive)
        var categoria_id = document.getElementById('categoria_id').value;
        // alert(isActive);
        $.ajax({
            url: '<?php echo base_url('gestao_corporativa/Categorias_campos/update_active_docto') ?>', // Replace with the server-side script URL to handle database updates
            method: "POST",
            data: {id: rowId, active: isActive, categoria_id: categoria_id},
            success: function (data) {
                reload();
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            },
            error: function (xhr, status, error) {
                alert("ERRO AO ATIVAR/INATIVAR", error);
            }
        });
    }

</script>
