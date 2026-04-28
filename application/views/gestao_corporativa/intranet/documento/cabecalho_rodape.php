<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_admin(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/plugins/fontawesome-free/css/all.min.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/adminltee.min.css?v=3.2.0">
<div class="content">
    <div class="row w-100">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin'); ?>"><i class="fa fa-backward"></i> Cadastros </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin/index/?group=publicacoes'); ?>"><i class="fa fa-backward"></i> Publicações </a></li> 
                    <li class=""> Novo </li>
                </ol>
            </div>
        </div>
        <div class="col-md-12 w-100">
            <div class="panel_s w-100">
                <div class="panel-body w-100">
                    <div class="clearfix"></div>
                    <div class="row w-100" style="padding: 5px;">
                        <input id="id" name="id" value="<?php echo $aviso->id; ?>" type="hidden" class="form-control">
                        <input id="foto" name="foto" value="<?php echo $aviso->foto; ?>" type="hidden" class="form-control">

                        <?php echo form_open("gestao_corporativa/intra/Documentos/add_cabecalho_rodape"); ?>
                        <div class="form-group col-md-6">
                            <label>Categoria:</label>
                            <input type="text" class="form-control" value="<?php echo $categoria->titulo; ?>" disabled>
                            <input type="hidden" class="form-control" name="id" required="required" value="<?php echo $categoria->id; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tipo:</label>
                            <select class="form-control" name="tipo" id=tipo" readonly>

                                <option value="cabecalho" <?php
                                if ($tipo == 1) {
                                    $tipo = 'cabecalho';
                                    echo 'selected';
                                }
                                ?>>CABEÇALHO</option>
                                <option value="rodape" <?php
                                if ($tipo == 2) {
                                    $tipo = 'rodape';
                                    echo 'selected';
                                }
                                ?>>RODAPÉ</option>

                            </select>
                        </div>
                        <div class=" col-md-12 W-100">
                            <label class="form-label ms-0 mb-0">Descrição</label>
                            <div id="dvCentro">
                                <textarea id="summernote" class="h-100" name="descricao"><?php echo $categoria->$tipo; ?></textarea>
                            </div>
                        </div>
                        <div class="w-100">
                            <button  type="submit" class="float-right btn btn-primary">Salvar</button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

        </div>
    </div>
    <div class="hide">
        <?php $this->load->view('gestao_corporativa/intranet/grupos/setor_staff.php'); ?>

    </div>
</div>

<?php //init_tail();           ?>
<script src="<?php echo base_url() ?>assets/menu/plugins/jquery/jquery.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/dist/js/adminltee.min.js?v=3.2.0"></script>


<link href="<?php echo base_url(); ?>assets/intranet/editor/summernote.css">
<script src="<?php echo base_url(); ?>assets/intranet/editor/summernote.js"></script>
<script>
    $(document).ready(function () {
        $('#summernote').summernote({
            height: 300, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            }
        });
    });

</script>
<script type="text/javascript">

    function botar(valor) {
        alert();
        $("textarea[name='descricao']").val(valor);
    }

</script>



