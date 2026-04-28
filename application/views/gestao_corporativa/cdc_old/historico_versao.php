<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">

    <div class="row">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Cdc/list_'); ?>"><i class="fa fa-backward"></i> CDC </a></li>
                    <li><i class="fa fa-file"></i> <?php echo $cdc->titulo; ?> </li>
                    <li> Histórico de versões </li>
                </ol>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel_s">

                <div class="panel-body">

                    <h4 class="customer-profile-group-heading"><?php echo $cdc->codigo . ' - ' . $cdc->titulo; ?></h4>

                    <div class="_buttons">
                        <a class="btn btn-info mtop5 mbot10"  href="<?php echo base_url('gestao_corporativa/Cdc/index/' . $cdc->id); ?>"> <i class="fa fa-plus"></i> NOVA VERSÃO</a>

                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />

                    <div class="clearfix"></div>

                    <div class="col-sm-12 col-md-12">

                        <div class="bg-success color-palette" style="padding: 10px;"><span>VERSÃO ATUAL</span></div>
                        <br>
                        <div class="panel_s ">
                            <div class="panel-heading">
                                <?php echo $cdc->titulo; ?> - Versão: <?php echo $cdc->numero_versao; ?>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="bold"><?php echo strtoupper($cdc->descricao); ?>  </span> 
                                        <?php
                                        $campos = [];
                                        $values_info['rel_type'] = 'cdc';
                                        //print_r($processo); exit;
                                        $values_info['campos'] = $this->Categorias_campos_model->get_values($cdc->id, 'cdc', '0');
                                        $this->load->view('gestao_corporativa/categorias_campos/values_info5', $values_info);
                                        ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?php echo form_open(base_url('gestao_corporativa/Intranet_general/file_'), array('id' => 'form_draft', 'method' => 'post', 'target' => '_blank')); ?>
                                        <input type="hidden" name="file" value="<?php echo 'arquivos/cdc_arquivos/cdc/' . $cdc->file; ?>">
                                        <input type="hidden" name="name" value="<?php echo $cdc->codigo; ?>">
                                        <?php echo form_close(); ?>
                                        <a href="javascript:void(0);" onclick="document.getElementById('form_draft').submit();">
                                            <div class="wrimagecard wrimagecard-topimage">
                                                <div class="wrimagecard-topimage_header" style="background-color: #f0f5ff; ">
                                                    <center><i class="fa fa-file-pdf-o" style="color: green;"></i></center>
                                                </div>

                                                <div class="wrimagecard-topimage_title">
                                                    <?php echo $cdc->codigo; ?>.pdf
                                                </div>

                                            </div>
                                        </a>
                                        <a target="_blank"  href="<?php echo base_url('gestao_corporativa/cdc/see?id=' . $cdc->id); ?>" class="btn btn-info btn-sm float-right"><i class="fa fa-info-circle"></i></a>
                                        <a  target="_blank"  href="<?php echo base_url('gestao_corporativa/cdc/destinatarios/' . $cdc->id); ?>" class="btn btn-success btn-sm float-right"><i class="fa fa-users"></i></a>
                                    </div>
                                    <div class="col-md-5">
                                        <span class="bold">USER CADASTRO: </span> <?php echo get_staff_full_name($cdc->user_cadastro); ?><br>
                                        <span class="bold">DATA CADASTRO: </span> <?php echo _d($cdc->data_cadastro) ?><br>
                                        <span class="bold">CÓDIGO: </span> <?php echo $cdc->codigo; ?><br>
                                        <span class="bold">CATEGORIA: </span> <?php echo $cdc->nome_categoria; ?><br>
                                        <span class="bold">DEPARTAMENTO: </span> <?php echo get_departamento_nome($cdc->setor_id); ?><br>
                                        <span class="bold">PUBLICAÇÃO: </span> <?php echo _d($cdc->data_publicacao) ?><br>
                                        <span class="bold">VALIDADE: </span> <?php echo $cdc->validade; ?> MESES
                                        <?php if ($versao['reason']) { ?>
                                            <br><span class="bold">MOTIVO: </span> <?php echo $cdc->reason; ?>
                                        <?php } ?>
                                    </div>


                                </div>


                            </div>
                        </div>
                    </div>



                    <?php if ($olds) { ?>
                        <div class="col-sm-12 col-md-12">

                            <div class="bg-danger color-palette" style="padding: 10px;"><span>VERSÕES OBSOLETAS</span></div>
                        </div>

                        <div class="col-sm-12 col-md-12">
                            <ul class="row " style="margin-top:10px;">

                                <?php
                                foreach ($olds as $versao) {
                                    ?>
                                    <?php echo form_open(base_url('gestao_corporativa/Intranet_general/file_'), array('id' => 'form_draft'.$versao['id'], 'method' => 'post', 'target' => '_blank')); ?>
                                    <input type="hidden" name="file" value="<?php echo 'arquivos/cdc_arquivos/cdc/' . $versao['file']; ?>">
                                    <input type="hidden" name="name" value="<?php echo $versao['codigo']; ?>">
                                    <?php echo form_close(); ?>

                                    <li class="col-md-6">
                                        <div class="panel_s ">
                                            <div class="panel-heading">
                                                <?php echo $versao['titulo']; ?> - Versão: <?php echo $versao['numero_versao']; ?> 
                                                <a target="_blank"  href="<?php echo base_url('gestao_corporativa/cdc/see?id=' . $versao['id']); ?>" class="btn btn-info btn-sm float-right"><i class="fa fa-info-circle"></i></a>
                                                <button  onclick="toggleClass('users<?php echo $versao['id']; ?>', 'hide');" class="btn btn-success btn-sm float-right"><i class="fa fa-users"></i></button>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <a href="javascript:void(0);" onclick="document.getElementById('form_draft<?php echo $versao['id']; ?>').submit();">
                                                        <div class="wrimagecard wrimagecard-topimage" style="display: inline-block;">
                                                            <div class="wrimagecard-topimage_header" style="background-color: #f0f5ff; ">
                                                                <center><i class="fa fa-file-pdf-o" style="color: red;"></i></center>
                                                            </div>

                                                            <div class="wrimagecard-topimage_title">
                                                                <?php echo $versao['codigo']; ?>.pdf
                                                            </div>

                                                        </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <span class="bold">USER CADASTRO: </span> <?php echo get_staff_full_name($versao['user_cadastro']); ?><br>
                                                        <span class="bold">DATA CADASTRO: </span> <?php echo _d($versao['data_cadastro']) ?><br>
                                                        <span class="bold">CÓDIGO: </span> <?php echo $versao['codigo']; ?><br>
                                                        <span class="bold">CATEGORIA: </span> <?php echo $versao['cat']; ?><br>
                                                        <span class="bold">DEPARTAMENTO: </span> <?php echo get_departamento_nome($versao['setor_id']); ?><br>
                                                        <span class="bold">PUBLICAÇÃO: </span> <?php echo _d($versao['data_publicacao']) ?><br>
                                                        <span class="bold">VALIDADE: </span> <?php echo $versao['validade']; ?> MESES
                                                        <?php if ($versao['reason']) { ?>
                                                            <br><span class="bold">MOTIVO: </span> <?php echo $versao['reason']; ?>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="col-md-12 hide" id="users<?php echo $versao['id']; ?>">
                                                        <?php $viewed = $this->Intranet_general_model->get_send($versao['id'], 'cdc', true); ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover expenses-report" id="expenses-report-table">
                                                                <thead>

                                                                    <tr> 

                                                                        <th class="bold">COLABORADOR</th>
                                                                        <th class="bold">STATUS</th>
                                                                        <th class="bold">DATA</th>


                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if (count($viewed) == 0) {
                                                                        echo '<td class="bold bg-odd" colspan="3"> SEM VISUALIZAÇÕES</td>';
                                                                    }
                                                                    foreach ($viewed as $view) {
                                                                        ?>
                                                                        <tr class="">
                                                                            <td class="bold bg-odd"> <?php echo get_staff_full_name($view['destino']); ?></td>
                                                                            <td class="bold"> <span class="ticket-label label label-success inline-block">
                                                                                    CIENTE
                                                                                </span></td>
                                                                            <td> <?php echo _d($view['dt_read']); ?></td>


                                                                        </tr>
                                                                    <?php }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>
                                    </li>

                                <?php } ?>
                                <?php ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="panel_s">

                <div class="panel-body">
                    <h4 class="customer-profile-group-heading">Histórico de alterações e responsabilidades</h4>
                    <div class="clearfix"></div>

                    <?php $flows = $this->Cdc_model->get_fluxos_by_docid($cdc->id); ?>
                    <blockquote>

                        <h5><?php if ($cdc->reason) { ?>
                                Motivo: <?php echo $cdc->reason; ?>
                            <?php } elseif ($cdc->id_principal == 0) { ?>
                                <?php echo $cdc->descricao; ?>
                            <?php } ?></h5>
                        <small>
                            <cite title="Source Title">
                                <?php echo _d($cdc->data_cadastro); ?> (Versão: <?php echo $cdc->numero_versao; ?>)
                            </cite>
                        </small>
                        <div class="alert alert-warning alert-dismissible" style="">
                            <?php if (count($flows) == 0) { ?>
                                <span class="bold"> <i class="fa fa-info-circle"></i> DOCUEMNTO CADASTRADO AUTOMATICAMENTE!</span>
                                <?php
                            } else {
                                foreach ($flows as $flow) {
                                    ?>
                                    <span class="bold"><?php echo $flow['fluxo_nome']; ?>: <?php echo get_staff_full_name($flow['staff_id']); ?> - <?php echo _d($flow['dt_aprovacao']); ?></span><br>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </blockquote>
                    <?php
                    foreach ($olds as $alt):
                        $flows = $this->Cdc_model->get_fluxos_by_docid($alt['id']);
                        ?>

                        <blockquote >
                            <h5><?php if ($alt['reason']) { ?>
                                    Motivo: <?php echo $alt['reason']; ?>
                                <?php } elseif ($alt['id_principal'] == 0) { ?>
                                    <?php echo $alt['descricao']; ?>
                                <?php } ?></h5>
                            <small> <cite title="Source Title"><?php echo _d($alt['data_cadastro']); ?> (Versão: <?php echo $alt['numero_versao']; ?>)</cite></small>
                            <div class="alert alert-warning alert-dismissible" >
                                <?php foreach ($flows as $flow) { ?>
                                    <span class="bold"><?php echo $flow['fluxo_nome']; ?>: <?php echo get_staff_full_name($flow['staff_id']); ?> - <?php echo _d($flow['dt_aprovacao']); ?></span><br>
                                <?php } ?>
                            </div>
                        </blockquote>


                    <?php endforeach; ?>

                </div>

            </div>

        </div>
    </div>


</div>


</div>


<?php //init_tail();                      ?>
<script src="<?php echo base_url() ?>assets/menu/plugins/jquery/jquery.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/dist/js/adminltee.min.js?v=3.2.0"></script>

<script src="<?php //echo base_url()                                ?>assets/menu/dist/js/demoe.js"></script>

<script>

                                                    function toggleClass(id, classe) {
                                                        var elemento = document.getElementById(id);
                                                        var classes = elemento.className.split(' ');
                                                        var getIndex = classes.indexOf(classe);

                                                        if (getIndex === -1) {
                                                            classes.push(classe);  // Adiciona a classe se não estiver presente
                                                        } else {
                                                            classes.splice(getIndex, 1);  // Remove a classe se estiver presente
                                                        }

                                                        elemento.className = classes.join(' ');
                                                    }
</script>
<style>
    .wrimagecard{
        margin-top: 0;
        margin-bottom: 1.5rem;
        text-align: left;
        position: relative;
        background: #fff;
        box-shadow: 12px 15px 20px 0px rgba(46,61,73,0.15);
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    .wrimagecard .fa{
        position: relative;
        font-size: 70px;
    }
    .wrimagecard-topimage_header{
        padding: 20px;
    }
    a.wrimagecard:hover, .wrimagecard-topimage:hover {
        box-shadow: 2px 4px 8px 0px rgba(46,61,73,0.2);
    }
    .wrimagecard-topimage a {
        width: 100%;
        height: 100%;
        display: block;
    }
    .wrimagecard-topimage_title {
        padding: 15px 15px;
        padding-bottom: 0.75rem;
        position: relative;
    }
    .wrimagecard-topimage a {
        border-bottom: none;
        text-decoration: none;
        color: #525c65;
        transition: color 0.3s ease;
    }
</style>








