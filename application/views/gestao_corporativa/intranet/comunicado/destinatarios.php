<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_admin(false); ?>
<?php //print_r($staffs);  ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/dist/css/adminlte.min.css?v=3.2.0">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intra/Comunicado'); ?>"><i class="fa fa-backward"></i> Comunicados Internos </a></li> 
                    <li class=""><?php echo $ci->titulo; ?> </li>
                    <li class=""> Destinatários </li>
                </ol>
            </div>
        </div>

        <div class="col-md-12">
            <div class="panel_s">

                <div class="panel-body">
                    <ol class="breadcrumb">
                        <li class=""> Destinatários <span class="badge bg-primary"><?php echo intval($porcentagem); ?>%</span></li>
                    </ol>
                    <div class="clearfix"></div>
                    <div class="project-overview-open-tasks">
                        <div class="col-md-9">
                            <p class="text-uppercase bold text-dark font-medium">
                                <span><?php echo $porcentagem; ?>% </span> DOS DESTINATÁRIOS ESTÃO CIENTES (<span class="text-muted bold"><?php echo $cientes; ?> / <?php echo $total ?></span>)</p>

                        </div>
                        <div class="col-md-12 mtop5">
                            <div class="progress progress-xs progress-striped active">
                                <div class="progress-bar bg-primary" style="width: <?php echo $porcentagem; ?>%"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="panel_s">

                <div class="panel-body">
                    <?php if (is_array($css)) { ?>
                        <?php if (count($css) > 0) { ?>
                            <button type="button" class="btn btn-outline-info btn-flat" data-toggle="modal" data-target="#modal-xal"><i class="fa fa-copy " ></i>VISUALIZAR OS DESTINATÁRIOS EM CÓPIA</button>
                            <div class="modal fade" id="modal-xal">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">DESTINATÁRIOS EM CÓPIA</h4>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table" style="">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px;">#</th>
                                                        <th>Nome</th>
                                                        <th>Data de Visualização</th>
                                                        <th>Data da Ciência</th>
                                                        <th>Ciente|Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody >
                                                    <?php
                                                    $count = 0;
                                                    foreach ($css as $ciente) {
                                                        $count++;
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $count; ?>.</td>
                                                            <td><?php echo $ciente['firstname'] . ' ' . $ciente['lastname']; ?></td>
                                                            <td ><?php if($ciente['dt_ciente']){ echo date("d/m/Y H:i:s", strtotime($ciente['dt_ciente'])); }  ?></td>
                                                            <td ><?php if($ciente['dt_ciente']){ echo date("d/m/Y H:i:s", strtotime($ciente['dt_read'])); }  ?></td>
                                                            <td>
                                                                <?php if ($ciente['status'] == 1) { ?>
                                                                    <span class="badge bg-primary">
                                                                        <i class="fa fa-check"></i>
                                                                    </span>
                                                                <?php } else { ?>
                                                                    <span class="badge bg-danger">
                                                                        <i class="fa fa-times"></i>
                                                                    </span>
                                                                <?php } ?> <?php if ($ciente['status'] != 1) { ?>| <a type="button" class="btn btn-flat" href="<?php echo base_url('gestao_corporativa/intra/comunicado/delete_send?ids=' . $ciente['id'] . '.' . $ciente['ci_id']); ?>" ><i class="fa fa-trash " ></i></a> <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>



                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>

                                </div>

                            </div><?php
                        }
                    }
                    ?>
                    <button type="button" class="btn btn-outline-info btn-flat" data-toggle="modal" data-target="#modal-destinatarios"><i class="fa fa-users " ></i> VISUALIZAR DESTINATÁRIOS</button>
                    <div class="modal fade" id="modal-destinatarios">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">DESTINATÁRIOS</h4>
                                </div>
                                <div class="modal-body">
                                    <?php if ($staffs_cientes) { ?>
                                        <table class="table" style="">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px;">#</th>
                                                    <th>Nome</th>
                                                    <th>Data de Visualização</th>
                                                    <th>Data de Ciência</th>
                                                    <th>Ciente</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                <?php
                                                $count2 = 0;
                                                foreach ($staffs_cientes as $ciente) {
                                                    $count2++;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $count2; ?>.</td>
                                                        <td><?php echo $ciente['firstname'] . ' ' . $ciente['lastname']; ?></td>
                                                        <td ><?php if($ciente['dt_ciente']){ echo date("d/m/Y H:i:s", strtotime($ciente['dt_ciente'])); }  ?></td>
                                                        <td ><?php if($ciente['dt_ciente']){ echo date("d/m/Y H:i:s", strtotime($ciente['dt_read'])); }  ?></td>
                                                        <td>
                                                            <?php if ($ciente['status'] == 1) { ?>
                                                                <span class="badge bg-primary">
                                                                    <i class="fa fa-check"></i>
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="badge bg-danger">
                                                                    <i class="fa fa-times"></i>
                                                                </span>
                                                            <?php } ?>
                                                        </td>
                                                        
                                                    </tr>
                                                    <?php
                                                }
                                                ?>



                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-ban"></i> Nenhum destinatário tomou ciência desse comunicado!</h5>
                                        </div>


                                    <?php } ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div><!-- comment -->
            </div>
        </div>




        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <ol class="breadcrumb">
                        <li class=""> Editar Destinatários <span class="badge bg-primary"><?php
                                if ($staffs) {
                                    echo count($staffs);
                                }
                                ?></span></li>
                    </ol>
                    <div class="row" style="padding: 5px;">
                        <div class="col-md-12">



                            <div class="col-md-12 " id="destinatarios">
                                <?php echo form_open_multipart('gestao_corporativa/intra/Comunicado/sends_edit'); ?>
                                <input type="hidden" name="id_ci" value="<?php echo $ci->id; ?>">
                                <?php $this->load->view('gestao_corporativa/intranet/comunicado/setor_staff.php'); ?>
                                <div class="col-md-12 mb-4 mt-2">
                                    <button class="btn btn-sm bg-green float-right" type="submit">Salvar</button> 
                                </div>
                                <?php echo form_close(); ?>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</div>


<?php //init_tail();            ?>




