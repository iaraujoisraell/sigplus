<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$__embed = $this->input->get('embed');
$aviso = isset($aviso) && is_object($aviso) ? $aviso : (object) ['id' => '', 'foto' => '', 'titulo' => '', 'tipo' => '', 'fim' => '', 'link' => '', 'descricao' => ''];
$registro_atendimento_id = isset($registro_atendimento_id) ? $registro_atendimento_id : '';
$categories = isset($categories) ? $categories : [];
?>
<?php if ($__embed): ?>
    <?php $this->load->view('admin/includes/intranet/head'); ?>
<?php else: ?>
    <?php init_head_intranet_novo(); ?>
    <?php $this->load->view('gestao_corporativa/css_background'); ?>
<?php endif; ?>
<div class="content">

    <div class="row">
        <?php echo form_open_multipart('gestao_corporativa/intra/Pubs/add_aviso'); ?>
        <input type="hidden" name="registro_atendimento_id" id="registro_atendimento_id"
            value="<?php echo $registro_atendimento_id; ?>">
        <?php if (!$__embed): ?>
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a>
                    </li>
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin/index/?group=publicacoes'); ?>"><i
                                class="fa fa-backward"></i> Cadastros - Publicações </a></li>
                    <li class="">Novo </li>
                </ol>
            </div>
        </div>
        <?php endif; ?>
        <div class="col-md-12">

            <div class="panel_s">
                <div class="panel-heading">
                    Nova Publicação
                </div>
                <div class="panel-body">
                    <div class="row" style="padding: 5px;">

                        <input id="id" name="id" value="<?php echo $aviso->id; ?>" type="hidden" class="form-control">
                        <input id="foto" name="foto" value="<?php echo $aviso->foto; ?>" type="hidden"
                            class="form-control">
                        <div class=" col-md-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-3 col-form-label">Título:</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" placeholder="Título"
                                        value="<?php echo $aviso->titulo; ?>" name="titulo">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="select-placeholder form-group" app-field-wrapper="categoria_id">
                                        <label for="categoria_id">Portal</label>
                                        <select id="categoria_id" name="categoria_id" class="selectpicker"
                                            required="true" data-width="100%" data-none-selected-text="Nada selecionado"
                                            data-live-search="true">
                                            <option value="0">INTRANET - INTERNO</option>
                                            <!-- Aqui assumo que $categories é um array de categorias com um índice 'titulo' -->
                                            <?php foreach ($categories as $category) { ?>
                                                <option value="<?= $category['id']; ?>"><?= $category['p_title']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <?php // Publicação pela home: tipo fixo (Notícia) e validade longa ?>
                            <input type="hidden" name="radio" value="<?php echo $aviso->tipo ?: 3; ?>">
                            <input type="hidden" name="fim" value="<?php echo $aviso->fim && $aviso->fim != '0000-00-00' ? $aviso->fim : '2099-12-31'; ?>">

                            <br>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Link:</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" placeholder="Link"
                                        value="<?php echo $aviso->link; ?>" name="link">
                                </div>
                            </div>

                            <div class="dropzone-previews"></div>
                            <label class="mt-3">Imagem</label>
                            <input type="file" name="arquivo" id="arquivo" size="20" required />

                            <div id="img-container" class="w-100">
                                <img id="preview" class="w-100"
                                    src="../../../assets/intranet/img/avisos/<?php echo $aviso->foto; ?>">
                            </div>
                            <script>
                                function readImage() {
                                    if (this.files && this.files[0]) {
                                        var file = new FileReader();
                                        file.onload = function (e) {
                                            document.getElementById("preview").src = e.target.result;
                                        };
                                        file.readAsDataURL(this.files[0]);
                                    }
                                }
                                document.getElementById("arquivo").addEventListener("change", readImage, false);
                            </script>

                        </div><!-- comment -->
                        <div class=" col-md-6 h-100">
                            <label class="form-label ms-0 mb-0">Descrição</label>
                            <div id="dvCentro">
                                <textarea id="descricao" class="h-100"
                                    name="descricao"><?php echo $aviso->descricao; ?></textarea>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <div class="panel_s">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group pull-right mleft4 " >
                        <button type="submit" class="btn btn-primary" id="disabled" >
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