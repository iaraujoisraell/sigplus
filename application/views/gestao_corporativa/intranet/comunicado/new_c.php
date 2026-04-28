<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">

    <div class="row">
        <?php echo form_open("gestao_corporativa/intra/Comunicado/add_comunicado", array('id' => 'testetesteteste')); ?>
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
                    Novo Comunicado Interno
                </div>
                <div class="panel-body">


                    <?php echo render_input('titulo', 'Titulo', '', 'text', array('required' => 'true')); ?>


                    <?php
                    $this->load->model('Departments_model');
                    $departments = $this->Departments_model->get_staff_departments();
                    echo render_select('setor_id', $departments, array('departmentid', 'name'), 'Setor', [], array('required' => 'true'));
                    ?>

                    <label class="mt-4 control-label">Descrição:</label>

                    <div id="dvCentro">
                        <textarea id="descricao" name="descricao"></textarea>
                    </div>
                    <br>
                    <div class="row attachments">
                        <div class="attachment">
                            <div class="col-md-12 col-md-offset12 mbot15">
                                <div class="form-group">
                                    <label for="attachment" class="control-label"><?php echo _l('ticket_add_attachments'); ?></label>
                                    <div class="input-group">
                                        <input type="file" extension="<?php echo str_replace(['.', ' '], '', get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success add_more_attachments p8-half" data-max="<?php echo get_option('maximum_allowed_ticket_attachments'); ?>" type="button"><i class="fa fa-plus"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="icheck-primary d-inline">

                        <input type="checkbox" id="checkboxPrimary1" value="1" name="retorno">
                        <label for="checkboxPrimary1">
                            EXIGIR RETORNO
                        </label>
                    </div>



                    <?php //$this->load->view('gestao_corporativa/intranet/comunicado/setor_staff.php'); ?>
                </div>
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


<script src="<?php echo base_url() ?>assets/intranet/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('descricao');
</script>

</body>
</html>
