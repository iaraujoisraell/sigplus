<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head_intranet_admin(false); ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/plugins/fontawesome-free/css/all.min.css">

<link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.min.css">



<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/adminltee.min.css?v=3.2.0">

<div class="content">

    <div class="row">

        <div class="col-md-12">

            <div>

                <ol class="breadcrumb">

                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 

                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin'); ?>"><i class="fa fa-backward"></i> Cadastros </a></li> 

                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin/index/?group=documentos'); ?>"><i class="fa fa-backward"></i> Documentos </a></li> 

                    <li class=""><?php echo $documento->codigo . ' - ' . $documento->titulo; ?> </li>

                </ol>

            </div>

        </div>

        <div class="col-md-7">

            <div class="panel_s">

                <div class="panel-body">

                    <div class="clearfix"></div>

                    <ol class="breadcrumb">
                        <li class=""><strong><?php echo $documento->titulo; ?> </strong></li>
                    </ol>

                    <div class="row" style="padding: 5px;">
                        <div class="col-md-12">
                            <div class="callout callout-info">
                                <h5>Objetivo: <a id="pencil" type="button" onclick="delClass('objetivo', 'hide'); addClass('pencil', 'hide'); delClass('closee', 'hide'); addClass('esconder', 'hide');" class="btn"><i class="fa fa-pencil-square-o"></i></a>
                                    <a id="closee" type="button" onclick="delClass('esconder', 'hide'); addClass('objetivo', 'hide'); addClass('closee', 'hide'); delClass('pencil', 'hide');" class="btn hide"><i class="fa fa-check"></i></a></h5>
                                <textarea class="form-control hide" id="objetivo" name="objetivo" rows="7" onkeydown="editar_objetivo();" onkeyup="editar_objetivo();" onkeypress="editar_objetivo();"><?php echo $documento->descricao; ?></textarea>

                                <p id="esconder"><?php echo $documento->descricao; ?></p>                                

                            </div>
                            <h5 style="padding: 2px;">Resposabilidade: <?php echo $processo->fluxo_nome; ?></h5>
                            <h5>Categoria: <?php echo $documento->nome_categoria; ?></h5>



                            <h5>Data de cadastro: <?php
                                setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

                                date_default_timezone_set('America/Sao_Paulo');

                                echo strftime('%A, %d de %B de %Y', strtotime($documento->data_cadastro));
                                ?></h5>



<?php
if ($total) {

    $ok = 0;

    $not = 0;

    foreach ($total as $um) {

        if ($um['status'] == 1) {

            $ok++;
        } else {

            $not++;
        }

        $count = count($total);

        $resultado = ($ok / $count) * 100;
    }
}
?>



                            <h5><div class="progress progress-sm">

                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="<?php echo $resultado; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $resultado; ?>%">

                                    </div>

                                </div>

                                <small>

<?php echo $resultado; ?>% do Processo de aprovação

                                </small>

                            </h5>

<?php
if ($total) {

    foreach ($total as $um) {

        if ($um['status'] == 1) {
            ?>

                                        <div class="callout callout-info">

                                            <h5><?php echo $um['fluxo_nome']; ?>(<?php echo $um['firstname'] . ' ' . $um['lastname']; ?>): AVALIADO </h5> 



                                        </div>

                                        <?php
                                    } else {
                                        ?>

                                        <div class="callout callout-danger">

                                            <h5><?php echo $um['fluxo_nome']; ?>(<?php echo $um['firstname'] . ' ' . $um['lastname']; ?>): AGUARDANDO AVALIAÇÃO <?php if ($processo->id == $um['id']) { ?><span class="float-right badge bg-success">Processo atual</span><?php } ?></h5>

                                        </div>

                                        <?php
                                    }
                                }
                            }
                            ?>



                            <!--<table class="table table-bordered table-hover">

                                <thead>

                                    <tr>

                                        <th>Versão</th>

                                        <th>Motivo</th>

                                        <th>Usuário</th>

                                        <th>Data</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr data-widget="expandable-table" aria-expanded="false">

                                        <td>183</td>

                                        <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>

                                        <td>John Doe</td>

                                        <td>11-7-2014</td>

                                    </tr>

                                    <tr class="expandable-body">

                                        <td colspan="5">

                                            <p>

                                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.

                                            </p>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>-->

                            <a class="btn bg-blue w-100 " onclick="delClass('destinatarios', 'hide');"  > VISUALIZAR DESTINAT�?RIOS <i class="fa fa-users"></i></a>

                            <div class="col-md-12 hide" id="destinatarios">
<?php echo form_open_multipart('gestao_corporativa/intra/Documentos/sends_edit'); ?>
                                <input type="hidden" name="id_doc" value="<?php echo $documento->id; ?>">
                                <input type="hidden" name="see" value="<?php echo $documento->id; ?>">
<?php $this->load->view('gestao_corporativa/intranet/documento/setor_staff.php'); ?>
                                <div class="col-md-12 mb-4 mt-2">
                                    <a class="btn btn-sm bg-red" onclick="addClass('destinatarios', 'hide');">Fechar</a> 
                                    <button class="btn btn-sm bg-green float-right" type="submit">Salvar</button> 
                                </div>
<?php echo form_close(); ?>

                            </div>
<?php
$tipo = explode(".", $documento->file);

for ($i = 0; $i < count($tipo); $i++) {

    $extensao = $tipo[$i];
}
?>



                            <ol class="breadcrumb" style="margin-top: 10px;">

                                <li class=""><strong>DOCUMENTO</strong></li>

                            </ol>

                            <div class="col-6 col-md-4 col-xl-3 col-xxl-3">

                                <div class="app-card app-card-doc shadow-sm h-100">

                                    <div class="app-card-thumb-holder p-3" ><a 

                                            href="<?php
                            if ($documento->pdf_principal == 1) {

                                if ($documento->publicado) {

                                    echo (base_url() . 'media' . $documento->pasta_destino . $documento->file);
                                } else {

                                    echo base_url("assets/intranet/img/documentos/$documento->file");
                                }
                            } else {

                                echo base_url("gestao_corporativa/intra/Documentos/visualizar_conteudo?id=$documento->id");
                            }
                            ?>" target="_blank">

<?php if ($extensao == 'pdf') { ?>

                                                <span class="icon-holder">

                                                    <i class="fa fa-file-pdf-o"></i>

                                                </span>

                                            <?php } elseif ($extensao == 'xlsx') { ?>

                                                <span class="icon-holder">

                                                    <i class="fa fa-file-excel-o"></i>

                                                </span>

                                            <?php } elseif ($extensao == 'zip') { ?>

                                                <span class="icon-holder">

                                                    <i class="fa fa-file-archive-o"></i>

                                                </span>

                                            <?php } elseif ($extensao == 'mp4' or $extensao == 'gif') { ?>

                                                <span class="icon-holder">

                                                    <i class="fa fa-file-video-o"></i>

                                                </span>

                                            <?php } elseif ($extensao == 'jpg' or $extensao == 'jpeg' or $extensao == 'png') { ?>

                                                <span class="icon-holder">

                                                    <i class="fa fa-file-image-o"></i>

                                                </span>

                                            <?php } elseif ($extensao == 'txt') { ?>

                                                <span class="icon-holder">

                                                    <i class="fa fa-file-text-o"></i>

                                                </span>

                                            <?php } elseif ($extensao == 'ppsx' or $extensao == 'ppt') { ?>

                                                <span class="icon-holder">

                                                    <i class="fa fa-file-powerpoint-o"></i>

                                                </span>

                                            <?php } else { ?>

                                                <span class="icon-holder">

                                                    <i class="fa fa-file-text"></i>

                                                </span>

                                            <?php } ?>

                                        </a>

                                    </div>

                                    <div class="app-card-body p-3 has-card-actions">



                                        <a class="btn bg-blue w-100" target="_blank" href="<?php echo base_url(); ?>gestao_corporativa/intra/documentos/pdf/<?php echo $documento->id; ?>"> PDF <i class="fa fa-eye"></i></a>

<?php if ($emissor->id == $processo->id and $emissor->staff_id == get_staff_user_id()) { ?>

                                            <a class="btn bg-yellow w-100" onclick="delClass('editar', 'hide'); delClass('editar_info', 'hide');"  > EDITAR <delClass('editar', 'hide');i class="fa fa-exclamation-triangle"></i></a>

                                            <a class="btn bg-green w-100" onclick="addClass('retroceder', 'hide'); delClass('prosseguir', 'hide');" > Aprovar <i class="fa fa-arrow-right"></i></a>

<?php } else { ?>



    <?php
    if ($processo->staff_id == get_staff_user_id()) {

        if ($proximo->id) {
            ?>

                                                    <a class="btn bg-green w-100" onclick="addClass('retroceder', 'hide'); delClass('prosseguir', 'hide');" > Aprovar <i class="fa fa-arrow-right"></i></a>

        <?php } else if ($final->id == $processo->id and $final->staff_id = $processo->staff_id) { ?>



                                                    <a class="btn bg-blue w-100" target="_blank" href="<?php echo base_url(); ?>gestao_corporativa/intra/documentos/pdf/<?php echo $documento->id; ?>"> Prévia <i class="fa fa-eye"></i></a>

                                                    <a class="btn bg-green w-100" onclick="addClass('retroceder', 'hide'); delClass('publicar', 'hide');" > Publicar <i class="fa fa-group"></i></a>

        <?php } ?>

        <?php if ($anterior->id) { ?>

                                                    <a class="btn bg-red w-100" onclick="addClass('prosseguir', 'hide'); addClass('publicar', 'hide'); delClass('retroceder', 'hide');"> <i class="fa fa-arrow-left"></i> Retornar </a>

            <?php
        }
    }
}
?>

                                        <p class="hide" id="editar_info" >Editor no fim da tela!</p>            

                                    </div>



                                </div><!--//app-card-->



                            </div><!--//col-->

                            <div class="col-6 col-md-9 col-xl-9 col-xxl-9 hide" id="prosseguir">

                                        <?php echo form_open_multipart('gestao_corporativa/intra/Documentos/prosseguir_retroceder'); ?>

                                        <?php echo render_textarea('description', 'Mensagem para o destinatário seguinte', '', array('rows' => 7)); ?>

                                <input type="hidden" name="atual" value="<?php echo $processo->id; ?>">

                                <input type="hidden" name="id_doc" value="<?php echo $documento->id; ?>">

                                <input type="hidden" name="nome_fluxo" value="<?php echo $processo->fluxo_nome; ?>">

                                <input type="hidden" name="nome_staff" value="<?php echo $processo->firstname . ' ' . $processo->lastname; ?>">

                                        <?php echo render_input('proximo', 'Enviando documento para:', $proximo->fluxo_nome . ': ' . $proximo->firstname . ' ' . $proximo->lastname, 'text', $attrs); ?>

                                <button class="btn btn-info pull-right mbot15">

<?php echo _l('send'); ?>

                                </button>

<?php echo form_close(); ?>

                            </div>

                            <div class="col-6 col-md-9 col-xl-9 col-xxl-9 hide" id="retroceder">

<?php echo form_open_multipart('gestao_corporativa/intra/Documentos/prosseguir_retroceder'); ?>

                                <input type="hidden" name="anterior" value="<?php echo $emissor->id; ?>">

                                <input type="hidden" name="nome_fluxo" value="<?php echo $processo->fluxo_nome; ?>">

                                <input type="hidden" name="nome_staff" value="<?php echo $processo->firstname . ' ' . $processo->lastname; ?>">

                                <input type="hidden" name="id_doc" value="<?php echo $documento->id; ?>">

<?php echo render_textarea('description', 'Mensagem para o destinatário anterior', '', array('rows' => 7)); ?>

<?php echo render_input('destinatario', 'Voltando o Documento para:', $emissor->fluxo_nome . ': ' . $emissor->firstname . ' ' . $emissor->lastname, 'text', $attrs); ?>

                                <button class="btn btn-info pull-right mbot15">

                                <?php echo 'Voltar' ?>

                                </button>

                                    <?php echo form_close(); ?>

                            </div>

                            <div class="col-6 col-md-9 col-xl-9 col-xxl-9 hide" id="publicar">

<?php echo form_open_multipart('gestao_corporativa/intra/Documentos/publicar'); ?>

                                <div class="card-body">



                                    <div class="callout callout-info">

                                        <h4>Publicar Documento</h4>

                                        <input name="documento_id" type="hidden" value="<?php echo $documento->id; ?>">

                                        <input name="processo_id" type="hidden" value="<?php echo $processo->id; ?>">

                                        <input name="id_principal" type="hidden" value="<?php echo $documento->id_principal; ?>">

                                        <input type="hidden" name="nome_fluxo" value="<?php echo $processo->fluxo_nome; ?>">

                                        <input type="hidden" name="nome_staff" value="<?php echo $processo->firstname . ' ' . $processo->lastname; ?>">

                                        <h5><strong><?php echo $documento->titulo; ?></strong> estará disponível a todos os destinatários finais e não poderá ser editado.</h5>

                                        <h5><strong>Tem deseja

                                                que deseja publicar?</strong></h5>

                                    </div>

                                    <button class="btn btn-info pull-right mbot15">

                                <?php echo 'Publicar' ?>

                                    </button>

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

        <div class="col-md-5">

            <div class="panel_s">

                <div class="panel-body">

                    <div class="clearfix"></div>

                    <ol class="breadcrumb">

                        <li class=""><strong>TIMELINE </strong></li>

                    </ol>

                    <div class="row" style="padding: 10px;">

                        <div class="col-md-12">



                            <div class="timeline">



<?PHP
foreach ($logs_documento as $log):

    if ($log['sim_nao'] == 1) {

        $color = 'green';

        $icon = 'fa fa-check';
    } elseif ($log['sim_nao'] == 2) {

        $color = 'red';

        $icon = 'fa fa-times';
    } elseif ($log['sim_nao'] == 3) {

        $color = 'blue';

        $icon = 'fa fa-plus';
    } elseif ($log['sim_nao'] == 4) {

        $color = 'yellow';

        $icon = 'fa fa-check';
    }
    ?>

    <?php if ($log['obs_id'] or $log['sim_nao'] == 4) { ?>

                                        <div class="alert alert-warning alert-dismissible">

                                            <h5><i class="icon fas fa-exclamation-triangle"></i> <?php echo $log['action'] ?>! - <?php echo strftime('%d-%m-%Y %H:%M:%S', strtotime($log['date_created'])); ?></h5>

                                        </div>

                                    <?php } else { ?>





                                        <div class="time-label">

                                            <span class="bg-blue"><?php
                                        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

                                        date_default_timezone_set('America/Sao_Paulo');

                                        echo strftime('%A, %d de %B', strtotime($log['date_created']));
                                        ?></span>

                                        </div>





                                        <div>

                                            <i class="<?php echo $icon; ?> bg-<?php echo $color; ?>"></i>

                                            <div class="timeline-item">

                                        <?php if ($log['sim_nao'] == 3) { ?>

                                                    <span class="time"><i class="fas fa-clock"></i> <?php echo date('h:i:s A', strtotime($log['date_created'])); ?></span>

                                                    <h3 class="timeline-header"><?php echo $log['action'] ?></h3>

                                                    <div class="timeline-body">

                                            <?php echo $log['descricao'] ?>

                                                    </div>

        <?php } else if ($log['sim_nao'] == 1 || $log['sim_nao'] == 2) { ?>

                                                    <span class="time"><i class="fas fa-clock"></i> <?php echo date('h:i:s A', strtotime($log['date_created'])); ?></span>

                                                    <h3 class="timeline-header"><?php echo $log['action'] ?></h3>

                                                    <div class="timeline-body">

                                                    <?php echo $log['msg'] ?>





                                                    </div>

        <?php } else { ?>

                                                    <span class="time"><i class="fas fa-clock"></i> <?php echo date('h:i:s A', strtotime($log['date_created'])); ?></span>

                                                    <h3 class="timeline-header"><?php echo $log['action'] ?></h3>

        <?php } ?>

                                            </div>

                                        </div>

    <?php } ?>



<?php endforeach; ?>

                            </div>

                        </div>





                    </div>



                    <div class="col-md-12" style="padding: 15px;">

                        <ol class="breadcrumb" style="margin-top: 10px;">

                            <li class=""><strong>OBSERVAÇÕES</strong></li>

                        </ol>

                        <div id="trocar">

<?php foreach ($obss as $obs): ?>

                                <div class="post clearfix">

                                    <div class="user-block">

    <?php echo staff_profile_image($current_user->staffid, array('img', 'img-responsive', 'staff-profile-image-small', 'pull-left')); ?>

                                        <span class="username">

                                            <a href="#"><?php echo $obs['firstname'] . ' ' . $obs['lastname']; ?></a>

                                        </span>

                                        <span class="description">Observação - <?php echo strftime('%d-%m-%Y %H:%M:%S', strtotime($obs['data_created'])); ?></span>

                                    </div>



                                    <p>

    <?php echo $obs['obs']; ?>

                                    </p>

                                </div>

<?php endforeach; ?>

                        </div>

                        <div class="input-group">

                            <textarea type="text" name="comentario"  id="comentario" placeholder="Escreva uma observação..." class="form-control col-md-10"></textarea>

                            <span class="input-group-append col-md-2">

                                <button onclick="refresh_table(<?php echo $documento->id; ?>);" class="btn btn-primary">Enviar</button>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-12 hide" id="editar">

            <div class="card card-outline card-info">

                <div class="card-header">

                    <h3 class="card-title">

                        <strong>CONTEÚDO DO DOCUMENTO:</strong>

                    </h3>

                </div>

                <div class="card-body">



                    <input type="hidden" class="form-control p-2" id="id" name="id" value="<?php echo $documento->id; ?>">

                    <input type="hidden" class="form-control p-2" id="id" name="id_principal" value="<?php echo $documento->id_principal; ?>">



                    <input type="hidden" class="form-control p-2" id="projectName" name="id_editado" value="<?php echo $processo->id; ?>">

                    <textarea class="summernote" name="descricao" id="summernote">
<?php echo $documento->conteudo; ?>
                    </textarea>
                    <br>

                    <div id="result">Texto atualizado automaticamente.</div>
                </div>

                <div class="card-footer">

                    <button class="btn mb-0 js-btn-prev bg-red" onclick="addClass('editar', 'hide');" title="Fechar">Fechar</button>


                </div>
            </div>

        </div>



    </div>



</div>







<?php //init_tail();           ?>

<script src="<?php echo base_url() ?>assets/menu/plugins/jquery/jquery.min.js"></script>



<script src="<?php echo base_url() ?>assets/menu/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>



<script src="<?php echo base_url() ?>assets/menu/dist/js/adminltee.min.js?v=3.2.0"></script>



<script src="<?php echo base_url() ?>assets/menu/dist/js/demoe.js"></script>

<style>

    .app-card .app-card-header{

        border-bottom:1px solid #e7e9ed

    }

    .app-card .app-card-title{

        font-size:1.125rem;

        margin-bottom:0

    }

    .app-card .card-header-action{

        font-size:.875rem

    }

    .app-card .card-header-action a:hover{

        text-decoration:underline

    }

    .app-card .form-select-holder{

        display:inline-block

    }

    .app-card .btn-close{

        padding:1rem

    }

    .app-card .btn-close:focus{

        box-shadow:none

    }

    .app-card-stat{

        text-align:center

    }

    .app-card-stat .stats-type{

        font-size:.875rem;

        color:#828d9f;

        text-transform:uppercase

    }

    .app-card-stat .stats-figure{

        font-size:2rem;

        color:#252930

    }

    .app-card-stat .stats-meta{

        font-size:.875rem;

        color:#828d9f

    }

    .app-card-progress-list .item{

        position:relative;

        border-bottom:1px solid #e7e9ed

    }

    .app-card-progress-list .item:hover .title{

        color:#252930

    }

    .app-card-progress-list .item:last-child{

        border:none

    }

    .app-card-progress-list .item .title{

        font-size:.875rem;

        font-weight:500

    }

    .app-card-progress-list .item .meta{

        font-size:.875rem;

        color:#828d9f

    }

    .app-card-progress-list .item-link-mask{

        position:absolute;

        width:100%;

        height:100%;

        display:block;

        left:0;

        top:0

    }

    .app-card-progress-list .progress{

        height:.5rem

    }

    .app-card-stats-table .table{

        font-size:.875rem

    }

    .app-card-stats-table .meta{

        color:#828d9f;

        font-weight:500;

        font-size:.875rem

    }

    .app-card-stats-table .stat-cell{

        text-align:right

    }

    .app-card-basic{

        height:100%

    }

    .app-card-basic .title{

        font-size:1rem

    }

    .app-card .app-icon-holder{

        display:inline-block;

        background:#edfdf6;

        color:#15a362;

        width:50px;

        height:50px;

        padding-top:10px;

        font-size:1rem;

        text-align:center;

        border-radius:50%

    }

    .app-card .app-icon-holder.icon-holder-mono{

        background:#f5f6fe;

        color:#828d9f

    }

    .app-card .app-icon-holder svg{

        width:24px;

        height:24px

    }

    .app-card .app-card-body.has-card-actions{

        position:relative;

        padding-right:1rem !important

    }

    .app-card .app-card-body .app-card-actions{

        display:inline-block;

        width:30px;

        height:30px;

        text-align:center;

        border-radius:50%;

        position:absolute;

        z-index:10;

        right:.75rem;

        top:.75rem

    }

    .app-card .app-card-body .app-card-actions:hover{

        background:#f5f6fe

    }

    .app-card .app-card-body .app-card-actions .dropdown-menu{

        font-size:.8125rem

    }

    .app-card-doc:hover{

        box-shadow:0 .5rem 1rem rgba(0,0,0,.15) !important

    }

    .app-card-doc .app-card-thumb-holder{

        background:#e9eaf1;

        text-align:center;

        position:relative;

        height:112px

    }

    .app-card-doc .app-card-thumb-holder .app-card-thumb{

        overflow:hidden;

        position:absolute;

        left:0;

        top:0;

        width:100%;

        height:100%;

        background:#000

    }

    .app-card-doc .app-card-thumb-holder .thumb-image{

        -webkit-opacity:.7;

        -moz-opacity:.7;

        opacity:.7;

        width:100%;

        height:auto

    }

    .app-card-doc .app-card-thumb-holder:hover{

        background:#fafbff

    }

    .app-card-doc .app-card-thumb-holder:hover .thumb-image{

        -webkit-opacity:1;

        -moz-opacity:1;

        opacity:1

    }

    .app-card-doc .app-card-thumb-holder .badge{

        position:absolute;

        right:.5rem;

        top:.5rem

    }

    .app-card-doc .app-card-thumb-holder .icon-holder{

        font-size:40px;

        display:inline-block;

        margin:0 auto;

        width:80px;

        height:80px;

        border-radius:50%;

        background:#fff;

        padding-top:10px

    }

    .app-card-doc .app-card-thumb-holder .icon-holder .pdf-file{

        color:#da2d27

    }

    .app-card-doc .app-card-thumb-holder .icon-holder .text-file{

        color:#66a0fd

    }

    .app-card-doc .app-card-thumb-holder .icon-holder .excel-file{

        color:#0da95f

    }

    .app-card-doc .app-card-thumb-holder .icon-holder .ppt-file{

        color:#f4b400

    }

    .app-card-doc .app-card-thumb-holder .icon-holder .video-file{

        color:#935dc1

    }

    .app-card-doc .app-card-thumb-holder .icon-holder .zip-file{

        color:#252930

    }

    .app-card-doc .app-doc-title{

        font-size:.875rem

    }

    .app-card-doc .app-doc-title a{

        color:#252930

    }

    .app-card-doc .app-doc-title.truncate{

        max-width:calc(100% - 30px);

        display:inline-block;

        overflow:hidden;

        text-overflow:ellipsis;

        white-space:nowrap

    }

    .app-card-doc .app-doc-meta{

        font-size:.75rem

    }

    .table-search-form .form-control{

        height:2rem;

        min-width:auto

    }

    .app-dropdown-menu{

        font-size:.875rem

    }

    .app-card-orders-table .table{

        font-size:.875rem

    }

    .app-card-orders-table .table .cell{

        border-color:#e7e9ed;

        color:#5d6778;

        vertical-align:middle

    }

    .app-card-orders-table .cell span{

        display:inline-block

    }

    .app-card-orders-table .cell .note{

        display:block;

        color:#828d9f;

        font-size:.75rem

    }

    .app-card-orders-table .btn-sm,.app-card-orders-table .btn-group-sm>.btn{

        padding:.125rem .5rem;

        font-size:.75rem

    }

    .app-card-orders-table .truncate{

        max-width:250px;

        display:inline-block;

        overflow:hidden;

        text-overflow:ellipsis;

        white-space:nowrap

    }

    .app-nav-tabs{

        background:#fff;

        padding:0

    }

    .app-nav-tabs .nav-link{

        color:#5d6778;

        font-size:.875rem;

        font-weight:bold

    }

    .app-nav-tabs .nav-link.active{

        color:#15a362;

        border-bottom:2px solid #15a362

    }

    .app-nav-tabs .nav-link.active:hover{

        background:none

    }

    .app-nav-tabs .nav-link:hover{

        background:#edfdf6;

        color:#15a362

    }

    .app-pagination .pagination{

        font-size:.875rem

    }

    .app-pagination .pagination .page-link{

        color:#5d6778;

        padding:.25rem .5rem

    }

    .app-pagination .pagination .page-item.active .page-link{

        background:#747f94;

        color:#fff;

        border-color:#747f94

    }

    .app-pagination .pagination .page-item.disabled .page-link{

        color:#9fa7b5

    }

    .app-card-accordion .app-card-title{

        font-size:1.125rem

    }

    .app-card-accordion .faq-accordion .accordion-item{

        border-radius:0;

        border:none;

        border-bottom:1px solid #e7e9ed

    }

    .app-card-accordion .faq-accordion .accordion-item:last-child{

        border-bottom:none

    }

    .app-card-accordion .faq-accordion .accordion-header{

        border:none

    }

    .app-card-accordion .faq-accordion .accordion-button{

        padding:1rem;

        border-radius:0;

        border:none;

        box-shadow:none;

        background:none;

        padding-left:0;

        font-size:1rem;

        text-decoration:none;

        color:#15a362

    }

    .app-card-accordion .faq-accordion .accordion-button:after{

        display:none

    }

    .app-card-accordion .faq-accordion .accordion-body{

        padding-left:0;

        padding-right:0;

        padding-top:0;

        font-size:1rem

    }

    .app-card-account{

        height:100%

    }

    .app-card-account .item{

        font-size:.875rem

    }

    .app-card-account .item .profile-image{

        width:60px;

        height:60px

    }

    .app-card-account .item .btn-sm,.app-card-account .item .btn-group-sm>.btn{

        padding:.125rem .5rem;

        font-size:.75rem

    }

    .settings-section .section-title{

        font-size:1.25rem

    }

    .settings-section .section-intro{

        font-size:.875rem

    }

    .app-card-settings{

        font-size:1rem

    }

    .app-card-settings .form-label{

        font-weight:bold

    }

    .app-card-settings .form-control{

        font-size:1rem

    }

    .app-404-page{

        padding-top:2rem

    }

    .app-404-page .page-title{

        font-size:3rem;

        line-height:.8;

        font-weight:bold

    }

    .app-404-page .page-title span{

        font-size:1.5rem

    }

    .chart-container{

        position:relative

    }

    .app-table-hover>tbody>tr:hover{

        background-color:#fafbff

    }

    .app-card-notification .notification-type .badge{

        font-size:.65rem;

        text-transform:uppercase

    }

    .app-card-notification .profile-image{

        width:60px;

        height:60px

    }

    .app-card-notification .notification-title{

        font-size:1.125rem

    }

    .app-card-notification .notification-content{

        font-size:.875rem

    }

    .app-card-notification .notification-meta{

        font-size:.75rem;

        color:#828d9f

    }

    .app-card-notification .action-link{

        font-size:.875rem

    }

    .app-card-notification .app-card-footer{

        background:#fafbff

    }

    @media(min-width: 1200px){

        .table-search-form .form-control{

            min-width:300px

        }

    }

    @media(max-width: 575.98px){

        .app-card-stat .stats-figure{

            font-size:1.125rem

        }

        .app-card-stat .stats-type{

            font-size:.75rem

        }

    }

</style>

<script>

                        function refresh_table(id) {

                            var input = document.querySelector("#comentario");

                            var texto = input.value;

                            $.ajax({

                                type: "POST",

                                url: "<?php echo base_url('gestao_corporativa/intra/Documentos/retorno_comentarios'); ?>",

                                data: {

                                    id: id,

                                    texto: texto

                                },

                                success: function (data) {

                                    $('#trocar').html(data);

                                }

                            });

                            document.getElementById('comentario').value = ''; // Limpa o campo

                        }

</script>





<script src="<?php echo base_url() ?>assets/intranet/lte/plugins/summernote/summernote-bs4.min.js"></script>

<script>


                        $(function () {
                            timer = setInterval(function () {
                                //alert("5 seconds are up"); 
                                var texto = document.getElementById("summernote").value;
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo base_url('gestao_corporativa/intra/Documentos/editar_texto'); ?>",
                                    data: {
                                        id: '<?php echo $documento->id; ?>',
                                        conteudo: texto
                                    }
                                });
                            }, 2000);
                        });
                        function editar_texto() {
                            //alert('chegou');
                            //exit;
                            var texto = document.getElementById("summernote").value;
                            $.ajax({
                                type: "POST",
                                url: "<?php echo base_url('gestao_corporativa/intra/Documentos/editar_texto'); ?>",
                                data: {
                                    id: '<?php echo $documento->id; ?>',
                                    conteudo: texto
                                }
                            });
                        }

                        function editar_objetivo() {
                            //alert('chegou');
                            //exit;
                            var texto = document.getElementById("objetivo").value;
                            $.ajax({
                                type: "POST",
                                url: "<?php echo base_url('gestao_corporativa/intra/Documentos/editar_objetivo'); ?>",
                                data: {
                                    id: '<?php echo $documento->id; ?>',
                                    objetivo: texto
                                }
                            });
                            var div = document.getElementById("esconder");

                            div.innerHTML = texto;
                        }

                        $(function () {
                            $('.summernote').summernote({
                                callbacks: {
                                    onKeyup: function (e) {
                                        setTimeout(function () {
                                            editar_texto();
                                        }, 200);
                                    },
                                    onKeydown: function (e) {
                                        setTimeout(function () {
                                            editar_texto();
                                        }, 200);
                                    }
                                }
                            });
                        });
</script>











