<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_admin(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/plugins/fontawesome-free/css/all.min.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/dist/css/adminltee.min.css?v=3.2.0">
<div class="content">
    <div class="row " style="width: 100%;">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb">
                <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intra/Documentos/list_'); ?>"><i class="fa fa-backward"></i> Documentos </a></li> 
                    <li><?php echo $documento->codigo . ' - ' . $documento->titulo; ?></li> 
                    <li class=""> Conteúdo </li>
                </ol>
            </div>
        </div>
        <div class="col-md-8" style="margin-left: auto;
    margin-right: auto; ">
            <div class="panel_s">
                <div class="panel-body" style="padding: 100px;">
                    <div class="clearfix"></div>
                    <?php echo $documento->cabecalho; ?>
                    <?php echo $documento->conteudo; ?>
                    <?php echo $documento->rodape; ?>
                </div>
            </div>

        </div>
        <div style="display: none;">
        <?php $this->load->view('gestao_corporativa/intranet/grupos/setor_staff.php'); ?>
    </div>
    </div>
    
</div>

<?php //init_tail();          ?>

<script src="<?php echo base_url() ?>assets/menu/plugins/jquery/jquery.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/dist/js/adminltee.min.js?v=3.2.0"></script>

<script src="<?php echo base_url() ?>assets/menu/dist/js/demoe.js"></script>

<script src="<?php echo base_url() ?>assets/intranet/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url() ?>assets/intranet/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('descricao');
</script>



