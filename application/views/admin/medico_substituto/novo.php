<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modal fade" id="add_substituto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo 'Cadastrar/Editar Substituto'; ?></span>

                </h4>
            </div>
            <?php echo form_open('admin/Medico_substituto/manage', array('id' => 'invoice_item_form')); ?>
            <?php echo form_hidden('id'); ?>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Médico Titular</label>
                        <select name="id_titular"  id="ativo" required="true" class="form-control">
                            <option selected disabled>Selecione</option>
                            <?php foreach ($medicos as $medico) { ?>
                                <option value="<?php echo $medico['medicoid'] ?>"><?php echo $medico['nome_profissional']; ?></option>
                            <?php } ?>
                        </select>
                        <br>
                        <label>Médico Substituto</label>
                        <select name="id_substituto"  id="ativo" required="true" class="form-control">
                            <option selected disabled>Selecione</option>
                            <?php foreach ($medicos as $medico) { ?>
                                <option value="<?php echo $medico['medicoid'] ?>"><?php echo $medico['nome_profissional']; ?></option>
                            <?php } ?>
                        </select>
                        <br>
                        <label>Unidade</label>
                        <select name="unidade_id"  id="unidade" required="true" class="form-control" onchange="setores(this.value);"> 
                            <option selected disabled>Selecione</option>
                            <?php foreach ($unidades as $unidade) { ?>
                                <option value="<?php echo $unidade['id'] ?>"><?php echo $unidade['razao_social']; ?></option>
                            <?php } ?>
                        </select>
                        <br>
                        <div id="setores_unidades">

                        </div>
                        <label>Data de validade</label>
                        <input type="date"  name="data_validade" id="plantao"  value="" class="form-control">
                        <br>
                        <label>Motivo</label>
                        <textarea class="form-control col-md-12" id="motivo" name="motivo" rows="3"></textarea>
                        <br>


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>


<?php foreach ($substitutos as $sub) { ?>
    <div class="modal fade" id="edit_substituto<?php echo $sub['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <span class="edit-title"><?php echo 'Cadastrar/Editar Substituto'; ?></span>

                    </h4>
                </div>
                <?php echo form_open('admin/Medico_substituto/manage', array('id' => 'invoice_item_form')); ?>
                <input type="hidden"  name="id" id="id"  value="<?php echo $sub['id']; ?>" class="form-control">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Médico Titular</label>
                            <select name="id_titular"  id="ativo" required="true" class="form-control">
                                <option selected disabled>Selecione</option>
                                <?php
                                foreach ($medicos as $medico) {
                                    $selected = '';
                                    if ($sub['id_titular'] == $medico['medicoid']) {
                                        $selected = 'selected';
                                    }
                                    ?>

                                    <option value="<?php echo $medico['medicoid'] ?>" <?php echo $selected; ?>><?php echo $medico['nome_profissional']; ?></option>
                                <?php } ?>
                            </select>
                            <br>
                            <label>Médico Substituto</label>
                            <select name="id_substituto"  id="ativo" required="true" class="form-control">
                                <option selected disabled>Selecione</option>
                                <?php
                                foreach ($medicos as $medico) {
                                    $selected = '';
                                    if ($sub['id_substituto'] == $medico['medicoid']) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?php echo $medico['medicoid'] ?>" <?php echo $selected; ?>><?php echo $medico['nome_profissional']; ?></option>
                                <?php } ?>
                            </select>
                            <br>
                            <label>Unidade</label>
                            <select name="unidade_id"  id="unidade" required="true" class="form-control" onchange="setores<?php echo $sub['id']; ?>(this.value);"> 
                                <option selected disabled>Selecione</option>
                                <?php
                                foreach ($unidades as $unidade) {
                                    $selected = '';
                                    if ($sub['setor_id'] == $unidade['id']) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?php echo $unidade['id'] ?>"><?php echo $unidade['razao_social']; ?></option>
                                <?php } ?>
                            </select>
                            <br>
                            <div id="setores_unidades<?php echo $sub['id'] ?>">

                            </div>
                            <label>Data de validade</label>
                            <input type="date"  name="data_validade" id="plantao"  value="<?php echo $sub['data_validade']; ?>" class="form-control">
                            <br>
                            <label>Motivo</label>
                            <textarea class="form-control col-md-12" id="motivo" name="motivo" rows="3"><?php echo $sub['motivo']?></textarea>
                            <br>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <script>

        function setores<?php echo $sub['id']; ?>(unidade_id) {
            $.ajax({
                type: "POST",
                url: "<?php echo admin_url("gestao_plantao/retorno_setores_gestao_escala"); ?>",
                data: {
                    unidade_id: unidade_id
                },
                success: function (data) {
                    $('#setores_unidades<?php echo $sub['id']; ?>').html(data);
                }
            });
        }
    </script>
<?php } ?>
<script>

    function setores(unidade_id) {
        $.ajax({
            type: "POST",
            url: "<?php echo admin_url("gestao_plantao/retorno_setores_gestao_escala"); ?>",
            data: {
                unidade_id: unidade_id
            },
            success: function (data) {
                $('#setores_unidades').html(data);
            }
        });
    }
</script>
