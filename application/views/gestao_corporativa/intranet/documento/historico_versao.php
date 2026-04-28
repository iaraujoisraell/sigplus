<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_admin(false); ?>
<div class="content">

    <div class="row">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin'); ?>"><i class="fa fa-backward"></i> Cadastros </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin/index/?group=documentos'); ?>"><i class="fa fa-backward"></i> Documentos </a></li> 
                    <li class=""><?php echo $documento->codigo . ' - ' . $documento->titulo; ?> </li>
                    <li class=""> Lista de versões </li>
                </ol>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel_s">

                <div class="panel-body">
                    <h4 class="customer-profile-group-heading"><?php echo $documento->codigo . ' - ' . $documento->titulo; ?></h4>
                    <?php //if ($pode == 1) { ?>
                        <div class="_buttons">
                            <a class="btn btn-info mtop5 mbot10"  href="<?php echo base_url('gestao_corporativa/intra/Documentos/index?id=' . $documento->id); ?>"> <i class="fa fa-plus"></i> NOVA VERSÃO</a>

                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />

                    <?php //} ?>
                    <div class="clearfix"></div>

                    <div class="col-sm-12 col-md-12">

                        <div class="bg-success color-palette" style="padding: 10px;"><span>VERSÃO ATUAL</span></div>
                    </div>


                    <ul class="mailbox-attachments row col-md-12" style="margin-top:10px;">

                        <li style="margin-left: 10px;">
                            <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></span>
                            <div class="mailbox-attachment-info">
                                <a target="_blanck" href="<?php echo base_url(); ?>gestao_corporativa/intra/documentos/see?id=<?php echo $versao_atual->id; ?>" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> <?php echo $versao_atual->codigo; ?></a>
                                <span class="mailbox-attachment-size clearfix mt-1">
                                    <span>Versão: <?php echo $versao_atual->numero_versao; ?><p><?php echo date("d-m-Y", strtotime($versao_atual->data_publicacao)); ?> </p></span>
                                    <a target="_blank" 
                                     href="<?php
                                            if ($versao_atual->pdf_principal == 1) {
                                                if ($versao_atual->publicado) {
                                                    echo (base_url() . 'media' . $versao_atual->pasta_destino . $versao_atual->file);
                                                } else {
                                                    echo base_url("assets/intranet/img/documentos/$versao_atual->file");
                                                }
                                            } else {
                                                echo base_url("gestao_corporativa/intra/Documentos/visualizar_conteudo?id=$versao_atual->id");
                                            }
                                            ?>" 
                                       class="btn btn-default btn-sm float-right"><i class="fa fa-download"></i></a>
                                </span>
                            </div>
                        </li>

                    </ul>
                    <?php if ($versoes_obsoletas) { ?>
                        <div class="col-sm-12 col-md-12">

                            <div class="bg-danger color-palette" style="padding: 10px;"><span>VERSÕES OBSOLETAS</span></div>
                        </div>

                        <ul class="mailbox-attachments row col-md-12" style="margin-top:10px;">

                            <?php
                            foreach ($versoes_obsoletas as $versao) {
                                ?>

                                <li style="margin-left: 10px;">
                                    <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></span>
                                    <div class="mailbox-attachment-info">
                                        <a target="_blanck" href="<?php echo base_url(); ?>gestao_corporativa/intra/documentos/see?id=<?php echo $versao['id']; ?>" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> <?php echo $versao['codigo'] ?></a>
                                        <span class="mailbox-attachment-size clearfix mt-1">
                                            <span>Versão: <?php echo $versao['numero_versao'] ?><p><?php echo date("d-m-Y", strtotime($versao['data_publicacao'])); ?></p></span>
                                            <a target="_blank" 
                                                href="<?php
                                            if ($versao->pdf_principal == 1) {
                                                if ($versao->publicado) {
                                                    echo (base_url() . 'media' . $versao->pasta_destino . $versao->file);
                                                } else {
                                                    echo base_url("assets/intranet/img/documentos/$versao->file");
                                                }
                                            } else {
                                                echo base_url("gestao_corporativa/intra/Documentos/visualizar_conteudo?id=$versao->id");
                                            }
                                            ?>" 
                                               class="btn btn-default btn-sm float-right"><i class="fa fa-download"></i></a>
                                        </span>
                                    </div>
                                </li>
                            <?php } ?>
                            <?php ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="panel_s">

                <div class="panel-body">
                    <h4 class="customer-profile-group-heading">Histórico de alterações</h4>
                    <div class="clearfix"></div>


                    <div class="alert alert-success alert-dismissible">
                        <h5><i class="icon fa fa-check"></i> <strong>Documento Cadastrado!</strong></h5>
                        <p><strong>Objetivo:</strong> <?php echo $historico_cadastro->objetivo; ?></p>
                        <p><strong>Publicado por:</strong> <?php echo $historico_cadastro->firstname . ' ' . $historico_cadastro->lastname; ?></p>
                        <p><strong>Data de cadastro:</strong> <?php echo date("d-m-Y", strtotime($historico_cadastro->date_created)); ?></p>
                    </div>
                    <?php
                    foreach ($historico as $alt):
                        if ($alt['cadastro'] == 1) {
                            ?>
                            <div class="alert alert-success alert-dismissible">
                                <h5><i class="icon fas fa-check"></i> <strong>Versão <?php echo $alt['numero_versao']; ?> Cadastrada!</strong>  </h5>
                                <p><strong>Alteração:</strong> <?php echo $alt['alteracao']; ?></p>
                                <p><strong>Elaborado por:</strong> <?php echo $alt['firstname'] . ' ' . $alt['lastname']; ?></p>
                                <p><strong>Data de cadastro:</strong> <?php echo date("d-m-Y", strtotime($alt['date_created'])); ?></p>
                            </div>
                        <?php } elseif ($alt['cadastro'] == 2) { ?>
                            <div class="col-sm-12 col-md-12">
                                <div class="bg-success color-palette" style="padding: 10px;"><span>Publicado!</span></div>
                            </div>
                        <?php } else { ?>
                            <blockquote>
                                <h3><?php echo $alt['alteracao']; ?> </h3>
                                <small><?php echo $alt['firstname'] . ' ' . $alt['lastname']; ?>, <cite title="Source Title"><?php echo date("d-m-Y", strtotime($alt['date_created'])); ?> (Versão: <?php echo $alt['numero_versao']; ?>)</cite></small>
                            </blockquote>
                        <?php } endforeach; ?>

                </div>
            </div>

        </div>
    </div>



    <div class="col-md-12 hide" id="destinatarios">
        <?php $this->load->view('gestao_corporativa/intranet/documento/setor_staff.php'); ?>


    </div>

</div>


</div>


<?php //init_tail();                ?>
<script src="<?php echo base_url() ?>assets/menu/plugins/jquery/jquery.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/dist/js/adminltee.min.js?v=3.2.0"></script>

<script src="<?php //echo base_url()   ?>assets/menu/dist/js/demoe.js"></script>

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






