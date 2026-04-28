<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <?php echo form_open_multipart('gestao_corporativa/intra/Arquivos/salvar'); ?>
                <div class="panel-body">
                    <div class="clearfix"></div>
                    <div>
                        <div class="tab-content">
                            <ol class="breadcrumb">
                                <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                                <li><a href="<?= base_url('gestao_corporativa/intranet/cadastros'); ?>"><i class="fa fa-backward"></i> Cadastros </a></li> 
                                <li><a href="<?= base_url('gestao_corporativa/intranetadastros/?group=arquivos'); ?>>"<i class="fa fa-backward"></i> Arquivos </a></li> 
                                <li class="">Novo</li>
                            </ol>
                            <div class="row">
                                <div class="col-md-12 mt-3">

                                     <?php echo render_input('titulo', 'Título', $aviso->titulo, 'text', $attrs); ?>
                                    <label class="form-label mt-4">Documento:</label> 
                                    <input class="form-control p-2" name="arquivo" type="file"/>
                                    <input type="hidden" value="<?php echo $id; ?>" name="id">

                                    <label class="form-label" style="margin-top: 15px;">Receptores:</label> 
                                    <?php $this->load->view('gestao_corporativa/intranet/comunicado/teste_setor_staff.php'); ?>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <button class="btn bg-gradient-dark pull-right mbot15" style="margin-top: 10px;"  type="submit" >
                        Salvar
                    </button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

</div>


<?php init_tail(); ?>




<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>0

<!--   Core JS Files   -->
<script src="../assets/intranet/js/core/popper.min.js"></script>
<script src="../assets/intranet/js/core/bootstrap.min.js"></script>
<script src="../assets/intranet/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/intranet/js/plugins/smooth-scrollbar.min.js"></script>
<!-- Kanban scripts -->
<script src="../assets/intranet/js/plugins/dragula/dragula.min.js"></script>
<script src="../assets/intranet/js/plugins/jkanban/jkanban.js"></script>
<script src="../assets/intranet/js/plugins/chartjs.min.js"></script>
<script src="../assets/intranet/js/plugins/world.js"></script>
<script>
    $(function () {
        $("#tabs").tabs();
    });
</script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }

</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/intranet/js/material-dashboard.min.js?v=3.0.2"></script>

<script src="../assets/intranet/js/plugins/fullcalendar.min.js"></script>



<script src="../../../assets/intranet/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('descricao');
</script>
<srcipt
    >

</srcipt>






