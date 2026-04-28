<style>
    .red-text {
        color: red;
    }
</style>
<div class="modal fade" id="add_terceiro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php if ($terceiro->id != '') {
                        echo 'Editar Empresa Tercerizada';
                    } else {
                        echo 'Adicionar Empresa Tercerizada';
                    }
                    ?>
                </h4>
            </div>



            <?php echo form_open(base_url('gestao_corporativa/Company/add_terceiros/' . $terceiro->id)); ?>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6">


                        <div class="form-group" app-field-wrapper="name">
                            <label for="name" class="control-label"><span class="red-text">*</span>Nome da Empresa</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo $terceiro->company; ?>">
                        </div>


                        <div class="form-group" app-field-wrapper="razao">
                            <label for="razao" class="control-label"><span class="red-text">*</span>Razão Social</label>
                            <input type="text" id="razao" name="razao" class="form-control" value="<?php echo $terceiro->razao; ?>">
                        </div>


                        <div class="form-group" app-field-wrapper="cnpj">
                            <label for="cnpj" class="control-label"><span class="red-text">*</span>CNPJ da Empresa</label>
                            <input type="text" id="cnpj" name="cnpj" class="form-control cnpj" value="<?php echo $terceiro->cnpj; ?>">
                        </div>

                    </div>


                    <div class="col-md-6">


                        <div class="form-group" app-field-wrapper="email">
                            <label for="email" class="control-label"><span class="red-text">*</span>E-mail Corporativo</label>
                            <input type="text" id="email" name="email" class="form-control" value="<?php echo $terceiro->email; ?>">
                        </div>

                        <div class="form-group" app-field-wrapper="fone">
                            <label for="fone" class="control-label"><span class="red-text">*</span>Telefone Corporativo</label>
                            <input type="text" id="fone" name="fone" class="form-control celular" value="<?php echo $terceiro->fone; ?>">
                        </div>

                        <div class="select-placeholder form-group" app-field-wrapper="responsavel">
                            <label for="responsavel" class="control-label"><span class="red-text">*</span>Responsável da Empresa</label>
                            <select id="responsavel" name="responsavel" class="selectpicker" data-width="100%" data-none-selected-text="Nada selecionado" data-live-search="true">
                                <?php foreach ($staffs as $staff) { ?>
                                    <?php
                                    $respid = '';
                                    if ($staff['staffid'] == $terceiro->responsavel) {
                                        $respid = 'selected';
                                    }
                                    ?>
                                    <option <?php echo $respid; ?> value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
                                <?php } ?>
                            </select>
                        </div>



                    </div>

                    <div class="col-md-1">

                        <div class="form-group" app-field-wrapper="cor">
                            <label for="cor" class="control-label"><span class="red-text">*</span>Cor</label>
                            <input type="color" id="cor" name="cor" class="form-control cor" value="<?php echo $terceiro->cor; ?>">
                        </div>

                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('.celular').mask('(00) 00000-0000');
    });
</script>
<script>
    $(document).ready(function() {
        $('.cnpj').mask('00.000.000/0001-00');
    });

    $(document).ready(function() {
        init_selectpicker();
    });
</script>