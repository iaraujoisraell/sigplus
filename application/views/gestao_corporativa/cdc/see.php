<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>

<?php init_tail(); ?>
<?php
if ($documento->publicado == 1) {
    $resultado = 100;
} else {
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
            $resultado = intval($resultado);
        }
    }
}

//print_r($total); exit;
?>
<input name="rel_id" type="hidden" value="<?php echo $documento->id; ?>">
<input name="rel_type" type="hidden" value="cdc">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li>
                    <li><a href="<?= base_url('gestao_corporativa/Cdc/list_'); ?>"><i class="fa fa-backward"></i> Controle Documental Contínuo </a></li>
                    <li class=""><?php echo $documento->codigo; ?> </li>
                </ol>
            </div>
        </div>
        <?php if ($first == true && $current == true) { ?>
            <div class="col-md-4">

                <div class="panel_s">
                    <div class="panel-heading">
                        ÁREA DE ELABORAÇÃO
                    </div>
                    <div class="panel-body">
                        <div class="row" style="">
                            <div class="col-md-12 col-sm-12">
                                <span class="text-muted">MATRIZ DO DOCUMENTO<?php if ($version == true) { ?> / VERSÃO ATUAL (OU ATUAL)<?php } ?>:</span>
                                <div class="clearfiz"></div>
                                <?php
                                if ($matriz != '') {
                                    $infoArquivo = pathinfo($matriz->file);
                                    $extensao = $infoArquivo['extension'];
                                ?>

                                    <div class="wrimagecard wrimagecard-topimage" style="display: inline-block;">
                                        <a href="<?php echo base_url($matriz->caminho); ?>" download="Matriz-<?php echo $matriz->titulo; ?>-CDC#<?php echo $documento->codigo; ?>.<?php echo $extensao; ?>">
                                            <div class="wrimagecard-topimage_header" style="background-color: #f0f5ff; ">
                                                <center><i class="fa fa-file-text-o" style="color: #82aafa; font-size: 40px;"></i></center>
                                            </div>
                                            <div class="wrimagecard-topimage_title">
                                                <span class="bold">Matriz.<?php echo $extensao; ?></span>
                                            </div>
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <span class="bold">MATRIZ NÃO CADASTRADA.</span>
                                <?php } ?>
                                <?php
                                if ($version == true) {
                                    $infoArquivo = pathinfo($cdc_old->doc);
                                    $extensao = $infoArquivo['extension'];
                                ?>
                                    <div class="wrimagecard wrimagecard-topimage" style="display: inline-block;">
                                        <a href="<?php echo base_url() . 'assets/intranet/arquivos/cdc_arquivos/cdc_/' . $cdc_old->doc; ?>" download="DOC-CDC#<?php echo $cdc_old->codigo; ?>.<?php echo $extensao; ?>">
                                            <div class="wrimagecard-topimage_header" style="background-color: #f0f5ff; ">
                                                <center><i class="fa fa-file-text-o" style="color: red; font-size: 40px;"></i></center>
                                            </div>
                                            <div class="wrimagecard-topimage_title">
                                                <span class="bold"><?php echo $cdc_old->codigo; ?>.<?php echo $extensao; ?></span>
                                            </div>
                                        </a>
                                    </div>


                                <?php } ?>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <br>
                                <span class="text-muted">CONTROLE DE ALTERAÇÕES</span>
                                <div class="clearfix"></div>
                                <div class="_buttons">
                                    <a onclick="slideToggle('.usernote');" class="btn btn-info pull-left">Adicionar Rascunho</a>

                                </div>

                                <div class="clearfix"></div>
                                <div class="usernote hide row" id="form">


                                    <form id="uploadForm" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $documento->id; ?>">
                                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <div class="col-md-12">
                                            <?php echo render_textarea('description_draft', 'Descrição', '', array(), array(), '', ''); ?>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="input-group">
                                                <input type="file" data-name_value="CDC-<?php echo $documento->codigo; ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="file_draft" id="file_draft" data-target="assets/intranet/arquivos/cdc_arquivos/draft/" accept=".doc, .docx">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-success p8-half" type="button" id="submitForm">Salvar <i class="fa fa-paper-plane-o"></i></button>
                                                </span>
                                            </div>
                                        </div>


                                    </form>
                                </div>
                                <div class="mtop10">
                                    <?php
                                    $table_data = [];
                                    $table_data = array_merge($table_data, array(
                                        'File',
                                        'Opções',
                                        'Cadastro'
                                    ));
                                    render_datatable($table_data, 'files');
                                    ?>
                                </div>
                            </div>



                        </div>

                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-md-<?php
                            if ($first == true && $current == true) {
                                echo '4';
                            } else {
                                echo '6';
                            }
                            ?>">
            <div class="panel_s">
                <div class="panel-heading" style="text-transform: uppercase;">
                    <?php echo $documento->titulo . ' (' . $documento->codigo . ')'; ?>
                </div>
                <div class="panel-body">
                    <div class="clearfix"></div>

                    <div class="row" style="padding: 5px;">
                        <div class="col-md-12">
                            <div class="callout callout-info">
                                <span class="ticket-label label label-default inline-block">
                                    Cadastro: <?php echo get_staff_full_name($documento->user_cadastro->staffid); ?> - <?php echo _d($documento->data_cadastro); ?>
                                </span>
                                <span class="ticket-label label label-info inline-block">
                                    Categoria: <?php echo $documento->nome_categoria; ?>
                                </span><!-- comment -->

                                <span class="ticket-label label label-warning inline-block">
                                    Validade: <?php
                                                if (!$documento->validity || $documento->validity == '0' || $documento->validity == '00') {
                                                    echo 'Sem validade';
                                                } elseif (!$documento->publicado == true) {
                                                    echo 'Aguardando Publicação';
                                                } else {
                                                    $dataObj = new DateTime($documento->data_publicacao);

                                                    $dataObj->modify("+" . $documento->validity . " months");

                                                    echo $dataObj->format('d/m/Y');
                                                }
                                                ?>
                                </span>
                                <?php if ($documento->publicado == true) { ?>
                                    <span class="ticket-label label label-primary inline-block">
                                        Dt Publicação: <?php echo _d($documento->data_publicacao); ?>
                                    </span>
                                <?php } ?>



                            </div>
                            <br>



                            <div class="panel_s">
                                <div class="panel-heading">
                                    INFORMAÇÕES DO DOCUMENTO

                                </div>
                                <div class="panel-body">


                                    <span id="esconder" class="text-muted"><a id="pencil" type="button" onclick="delClass('objetivo', 'hide'); addClass('pencil', 'hide'); delClass('closee', 'hide'); addClass('esconder', 'hide');"> <i class="fa fa-pencil-square-o"></i></a> <?php echo $documento->descricao; ?></span>
                                    <a id="closee" type="button" onclick="delClass('esconder', 'hide'); addClass('objetivo', 'hide'); addClass('closee', 'hide'); delClass('pencil', 'hide');" class="btn hide"><i class="fa fa-check"></i></a><textarea class="form-control hide" id="objetivo" name="objetivo" rows="7" onkeydown="editar_objetivo();" onkeyup="editar_objetivo();" onkeypress="editar_objetivo();"><?php echo $documento->descricao; ?></textarea>
                                    <br>
                                    <?php
                                    $campos = [];
                                    $values_info['rel_type'] = 'cdc';
                                    //print_r($processo); exit;
                                    $values_info['campos'] = $this->Categorias_campos_model->get_values($documento->id, 'cdc', '0');
                                    $this->load->view('gestao_corporativa/categorias_campos/values_info', $values_info);
                                    ?>

                                </div>


                            </div>

                            <div class="row">
                                <div class="col-md-4 text-center project-percent-col mtop10">

                                    <div class="row">
                                        <?php if ($first == false) { ?>
                                            <div class="col-md-12">
                                                <div class="wrimagecard wrimagecard-topimage" style="display: inline-block; margin-left: 10px;">
                                                    <a href="javascript:void(0);" onclick="document.getElementById('form_draft').submit();">
                                                        <div class="wrimagecard-topimage_header" style="background-color: #f0f5ff; ">
                                                            <center><i class="fa fa-file-pdf-o" style="color: red;"></i></center>
                                                        </div>
                                                        <div class="wrimagecard-topimage_title">
                                                            <span class="bold"><?php echo $documento->codigo; ?>.pdf</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="col-md-12" style="display: flex; justify-content: center; align-items: center;">
                                            <div class="progress-bar relative mtop15" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                <div class="percentage"><?php 
                                                                        if ($resultado) {
                                                                            echo $resultado;
                                                                        } else {
                                                                            echo '0';
                                                                        }
                                                                        ?>%</div>
                                            </div>
                                        </div>

                                    </div>



                                </div>
                                <!--<div class="project-progress-bars">
                                                                       <div class="row">
                                                                           <div class="project-overview-open-tasks">
                                                                               <div class="col-md-9">
                                                                                   <p class="text-muted bold"><?php
                                                                                                                if ($resultado) {
                                                                                                                    echo $resultado;
                                                                                                                } else {
                                                                                                                    echo '0';
                                                                                                                }
                                                                                                                ?>% DO PROCESSO</p>
                                                                               </div>
                                                                               <div class="col-md-3 text-right">
                                                                                   <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                                               </div>
                                                                               <div class="col-md-12 mtop5">
                                                                                   <div class="progress no-margin progress-bar-mini">
                                                                                       <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $resultado; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $resultado; ?>">
                                                                                       </div>
                                                                                   </div>
                                                                               </div>
                                                                           </div>
                                                                       </div>
                                                                   </div>-->

                                <div class="col-md-8">



                                    <?php if ($total) { ?>
                                        <div class="activity-feed">
                                            <?php foreach ($total as $um) { ?>

                                                <div class="feed-item row col-md-12" data-sale-activity-id="">
                                                    <div class="date">
                                                        <span class="text-has-action" data-toggle="tooltip" data-title="">
                                                            <?php echo $um['fluxo_nome']; ?> (<span class="bold"><?php echo get_staff_full_name($um['staff_id']); ?></span>):
                                                            <?php
                                                            if ($processo->id == $um['id']) {
                                                                echo '<span class="text-muted" style="color: #ff7070;">PROCESSO ATUAL</span>';
                                                            } elseif ($um['status'] == 1) {
                                                                echo '<span class="text-muted" style="color: #70ffa5" >DOCUMENTO AVALIDADO</span>' . '<br><span class="posttime">' . _d($um['dt_aprovacao']) . '</span><br>';
                                                                $campos = [];
                                                                $values_info['rel_type'] = 'cdc';
                                                                //print_r($processo); exit;
                                                                $values_info['campos'] = $this->Categorias_campos_model->get_values($documento->id, 'cdc', $um['fluxo_id']);
                                                                $this->load->view('gestao_corporativa/categorias_campos/values_info5', $values_info);
                                                            } else {
                                                                echo '<span class="text-muted";">AGUARDANDO PARA AVALIAÇÃO</span>';
                                                            }

                                                            //DT_APROVACAO
                                                            ?>

                                                        </span>
                                                    </div>

                                                </div>

                                            <?php
                                            }
                                            ?>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                            </div>

                            <!--<div class="progress progress-sm active">
                                <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $resultado; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $resultado; ?>%;">
                                    <span class="sr-only">% Complete</span>
                                </div>
                            </div>-->







                            <?php if ($first == false) { ?>
                                <?php echo form_open(base_url('gestao_corporativa/Intranet_general/file_'), array('id' => 'form_draft', 'method' => 'post', 'target' => '_blank')); ?>

                                <?php if ($documento->publicado != 1) { ?>
                                    <input type="hidden" name="draft" value="<?php echo true; ?>">
                                <?php } ?>
                                <?php if ($documento->publicado == 1) { ?>
                                    <input type="hidden" name="publicado" value="<?php echo true; ?>">
                                <?php } else { ?>
                                    <input type="hidden" name="publicado" value="<?php echo false; ?>">
                                <?php  } ?>
                                <input type="hidden" name="file" value="<?php echo 'arquivos/cdc_arquivos/cdc/' . $documento->file; ?>">
                                <input type="hidden" name="name" value="<?php echo $documento->codigo; ?>">
                                <?php echo form_close(); ?>
                                <!--<div class="row">
                                    <br>
                                    <br>
                                    <div class="col-md-12 col-sm-12" style="display: flex; flex-direction: row; align-items: flex-start;">

                                        <span class="text-muted" id="first">DOCUMENTO ANEXADO: </span>
                                        <div class="wrimagecard wrimagecard-topimage" style="display: inline-block; margin-left: 10px;">
                                            <a  href="javascript:void(0);" onclick="document.getElementById('form_draft').submit();">
                                                <div class="wrimagecard-topimage_header" style="background-color: #f0f5ff; ">
                                                    <center><i class="fa fa-file-pdf-o" style="color: #82aafa;"></i></center>
                                                </div>
                                                <div class="wrimagecard-topimage_title">
                                                    <span class="bold"><?php echo $documento->codigo; ?>.pdf</span>
                                                </div>
                                            </a>
                                        </div>
                                        
                                    </div>
                                </div>-->
                            <?php } ?>





                        </div>

                    </div>
                </div>
                <?php
                if ($documento->publicado != true and $current == true) {
                    $campos = $this->Categorias_campos_model->get_categoria_campos($documento->categoria_id, $processo->fluxo_id, false, '', $documento->id);

                    if ($last == true) {
                        echo form_open(base_url('gestao_corporativa/cdc/publicar?id=' . $documento->id), array('id' => 'form_cdc', 'onsubmit' => "document.getElementById('disabled').disabled = true;"));
                    } else {
                        echo form_open(base_url('gestao_corporativa/cdc/prosseguir_retroceder/' . $documento->id), array('id' => 'form_cdc', 'onsubmit' => "document.getElementById('disabled').disabled = true;"));
                    }
                ?>

                    <?php ?>
                    <div class="panel-footer">
                        <?php if (count($campos) > 0) { ?>
                            <div class="panel_s">
                                <div class="panel-heading">
                                    CAMPOS PARA A FASE DE APROVAÇÃO
                                </div>
                                <div class="panel-body">
                                    <?php
                                    $data['campos'] = $campos;
                                    $data['categoria_id'] = $documento->categoria_id;
                                    $data['just_campos'] = true;
                                    $this->load->view('gestao_corporativa/categorias_campos/retorno_categoria', $data);
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($first == false) { ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <a class="btn btn-danger w-100"
                                        data-toggle="popover" data-placement="right" data-html="true" data-trigger="manual" data-title="Nota" data-content='
                                        <div class="form-group" app-field-wrapper="timesheet_note">
                                        <textarea id="timesheet_note" name="msg" 
                                        class="form-control" 
                                        rows="4"></textarea></div>
                                        <button type="submit" class="btn btn-info btn-xs">Salvar</button>
                                        '><i class="fa fa-times"></i> REPROVAR </a>
                                </div>
                                <?php if ($last == true) { ?>
                                    <div class="col-md-6">
                                        <button id="disabled" type="submit" class="btn btn-primary w-100" <?php
                                                                                                            if ($first == true) {
                                                                                                                echo 'disabled data-toggle="tooltip" title="Aguardando envio final do documento." ';
                                                                                                            }
                                                                                                            ?>><i class="fa fa-check"></i> PUBLICAR DOCUMENTO </button>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-md-6">
                                        <a class="btn btn-success w-100" data-toggle="popover" data-placement="right" data-html="true" data-trigger="manual" data-title="Nota" data-content='
                                        <?php // REPROVAR echo form_open(base_url('gestao_corporativa/cdc/prosseguir_retroceder?id=' . $documento->id . '&action=' . true));   
                                        ?>
                                           <div class="form-group" app-field-wrapper="timesheet_note">
                                           <input type="hidden" name="action" value="1">
                                           <textarea id="timesheet_note" name="msg" 
                                           class="form-control" 
                                           rows="4"></textarea></div><button  id="disabled" type="submit" class="btn btn-info btn-xs">Salvar</button>'><i class="fa fa-check"></i> APROVAR </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php // PUBLICAR    
                                    ?>
                                    <input type="hidden" name="id" value="<?php echo $documento->id; ?>">
                                    <input type="hidden" name="action" value="<?php echo true; ?>">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <div class="alert alert-warning alert-dismissible">
                                        <p style="text-align: center;"><i class="icon fa fa-info-circle"></i> Para enviar para o fluxo seguinte: <br> - Anexe o documento Final para edição nas próximas versões.<br> - Anexe o documento Final para exibição ao destinatários.<br> - Clique em enviar.</p>
                                        <br><!-- comment -->
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="file" data-name_value="<?php echo $documento->id; ?>-CDC-<?php echo $documento->codigo; ?>" class="form-control" name="file_" id="file_" data-target="assets/intranet/arquivos/cdc_arquivos/cdc_/" accept=".doc, .docx">
                                                <input required type="file" data-name_value="<?php echo $documento->id; ?>-CDC-<?php echo $documento->codigo; ?>__" class="form-control" name="file" id="file" data-target="assets/intranet/arquivos/cdc_arquivos/cdc/" accept=".pdf, .PDF">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-success  p8-half" type="submit" id="disabled">ENVIAR <i class="fa fa-paper-plane"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                    <?php echo form_close(); ?>
                <?php } ?>
            </div>

        </div>

        <div class="col-md-<?php
                            if ($first == true && $current == true) {
                                echo '4';
                            } else {
                                echo '6';
                            }
                            ?>">

            <div class="panel_s">
                <div class="panel-heading">
                    DOCUMENTOS VINCULADOS
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">

                            <p style=""><i class="icon fa fa-info-circle"></i> Vincule documentos secundários. (Buscar por Código ou Título)</p>
                            <form onsubmit="return false;">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="search_value" name="search_value">
                                        <span class="input-group-addon"><button style=" border: none;" onclick="search_cdc();"><i class="fa fa-search"></i> Buscar</button></span>
                                    </div>
                                </div>
                            </form>



                            <div id="div_search">

                            </div>
                            <div id="table_search">
                                <?php
                                $table['links'] = $links;
                                if (count($links) > 0) {
                                    $this->load->view('gestao_corporativa/cdc/table_links', $table);
                                }
                                ?>

                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="panel_s">
                <div class="panel-heading">
                    OBSERVAÇÕES
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible">
                                <p style=""><i class="icon fa fa-info-circle"></i> Aqui estão as observações sobre o documento.</p>

                            </div>

                            <div id="trocar">
                                <?php $this->load->view('gestao_corporativa/cdc/retorno_comentarios', ['obss' => $obss]); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <form class="chat-input" onsubmit="return false;">
                        <input type="text" autocomplete="on" placeholder="Escreva uma observação..." name="comentario" id="comentario" />
                        <button onclick="refresh_table(<?php echo $documento->id; ?>);">
                            <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>

            </div>

            <div class="panel_s">
                <div class="panel-heading">
                    TIMELINE
                </div>
                <div class="panel-body">
                    <div class="row" style="">
                        <div class="col-md-12">

                            <div class="activity-feed">



                                <?PHP
                                foreach ($logs_documento as $log):
                                    if ($log['obs_id'] or $log['sim_nao'] == 4) {
                                        $bg = 'warning';
                                        $icon = 'fa fa-check';
                                    } else {
                                        if ($log['sim_nao'] == 1) {
                                            $bg = 'success';
                                            $icon = 'fa fa-check';
                                        } elseif ($log['sim_nao'] == 2) {
                                            $bg = 'danger';
                                            $icon = 'fa fa-times';
                                        } elseif ($log['sim_nao'] == 3) {
                                            $bg = 'info';
                                            $icon = 'fa fa-file-pdf-o';
                                        }
                                    }
                                ?>
                                    <div class="feed-item" style="margin-top: 0; margin-bottom: 0;">
                                        <span class="timestamp"><span class="username"><?php echo get_staff_full_name($log['user_created']); ?></span>&bull;<span class="posttime"><?php echo _d($log['date_created']); ?></span></span>
                                        <div class="alert alert-<?php echo $bg; ?> alert-dismissible">
                                            <p> <?php echo staff_profile_image($log['user_created'], array('staff-profile-xs-image pull-left mright5')); ?><i class="icon <?php echo $icon; ?>"></i> <?php echo $log['action']; ?></p>
                                            <?php if ($log['msg']) { ?>
                                                <p> Nota: <?php echo $log['msg']; ?></p>
                                            <?php } ?>
                                        </div>


                                    </div>

                                <?php endforeach; ?>
                            </div>
                        </div>


                    </div>

                </div>
            </div>


        </div>

    </div>

</div>



<script>
    function search_cdc() {
        var value = document.getElementById("search_value").value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Cdc/search'); ?>",
            data: {
                value: value,
                linker: '<?php echo $documento->id; ?>'
            },
            success: function(response) {
                document.getElementById("div_search").innerHTML = response;

            }
        });

    }

    function select_cdc(linked) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Cdc/select_link'); ?>",
            data: {
                linked: linked,
                linker: '<?php echo $documento->id; ?>'
            },
            success: function(response) {
                document.getElementById("div_search").innerHTML = '';
                document.getElementById("search_value").value = '';
                document.getElementById("table_search").innerHTML = response;
            }
        });

    }

    $(document).ready(function() {
        $('#submitForm').on('click', function() {
            var uploadForm = $('#uploadForm');
            var formData = new FormData($('#uploadForm')[0]);

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
            formData.append('target', '1');


            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('controle_upload/All.php'); ?>', // Substitua com o URL do seu script PHP
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var obj = JSON.parse(response);
                    var description = document.getElementById("description_draft").value;

                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('gestao_corporativa/cdc/save_draft'); ?>',
                        data: {
                            rel_id: '<?php echo $documento->id; ?>',
                            description: description,
                            target: obj['file_draft']
                        },
                        success: function(otherResponse) {
                            var obj = JSON.parse(otherResponse);
                            alert_float(obj.alert, obj.message);
                            slideToggle('.usernote');
                            reload_draft();
                        }
                    });
                }
            });
        });
    });

    $(document).ready(function() {
        $('#form_cdc').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission
            var uploadForm = $('#form_cdc');
            var formData = new FormData($('#form_cdc')[0]);
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
                    $('#form_cdc').unbind('submit').submit();

                }
            });
        });
    });
</script>
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
<script>
    $(function() {
        reload_draft();
    });

    function reload_draft() {
        if ($.fn.DataTable.isDataTable('.table-files')) {
            $('.table-files').DataTable().destroy();
        }
        var CustomersServerParams = {};
        CustomersServerParams['rel_id'] = '[name="rel_id"]';
        CustomersServerParams['rel_type'] = '[name="rel_type"]';
        initDataTable('.table-files', '<?php echo base_url(); ?>' + 'gestao_corporativa/Intranet_general/table_files', [2], [2], CustomersServerParams, [1, 'desc']);
    }
</script>

<script>
    function editar_objetivo() {
        //alert('chegou');
        //exit;
        var texto = document.getElementById("objetivo").value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Cdc/editar_objetivo'); ?>",
            data: {
                id: '<?php echo $documento->id; ?>',
                objetivo: texto
            }
        });
        var div = document.getElementById("esconder");
        div.innerHTML = texto;
    }
</script>

<script>
    function refresh_table(id) {
        var input = document.querySelector("#comentario");
        var texto = input.value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Cdc/retorno_comentarios'); ?>",
            data: {
                id: id,
                texto: texto
            },
            success: function(data) {
                $('#trocar').html(data);
            }
        });
        document.getElementById('comentario').value = ''; // Limpa o campo
    }
</script>
<style>
    .wrimagecard {
        margin-top: 0;
        margin-bottom: 1.5rem;
        text-align: left;
        position: relative;
        background: #fff;
        box-shadow: 12px 15px 20px 0px rgba(46, 61, 73, 0.15);
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .wrimagecard .fa {
        position: relative;
        font-size: 70px;
    }

    .wrimagecard-topimage_header {
        padding: 20px;
    }

    a.wrimagecard:hover,
    .wrimagecard-topimage:hover {
        box-shadow: 2px 4px 8px 0px rgba(46, 61, 73, 0.2);
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


    .progress-bar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background:
            radial-gradient(closest-side, white 79%, transparent 80% 100%),
            conic-gradient(#36f57c <?php echo $resultado; ?>%, #f0fff5 0);
        position: relative;
    }

    .percentage {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        font-weight: bold;
        color: #333;
        /* Cor do texto da porcentagem */
    }

    ::-webkit-scrollbar {
        width: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #4c4c6a;
        border-radius: 2px;
    }


    .chat-input {
        flex: 0 0 auto;
        height: 35px;
        background: #f0f5ff;
        box-shadow: 0 0 4px rgba(0, 0, 0, .14), 0 4px 8px rgba(0, 0, 0, .28);
    }

    .chat-input input {
        height: 34px;
        line-height: 35px;
        outline: 0 none;
        border: none;
        width: calc(100% - 60px);
        text-indent: 10px;
        font-size: 10pt;
        padding: 0;
        background: #f0f5ff;
    }

    .chat-input button {
        float: right;
        outline: 0 none;
        border: none;
        background: #82aafa;
        height: 25px;
        width: 25px;
        border-radius: 50%;
        padding: 2px 0 0 0;
        margin: 5px;
        transition: all 0.15s ease-in-out;
    }

    .msg-container {
        position: relative;
        display: inline-block;
        width: 100%;
        margin: 0 0 10px 0;
        padding: 0;
    }

    .msg-box {
        display: flex;
        background: #f0f5ff;
        padding: 10px 10px 0 10px;
        border-radius: 0 6px 6px 0;
        max-width: 80%;
        width: auto;
        float: left;
        box-shadow: 0 0 2px rgba(0, 0, 0, .12), 0 2px 4px rgba(0, 0, 0, .24);
    }

    .user-img {
        display: inline-block;
        border-radius: 50%;
        height: 40px;
        width: 40px;
        background: #2671ff;
        margin: 0 10px 10px 0;
    }

    .messages {
        flex: 1 0 auto;
    }

    .msg {
        display: inline-block;
        font-size: 10pt;
        line-height: 13pt;
        margin: 0 0 4px 0;
    }

    .msg:first-of-type {
        margin-top: 8px;
    }

    .timestamp {
        color: rgba(0, 0, 0, .38);
        font-size: 8pt;
        margin-bottom: 10px;
    }

    .username {
        margin-right: 3px;
    }

    .posttime {
        margin-left: 3px;
    }

    .msg-self .msg-box {
        border-radius: 6px 0 0 6px;
        float: right;
    }

    .msg-self .user-img {
        margin: 0 0 10px 10px;
    }

    .msg-self .msg {
        text-align: right;
    }

    .msg-self .timestamp {
        text-align: right;
    }
</style>