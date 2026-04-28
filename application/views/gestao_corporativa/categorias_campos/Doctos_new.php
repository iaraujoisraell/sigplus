<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade" id="Doctos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo 'Documentos de: ' . strtoupper($tipo->titulo); ?>
                </h4>
            </div>
            <div class="modal-body">



                <div class="row">

                    <div class="col-md-12">
                        <a href="#" class="btn btn-info mtop15 mbot10" id="teste" onclick="slideToggle('.usernote<?php echo $tipo->id; ?>');"><?php echo 'ADD Documento' ?></a>
                        <div class="clearfix"></div>
                        <input type="hidden" id="categoria_id" value="<?php echo $tipo->id; ?>" />

                        <div class="usernote<?php echo $tipo->id; ?> hide row" id="form">
                            <form id="uploadForm<?php echo $tipo->id; ?>" enctype="multipart/form-data">
                                <?php
                                echo render_input("titulo", 'Titulo', '', 'text', array('required' => 'true'), [], 'col-md-6');
                                ?>
                                <div class="form-group col-md-6">
                                    <label for="fileToUpload" class="control-label">Arquivo</label>
                                    <div class="input-group">
                                        <input type="file" extension="<?php echo str_replace(['.', ' '], '', get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="fileToUpload" id="fileToUpload" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info  p8-half" type="button"><i class="fa fa-file"></i></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="icheck-primary d-inline col-md-12">
                                    <input type="checkbox" id="intranet">
                                    <label for="intranet" class="control-label">
                                        Intranet
                                    </label>
                                    <input type="checkbox" id="portal" style="margin-left: 5px;">
                                    <label for="portal" class="control-label">
                                        Portal
                                    </label>

                                </div>

                                <div class="col-md-12">
                                    <input class="btn btn-info pull-right mbot15 w-100" type="submit" id="btn_salvar" value="Salvar">


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
                        array_push($table_data, 'Portal');
                        array_push($table_data, 'Intranet');
                        array_push($table_data, 'Deletar');
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

const cadForm<?php echo $tipo->id; ?> = document.getElementById("uploadForm<?php echo $tipo->id; ?>");

// Acessa o IF quando existe o formulário com o SELETOR "upload-arquivo"
if (cadForm<?php echo $tipo->id; ?>) {

    // Quando o usuário clicar no botão com submit executa a função
    cadForm<?php echo $tipo->id; ?>.addEventListener("submit", async (e) => {
        // Bloquear o recarregamento da página
        e.preventDefault();
        // Receber o arquivo do formulário
        var arquivo = document.getElementById('fileToUpload').files[0];
        //console.log(arquivo);
        // Validar extensão do arquivo
       
            // Criar o objeto para receber os dados
            var dadosForm = new FormData();
            // Atribuir as informações do arquivo para o objeto que será enviado para o PHP
            dadosForm.append("fileToUpload", arquivo);

            var titulo = document.getElementById('titulo').value;
            dadosForm.append('titulo', titulo);

        var cat_id = '<?php echo $tipo->id; ?>';
        dadosForm.append('cat_id', cat_id);

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

        dadosForm.append('portal', _portal);
        dadosForm.append('intranet', _intranet);
        dadosForm.append('<?php echo $this->security->get_csrf_token_name(); ?>', "<?php echo $this->security->get_csrf_hash(); ?>");

           
            // Enviar os dados para o arquivo PHP responsável pelo upload do arquivo
            const dados = await fetch("<?php echo base_url(); ?>controle_upload/tasks.php?task_id=1", {
                method: "POST",
                body: dadosForm
            });
           // alert('aqui'); exit;
            // Ler os dados retornado do arquivo PHP
            const resposta = await dados.json();
            //console.log(resposta);
            alert(resposta);
            reload();
            //$('#Doctos').modal('hide');

        
    });
}




    function uploadFile_old() {
        var fileInput = document.getElementById('fileToUpload');
        var file = fileInput.files[0];
        var formData = new FormData();
        formData.append('fileToUpload', file);
        formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', "<?php echo $this->security->get_csrf_hash(); ?>");

        var titulo = document.getElementById('titulo').value;
        formData.append('titulo', titulo);

        var cat_id = '<?php echo $tipo->id; ?>';
        formData.append('cat_id', cat_id);

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
        initDataTable('.table-doctos', '<?php echo base_url(); ?>' + 'gestao_corporativa/Categorias_campos/table_doctos', [0], [0], Params, [1, 'desc']);
    });
    
    function reload() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-doctos')) {
            $('.table-doctos').DataTable().destroy();
        }
        Params['categoria_id'] = '[id="categoria_id"]';
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
</script>
