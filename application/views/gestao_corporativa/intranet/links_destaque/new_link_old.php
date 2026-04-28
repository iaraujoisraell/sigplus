<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_admin(false); ?>

<div class="content">

    <div class="container-fluid py-4">
        <div class="row">

            <div class="col-md-12 mt-3">
                <?php echo form_open("gestao_corporativa/intra/Links_destaque/salvar", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>
                <input type="hidden" class="form-control" name="id" id="nome" placeholder="Ex: Site G1" value="<?php echo $link->id;?>">
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin'); ?>"><i class="fa fa-backward"></i> Cadastros </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin/index/?group=links_destaque'); ?>">  Links Destaque </a></li> 
                    <li class="active"><?php
                        if ($link) {
                            echo " $link->nome ";
                        } else {
                            echo ' Novo ';
                        }
                        ?></li>
                </ol>

                <div class="card mt-4">
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-6">
                                <br>
                                <div class="form-group">
                                    <label>NOME</label>
                                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Ex: Site G1" value="<?php echo $link->nome;?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="exampleSelectBorder">CATEGORIA</label>
                                    <select class="form-control mt-0" name="categoria" id="choices" required>
                                        <?php if (!$link->categoria_id) { ?>
                                            <option value="" selected="" disabled>Selecione</option>
                                        <?php } ?>
                                        <?php foreach ($categorias as $staff): ?>
                                            <option value="<?php echo $staff->id; ?>" <?php
                                            if ($link->categoria_id == $staff->id) {
                                                echo 'selected';
                                            }
                                            ?>>
                                                <?php echo $staff->titulo; ?></option>
                                        <?php endforeach; ?>


                                    </select>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <br>
                                <div class="form-group">
                                    <label>URL</label>
                                    <input type="text" name="url" id="url" class="form-control" placeholder="Ex: https://google.com.br" required value="<?php echo $link->url;?>">
                                </div>
                                <br>

                                <?php
                                if ($link->img) {
                                    
                                    $hide_icon = 'hide';
                                    $selected_img = 'checked';
                                } elseif ($link->icon) {
                                    $hide_img = 'hide';
                                    $selected_icon = 'checked';
                                } else {
                                    $hide_icon = 'hide';
                                    $hide_img = 'hide';
                                }
                                ?>
                                <div class="form-group mt-4">
                                    <div class="custom-control custom-radio col-md-6">
                                        <input class="custom-control-input" onclick="addClass('imagem', 'hide'); delClass('iconi', 'hide');" type="radio" value="icon" name="confirm" id="customRadio1" <?php echo $selected_icon; ?>>
                                        <label for="customRadio1" class="custom-control-label">Adicionar Ícone</label>
                                    </div>
                                    <div class="custom-control custom-radio col-md-6">
                                        <input class="custom-control-input"  onclick="addClass('iconi', 'hide'); delClass('imagem', 'hide');" type="radio" value="img" name="confirm" id="customRadio2" <?php echo $selected_img; ?>>
                                        <label for="customRadio2" class="custom-control-label">Adicionar Imagem</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">

                                <div class="iconi row <?php echo $hide_icon; ?>" id="iconi">
                                    <div class="col-md-6">

                                        <br>
                                        <div class="form-group">
                                            <label>ÍCONE</label>
                                            <input type="text" name="icon"  class="form-control" placeholder="Ex: fa fa-link" value="<?php echo $link->icon;?>">
                                            <span class="" style="font-size: 12px; margin-left: 3px;">Icones: <a target="_blank" href="https://fontawesome.com/v4/icons/">CLASSE FA</a></span>
                                        </div>

                                    </div>
                                    <div class="col-md-6 ">

                                        <br>
                                        <div class="form-group">
                                            <label>COR DO ÍCONE</label>
                                            <input type="color" name="color" class="form-control" value="<?php echo $link->color;?>">
                                        </div>
                                    </div> 

                                </div>  
                                <div class="imagem <?php echo $hide_img; ?> col-md-5" id="imagem" >
                                    <br>
                                    <label>IMAGEM</label>
                                    <input type="file" name="arquivo" class="form-control" placeholder="Adicione uma imagem">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php $this->load->view('gestao_corporativa/intranet/comunicado/teste_setor_staff.php'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer w-100">
                        <button  type="submit" class="float-right btn btn-primary">Cadastrar</button>
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>

        </div>

    </div>





</div>
<?php //init_tail();       ?>

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
<script src="<?php echo base_url(); ?>assets/lte/plugins/jquery/jquery.min.js"></script>

<script src="<?php echo base_url(); ?>assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url(); ?>assets/lte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<script src="<?php echo base_url(); ?>assets/lte/dist/js/adminlte.min.js?v=3.2.0"></script>

<script src="<?php echo base_url(); ?>assets/lte/dist/js/demo.js"></script>

<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>









