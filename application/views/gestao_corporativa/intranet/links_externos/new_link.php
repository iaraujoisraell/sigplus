<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">

    <div class="row">
        <?php echo form_open("gestao_corporativa/intra/Links/salvar", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>
        <input type="hidden" name="id" value="<?php echo $link->id; ?>">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin/index/?group=links'); ?>"><i class="fa fa-backward"></i> Cadastros - Links Externos </a></li> 
                    <li class="">Novo </li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">

            <div class="panel_s">
                <div class="panel-heading">
                    Novo Link Externo
                </div>
                <div class="panel-body">
                    <div class="row" style="padding: 5px;">
                        <div class="col-md-6">
                            <br>
                            <div class="form-group">
                                <label class="control-label">NOME</label>
                                <input type="text" class="form-control" name="nome" id="nome" placeholder="Ex: Site G1" value="<?php echo $link->nome; ?>" required>
                            </div>

                             <?php
                            echo render_select('categoria_id', $categorias, array('id', 'titulo'), 'CATEGORIA', $link->categoria_id, array('required' => 'true'));
                            ?>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <div class="form-group">
                                <label class="control-label">URL</label>
                                <input type="text" name="url" id="url" class="form-control" placeholder="Ex: https://google.com.br" required value="<?php echo $link->url; ?>">
                            </div>
                            
                            <div>
                            <div class="col-md-6">

                                    <div class="form-group">
                                        <label  class="control-label">ÍCONE</label>
                                        <input type="text" name="icon"  class="form-control" placeholder="Ex: fa fa-link" value="<?php echo $link->icon; ?>">
                                        <span class="" style="font-size: 12px; margin-left: 3px;">Icones: <a target="_blank" href="https://fontawesome.com/v4/icons/">CLASSE FA</a></span>
                                    </div>

                                </div>
                                <div class="col-md-6 ">

                                    <div class="form-group">
                                        <label  class="control-label">COR DO ÍCONE</label>
                                        <input type="color" name="color" class="form-control" value="<?php echo $link->color; ?>">
                                    </div>
                                </div>
                                </div>
                            <!--<div class="form-group mt-4">
                                <div class="custom-control custom-radio col-md-6">
                                    <input class="custom-control-input" onclick="addClass('imagem', 'hide'); delClass('iconi', 'hide');" type="radio" value="icon" name="confirm" id="customRadio1" <?php echo $selected_icon; ?>>
                                    <label for="customRadio1" class="custom-control-label">Adicionar Ícone</label>
                                </div>
                                <div class="custom-control custom-radio col-md-6">
                                    <input class="custom-control-input"  onclick="addClass('iconi', 'hide'); delClass('imagem', 'hide');" type="radio" value="img" name="confirm" id="customRadio2" <?php echo $selected_img; ?>>
                                    <label for="customRadio2" class="custom-control-label">Adicionar Imagem</label>
                                </div>
                            </div>-->
                        </div>
                        <div class="col-md-12">
                            <BR>
                            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="ESTE LINKS ESTARÁ DISPONÍVEL PARA OS SEGUINTES DEPARTAMENTOS"></i> <label  class="control-label">DISPONÍVEL PARA SETORES:</label>
                            <br>
                                
                            <?php foreach ($departments as $department) {
                                 $checked = '';
                                if (is_array($destinos)) {
                                    if (in_array($department['departmentid'], $destinos)) {
                                        $checked = 'checked';
                                    }
                                }?>
                                <div class="checkbox checkbox-primary col-md-6">
                                    
                                    <input type="checkbox" id="dep_<?php echo $department['departmentid']; ?>" name="departments[]" value="<?php echo $department['departmentid']; ?>" <?php echo $checked;?>>
                                    <label for="dep_<?php echo $department['departmentid']; ?>"><?php echo $department['name']; ?></label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>


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

<script src="<?php echo base_url() ?>assets/intranet/ckeditor/ckeditor.js"></script>
<script>
                                        CKEDITOR.replace('descricao');
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

