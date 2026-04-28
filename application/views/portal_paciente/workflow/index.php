<html lang="en" translate="no">
<?php //echo 'entrou'; exit;
?>

<?php $this->load->view('portal_paciente/includes/head'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<body class="g-sidenav-show  bg-gray-200">


    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <?php $this->load->view('portal_paciente/includes/menu'); ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

        <?php $this->load->view('portal_paciente/includes/navbar'); ?>

        <div class="container-fluid px-2 px-md-4">

            <div class="card card-body">

                <div class="row">

                    <div class="col-12 col-md-12 col-xl-12 mt-md-0 position-relative">
                        <div class="card card-plain h-100" id="card-plain-info" style="display: block;">
                            <div class="card-header pb-0 pt-0">
                                <div class="row">
                                    <div class="col-md-8 d-flex align-items-center">
                                        <h6 class="mb-0">Solicitações de Serviço</h6>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <a href="javascript:;">
                                            <button class="btn bg-gradient-info mb-0 mx-auto" onclick="change('add');">NOVA SOLICITAÇÃO</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-plain h-100" id="card-plain-add" style="display: none;">

                            <div class="card-header pb-0 p-3">
                                <div class="row">
                                    <div class="col-md-8 d-flex align-items-center">
                                        <h6 class="mb-0">NOVA SOLICITAÇÃO</h6>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <button class="btn bg-gradient-danger mb-0 mx-auto" onclick="change('');">FECHAR</button>

                                    </div>
                                </div>
                            </div>
                            <?php echo form_open_multipart(base_url('portal_paciente/workflow/add'), array('onsubmit' => "document.getElementById('disabled').disabled = true;", "id" => "workflow_form")); ?>
                            <div class="card-body p-3">
                                <div class="col-12 col-sm-12">
                                    <label for="categoria_id" class="form-label">Categoria</label>
                                    <div class="input-group input-group-outline mb-4">
                                        <select class="form-control" id="categoria_id" name="categoria_id" onchange="escolha(this.value);" required="required">
                                            <option value="" selected disabled>Selecione</option>
                                            <?php foreach ($categorias as $categoria) { ?>
                                                <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['titulo_abreviado']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="trocar">

                                </div>


                            </div>
                            <?php echo form_close(); ?>

                            <hr class="vertical dark">
                        </div>
                    </div>

                </div>
            </div>

            <?php if (count($workflows) > 0) { ?>
                <div class="card mt-3">
                    <div class="card-header pb-0 p-3">
                        <div class="row">
                            <div class="col-md-8 d-flex align-items-center">
                                <h6 class="mb-0">Solicitação de Serviço Anteriores</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-flush" id="datatable-search">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Solicitação</th>
                                        <th>Data de Cadastro</th>
                                        <th>Data de Prazo</th>
                                        <th>Protocolo</th>
                                        <th>Status</th>
                                        <th>Acompanhar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($workflows as $workflow) { ?>
                                        <tr>
                                            <td class="text-sm font-weight-normal"><?php echo $workflow['titulo_abreviado']; ?></td>
                                            <td class="text-sm font-weight-normal"><?php echo date("d/m/Y H:i:s", strtotime($workflow['date_created'])); ?></td>
                                            <td class="text-sm font-weight-normal"><?php echo date("d/m/Y", strtotime($workflow['date_prazo_client'])); ?></td>
                                            <td class="text-sm font-weight-normal"><?php echo $workflow['protocolo']; ?></td>
                                            <td class="text-sm font-weight-normal">
                                                <?php if ($workflow['date_end_client'] == '') { ?>
                                                    <?php $status = get_ro_status($workflow['status']); ?>
                                                    <span class="badge badge-<?php echo $status['class']; ?>"><?php echo $status['label']; ?></span>
                                                <?php } else { ?>
                                                    <span class="badge badge-success">Finalizado</span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-sm font-weight-normal"><a class="btn btn-sm btn-warning mb-0 mx-auto" href="<?php echo base_url(); ?>portal_paciente/workflow/workflow/<?php echo $workflow['id']; ?>">Acompanhar</a></td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            <?php } ?>
        </div>






        <script src="<?php echo base_url(); ?>assets/portal/js/core/popper.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/core/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/perfect-scrollbar.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/smooth-scrollbar.min.js"></script>
        <!-- Kanban scripts -->
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/dragula/dragula.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/jkanban/jkanban.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/leaflet.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/nouislider.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/choices.min.js"></script>

        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/dropzone.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/quill.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/portal/js/plugins/multistep-form.js"></script>
        <!-- Github buttons -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.min.js?v=3.0.2"></script>


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

            function escolha(opcaoValor) {

                if (opcaoValor != "") {
                    $.ajax({
                        type: "GET",
                        url: "<?php echo base_url('portal_paciente/Workflow/campos'); ?>",
                        data: {
                            categoria_id: opcaoValor
                        },
                        success: function(data) {
                            $('#trocar').html(data);
                        }
                    });
                } else {
                    alert('Selecione euma categoria!');
                }
            }

            $(document).ready(function() {
                $('#workflow_form').submit(function(e) {
                    e.preventDefault(); // Prevent the default form submission
                    var uploadForm = $('#workflow_form');
                    var formData = new FormData($('#workflow_form')[0]);
                    // Inicialize um objeto que irá conter o mapeamento de campo de entrada para destino
                    var fieldToTargetMap = {};
                    var fieldValueMap = {};

                    // Percorra cada campo de entrada de arquivo
                    uploadForm.find('input[type="file"]').each(function() {
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
                        success: function(response) {
                            var obj = JSON.parse(response);
                            $('form input[type="file"]').each(function() {
                                var fileInput = $(this);
                                if (fileInput.length > 0) {
                                    var inputName = fileInput.attr('name');
                                    fileInput.attr('type', 'text');
                                    fileInput.val(obj[inputName]);

                                }
                            });


                            // Condition is satisfied, submit the form
                            $('#workflow_form').unbind('submit').submit();

                        }
                    });
                });
            });
        </script>
        <footer class="footer py-4  ">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <script>
                                document.write(new Date().getFullYear())
                            </script>,
                            made with <i class="fa fa-heart"></i> by
                            <a href="" class="font-weight-bold" target="_blank">Sigplus</a>/<a href="" class="font-weight-bold" target="_blank">Unimed Manaus</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="" class="nav-link pe-0 text-muted" target="_blank">Dúvidas Frequentes</a>
                            </li>
                            <li class="nav-item">
                                <a href="" class="nav-link pe-0 text-muted" target="_blank">Sobre Nós</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </main>
</body>

</html>