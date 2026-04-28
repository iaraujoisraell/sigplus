<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="modal fade" id="Portal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo $tipo->titulo; ?>: Portal Associado
                </h4>
            </div>

            <div class="modal-body">
                <br/>
                <div class="col-md-12">
                    <div class="horizontal-scrollable-tabs">
                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">

                                <li role="presentation" class="active">
                                    <a href="#config" aria-controls="config" role="tab" data-toggle="tab">
                                        Configurações
                                    </a>
                                </li>

                                <li role="presentation" class="">
                                    <a href="#image" aria-controls="image" role="tab" data-toggle="tab">
                                        Banner
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-content mtop15">

                    <div role="config" class="tab-pane active" id="config">
                        <?php echo form_open_multipart(base_url('gestao_corporativa/Categorias_campos/edit'), array('id' => 'edit_form', 'onsubmit' => "document.getElementById('disabled').disabled = true;")); ?>

                        <div class="checkbox checkbox-primary checkbox-inline">

                            <input type="checkbox" name="p_master" value="1"<?php
                            if ($tipo->p_master == 1) {
                                echo 'checked';
                            }
                            ?>>
                            <label for="p_master" data-toggle="tooltip" data-placement="bottom" title="Se você definir essa categoria como master, os usuários poderão acessar o portal com acesso master da intranet.">Permitir Acesso Master</label>
                        </div>
                        <br>
                        <hr class="no-mtop"/>
                        <div class="row">
                            <input name="id" type="hidden" value="<?php echo $tipo->id; ?>">
                            <div class="col-md-6">
                                <?php echo render_input("p_title", 'Titulo Portal', $tipo->p_title, 'text', array('required' => 'true', 'maxlength' => '100')); ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                echo render_select('apicategoria_id', $apis, array('id', 'titulo'), 'Categoria', $tipo->apicategoria_id, array('required' => 'true'));
                                ?>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Descrição:</label>
                                    <textarea class="form-control" rows="2" placeholder="Escreva a saudação inicial..." name="p_salutation" maxlength="200"><?php echo $tipo->p_salutation; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Saudação:</label>
                                    <textarea class="form-control" rows="2" placeholder="Descreva as funcionalidades..." name="p_description" maxlength="500"><?php echo $tipo->p_description; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Mensagem Orientativa(1):</label>
                                    <textarea class="form-control" rows="5" placeholder="Mensagem Orientativa..." name="p_msg" maxlength="300"><?php echo $tipo->p_msg; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Mensagem Orientativa(2):</label>
                                    <textarea class="form-control" rows="5" placeholder="Mensagem Orientativa..." name="p_msg2" maxlength="300"><?php echo $tipo->p_msg2; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn bg-info pull-right" style="color: white;" type="submit" id="disabled"><?php echo _l('edit'); ?></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <div role="image" class="tab-pane" id="image">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <?php echo form_open_multipart('gestao_corporativa/Company/add_assignature', array('id' => 'formImage')); ?>
                                    <div class="col-md-5">

                                        <h4>Assinatura de Email Principal (Imagem)</h4>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Título">

                                        <br>
                                        <input data-name_value="ASSIGNATURE" type="file" name="file" id="file" class="form-control" placeholder="Imagem" data-target="assets/intranet/arquivos/assignature_arquivos/assignature/" accept=".jpg, .jpeg">

                                        <br>
                                        <button class="btn btn-info w-100" id="new-categoria-insert"><?php echo 'Salvar'; ?></button>

                                    </div>
                                    <?php echo form_close(); ?>


                                    <div class="col-md-7">



                                        <?php
                                        $table_data = [];
                                        array_push($table_data, 'Mac');
                                        array_push($table_data, 'Descrição');
                                        array_push($table_data, 'Data');
                                        array_push($table_data, 'Cadastro');
                                        array_push($table_data, 'Ativo');
                                        array_push($table_data, 'Deletar');
                                        render_datatable($table_data, 'assignature');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


            </div>


        </div>
    </div>
</div>
<script>

    $(document).ready(function () {
        $('#formImage').submit(function (e) {
            e.preventDefault();
            var uploadForm = $('#formImage');
            var formData = new FormData($('#formImage')[0]);
            var fieldToTargetMap = {};
            var fieldValueMap = {};
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
            formData.append('target', '0');
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('controle_upload/All.php'); ?>',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    var obj = JSON.parse(response);
                    var file = obj['file'];
                    var title = document.getElementById("title").value;
                    if (file != '') {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('gestao_corporativa/Company/add_assignature'); ?>",
                            data: {
                                title: title,
                                file: file,
                                target: 'assets/intranet/arquivos/assignature_arquivos/assignature/',
                                rel_type: 'assignature'
                            },
                            success: function (data) {
                                reload_assignature();
                                var obj = JSON.parse(data);
                                alert_float(obj.alert, obj.message);
                            }
                        });
                    }
                }
            });
        });
    });
    $(document).ready(function () {

        init_selectpicker();

        var form = document.getElementById('edit_form');

        // Adiciona um event listener para o evento de submit do formulário
        form.addEventListener('submit', function (event) {
            // Previne o comportamento padrão de envio do formulário
            event.preventDefault();
            //alert('aaaaaaaaaa'); exit;

            // Obtém os dados do formulário
            var formData = new FormData(form);

            // Envia os dados via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo base_url('gestao_corporativa/Categorias_campos/edit'); ?>', true); // Substitua 'url_para_o_script_de_processamento.php' pela URL do seu script de processamento no servidor
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    alert_float(response.alert, response.message);
                    document.getElementById('disabled').disabled = false;
                } else {
                    // Se a requisição falhar, mostra uma mensagem de erro
                    alert('ERRO');
                }
            };
            xhr.onerror = function () {
                // Em caso de erro na requisição, mostre uma mensagem de erro
                alert('ERRO');
            };
            xhr.send(formData); // Envia os dados do formulário via AJAX
        });

    });



</script>
