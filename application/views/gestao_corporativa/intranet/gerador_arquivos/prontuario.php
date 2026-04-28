<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12" id="small-table">
                <div class="panel_s project-top-panel">
                    <div class="panel-body _buttons">
                        <div class="row">
                            <div class="col-md-7 project-heading">
                                <h4><a href="<?= admin_url(''); ?>"> <i class="fa fa-home"></i> HOME / </a> <a href="<?= admin_url('appointly/pep') ?>"><i class="fa fa-user"></i> <?php echo $title; ?></a>/ </a> #<?php echo $id_prontuario; ?></h4>

                                <div class="visible-xs">
                                    <div class="clearfix"></div>
                                </div>

                            </div>
                            <div class="col-md-5 text-right">
                                <!-- <button type="button" data-target="#filtro_busca" data-toggle="modal" class="btn btn-default pull-right display-block">
                                    <?php echo 'Localizar Atendimento'; ?>
                                </button>-->



                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel_s project-top-panel">
                    <div class="panel-body _buttons">
                        <div class="row">



                            <div class="col-md-6">
                                <h4><i class="fa fa-user"></i> <?php echo $info_paciente['company'] . ' - ' . _d($info_paciente['dt_nascimento']); ?></h4>
                                <a href="<?= admin_url('appointly/appointments_relatorios/prontuario/' . $id_prontuario )?>" class="btn btn-default mtop15 mbot10" target="_blank">
            <i class="fa fa-print" aria-hidden="true"></i> IMPRIMIR
       </a>

                            </div>




                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="small-table">
                        <div class="panel_s">
                            <div class="">

                            </div>
                            <div class="panel-body">

                                <!-- if estimateid found in url -->
                                <?php echo form_hidden('estimateid', $estimateid); ?>
                                <table class="table dt-table scroll-responsive" data-order-col="3" data-order-type="desc">
                                    <thead>
                                        <tr>
                                            <th>
                                                <?php echo '#Num Atendimento'; ?>
                                            </th>
                                            <th>
                                                <?php echo 'Data'; ?>
                                            </th>
                                            <th>
                                                <?php echo 'Profissional'; ?>
                                            </th>
                                            <th>
                                                <?php echo 'Convenio'; ?>
                                            </th>
                                            <th>
                                                <?php echo 'Tipo Atendimento'; ?>
                                            </th>
                                            <th>
                                                <?php echo 'Registro Medico'; ?>
                                            </th>


                                    </thead>
                                    <tbody>
                                        <?php foreach ($atendimentos as $atend) { ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= admin_url('appointly/appointments/view?appointment_id=' . $atend['id'])  ?>">#<?php echo $atend['id']; ?></a>
                                                </td>
                                                <td>
                                                    <?php echo _d($atend['date']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $atend['nome_profissional']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $atend['convenio']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $atend['tipo_atendimento']; ?>
                                                </td>
                                                <td>
                                                    <?php if ($atend['tipo_grupo_id'] == '3') {   ?>
                                                        <button onclick="registro_medico(<?php echo $atend['id']; ?>)" class="btn btn-danger" title="Registro Cirúrgico">RC</button>
                                                        <?php

                                                        $enf = $this->Evolucao_model->get_notes($atend['id'], 'evolucao_enfermagem');

                                                        if ($enf) { ?>
                                                            <button onclick="registro_enf(<?php echo $atend['id']; ?>)" class="btn btn-success" title="Registro Enfermagem">RE</button>
                                                        <?php } ?>
                                                       
                                                              
                                                    <?php } else { ?>
                                                        <button onclick="registro_medico(<?php echo $atend['id']; ?>)" class="btn btn-info" title="Registro Clínico">RM</button>


                                                    <?php }  ?>
                                                </td>


                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="modal_wrapper"></div>
                    <?php // 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var hidden_columns = [2, 5, 6, 8, 9];
</script>
<?php init_tail(); ?>


<script>
    function registro_medico(id) {
        // alert ("aqui"); exit;
        $("#modal_wrapper").load("<?php echo base_url(); ?>appointly/Pep/modal", {
            slug: 'registro_medico',
            id: id
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#modal_registro_medico').is(':hidden')) {
                $('#modal_registro_medico').modal({
                    show: true
                });
            }
        });

    }

    function registro_enf(id) {
        // alert ("aqui"); exit;
        $("#modal_wrapper").load("<?php echo base_url(); ?>appointly/Pep/modal", {
            slug: 'registro_enf',
            id: id
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#modal_registro_enf').is(':hidden')) {
                $('#modal_registro_enf').modal({
                    show: true
                });
            }
        });

    }
</script>