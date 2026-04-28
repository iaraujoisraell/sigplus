
<!DOCTYPE html>

<html lang="en" translate="no"> 
  
    <?php $this->load->view('portal/includes/head'); ?> <?php //echo 'kskks'; exit;?>
    
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

    <body class="g-sidenav-show  bg-gray-200">


        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

        <?php $this->load->view('portal/includes/menu'); ?>
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">


            <?php $this->load->view('portal/includes/navbar'); ?>

            <div class="container-fluid px-2 px-md-4">

                <div class="card card-body">

                    <div class="row">
                        <div class="row">

                            <div class="col-12 col-md-12 col-xl-12 mt-md-0 mt-1 position-relative">
                                <div class="card card-plain h-100"  id="card-plain-info" style="display: block;">
                                    <div class="card-header pb-0 p-3">
                                        <div class="row">
                                            <div class="col-md-8 d-flex align-items-center">
                                                <h6 class="mb-0"><?php echo strtoupper($tittle); ?></h6>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <a href="javascript:;">
                                                    <button class="btn bg-gradient-info mb-0 mx-auto" onclick="change('add');">NOVO REGISTRO</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="text-sm">
                                            <?php echo $categoria->orientacoes; ?> <br>
                                        </p>

                                    </div>
                                </div>
                                <div class="card card-plain h-100"  id="card-plain-add" style="display: none;">

                                    <div class="card-header pb-0 p-3">
                                        <div class="row">
                                            <div class="col-md-8 d-flex align-items-center">
                                                <h6 class="mb-0">NOVO REGISTRO</h6>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <button class="btn bg-gradient-danger mb-0 mx-auto" onclick="change('');">FECHAR</button>

                                            </div>
                                        </div>
                                    </div>
                                    <?php echo form_open_multipart(base_url('portal/registro_ocorrencia/add'), array('onsubmit' => "document.getElementById('disabled').disabled = true;", "id" => "ro_form")); ?>
                                    <div class="card-body p-3">
                                        <div class="row mt-3">
                                            <input name="categoria_id" id="categoria_id" type="hidden" value="<?php echo $categoria->id; ?>"/>
                                            <div class="col-12 col-sm-6">
                                                <label for="exampleFormControlInput1" class="form-label">Assunto</label>
                                                <div class="input-group input-group-outline ">
                                                    <input name="assunto" id="assunto"  class="multisteps-form__input form-control" type="text" placeholder="Assunto"/>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 mt-3 mt-sm-0">
                                                <label for="exampleFormControlInput1" class="form-label">Data Ocorrido</label>
                                                <div class="input-group input-group-outline ">
                                                    <input name="data_ocorrido" id="data_ocorrido"  class="multisteps-form__input form-control" type="datetime-local" placeholder="Assunto" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-sm-6">
                                                <label for="exampleFormControlInput1" class="form-label">Relato Detalhado</label>
                                                <div class="input-group input-group-outline ">
                                                    <textarea class="form-control" rows="10" placeholder="Descreva..." spellcheck="false" name="descricao" id="descricao" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 mt-sm-3 mt-6">
                                                <div class="col-12 col-sm-12 mt-3">
                                                    <label for="exampleFormControlInput1" class="form-label">Prioridade</label>
                                                    <div class="input-group input-group-outline mb-4">
                                                        <select class="form-control" id="priority" name="priority">
                                                            <option value="1">Baixa</option>
                                                            <option value="2">Media</option>
                                                            <option value="3" selected>Alta</option>
                                                        </select>
                                                    </div>
                                                    <div class="">
                                                        <label for="exampleFormControlInput1" class="form-label">Anexo</label>
                                                        <div class="input-group input-group-outline ">
                                                            <input type="file" class="form-control" name="attachment" data-target="assets/intranet/arquivos/ro_arquivos/" data-name_value="RO-REPORT<?php echo uniqid(); ?>">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if (count($campos) > 0) { ?>
                                                <?php $this->load->view('portal/categorias_campos/campos', array('campos' => $campos)); ?>


                                            <?php } ?>
                                            <div class="button-row d-flex mt-4">
                                                <button class="btn bg-gradient-success ms-auto mb-0" type="submit" id="disabled">Salvar</button>
                                            </div>

                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                    <hr class="vertical dark">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <?php if (count($registros) > 0) { ?>
                    <div class="card mt-3">
                        <div class="card-header pb-0 p-3">
                            <div class="row p-3">
                                <div class="col-md-8 d-flex align-items-center">
                                    <h6 class="mb-0">Registros de (<?php echo $tittle ?>)</h6>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-flush" id="datatable-search">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Assunto</th>
                                        <th>Data ocorrido</th>
                                        <th>Prioridade</th>
                                        <th>Data de Cadastro</th>
                                        <th>Protocolo</th>
                                        <th>Acompanhar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($registros as $ro) { ?>
                                        <tr>
                                            <td class="text-sm font-weight-normal"><?php echo $ro['subject']; ?></td>
                                            <td class="text-sm font-weight-normal"><?php echo date("d/m/Y H:i:s", strtotime($ro['date'])); ?></td>
                                            <td class="text-sm font-weight-normal"><?php
                                                if ($ro['priority'] == 1) {
                                                    echo 'Baixa';
                                                } elseif ($ro['priority'] == 2) {
                                                    echo 'Média';
                                                } else {
                                                    echo 'Alta';
                                                }
                                                ?></td>
                                            <td class="text-sm font-weight-normal"><?php echo date("d/m/Y H:i:s", strtotime($ro['date_created'])); ?></td>
                                            <td class="text-sm font-weight-normal"><?php echo $ro['protocolo']; ?></td>
                                            <td class="text-sm font-weight-normal"><a class="btn btn-sm btn-warning mb-0 mx-auto" href="<?php echo base_url(); ?>portal/registro_ocorrencia/registro?id=<?php echo openssl_encrypt($ro['id'], "aes-256-cbc", 'sigplus', 0, 'sigplus'); ?>">Acompanhar</a></td>
                                        </tr>
    <?php } ?>

                                </tbody>
                            </table>

                        </div>

                    </div>
<?php } ?>
            </div>
            <script src="<?php echo base_url(); ?>assets/portal/js/core/popper.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/portal/js/core/bootstrap.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/portal/js/plugins/perfect-scrollbar.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/portal/js/plugins/smooth-scrollbar.min.js"></script>

            <script src="<?php echo base_url(); ?>assets/portal/js/plugins/dragula/dragula.min.js"></script>
            <script src="<?php echo base_url(); ?>assets/portal/js/plugins/jkanban/jkanban.js"></script>
            <script>
                                                    var win = navigator.platform.indexOf('Win') > -1;
                                                    if (win && document.querySelector('#sidenav-scrollbar')) {
                                                        var options = {
                                                            damping: '0.5'
                                                        }
                                                        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
                                                    }
            </script>

            <script async defer src="https://buttons.github.io/buttons.js"></script>

            <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.min.js?v=3.0.6"></script>
            <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v2b4487d741ca48dcbadcaf954e159fc61680799950996" integrity="sha512-D/jdE0CypeVxFadTejKGTzmwyV10c1pxZk/AqjJuZbaJwGMyNHY3q/mTPWqMUnFACfCTunhZUVcd4cV78dK1pQ==" data-cf-beacon='{"rayId":"7b6c143ec9631af3","version":"2023.3.0","r":1,"token":"1b7cbb72744b40c580f8633c6b62637e","si":100}' crossorigin="anonymous"></script>
            <script>
                                                                function change(add) {
                                                                    if (add == 'add') {
                                                                        document.getElementById('card-plain-add').style.display = 'block';
                                                                        document.getElementById('card-plain-info').style.display = 'none';
                                                                    } else {
                                                                        document.getElementById('card-plain-add').style.display = 'none';
                                                                        document.getElementById('card-plain-info').style.display = 'block';
                                                                    }
                                                                }
                                                                
                                                                $(document).ready(function () {
                                                    $('#ro_form').submit(function (e) {
                                                        e.preventDefault(); // Prevent the default form submission
                                                        var uploadForm = $('#ro_form');
                                                        var formData = new FormData($('#ro_form')[0]);
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
                                                        formData.append('target', '0');
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: '<?php echo base_url('controle_upload/All.php'); ?>', // Substitua com o URL do seu script PHP
                                                            data: formData,
                                                            processData: false,
                                                            contentType: false,
                                                            success: function (response) {
                                                                var obj = JSON.parse(response);
                                                                $('form input[type="file"]').each(function () {
                                                                    var fileInput = $(this);
                                                                    if (fileInput.length > 0) {
                                                                        var inputName = fileInput.attr('name');
                                                                        fileInput.attr('type', 'text');
                                                                        fileInput.val(obj[inputName]);

                                                                    }
                                                                });


                                                                // Condition is satisfied, submit the form
                                                                $('#ro_form').unbind('submit').submit();

                                                            }
                                                        });
                                                    });
                                                });
            </script>

        </main>

    </body>
</html>


<!--
<div class="timeline-block mb-3">
<span class="timeline-step">
<i class="material-icons text-secondary text-white">notifications</i>
</span>
<div class="timeline-content">
<h6 class="text-dark text-sm font-weight-bold mb-0">Order received</h6>
<p class="text-secondary font-weight-normal text-xs mt-1 mb-0">22 DEC 7:20 AM</p>
</div>
</div>-->