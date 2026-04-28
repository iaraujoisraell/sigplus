<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">

    <div class="row">
        <?php echo form_open_multipart($this->uri->uri_string(), array('id' => 'workflow_form','onsubmit' => "document.getElementById('disabled').disabled = true;")); ?>
        <input type="hidden" name="registro_atendimento_id" id="registro_atendimento_id" value="<?php echo $registro_atendimento_id; ?>">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Workflow'); ?>"><i class="fa fa-backward"></i> Workflow </a></li> 
                    <li class="">Novo </li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">

            <div class="panel_s">
                <div class="panel-heading">
                        Novo Workflow
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            echo render_select('categoria_id', $categorias, array('id', 'titulo'), 'Categoria', [], array('required' => 'true'));
                            ?>
                        </div>

                    </div>
                    
                    <?php //$this->load->view('gestao_corporativa/intranet/comunicado/setor_staff.php'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" id="trocar">

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
    
    $(document).ready(function () {
            $('#workflow_form').submit(function (e) {
                e.preventDefault(); // Prevent the default form submission
                var uploadForm = $('#workflow_form');
                var formData = new FormData($('#workflow_form')[0]);
                // Inicialize um objeto que irá conter o mapeamento de campo de entrada para destino
                var fieldToTargetMap = {};
                var fieldValueMap = {};

                // Percorra cada campo de entrada de arquivo
                uploadForm.find('input[type="file"]').each(function () {
                    var field = $(this);
                    var fieldName = field.attr('name');
                    var target = field.data('target');
                   // alert(target); exit;
                    var value = field.data('name_value');
                    fieldToTargetMap[fieldName] = target;
                    fieldValueMap[fieldName] = value;
                });

                // Adicione o mapeamento ao FormData
                formData.append('fieldToTargetMap', JSON.stringify(fieldToTargetMap));
                formData.append('fieldValueMap', JSON.stringify(fieldValueMap));
                formData.append('target', '0');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url('controle_upload/All.php'); ?>', // Substitua com o URL do seu script PHP
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        var obj = JSON.parse(response);
                        $('form input[type="file"]').each(function () {
                            var fileInput = $(this);
                            if (fileInput.length > 0) {
                                var inputName = fileInput.attr('name');
                                fileInput.attr('type', 'text');
                                fileInput.val(obj[inputName]);

                            }
                        });


                        // Condition is satisfied, submit the form
                        $('#workflow_form').unbind('submit').submit();

                    }
                });
            });
        });
</script>
