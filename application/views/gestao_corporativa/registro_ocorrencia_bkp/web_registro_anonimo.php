<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo 'Registro Anônimo'; ?></title>

        <link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

        <link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/fontawesome-free/css/all.min.css">

        <link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/summernote/summernote-bs4.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

        <link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/dist/css/adminlte.min.css?v=3.2.0">

    <body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

        <nav class=" navbar navbar-expand navbar-dark">

            <ol class="navbar-nav">
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link"><?php echo $company_name; ?></a>
                </li>
            </ol>

        </nav>



        <div class="">

            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?php echo $categoria->titulo; ?></h1>
                        </div>
                    </div>
                    <?php if ($categoria->orientacoes != '') { ?>

                        <div class="callout callout-info">
                            <h6><i class="icon fa fa-exclamation-triangle"></i> Orientações de <?php echo $company_name; ?></h6>
                            <p><?php echo $categoria->orientacoes; ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>



            <section class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-12">
                            <?php echo form_open($this->uri->uri_string(), array('id' => $form->form_key, 'class' => 'disable-on-submit')); ?>
                            <?php echo form_hidden('hash', $hash); ?>
                            <?php echo form_hidden('categoria_id', $categoria->id); ?>
                            <?php echo form_hidden('empresa_id', $categoria->empresa_id); ?>
                            <div class="card">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Assunto:</label>
                                                <input name="assunto" type="text" class="form-control" placeholder="Escreva o Assunto.." required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">


                                            <div class="form-group">
                                                <label>Data do Ocorrido:</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" ><i class="far fa-calendar-alt"></i></span>
                                                    </div>
                                                    <input type="text" name="date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask value="<?php echo date('d-m-Y'); ?>" required>
                                                </div>


                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label>Priordade:</label>
                                                <select class="form-control" style="width: 100%;" name="priority" required>
                                                    <option value="1" selected="selected">Baixa</option>
                                                    <option value="2">Média</option>
                                                    <option value="3">Alta </option>
                                                </select>
                                            </div>


                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Relato Detalhado:</label>
                                                <textarea class="form-control" rows="10" placeholder="Descreva a Situação..." name="descricao" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="attachments">Anexos</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="attachments" name="attachments[]" multiple>
                                                        <label class="custom-file-label" for="exampleInputFile">Selecionar Anexos</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                    </div>
                                    <div class="row">

                                        <?php
                                        //print_r($campos); exit;
                                        foreach ($campos as $campo) {
                                            ?>
                                            <?php if ($campo['type'] == 'text' || $campo['type'] == 'number' || $campo['type'] == 'date' || $campo['type'] == 'time' || $campo['type'] == 'color') { ?>
                                                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                                                    <label for=""><?php echo $campo['nome']; ?></label>
                                                    <input name="campo_<?php echo $campo['name']; ?>" type="<?php echo $campo['type']; ?>" class="form-control" id="<?php echo $campo['nome']; ?>" placeholder="<?php echo $campo['nome']; ?>" <?php if ($campo['obrigatorio'] == 1) { ?> required <?php } ?>>

                                                </div>
                                            <?php } elseif ($campo['type'] == 'textarea') { ?>
                                                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                                                    <label><?php echo $campo['nome']; ?></label>
                                                    <textarea class="form-control" rows="3" placeholder="<?php echo $campo['nome']; ?> ..." name="campo_<?php echo $campo['name']; ?>" <?php if ($campo['obrigatorio'] == 1) { ?> required <?php } ?>></textarea>
                                                </div>

                                            <?php } elseif ($campo['type'] == 'checkbox') { ?>
                                                <div class="icheck-primary d-inline col-md-<?php echo $campo['tam_coluna']; ?> mt-4">
                                                    <input type="checkbox" id="checkboxPrimary1" name="campo_<?php echo $campo['name']; ?>">
                                                    <label for="checkboxPrimary1">
                                                        <?php echo $campo['nome']; ?>
                                                    </label>
                                                </div>
                                            <?php } elseif ($campo['type'] == 'setores') { ?>
                                                <?php
                                                ?>
                                                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                                                    <label for=""><?php echo $campo['nome']; ?></label>
                                                    <select class="select2 w-100" data-placeholder="Selecione" name="campo_<?php echo $campo['name']; ?>" <?php if ($campo['obrigatorio'] == 1) { ?> required <?php } ?>>
                                                        <option value="" selected disabled>Selecione</option>
                                                        <?php
                                                        foreach ($setores as $dep) {
                                                            ?>
                                                            <option value="<?php echo $dep['departmentid']; ?>"><?php echo $dep['name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } elseif ($campo['type'] == 'funcionarios') { ?>
                                                <?php
                                                ?>
                                                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                                                    <label for=""><?php echo $campo['nome']; ?></label>
                                                    <select class="select2 w-100" data-placeholder="Selecione" name="campo_<?php echo $campo['name']; ?>" <?php if ($campo['obrigatorio'] == 1) { ?> required <?php } ?>>
                                                        <option value="" selected disabled>Selecione</option>
                                                        <?php
                                                        foreach ($staffs as $staff) {
                                                            ?>
                                                            <option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } elseif ($campo['type'] == 'separador') { ?>
                                                <hr size="20" width="100%" align="center" > <h6 style="padding-left: 10px;"><strong><?php echo strtoupper($campo['nome']); ?></strong></h6>  <hr size="20" width="100%" align="center">
                                            <?php } elseif ($campo['type'] == 'multiselect') { ?>
                                                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                                                    <label for=""><?php echo $campo['nome']; ?></label>
                                                    <select class="select2 w-100" multiple="multiple" data-placeholder="Selecione" name="campo_<?php echo $campo['name']; ?>[]" <?php if ($campo['obrigatorio'] == 1) { ?> required="required" <?php } ?>>

                                                        <?php
                                                        $id = $campo['id'];
                                                        $options = $this->db->query("SELECT * from tbl_intranet_categorias_campo_options "
                                                                        . "WHERE   tbl_intranet_categorias_campo_options.campo_id = $id AND  tbl_intranet_categorias_campo_options.deleted = 0  ORDER BY tbl_intranet_categorias_campo_options.id asc")->result_array();
                                                        foreach ($options as $option) {
                                                            ?>
                                                            <option value="<?php echo $option['id']; ?>"><?php echo $option['option']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php } elseif ($campo['type'] == 'select') { ?>
                                                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                                                    <label for=""><?php echo $campo['nome']; ?></label>
                                                    <select class="select2 w-100" data-placeholder="Selecione" name="campo_<?php echo $campo['name']; ?>" <?php if ($campo['obrigatorio'] == 1) { ?> required <?php } ?>>
                                                        <option value="" selected disabled>Selecione</option>
                                                        <?php
                                                        $id = $campo['id'];
                                                        $options = $this->db->query("SELECT * from tbl_intranet_categorias_campo_options "
                                                                        . "WHERE   tbl_intranet_categorias_campo_options.campo_id = $id AND  tbl_intranet_categorias_campo_options.deleted = 0  ORDER BY tbl_intranet_categorias_campo_options.id asc")->result_array();
                                                        foreach ($options as $option) {
                                                            ?>
                                                            <option value="<?php echo $option['id']; ?>"><?php echo $option['option']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                            <?php } elseif ($campo['type'] == 'file') {
                                                ?>

                                                <div class="form-group col-md-<?php echo $campo['tam_coluna']; ?>">
                                                    <label for="campo_<?php echo $campo['name']; ?>"><?php echo $campo['nome']; ?></label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="campo_<?php echo $campo['name']; ?>" name="campo_<?php echo $campo['name']; ?>">
                                                            <label class="custom-file-label" for="campo_<?php echo $campo['name']; ?>">Selecionar Anexo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>



                                    </div>

                                </div>

                                <div class="card-footer">
                                    <strong>&copy; <?php echo date('d/m/Y'); ?> - <?php echo $company_name; ?></strong>
                                    - Registro Anônimo
                                    <div class="float-right d-none d-sm-inline-block">
                                        <button type="submit" class="btn btn-info">Enviar</button>
                                    </div>

                                </div>

                            </div>
                            <?php echo form_close(); ?>
                        </div>

                    </div>



                </div>
            </section>

        </div>


        <script src="<?php echo base_url() ?>assets/lte/plugins/jquery/jquery.min.js"></script>

        <script src="<?php echo base_url() ?>assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

        <script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>

        <script src="<?php echo base_url() ?>assets/lte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>

        <script src="<?php echo base_url() ?>assets/lte/plugins/moment/moment.min.js"></script>
        <script src="<?php echo base_url() ?>assets/lte/plugins/inputmask/jquery.inputmask.min.js"></script>

        <script src="<?php echo base_url() ?>assets/lte/plugins/daterangepicker/daterangepicker.js"></script>

        <script src="<?php echo base_url() ?>assets/lte/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

        <script src="<?php echo base_url() ?>assets/lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

        <script src="<?php echo base_url() ?>assets/lte/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

        <script src="<?php echo base_url() ?>assets/lte/plugins/bs-stepper/js/bs-stepper.min.js"></script>

        <script src="<?php echo base_url() ?>assets/lte/plugins/dropzone/min/dropzone.min.js"></script>

        <script src="<?php echo base_url() ?>assets/lte/dist/js/adminlte.min.js?v=3.2.0"></script>

        <script>
            $(function () {
                //Initialize Select2 Elements
                $('.select2').select2()

                //Initialize Select2 Elements
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })

                //Datemask dd/mm/yyyy
                $('#datemask').inputmask('dd/mm/yyyy', {'placeholder': 'dd/mm/yyyy'})
                //Datemask2 mm/dd/yyyy
                $('#datemask2').inputmask('dd/mm/yyyy', {'placeholder': 'mm/dd/yyyy'})
                //Money Euro
                $('[data-mask]').inputmask()

                //Date picker
                $('#reservationdate').datetimepicker({
                    format: 'L'
                });
                //Date and time picker
                $('#reservationdatetime').datetimepicker({icons: {time: 'far fa-clock'}});
                //Date range picker
                $('#reservation').daterangepicker()
                //Date range picker with time picker
                $('#reservationtime').daterangepicker({
                    timePicker: true,
                    timePickerIncrement: 30,
                    locale: {
                        format: 'MM/DD/YYYY hh:mm A'
                    }
                })
                //Date range as a button
                $('#daterange-btn').daterangepicker(
                        {
                            ranges: {
                                'Today': [moment(), moment()],
                                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                                'This Month': [moment().startOf('month'), moment().endOf('month')],
                                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                            },
                            startDate: moment().subtract(29, 'days'),
                            endDate: moment()
                        },
                        function (start, end) {
                            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
                        }
                )

                //Timepicker
                $('#timepicker').datetimepicker({
                    format: 'LT'
                })

                //Bootstrap Duallistbox
                $('.duallistbox').bootstrapDualListbox()

                //Colorpicker
                $('.my-colorpicker1').colorpicker()
                //color picker with addon
                $('.my-colorpicker2').colorpicker()

                $('.my-colorpicker2').on('colorpickerChange', function (event) {
                    $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
                })

                $("input[data-bootstrap-switch]").each(function () {
                    $(this).bootstrapSwitch('state', $(this).prop('checked'));
                })

            })
            // BS-Stepper Init
            document.addEventListener('DOMContentLoaded', function () {
                window.stepper = new Stepper(document.querySelector('.bs-stepper'))
            })

            // DropzoneJS Demo Code Start
            Dropzone.autoDiscover = false

            // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
            var previewNode = document.querySelector("#template")
            previewNode.id = ""
            var previewTemplate = previewNode.parentNode.innerHTML
            previewNode.parentNode.removeChild(previewNode)

            var myDropzone = new Dropzone(document.body, {// Make the whole body a dropzone
                url: "/target-url", // Set the url
                thumbnailWidth: 80,
                thumbnailHeight: 80,
                parallelUploads: 20,
                previewTemplate: previewTemplate,
                paramName: "file",
                autoQueue: false, // Make sure the files aren't queued until manually added
                previewsContainer: "#previews", // Define the container to display the previews
                clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
            })

            myDropzone.on("addedfile", function (file) {
                // Hookup the start button
                file.previewElement.querySelector(".start").onclick = function () {
                    myDropzone.enqueueFile(file)
                }
            })

            // Update the total progress bar
            myDropzone.on("totaluploadprogress", function (progress) {
                document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
            })

            myDropzone.on("sending", function (file) {
                // Show the total progress bar when upload starts
                document.querySelector("#total-progress").style.opacity = "1"
                // And disable the start button
                file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
            })

            // Hide the total progress bar when nothing's uploading anymore
            myDropzone.on("queuecomplete", function (progress) {
                document.querySelector("#total-progress").style.opacity = "0"
            })

            // Setup the buttons for all transfers
            // The "add files" button doesn't need to be setup because the config
            // `clickable` has already been specified.
            document.querySelector("#actions .start").onclick = function () {
                myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
            }
            document.querySelector("#actions .cancel").onclick = function () {
                myDropzone.removeAllFiles(true)
            }
            // DropzoneJS Demo Code End
        </script>

        <script src="<?php echo base_url() ?>assets/lte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <script>
            $(function () {
                bsCustomFileInput.init();
            });
        </script>
        <script>
            $(function () {
                // Summernote
                $('#summernote').summernote()

                // CodeMirror
                CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                    mode: "htmlmixed",
                    theme: "monokai"
                });
            })
        </script>
    </body>

    <script>

        $(document).ready(function () {
            init_selectpicker();
        });

    </script>
    <script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>

</html>
