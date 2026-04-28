<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<div class="content">

    <div class="row">
        <?php echo form_open("gestao_corporativa/intra/Comunicado/send_comunicado", array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>
        
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intra/Comunicado'); ?>"><i class="fa fa-backward"></i> Comunicados Internos </a></li> 
                    <li class=""> Novo </li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">

            <div class="panel_s">
                <div class="panel-heading">
                    Destinatários
                </div>
                <div class="panel-body">
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fa fa-check"></i> Tudo certo!!</h5>
                        O comunicado foi salvo, agora vamos aos destinatários.
                    </div>
                    <?php echo form_open("gestao_corporativa/intra/Comunicado/send_comunicado", array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>
                    <input type="hidden" value="<?php echo $id; ?>" name="id">
                    <?php $this->load->view('gestao_corporativa/intranet/comunicado/setor_staff.php'); ?>
                    <?php 
                    $this->load->model('Staff_model');
                    $staffs = $this->Staff_model->get();
                    
                    //print_r($staffs);?>
                    <div class="form-group" style="margin-top: 10px;">

                        <label class="control-label">CC</label>
                        <div class="select2-blue">
                            <select class="select2" multiple data-placeholder="Selecione" data-dropdown-css-class="select2-blue" style="width: 100%;" name="cc[]">
                                <?php foreach ($staffs as $staff) { ?>
                                    <option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
                                <?php } ?>

                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="panel_s">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">



                            <div class="btn-group pull-right mleft4 " >
                                <button type="submit" class="btn btn-info" >
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

<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    });

    // DropzoneJS Demo Code End
</script>


</body>
</html>
