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
                    <li><a href="<?= base_url('gestao_corporativa/intranet_admin/index/?group=grupos'); ?>"><i class="fa fa-backward"></i> Grupos </a></li> 
                    <li class=""> Novo </li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div class="row" style="padding: 5px;">
                        <div class="col-md-12">
                            <?php echo form_open_multipart('gestao_corporativa/intra/Grupos/salvar'); ?>
                            <div class="form-group">
                                <label>Nome do Grupo:</label>
                                <input type="text" class="form-control" name="nome" required="required" value="<?php echo $grupo->nome; ?>">
                            </div>
                            <input type="hidden" value="<?php echo $grupo->id; ?>" name="id">
                            
                            <label>Integrantes do Grupo:</label>
                            
                            <?php $this->load->view('gestao_corporativa/intranet/grupos/setor_staff.php'); ?>

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

</div>


<?php //init_tail();        ?>
<script src="<?php echo base_url() ?>assets/menu/plugins/jquery/jquery.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo base_url() ?>assets/menu/dist/js/adminltee.min.js?v=3.2.0"></script>

<script src="<?php echo base_url() ?>assets/menu/dist/js/demoe.js"></script>





