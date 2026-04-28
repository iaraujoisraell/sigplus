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
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin'); ?>"><i class="fa fa-backward"></i> Cadastros </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin/index/?group=menu'); ?>"><i class="fa fa-backward"></i> Menus </a></li> 
                    <?php if ($id_pai) { ?>
                        <li class=""> <?php echo $menu_pai->nome_menu; ?> </li>
                        <li class=""> Novo Submenu </li>
                    <?php } else if ($menu) { ?>
                        <li class=""> <?php echo $menu->nome_menu; ?> </li>
                    <?php } else { ?>
                        <li class=""> Novo </li>
                    <?php } ?>

                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <?php if($id_pai) { ?>
                        <div class="callout callout-info">
                            <h5>VOCÊ ESTÁ CADASTRANDO UM SUBMENU!</h5>
                            <p>O que significa que esse menu está vinculado a um superior.</p>
                        </div>
                    <?php } ?>
                    <div class="clearfix"></div>
                    <div class="row" style="padding: 5px;">
                        <div class="col-md-12">
                            <?php echo form_open("gestao_corporativa/intra/Menus/salvar"); ?>
                            <div class="form-group col-md-6">
                                <label>Título do menu:</label>
                                <input type="text" class="form-control" name="nome" required="required" value="<?php echo $menu->nome_menu; ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Url:</label>
                                <input type="text" style="display: block;" name="url_disabled" id="url_disabled" class="form-control" value="<?php if ($menu) {
                                echo $menu->urk;
                            } else {
                                echo (base_url() . 'Paginas/titulo_da_sua_pagina');
                            } ?>" disabled>
                                <input type="text" style="display: none;" name="url" id="url" class="form-control" value="" placeholder="Ex: https://www.google.com/">
                            </div>
                            <div class="form-group  col-md-12">
                                <div class="icheck-primary d-inline">

                                    <input type="checkbox" id="checkboxPrimary1" onclick="mudar('url'); mudar('url_disabled'); mudar('conteudo');" >
                                    <label for="checkboxPrimary1">
                                        Link externo
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Ordem:</label>
                                <input type="number" name="ordem"  class="form-control" value="<?php echo $menu->ordem; echo $ordem_menu;?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Icon:</label>
                                <input type="text" name="icon"  class="form-control" value="<?php echo $menu->icon; ?>" placeholder="Ex: fa fa-link">
                                <span class="" style="font-size: 12px; margin-left: 3px;">Icones: <a target="_blank" href="https://fontawesome.com/v4/icons/">CLASSE FA</a></span>
                            </div>
                            <input type="hidden" style="display: block;" name="menu_pai" id="menu_pai" class="form-control" value="<?php echo $id_pai; ?>">
                            <input type="hidden" style="display: block;" name="id" id="id" class="form-control" value="<?php echo $menu->id; ?>">

                            <div class="form-group col-md-12" id="conteudo" style="display: block;">
                                <label>Conteúdo da página:</label>
                                <textarea id="descricao" name="descricao"> <?php echo $menu->conteudo; ?>"</textarea>
                            </div>



                            <div class="w-100">
                                <button  type="submit" class="float-right btn btn-primary">Salvar</button>
                            </div>
<?php echo form_close(); ?> 

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hide">
<?php $this->load->view('gestao_corporativa/intranet/grupos/setor_staff.php'); ?>
    </div>
</div>


<?php //init_tail();         ?>

<script>
    function mudar(el) {
        var display = document.getElementById(el).style.display;
        if (display == "none")
            document.getElementById(el).style.display = 'block';
        else
            document.getElementById(el).style.display = 'none';
    }
</script>

<script src="<?php echo base_url() ?>assets/menu/plugins/jquery/jquery.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/dist/js/adminltee.min.js?v=3.2.0"></script>

<script src="<?php //echo base_url()    ?>assets/menu/dist/js/demoe.js"></script>
<script src="<?php echo base_url(); ?>assets/intranet/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('descricao');
</script>






