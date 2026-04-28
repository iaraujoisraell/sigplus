<html lang="en" translate="no">
    <?php //echo 'entrou'; exit;?>

    <?php $this->load->view('portal/includes/head'); ?>
    <script src="<?php echo base_url(); ?>assets/portal/js/plugins/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <body class="g-sidenav-show  bg-gray-200">


        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <?php $this->load->view('portal/includes/menu'); ?>

        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

            <?php $this->load->view('portal/includes/navbar'); ?>

            <div class="container-fluid px-2 px-md-4">

                <!--<button class="btn bg-gradient-primary mb-0 mx-auto" onclick="material.showSwal('success-message')">Mensagem de sucesso!</button>
                <button class="btn bg-gradient-primary mb-0 mx-auto" onclick="material.showSwal('custom-html')">Info + curtir, descurtir!</button>
                <button class="btn bg-gradient-primary mb-0 mx-auto" onclick="material.showSwal('input-field')">Pergunta!</button>
                <button class="btn bg-gradient-primary mb-0 mx-auto" onclick="material.showSwal('title-and-text')">Imagem!</button>
                <button class="btn bg-gradient-primary mb-0 mx-auto" onclick="material.showSwal('auto-close')">Tempo!</button>
                <button class="btn bg-gradient-primary mb-0 mx-auto" onclick="material.showSwal('warning-message-and-confirmation')">Confirmar ou não!</button>
                <button class="btn bg-gradient-primary mb-0 mx-auto" onclick="material.showSwal('warning-message-and-cancel')">Confirmar com icon!</button>-->

                <div class="card card-body">


                    <div class="row">

                        <div class="col-12 col-md-12 col-xl-12 mt-md-0 position-relative">
                            <div class="card card-plain h-100"  id="card-plain-info" style="display: block;">
                                <div class="card-header pb-0 pt-0">
                                    <div class="row">
                                        <div class="col-md-8 d-flex align-items-center">
                                            <h6 class="mb-0">Agendamento de Consultas</h6>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <a href="javascript:;">
                                                <button class="btn bg-gradient-info mb-0 mx-auto w-100" onclick="change('add');">Agendar Consulta</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-plain h-100"  id="card-plain-add" style="display: none;">

                                <div class="card-header pb-0 pt-0">
                                    <div class="row">
                                        <div class="col-md-8 d-flex align-items-center">
                                            <h6 class="mb-0">Agendar Consulta</h6>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <button class="btn bg-gradient-danger mb-0 mx-auto w-100" onclick="change('');">FECHAR</button>

                                        </div>
                                    </div>
                                </div>
                                <?php echo form_open_multipart(base_url('portal/workflow/add')); ?>
                                <div class="card-body">
                                    <div class="col-12 col-sm-12">
                                        <label for="categoria_id" class="form-label">Especialidades Disponíveis</label>
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" id="categoria_id" name="categoria_id" onchange="escolha(this.value);" required="required">
                                                <option value="" selected disabled>Selecione</option>

                                                <?php foreach ($especialidades as $especialidade) { ?>
                                                    <option value="<?php echo $especialidade->CD_ESPECIALIDADE; ?>"><?php echo $especialidade->DS_ESPECIALIDADE; ?></option>
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

                <?php if (count($my_appointments) > 0) { ?>
                    <div class="table-responsive">
                        <div class="card card-body mt-3">

                            <div class="row">

                                <div class="col-12 col-md-12 col-xl-12 mt-md-0 position-relative">
                                    <div class="card card-plain h-100"  id="card-plain-info" style="display: block; padding-top: 15px;">
                                        <div class="card-header pb-0 pt-0">
                                            <div class="row">
                                                <div class="col-md-8 d-flex align-items-center">
                                                    <h6 class="mb-0">Minhas Consultas</h6>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-flush" id="datatable-search">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Especialidade</th>
                                                            <th>Médico</th>
                                                            <th>Dt Cadastro</th>
                                                            <th>Dt Consulta</th>
                                                            <th>Status</th>
                                                            <th>Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($my_appointments as $appointment) {
                                                            //print_r($appointment);
                                                            //DT_CONFIRMACAO
                                                            //
                                                            ?>
                                                            <tr>
                                                                <td class="text-sm font-weight-normal"><?php echo $appointment['DS_ESPECIALIDADE']; ?></td>
                                                                <td class="text-sm font-weight-normal"><?php echo $appointment['NM_AGENDA']; ?></td>
                                                                <td class="text-sm font-weight-normal"><?php echo $appointment['DT_AGENDAMENTO']; ?></td>
                                                                <td class="text-sm font-weight-normal"><?php echo $appointment['DT_AGENDA'].' '.$appointment['HR_INICIO']; ?></td>
                                                                <td class="text-sm font-weight-normal">

                                                                    <span class="badge badge-warning"><?php echo $appointment['DS_STATUS_AGENDA']; ?></span>
                                                                </td>
                                                                <td class="text-sm font-weight-normal">

                                                                    <p>

                                                                        <?php
                                                                        //echo strtotime(date('Y-m-d', strtotime(str_replace('/', '-', $appointment['DT_AGENDA']))));
                                                                        //echo strtotime(date('Y-m-d'));
                                                                        if (strtotime(date('Y-m-d', strtotime(str_replace('/', '-', $appointment['DT_AGENDA'])))) >= strtotime(date('Y-m-d'))) {
                                                                            ?>
                                                                            <?php if ($appointment['DT_CONFIRMACAO'] == '' and $appointment['DT_CANCELAMENTO'] == '') { ?>
                                                                                <a class="btn btn-sm btn-success mb-0" onclick="consulta('1', '<?php echo $appointment['NR_SEQUENCIA']; ?>');"><i class="material-icons">check</i> Confirmar</a><br>
                                                                                <a class="btn btn-sm btn-danger mb-0 mt-1" onclick="consulta('2', '<?php echo $appointment['NR_SEQUENCIA']; ?>');"><i class="material-icons">close</i> Cancelar</a><br>
                                                                            <?php } elseif ($appointment['DT_CANCELAMENTO']) { ?>
                                                                                <span class="badge badge-danger mb-0">Cancelado</span><br>
                                                                                <span class="mb-0 text-xs font-weight-bold"><?php echo $appointment['DT_CANCELAMENTO'] ?></span><br>
                                                                            <?php } elseif ($appointment['DT_CONFIRMACAO']) { ?>
                                                                                <span class="badge badge-success">Confirmado</span><br>
                                                                                <span class="mb-0 text-xs font-weight-bold mt-1"><?php echo $appointment['DT_CONFIRMACAO'] ?></span><br>
                                                                                <a class="btn btn-sm btn-danger mb-0 mt-1" onclick="consulta('2', '<?php echo $appointment['NR_SEQUENCIA']; ?>');"><i class="material-icons">close</i> Cancelar</a><br>
                                                                            <?php } ?>
                                                                        <?php } ?>

                                                                        <a class="btn btn-sm btn-dark mt-1 mb-0" onclick="consulta_det('<?php echo $appointment['NR_SEQUENCIA']; ?>');" ><i class="material-icons">visibility</i> Detalhes</a>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
            <script src="<?php echo base_url(); ?>assets/portal/js/material-dashboard.js"></script>


            <script>
<?php if ($consulta) { ?>
                                                                                        document.addEventListener('DOMContentLoaded', function () {
    <?php if ($consulta['result'] == true) { ?>
                                                                                                material.showSwal('success-message', '', '<?php echo $consulta['msg']; ?>');
    <?php } else { ?>
                                                                                                material.showSwal('error-message', '', '<?php echo $consulta['msg']; ?>');
    <?php } ?>


                                                                                        });
<?php } ?>


                                                                                    function change(add) {
                                                                                        if (add == 'add') {
                                                                                            document.getElementById('card-plain-add').style.display = 'block';
                                                                                            document.getElementById('card-plain-info').style.display = 'none';
                                                                                        } else {
                                                                                            document.getElementById('card-plain-add').style.display = 'none';
                                                                                            document.getElementById('card-plain-info').style.display = 'block';
                                                                                        }
                                                                                    }

                                                                                    function consulta(option, sequencia) {
                                                                                        if (option == '1') {
                                                                                            var msg = 'Clique em <strong>SIM</strong> se deseja confirmar presença na consulta.';
                                                                                        } else {
                                                                                            var msg = 'Clique em <b>SIM</b> se deseja cancelar a consulta.';
                                                                                        }
                                                                                        material.showSwal('warning-message-and-confirmation', 'Você tem certeza?', msg, '<?php echo base_url() ?>portal/Appointments/confirm_cancel?option=' + option + '&sequencia=' + sequencia);

                                                                                    }

                                                                                    function consulta_det(sequencia) {
                                                                                        $.ajax({
                                                                                            type: "GET",
                                                                                            url: "<?php echo base_url('portal/Appointments/get_appointment'); ?>",
                                                                                            data: {
                                                                                                sequencia: sequencia
                                                                                            },
                                                                                            success: function (data) {
                                                                                               // alert(data);
                                                                                                material.showSwal('custom-html', 'Detalhes do Agendamento', data);
                                                                                            }
                                                                                        });

                                                                                    }

                                                                                    function escolha(opcaoValor) {

                                                                                        if (opcaoValor != "") {
                                                                                            $.ajax({
                                                                                                type: "GET",
                                                                                                url: "<?php echo base_url('portal/Appointments/get_especialidade_vagas'); ?>",
                                                                                                data: {
                                                                                                    cd_especialidade: opcaoValor
                                                                                                },
                                                                                                success: function (data) {
                                                                                                    $('#trocar').html(data);
                                                                                                }
                                                                                            });
                                                                                        } else {
                                                                                            alert('Selecione uma especialidade!');
                                                                                        }
                                                                                    }
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