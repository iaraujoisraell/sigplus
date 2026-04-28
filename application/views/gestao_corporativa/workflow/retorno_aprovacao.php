
<div class="panel_s">
    <div class="panel-heading">
        APROVADOR
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fa fa-info-circle"></i> A CATEGORIA EXIGE APROVADOR ANTES DO SEGUIMENTO DO WORKFLOW!</h5>
                    Selecione o aprovador do seu setor no campo abaixo.
                </div>
            </div>
            <div class="col-md-12 mbot20 before-ticket-message">
                <div class="row">
                    <?php
                    $this->load->model('Workflow_model');
                    $departamentos = $this->Workflow_model->get_departamentos();
                    if (count($departamentos) == 0) {
                        ?>
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h7><i class="icon fa fa-exclamation"></i> Você precisa estar vinclulado a um setor que tenha aprovador!</h7>
                            </div>
                        </div>

                        <?php
                    }
                    ?>
                    <div class="select-placeholder form-group   col-md-12" app-field-wrapper="campo_teste_para_o_primeiro_de_novo_[]">
                        <label for="aprovador" class="control-label">Aprovador </label>
                        <select id="aprovador" name="aprovador" class="selectpicker" required="required" data-width="100%" data-none-selected-text="Nada selecionado" data-live-search="true">
                            <?php foreach ($departamentos as $dep) { ?>
                                <option value="<?php echo $dep['departmentid']; ?>"><?php echo $dep['name']; ?> - <?php echo $dep['firstname']; ?> <?php echo $dep['lastname']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>

        $(document).ready(function () {
            init_selectpicker();
        });

    </script>
</div>
