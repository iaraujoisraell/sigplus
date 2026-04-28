<?php
$this->load->model('Departments_model');
$setores = $this->Departments_model->get();
?>
<div class="row" style="border-left: 1px solid #e2e2e2; padding: 5px;">
    <ol class="breadcrumb" style="">
        <li><a  class="label label-sm label-danger" style="background-color: orange; color: white;" onclick="fechar();"><i class="fa fa-times"></i></a><a href=""> <?php echo $fluxo->codigo_sequencial; ?> - <?php echo $fluxo->setor_name; ?> </a></li> 
    </ol>
    <div class="col-md-12" id="">

        <?php echo render_input('codigo_sequencial' . $fluxo->id, '', $fluxo->codigo_sequencial, 'hidden'); ?>
        <?php echo render_input('categoria_id' . $fluxo->id, '', $fluxo->categoria_id, 'hidden'); ?>
        <div class="col-md-6">
            <?php echo render_select('setor' . $fluxo->id, $setores, array('departmentid', 'name'), 'Setor', $fluxo->setor, array('required' => 'true')); ?>
        </div>
        <div class="col-md-6">
            <?php echo render_input('prazo' . $fluxo->id, 'Prazo', $fluxo->prazo, 'text', array('required' => 'true')); ?>


        </div>
        <div class="col-md-12">
            
            <?php echo render_textarea('objetivo' . $fluxo->id, 'Objetivo', $fluxo->objetivo); ?>
            <div class="icheck-primary d-inline" data-toggle="tooltip" data-toggle="end" title="Fazer desse fluxo um modelo para outros.">
                    <input type="checkbox" id="template" <?php
                if ($fluxo->template == 1) {
                    echo 'checked';
                }
                ?>>
                    <label for="template" class="control-label">
                        MODELO
                    </label>

                </div>
            <div class="icheck-primary d-inline">
                <input type="checkbox" id="contato_cliente" value="1"<?php
                if ($fluxo->contato_cliente == 1) {
                    echo 'checked';
                }
                ?>>
                <label for="checkboxPrimary1" class="control-label">
                    NECESSÁRIO CONTATO COM O CLIENTE
                </label>

            </div>

            <?php
            $this->load->model('Workflow_model');

            $response = $this->Workflow_model->if_last_finish_client($fluxo->id);
            if ($response == false) {
                ?>
                <div class="icheck-primary d-inline">
                    <input type="checkbox" id="finaliza_cliente" value="1"<?php
                    if ($fluxo->finaliza_cliente == 1) {
                        echo 'checked';
                    }
                    ?> >
                    <label for="checkboxPrimary1" class="control-label">
                        FINALIZAR COM O CLIENTE
                    </label>

                </div>
<?php } else { ?>
                <div class="icheck-primary d-inline" data-toggle="tooltip" data-toggle="end" title="Existe um fluxo que finaliza com o cliente antes desse.">
                    <input type="checkbox" id="finaliza_cliente" disabled >
                    <label for="checkboxPrimary1" class="control-label">
                        FINALIZAR COM O CLIENTE
                    </label>

                </div>
<?php } ?>
        </div>

        <div class="col-md-12">
            <a class="btn btn-success btn-xs w-100" data-toggle="tooltip" onclick="edit_atual('<?php echo $fluxo->id; ?>');"> Salvar <i class="fa fa-save"></i></a>
        </div>
    </div>
</div>
<script>

    $(document).ready(function () {
        init_selectpicker();
    });

    function edit_atual() {

        var prazo = document.querySelector("#prazo" + '<?php echo $fluxo->id; ?>');

        var prazo = prazo.value;

        var codigo_sequencial = document.querySelector("#codigo_sequencial" + '<?php echo $fluxo->id; ?>');
        var codigo_sequencial = codigo_sequencial.value;

        var categoria_id = document.querySelector("#categoria_id" + '<?php echo $fluxo->id; ?>');
        var categoria_id = categoria_id.value;

        var objetivo = document.querySelector("#objetivo" + '<?php echo $fluxo->id; ?>');
        var objetivo = objetivo.value;

        var select = document.getElementById('setor' + '<?php echo $fluxo->id; ?>');
        var setor = select.options[select.selectedIndex].value;

        let checkbox = document.getElementById('contato_cliente');
        if (checkbox.checked) {
            var contato_cliente = 1;
        } else {
            var contato_cliente = 0;
        }

        let checkbox2 = document.getElementById('finaliza_cliente');
        if (checkbox2.checked) {
            var finaliza_cliente = 1;
        } else {
            var finaliza_cliente = 0;
        }
        
        let checkbox3 = document.getElementById('template');
        if (checkbox3.checked) {
            var template = 1;
        } else {
            var template = 0;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/edit_fluxo'); ?>",
            data: {
                id: '<?php echo $fluxo->id; ?>', prazo: prazo, objetivo: objetivo, setor: setor, contato_cliente: contato_cliente, finaliza_cliente: finaliza_cliente, categoria_id: categoria_id, template: template,
        codigo_sequencial: codigo_sequencial
            },
            success: function (data) {
                reload_fluxos('<?php echo $fluxo->id; ?>');

            }
        });
    }

</script>


