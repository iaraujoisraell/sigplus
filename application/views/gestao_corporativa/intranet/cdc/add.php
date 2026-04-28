<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">

    <div class="row">
        <?php echo form_open_multipart($this->uri->uri_string(), array('id' => 'new_ro_form')); ?>
        <input type="hidden" name="registro_atendimento_id" id="registro_atendimento_id" value="<?php echo $registro_atendimento_id; ?>">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Registro_ocorrencia'); ?>"><i class="fa fa-backward"></i> Registro de Ocorrência </a></li> 
                    <li class="">Novo Registro </li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">

            <div class="panel_s">
                <div class="panel-heading">
                    Novo Registro
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">

                            <?php
                            echo render_select('categoria_id', $categorias, array('id', 'titulo', 'name'), 'Categoria', [], array('required' => 'true'));
                            ?>
                            <?php echo render_input('assunto', 'Assunto', '', 'text', array('required' => 'true')); ?>


                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php echo render_date_input('data_ocorrido', 'Data do Ocorrido', ''); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    $priorities['callback_translate'] = 'ticket_priority_translate';
                                    echo render_select('priority', $priorities, array('priorityid', 'name'), 'ticket_settings_priority', hooks()->apply_filters('new_ticket_priority_selected', 3), array('required' => 'true'));
                                    ?>
                                </div>
                            </div>
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
                        </div>
                        <div class="col-md-12">
                            <?php echo render_textarea('descricao', 'Relato Detalhado', '', array(), array(), '', 'tinymce'); ?>
                        </div>

                    </div>
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
<script>


    $(document).on('change', "#categoria_id", function () {
        var select = document.getElementById("categoria_id");
        var opcaoValor = select.options[select.selectedIndex].value;
        if (opcaoValor != "") {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/categorias_campos/retorno_categoria_campos?rel_type=r.o'); ?>",
                data: {
                    categoria_id: opcaoValor
                },
                success: function (data) {
                    $('#trocar').html(data);
                }
            });
        } else {
            alert('Selecione euma categoria!');
        }
    });
</script>
