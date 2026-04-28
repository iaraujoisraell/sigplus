<input type="hidden" id="categoria_id" value="<?php echo $tipo->id; ?>" />
<div class="clearfix"></div>
<div class="col-md-12">
    <div class="form-group" app-field-wrapper="nome"><label for="name" class="control-label" >Nome do Campo</label>
        <input type="text" id="nome_campo_novo" name="nome" class="form-control"  value="" required="true" maxlength="100"></div>
</div>  
<div class="col-md-6">
    <div class="select-placeholder form-group" id="trocar">
        <label for="type" class="control-label">Tipo</label>
        <select id="type_campo_novo" onchange="change(this.value); change_separador(this.value);" class="selectpicker" data-width="100%" required="true">
            <option id="op_type" value="" disabled selected>Selecione</option>
            <option id="op_type" value="text" >Campo de texto</option>
            <option id="op_type" value="textarea"  >Caixa de texto grande</option>
            <option id="op_type" value="number"  >Número</option>
            <option id="op_type" value="select"   >Select (Apenas uma opção)</option>
            <option id="op_type" value="multiselect" >Multi Select (Mais de uma opção )</option>
            <option id="op_type" value="checkbox">Checkbox (Caixas de seleção)</option>

            <option id="op_type" value="date" id="date">Data(dd-mm-yyyy)</option>
            <option id="op_type" value="time" id="datetime-local">Hora (00:00:00)</option>
            <option id="op_type" value="color" id="color">Cor(#000000)</option>
            <option id="op_type" value="separador" id="color">Separador(Barra)</option>
            <option id="op_type" value="setores" id="color">Setores do sistema</option><!-- comment -->
            <option id="op_type" value="funcionarios" id="color">Funcionários do sistema</option>
        </select>
    </div>
</div>
<div class="col-md-6" id="tam_ocultar">
    <div class="form-group">
        <label for="bs_column" class="control-label">Grade (Coluna Bootstrap) - Máximo são 12</label>
        <div class="input-group">
            <span class="input-group-addon">col-md-</span>
            <input type="number" max="12" class="form-control" id="tam_campo_novo" value="12" required="true">
        </div>
    </div>
</div>
<div class="form-group col-md-12" style="display: none;" id="listadeopcoes">
    <label for="bs_column">Adicionar Opção</label>
    <div class="input-group duplicar" id="duplicar">
        <input type="text" class="form-control" name="options_select" id="opcao" placeholder="Escreva a opção...">
        <span class="input-group-btn">
            <button class="btn btn-success add_more_attachments p8-half" data-max="5" type="button" onclick="add_opcoes()"><i class="fa fa-plus"></i></button>
        </span>
    </div>
    <div style="display: none;">
        <div class="input-group duplicar div_tirar" id="opcao_tirar2" >

            <span class="input-group-btn" onclick="event.target.parentNode.parentNode.remove();">
                <input type="text" class="form-control input_tirar" name="options_select_new" id="options_select_new" placeholder="OPÇÃO" disabled >
            </span>
        </div>
    </div>
    <div class="caixadefilhos" id="caixadefilhos">

    </div>
</div>
<div class="clearfix"></div>
<div class="icheck-primary d-inline col-md-12" id="check_ocultar">
    <input type="checkbox" id="obrigatorio" value="1" checked>
    <label for="checkboxPrimary1" class="control-label">
        Campo Obrigatório
    </label>

</div>
<div class="col-md-12">
    <button class="btn btn-info pull-right mbot15" onclick="add_campo();" id="btn_salvar">
        <?php echo _l('submit'); ?>
    </button>
</div>
<script>

$(document).ready(function () {
        init_selectpicker();
    });

</script>