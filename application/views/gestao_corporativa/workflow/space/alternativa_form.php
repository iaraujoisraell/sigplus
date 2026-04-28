<?php
$this->load->model('Departments_model');
$setores = $this->Departments_model->get();
?>


<?php echo form_open('', array('id' => 'form_alternative', 'onsubmit' => "document.getElementById('disabled').disabled = true;")); ?>
<div class="panel_s" id="fluxo_novo">
    <div class="panel-heading">
        NOVA ALTERNATIVA
    </div>
    
    <div class="panel-body ">
        <div class="row">
            
            <?php echo render_input('categoria_id', '', $categoria_id, 'hidden')?>
            <div class="col-md-4">
                <?php echo render_input('alternativa', 'Alternativa', '', 'text', array('required' => 'true')); ?>
            </div><!-- comment -->
            <div class="col-md-4">
                <?php echo render_select('setor', $setores, array('departmentid', 'name'), 'Setor', '', array('required' => 'true')); ?>
            </div>
            <div class="col-md-4">
                <?php echo render_select('template', $models, array('id', 'codigo_sequencial'), 'Modelo'); ?>
            </div>

        </div>
    </div>
    <div class="panel-footer ">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group pull-right mleft4 " >
                    <button class="btn btn-success btn-xs" id="disabled" type="submit"><i class="fa fa-check"></i> Salvar</button>
                </div>
            </div>
        </div>

    </div>
</div>
    <?php echo form_close(); ?>

    <script>

        $(document).ready(function () {
            init_selectpicker();
        });

        $('#form_alternative').submit(function (event) {
            event.preventDefault(); // Prevent default form submission
            var formData = $(this).serialize(); // Serialize form data

            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/workflow/add_fluxo_question/'.$fluxo_id); ?>",
                data: formData,
                success: function (data) {
                    if (data == 'PRAZO EXCEDIDO') {
                        var card = document.getElementById("mensagem_card");
                        card.style.display = 'block';
                        var element = document.getElementById("mensagem");
                        element.innerHTML = '<i class="icon fa fa-info-circle"></i> PRAZO EXCEDIDO!';
                    } else {
                        $(".caixadefilhos_alternativa").append(data);
                        reload_fluxos();
                        $('#form_alternative')[0].reset(); // Reset form fields after successful submission
                        var node = document.getElementById("form_alternative");
                            if (node.parentNode) {
                                node.parentNode.removeChild(node);
                            }
                       
                    }
                     document.getElementById('add').disabled = false;
                }
            });
        });

    </script>