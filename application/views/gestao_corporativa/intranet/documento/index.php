<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">

    <div class="row">
        <?php echo form_open_multipart('gestao_corporativa/intra/Documentos/salvar'); ?>
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Intra/documentos/list_'); ?>"><i class="fa fa-backward"></i> Documentos </a></li> 
                    <li class=""> Novo </li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">

            <div class="panel_s">
                <div class="panel-heading">
                    Novo documento
                </div>
                <div class="panel-body">


                    <?php
                    if ($id_principal) {
                        $value_titulo = $ultima_versao->titulo;
                        $readonly = 'readonly';
                        $value_desc = $ultima_versao->descricao;
                        $sequencial = $ultima_versao->sequencial;
                        $value_numero_versao = $ultima_versao->numero_versao;
                        $value_numero_versao = $value_numero_versao + 1;
                        $value_conteudo = $ultima_versao->conteudo;
                        $hide_dest = 'hide';
                        $alteracao = '<div class="form-group">
                     <label for="message-text" class="col-form-label">Alterações:</label>
                     <textarea class="form-control" id="message-text" name="alteracoes" rows="7" placeholder="Descreva as alterações feitas no documento:"></textarea>
                  </div>';
                    }
                    ?>

                    

                    <input type="hidden" class="form-control p-2" id="id" name="id_principal" value="<?php echo $id_principal; ?>">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Titulo:</label>
                        <input type="text" class="form-control" name="titulo" required="required" value="<?php echo $value_titulo; ?>">
                    </div>

                    <div class="form-group ">
                        <label>Objetivo:</label>
                        <textarea class="form-control" rows="3" placeholder="Descrição do documento" name="descricao_doc" required><?php echo $value_desc; ?></textarea>
                    </div>
                    
                    <div class="col-md-12">
                        <?php if(!$sequencial){?>
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" id="radioPrimary1" name="r1" checked onclick="mudar('sequencia');">
                                <label for="radioPrimary1">
                                    Sequencia Automática
                                </label>
                            </div>
                            <div class="icheck-primary d-inline" >
                                <input type="radio" id="radioPrimary2" name="r1" onclick="mudar('sequencia');">
                                <label for="radioPrimary2">
                                    Sequencia Manual
                                </label>
                            </div>

                        </div>
                        <?php }?>
                        <input type="number" style="display: none;" id="sequencia" class="form-control col-md-3" name="sequencial" placeholder="Sequencia" value="<?php echo $sequencial?>" <?php echo $readonly;?>>

                    </div>
                    <label class="form-label" style="margin-top: 10px;">Categoria</label>

                    <select class="form-control" name="categoria" id=categoria" onchange="setores(this.value);" required>
                        <option value="" selected="" disabled>Selecione</option>
                        <?php
                        foreach ($documento_categorias as $categoria):
                            ?>
                            <option value="<?php echo $categoria['id']; ?>" <?php
                            if ($documento->categoria_id == $categoria['id']) {
                                echo 'selected';
                            }
                            ?>><?php echo $categoria['id'] . ': ' . $categoria['titulo']; ?></option>
                                <?php endforeach; ?>
                    </select>

                    <br>
                    <div class="col-md-12" id="trocar" style="padding-left: 10px; padding-right: 10px;">

                        <?php
                        $i = 0;
                        foreach ($fluxos as $fluxo) {
                            ?>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="" ><?php echo $fluxo['fluxo_nome']; ?>: </label>
                                    <select class="form-control select2" style="height: 50px;" name="processo[]" required>
                                        <option value="" selected disabled>Selecione</option>
                                        <?php
                                        foreach ($staffs as $staff):
                                            $select = '';
                                            if ($staff['staffid'] == $fluxo['staff_id']) {
                                                $select = 'selected';
                                            }
                                            ?>
                                            <option <?php echo $select; ?> value="<?php echo ($fluxo['id'] . ':' . $staff['staffid'] . ':' . $fluxo['fluxo_nome'] . ':' . ($i + 1)); ?>"><?php echo $staff['firstname'] . ' ' . $staff['lastname'] . ' (' . $staff['name'] . ')'; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <script>
                                $(function () {
                                    //Initialize Select2 Elements
                                    $('.select2<?php echo $fluxo['id']; ?>').select2();

                                    //Initialize Select2 Elements
                                    $('.select2bs4').select2({
                                        theme: 'bootstrap4'
                                    });

                                });
                            </script>
                            <?php
                            $i++;
                        }
                        ?>
                        <input name="formato_codigo" value="<?php echo $categoria['formato_codigo']; ?>" type="hidden">
                        <input name="nome_categoria" value="<?php echo $categoria['titulo']; ?>" type="hidden">

                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label ">Documento:</label> 
                            <br>
                            <input class="form-control" name="arquivo" id="arquivo" type="file"/>
                        </div>

                        <?php if ($id_principal) { ?>
                            <div class="form-group col-md-3">
                                <label for="number">Número da versão:</label>
                                <input type="number" class="form-control" name="numero_versao" required="required" value="<?php echo $value_numero_versao; ?>">
                            </div>
                        <?php } else { ?>
                            <div class="form-group col-md-3">
                                <label for="number">Número Inicial de versao:</label>
                                <input type="number" class="form-control" name="numero_versao" required="required">
                            </div>
                        <?php } ?>
                        <div class="form-group col-md-3">
                            <label class="form-label" >Pasta de destino</label>
                            <?php ?>
                            <select class="form-control" name="pasta" id="choices-category" required>
                                <option value="" selected="" disabled>Selecione</option>
                                <?php
                                $pasta = 'media/Documentos';
                                $diretorio = dir($pasta);
                                //echo $diretorio->read(); exit;
                                while ($arquivo = $diretorio->read()) {
                                    if ($arquivo != '.' and $arquivo != '..') {
                                        ?>
                                        <option value="<?php echo "$arquivo/"; ?>" <?php if ("$documento->pasta_destino" == "/Documentos/$arquivo/") { echo 'selected'; } ?>>
                                            <?php echo $arquivo; ?></option>
                                                    <?php
                                                }
                                            } $diretorio->close();
                                            ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="form-label" >Departamento</label>
                                <?php ?>
                            <select class="form-control" name="dep" id="choices-category" required>
                                <option value="" selected="" disabled>Selecione</option>
                                <?php
                                foreach ($departments as $departamento) {
                                    ?>

                                    <option value="<?php echo $departamento['departmentid']; ?>" <?php
                                    if ($documento->setor_id == $departamento['departmentid']) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $departamento['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                            </select>
                        </div>

                    </div>


                    <br>
                    <label for="number">Conteúdo do Documento:</label>
                    <textarea id="descricao" name="descricao"><?php echo $value_conteudo; ?></textarea>
                    <br>
                    <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="checkboxPrimary3" name="publico" value="1">
                            <label for="checkboxPrimary3">
                                Público
                            </label>
                        </div>
<?php if (!$id_principal) { ?>
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="checkboxPrimary1" name="imediato" value="1">
                                <label for="checkboxPrimary1">
                                    Publicar imediatamente
                                </label>
                            </div>
<?php } ?>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="checkboxPrimary2" name="pdf_principal" value="1">
                            <label for="checkboxPrimary2">
                                Usar pdf como arquivo principal
                            </label>
                        </div>
                        
                    </div>
                    <div class="">
                        <h5 class="font-weight-bolder ">Receptores</h5>

                    <?php //$this->load->view('gestao_corporativa/intranet/comunicado/setor_staff.php'); ?>
                    <?php $this->load->view('gestao_corporativa/intranet/documento/setor_staff.php'); ?>
                    </div>
                <?php echo $alteracao; ?></div>
            </div>
            <div class="row">
                <div class="col-md-12" id="trocar">

                </div>
            </div>
            <div class="panel_s">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">



                            <div class="btn-group pull-right mleft4 " >
                                <button type="submit" class="btn btn-primary" >
                                    Salvar
                                </button>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php init_tail(); ?>



<div id="modal_wrapper"></div>


<script language="JavaScript">
    function marcar(nome) {
        var boxes = document.getElementsByClassName(nome);
        for (var i = 0; i < boxes.length; i++) {
            if (boxes[i].checked === false) {
                boxes[i].checked = true;
            } else {
                boxes[i].checked = false;
            }
        }
    }

    function desmarcar() {
        var boxes = document.getElementsByName("linguagem");
        for (var i = 0; i < boxes.length; i++)
            boxes[i].checked = false;
    }
</script>
<script>

    function setores(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/intra/Documentos/retorno_fluxos_categoria'); ?>",
            data: {
                id: id,
            },
            success: function (data) {
                $('#trocar').html(data);
            }
        });

    }
    if ($('#arquivo').files.length === 0) {
        alert("É Obrigatório Anexar Seu Currículo!")
    }
</script>
<script>
    function mudar(el) {
        var display = document.getElementById(el).style.display;
        if (display == "none")
            document.getElementById(el).style.display = 'block';
        else
            document.getElementById(el).style.display = 'none';
    }
</script>
<script src="<?php echo base_url(); ?>assets/intranet/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('descricao');
</script>

</body>
</html>
