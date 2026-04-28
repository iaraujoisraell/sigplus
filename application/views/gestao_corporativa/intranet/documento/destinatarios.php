<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_admin(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/plugins/fontawesome-free/css/all.min.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/adminltee.min.css?v=3.2.0">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intra/Documentos/list_'); ?>"><i class="fa fa-backward"></i> Documentos </a></li> 
                    <li> <?php echo $documento->codigo . ' - ' . $documento->titulo; ?></li> 
                    <li class=""> Editar Destinatários </li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel_s">

                <div class="panel-body">
                    <ol class="breadcrumb">
                        <li class=""> Cientes <span class="badge bg-primary"><?php echo intval($porcentagem); ?>%</span></li>
                    </ol>
                    <div class="clearfix"></div>

                    <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-primary" style="width: <?php echo $porcentagem; ?>%"></div>
                    </div>

                    <?php if ($staffs_cientes) { ?>
                        <div style="max-height: 300px; overflow: auto">
                            <table class="table" style="">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;">#</th>
                                        <th>Nome</th>
                                        <th>Setor</th>
                                        <th>Ciente</th>
                                        <th>Data de ciente</th>
                                    </tr>
                                </thead>
                                <tbody >
                                    <?php
                                    $sequencia = 1;
                                    foreach ($staffs_cientes as $ciente) {
                                        ?>
                                        <tr>
                                            <td><?php echo $sequencia; ?>.</td>
                                            <td><?php echo $ciente['firstname'] . ' ' . $ciente['lastname']; ?></td>
                                            <td><?php echo $ciente['name']; ?></td>
                                            <td>
                                                <?php if ($ciente['lido'] == 1) { ?>
                                                    <span class="badge bg-primary">
                                                        <i class="fa fa-check"></i>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="badge bg-danger">
                                                        <i class="fa fa-times"></i>
                                                    </span>
                                                <?php } ?>
                                            </td>
                                            <td ><?php echo $ciente['dt_lido']; ?></td>
                                        </tr>
                                        <?php
                                        $sequencia++;
                                    }
                                    ?>



                                </tbody>
                            </table>
                        </div>
<?php } else { ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-ban"></i> Nenhum destinatário tomou ciência desse documento!</h5>
                        </div>


<?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <ol class="breadcrumb">
                        <li class=""> Editar destinatários <span class="badge bg-primary"><?php if ($staffs) {
    echo count($staffs);
} ?></span></li>
                    </ol>
                    <div class="row" style="padding: 5px;">
                        <div class="col-md-12">



                            <div class="col-md-12 " id="destinatarios">
<?php echo form_open_multipart('gestao_corporativa/intra/Documentos/sends_edit'); ?>
                                <input type="hidden" name="id_doc" value="<?php echo $documento->id; ?>">
                                <?php $this->load->view('gestao_corporativa/intranet/documento/setor_staff.php'); ?>
                                <div class="col-md-12 mb-4 mt-2">
                                    <button class="btn btn-sm bg-green float-right" type="submit">Salvar</button> 
                                </div>
<?php echo form_close(); ?>

                            </div>
                            <script>

                                function addClass(id, classe) {
                                    var elemento = document.getElementById(id);
                                    var classes = elemento.className.split(' ');
                                    var getIndex = classes.indexOf(classe);

                                    if (getIndex === -1) {
                                        classes.push(classe);
                                        elemento.className = classes.join(' ');
                                    }
                                }

                                function delClass(id, classe) {
                                    var elemento = document.getElementById(id);
                                    var classes = elemento.className.split(' ');
                                    var getIndex = classes.indexOf(classe);

                                    if (getIndex > -1) {
                                        classes.splice(getIndex, 1);
                                    }
                                    elemento.className = classes.join(' ');
                                }

                            </script>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<?php //init_tail();         ?>
<script src="<?php echo base_url() ?>assets/menu/plugins/jquery/jquery.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/dist/js/adminltee.min.js?v=3.2.0"></script>

<script src="<?php echo base_url() ?>assets/menu/dist/js/demoe.js"></script>






