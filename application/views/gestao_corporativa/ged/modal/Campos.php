<div class="modal fade" id="Campos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo 'Campos de: ' . strtoupper($tipo->titulo); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <a href="#" class="btn btn-info mtop15 mbot10" id="teste" onclick="slideToggle('.usernote<?php echo $tipo->id; ?>');
                                return false;"><?php echo 'ADD Campo' ?></a>
                        <div class="clearfix"></div>
                        <div class="usernote<?php echo $tipo->id; ?> hide row" id="form">

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
                        </div>
                        <div>

                        </div>




                    </div>
                </div>
                <div class="row">
                    <div class="container-fluid">

                        <?php
                        $table_data = [];

                        $table_data = array_merge($table_data, array(
                            'Ordem',
                            'Nome',
                            'Tipo',
                            'Tamanho',
                            'Cadastro',
                            'Opções'
                        ));
                        render_datatable($table_data, 'campos');
                        ?>



                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div>
    </div>
</div>
<script>
    function add_opcoes(value) {
        document.getElementById('options_select_new').value = document.getElementById('opcao').value;

        var div = document.getElementById('opcao_tirar2'),
                clone = div.cloneNode(true); // true means clone all childNodes and all event handlers
        $('.caixadefilhos').append(clone);
        //deletclass('duplicar' + id);

        document.getElementById('opcao').value = ''; // Limpa o campo
    }
    $(document).ready(function () {
        init_selectpicker();
    });
    $(function () {
        var CustomersServerParams = {};
        CustomersServerParams['categoria_id'] = '[id="categoria_id"]';
        var tAPI = initDataTable('.table-campos', '<?php echo base_url(); ?>' + 'gestao_corporativa/Ged/table_campos', [0], [0], CustomersServerParams, [1, 'desc']);
    });

    function delete_campo(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Ged/delete_campo'); ?>",
            data: {
                id: id, categoria_id: '<?php echo $tipo->id ?>'
            },
            success: function (data) {
                reload_campos();
            }
        });
    }

    function reload_campos() {

        if ($.fn.DataTable.isDataTable('.table-campos')) {
            $('.table-campos').DataTable().destroy();
        }
        var CustomersServerParams = {};
        CustomersServerParams['categoria_id'] = '[id="categoria_id"]';
        var tAPI = initDataTable('.table-campos', '<?php echo base_url(); ?>' + 'gestao_corporativa/Ged/table_campos', [0], [0], CustomersServerParams, [1, 'desc']);
    }
    function add_campo() {

        var div = document.getElementById("btn_salvar");

        div.innerHTML = 'Carregando...';
        document.getElementById("btn_salvar").disabled = true;
        var nome = document.querySelector("#nome_campo_novo");
        var nome = nome.value;
        var tam_coluna = document.querySelector("#tam_campo_novo");

        var tam_coluna = tam_coluna.value;
        
        var select = document.getElementById('type_campo_novo');
        var type = select.options[select.selectedIndex].value;
        var inputs = jQuery('input[name="options_select_new"]');
        var values = [];
        for (var i = 1; i < inputs.length; i++) {
            if ($(inputs[i]).val() !== '') {
                values.push($(inputs[i]).val());
            }
        }
        
        let checkbox = document.getElementById('obrigatorio');
        if (checkbox.checked) {
            var obrigatorio = 1;
        } else {
            var obrigatorio = 0;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Ged/save_campo'); ?>",
            data: {
                nome: nome, tam_coluna: tam_coluna, type: type, categoria_id: '<?php echo $tipo->id ?>', options: values, obrigatorio: obrigatorio
            },
            success: function (data) {
                reload_campos();
                slideToggle('.usernote<?php echo $tipo->id; ?>');
                $('#form').html(data);
                div.innerHTML = 'Salvar';
                document.getElementById("btn_salvar").disabled = false;

            }
        });
    }

    function subir(ordem) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/subir'); ?>",
            data: {
                ordem: ordem, categoria_id: '<?php echo $tipo->id ?>'
            },
            success: function (data) {
                reload_campos();

            }
        });
    }

    function descer(ordem) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Registro_ocorrencia/descer'); ?>",
            data: {
                ordem: ordem, categoria_id: '<?php echo $tipo->id ?>'
            },
            success: function (data) {
                reload_campos();

            }
        });
    }

</script>