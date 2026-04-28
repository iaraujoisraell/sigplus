<?php if ($modal == true) { ?>
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
                <?php } ?>
                <input value="<?php echo $rel_type; ?>" type="hidden" name="rel_type<?php echo $rel_type; ?>">
                <?php if ($rel_type == 'api') { ?>
                    <input value="<?php echo $in_out; ?>" type="hidden" name="in_out">
                <?php } ?>

                <div class="row">

                    <div class="col-md-12">

                        <a href="#" class="btn btn-info mtop15 mbot10" id="teste" onclick="trocar_form();"><?php echo 'ADD Campo' ?></a>
                        <div class="clearfix"></div>
                        <input type="hidden" id="categoria_id" value="<?php echo $tipo->id; ?>" />


                        <input type="hidden" id="fluxo_id" name="fluxo_id" value="<?php echo $fluxo->id; ?>" />

                        <div class="usernote<?php echo $tipo->id; ?> hide row" id="form">

                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="container-fluid">

                        <?php if ($rel_type != 'ro_more') { ?>
                            <?php if ($rel_type == 'r.o' || $rel_type == 'cdc') { ?>

                                <div class="col-md-12">
                                    <br>
                                    <div class="horizontal-scrollable-tabs">
                                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                                        <div class="horizontal-tabs">
                                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">

                                                <?php
                                                if ($rel_type == 'r.o') {
                                                    $padrao = [];
                                                    $padrao[] = array("id" => "0", "titulo" => "Notificante", "active" => "active");
                                                    $padrao[] = array("id" => "setor_responsavel", "titulo" => "Setor Responsável");
                                                    $padrao[] = array("id" => "classificacao", "titulo" => "Classificação");

                                                    $atuantes = array_merge($padrao, $categoria_atuantes);
                                                } else {
                                                    $padrao = [];
                                                    $padrao[] = array("id" => "0", "titulo" => "Formulário CDC", "active" => "active");
                                                    $atuantes = array_merge($padrao, $categoria_atuantes);
                                                }
                                                ?>
                                                <?php
                                                foreach ($atuantes as $atuante) {
                                                ?>
                                                    <li role="presentation" class="<?php echo $atuante['active']; ?>">
                                                        <input type="hidden" name="fluxo_id_<?php echo $atuante['id']; ?>" value="<?php echo $atuante['id']; ?>" />
                                                        <a href="#atuante_<?php echo $atuante['id']; ?>" aria-controls="atuante_<?php echo $atuante['id']; ?>" role="tab" data-toggle="tab">
                                                            <?php echo $atuante['titulo']; ?>
                                                        </a>
                                                    </li>
                                                <?php
                                                }
                                                ?>

                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-content mtop15">

                                        <?php foreach ($atuantes as $atuante) { ?>
                                            <div role="atuante_<?php echo $atuante['id']; ?>" class="tab-pane <?php echo $atuante['active']; ?>" id="atuante_<?php echo $atuante['id']; ?>">
                                                <?php
                                                $table_data = [];

                                                //array_push($table_data, 'Ordem');

                                                array_push($table_data, 'Nome');

                                                array_push($table_data, 'Tipo');

                                                array_push($table_data, 'Tamanho');

                                                array_push($table_data, 'Cadastro');

                                                array_push($table_data, 'Opções');

                                                render_datatable($table_data, 'campos' . $atuante['id']);
                                                ?>
                                            </div>
                                        <?php } ?>
                                    </div>

                                <?php } else { ?>


                                    <?php
                                    $table_data = [];
                                    if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') {
                                        //array_push($table_data, 'Ordem');
                                    }
                                    array_push($table_data, 'Nome');
                                    if ($rel_type != 'api') {
                                        array_push($table_data, 'Tipo');
                                        if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') {
                                            array_push($table_data, 'Tamanho');
                                        }
                                    } else {
                                        array_push($table_data, 'Vínculo');
                                        array_push($table_data, 'Max');
                                        array_push($table_data, 'Min');
                                    }

                                    if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') {
                                        array_push($table_data, 'Cadastro');
                                    }
                                    if ($rel_type == 'r.o') {
                                        array_push($table_data, 'Para');
                                    }
                                    if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') {
                                        array_push($table_data, 'Opções');
                                    }

                                    render_datatable($table_data, 'campos');
                                    ?>
                                <?php } ?>
                            <?php } else { ?>

                                <div class="tab-content mtop5">
                                    <input value="<?php echo $rel_id; ?>" type="hidden" id="rel_id<?php echo $rel_type; ?>">

                                    <?php
                                    $table_data = [];

                                    //array_push($table_data, 'Ordem');

                                    array_push($table_data, 'Nome');

                                    array_push($table_data, 'Tipo');

                                    array_push($table_data, 'Info');

                                    array_push($table_data, 'Data');

                                    render_datatable($table_data, 'values' . $rel_type);
                                    ?>

                                </div>
                            <?php } ?>


                                </div>
                    </div>
                    <?php if ($modal == true) { ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <script>
        function mudar(el) {

            var display = document.getElementById(el).style.display;
            if (display == "none")
                document.getElementById(el).style.display = 'block';
            else
                document.getElementById(el).style.display = 'none';
        }

        function change(value) {
            if (value === 'select' || value === 'multiselect') {
                document.getElementById('listadeopcoes').style.display = 'block';
            } else {
                document.getElementById('listadeopcoes').style.display = 'none';
            }
            if (value === 'list') {
                document.getElementById('lists').style.display = 'block';
            } else {
                document.getElementById('lists').style.display = 'none';
            }
            if (value === 'textarea') {
                document.getElementById('default_div').style.display = 'block';
            } else {
                document.getElementById('default_div').style.display = 'none';
                document.getElementById('default_text').value = '';
            }
            if (value === 'date') {
                document.getElementById('date_limit_div').style.display = 'block';
            } else {
                document.getElementById('date_limit_div').style.display = 'none';
                document.getElementById('date_div').style.display = 'none';
                document.getElementById('date_limit_min').value = '';
                document.getElementById('date_limit_max').value = '';
            }
            if (value === 'setores' || value === 'list') {
                document.getElementById('just_one_div').style.display = 'block';
            } else {
                document.getElementById('just_one_div').style.display = 'none';
                document.getElementById('just_one').checked = false;
            }
        }

        function change_separador(value) {
            if (value === 'separador') {
                document.getElementById('tam_ocultar').style.display = 'none';
                document.getElementById('check_ocultar').style.display = 'none';
                document.getElementById('obrigatorio').removeAttribute("checked");
            } else {
                document.getElementById('tam_ocultar').style.display = 'block';
                document.getElementById('check_ocultar').style.display = 'block';
            }
        }
        $(document).ready(function() {
            init_selectpicker();
        });

        function add_campo() {

         //   alert("aqui"); exit;

            var div = document.getElementById("btn_salvar");
            div.innerHTML = 'Carregando...';
            document.getElementById("btn_salvar").disabled = true;
            var nome = document.querySelector("#nome_campo_novo");
            var nome = nome.value;
            var id = document.querySelector("#id");
            var id = id.value;
            <?php if ($rel_type != 'api') { ?>
                var days_min = document.getElementById('date_limit_min').value;
                var days_max = document.getElementById('date_limit_max').value;
                var default_text = document.getElementById('default_text').value;
                let just_one_ = document.getElementById('just_one');
                if (just_one_.checked) {
                    var just_one = 1;
                } else {
                    var just_one = 0;
                }
                // Obtém o elemento de entrada pelo ID

                //  alert(color);
                <?php if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') { ?>
                    var tam_coluna = document.querySelector("#tam_campo_novo");
                    var tam_coluna = tam_coluna.value;
                    var colorInput = document.getElementById("color_camp");
                    // Obtém o valor do campo de entrada
                    var color = colorInput.value;
                <?php } ?>
                var select = document.getElementById('type_campo_novo');
                var type = select.options[select.selectedIndex].value;
                var select = document.getElementById('list_id');
                var list_id = select.options[select.selectedIndex].value;
                //alert(list_id); exit;
                //document.getElementById('type_campo_novo').prop('selectedIndex', false);
                //document.getElementById('type_campo_novo').value = '';
                //$('#type_campo_novo option[id="selecione_type"]').attr('selected','selected');
                <?php if ($rel_type == 'r.o' || $rel_type == 'cdc') { ?>
                    var select = document.getElementById('preenchido_por_campo_novo');
                    var preenchido_por = select.options[select.selectedIndex].value;
                <?php } ?>
                <?php if ($rel_type == 'workflow') {
                ?>
                    var preenchido_por = '0';
                    if (document.getElementById('fluxo_id').value != '') {
                        preenchido_por = document.getElementById('fluxo_id').value;
                    }

                <?php } ?>
                var values = {
                    id: [],
                    value: []
                };
                $("input[name='options[id][]']").each(function() {
                    values.id.push($(this).val());
                });
                $("input[name='options[value][]']").each(function() {
                    values.value.push($(this).val());
                });
                let checkbox = document.getElementById('obrigatorio');
                if (checkbox.checked) {
                    var obrigatorio = 1;
                } else {
                    var obrigatorio = 0;
                }
                <?php if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') { ?>
                    let campo_chave = document.getElementById('chave');
                    if (campo_chave.checked) {
                        var chave = 1;
                    } else {
                        var chave = 0;
                    }
                    let campo_portal = document.getElementById('portal');
                    if (campo_portal.checked) {
                        var portal = 1;
                    } else {
                        var portal = 0;
                    }
                <?php } ?>
            <?php } else { ?>
                var select = document.getElementById('vinculo');
                var vinculo = select.options[select.selectedIndex].value;
                var maxlength = document.getElementById('maxlength').value;
                var minlength = document.getElementById('minlength').value;
                var in_out = '<?php echo $in_out; ?>';
            <?php } ?>

            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Categorias_campos/save_campo'); ?>",
                data: {
                    id: id,
                    nome: nome,
                    rel_type: '<?php echo $rel_type; ?>',
                    categoria_id: '<?php echo $tipo->id ?>',
                    <?php if ($rel_type != 'api') { ?>

                        <?php if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') { ?>
                            tam_coluna: tam_coluna,
                        <?php } ?>
                        type: type,
                        list_id: list_id,
                        days_min: days_min,
                        days_max: days_max,
                        default_text: default_text,
                        just_one: just_one,
                        <?php if ($rel_type == 'r.o' || $rel_type == 'workflow' ||  $rel_type == 'cdc') { ?>
                            preenchido_por: preenchido_por,
                            color: color,
                        <?php } ?>
                        options: values,
                        obrigatorio: obrigatorio,
                        <?php if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') { ?>
                            chave: chave,
                            portal: portal
                        <?php } ?>
                    <?php } else { ?>
                        vinculo: vinculo,
                        maxlength: maxlength,
                        minlength: minlength,
                        in_out: in_out
                    <?php } ?>
                },
                success: function(data) {
                    reload_campos();
                    slideToggle('.usernote<?php echo $tipo->id; ?>');
                    //$('#form').html(data.resposta2);

                    div.innerHTML = 'Salvar';
                    document.getElementById("btn_salvar").disabled = false;
                    var obj = JSON.parse(data);
                    alert_float(obj.alert, obj.message);
                }
            });
        }



        function edit_campo(id) {
           // alert("aqui"); exit;
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Categorias_campos/edit_campo'); ?>",
                data: {
                    id: id,
                    categoria_id: '<?php echo $tipo->id ?>',
                    rel_type: '<?php echo $rel_type; ?>'
                },
                success: function(data) {
                    slideToggle('.usernote<?php echo $tipo->id; ?>');
                    $('#form').html(data);
                }
            });
        }

        function trocar_form() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Categorias_campos/trocar_form'); ?>",
                data: {
                    categoria_id: '<?php echo $tipo->id ?>',
                    rel_type: '<?php echo $rel_type; ?>',
                    rel_id: '<?php echo $rel_id; ?>',
                    retorno: window.location.href
                },
                success: function(data) {
                    slideToggle('.usernote<?php echo $tipo->id; ?>');
                    $('#form').html(data);
                }
            });
        }


        $(function() {
            var CustomersServerParams = {};
            CustomersServerParams['categoria_id'] = '[id="categoria_id"]';
            CustomersServerParams['rel_type'] = '[name="rel_type<?php echo $rel_type; ?>"]';
            <?php if ($rel_type == 'api') { ?>
                CustomersServerParams['in_out'] = '[name="in_out"]';
            <?php } ?>
            <?php if ($rel_type == 'workflow') { ?>
                CustomersServerParams['preenchido_por'] = '[name="fluxo_id"]';
            <?php } ?>

            <?php if ($rel_type == 'r.o' || $rel_type == 'cdc') { ?>
                <?php foreach ($atuantes as $atuante) {
                ?> CustomersServerParams['preenchido_por'] = '[name="fluxo_id_<?php echo $atuante['id']; ?>"]';
                    initDataTable('.table-campos<?php echo $atuante['id']; ?>', '<?php echo base_url(); ?>' + 'gestao_corporativa/Categorias_campos/table_campos', [3], [3], CustomersServerParams, [1, 'desc']);
                <?php
                }
            } else {
                ?>
                <?php if ($rel_type == 'internal_request_workflow' || $rel_type == 'external_request_workflow') { ?>
                    var search = 1;
                    var sort = 1;
                <?php } else { ?>
                    var search = 3;
                    var sort = 3;
                <?php } ?>
                initDataTable('.table-campos', '<?php echo base_url(); ?>' + 'gestao_corporativa/Categorias_campos/table_campos', [search], [sort], CustomersServerParams, [1, 'desc']);
            <?php } ?>
        });

        function reload_campos() {

            <?php if ($rel_type == 'r.o' || $rel_type == 'cdc') { ?>

                <?php foreach ($atuantes as $atuante) {
                ?>

                    if ($.fn.DataTable.isDataTable('.table-campos<?php echo $atuante['id']; ?>')) {
                        $('.table-campos<?php echo $atuante['id']; ?>').DataTable().destroy();
                    }
                <?php
                }
            } else {
                ?>
                if ($.fn.DataTable.isDataTable('.table-campos')) {
                    $('.table-campos').DataTable().destroy();
                }
            <?php } ?>

            var CustomersServerParams = {};
            CustomersServerParams['categoria_id'] = '[id="categoria_id"]';
            CustomersServerParams['rel_type'] = '[name="rel_type<?php echo $rel_type; ?>"]';
            <?php if ($rel_type == 'workflow') { ?>
                CustomersServerParams['preenchido_por'] = '[id="fluxo_id"]';
            <?php } ?>
            <?php if ($rel_type == 'api') { ?>
                CustomersServerParams['in_out'] = '[name="in_out"]';
            <?php } ?>

            <?php if ($rel_type == 'r.o' || $rel_type == 'cdc') { ?>
                <?php foreach ($atuantes as $atuante) {
                ?> CustomersServerParams['preenchido_por'] = '[name="fluxo_id_<?php echo $atuante['id']; ?>"]';
                    initDataTable('.table-campos<?php echo $atuante['id']; ?>', '<?php echo base_url(); ?>' + 'gestao_corporativa/Categorias_campos/table_campos', [3], [3], CustomersServerParams, [1, 'desc']);
                <?php
                }
            } else {
                ?>
                <?php if ($rel_type == 'internal_request_workflow' || $rel_type == 'external_request_workflow') { ?>
                    var search = 1;
                    var sort = 1;
                <?php } else { ?>
                    var search = 3;
                    var sort = 3;
                <?php } ?>
                initDataTable('.table-campos', '<?php echo base_url(); ?>' + 'gestao_corporativa/Categorias_campos/table_campos', [search], [sort], CustomersServerParams, [1, 'desc']);
            <?php } ?>
        }


        <?php if ($rel_type == 'r.o') { ?>
            <?php foreach ($atuantes as $atuante) { ?>
                $(document).ready(function() {
                    $('.table-campos<?php echo $atuante['id']; ?> tbody').sortable({
                        update: function(event, ui) {
                            //var inputValue = ui.item.find('#categoria_ordem').val();
                            var ordemAtualizada = [];
                            $('.table-campos<?php echo $atuante['id']; ?> tbody tr').each(function() {
                                var inputValor = $(this).find('#campo_ordem').val();
                                ordemAtualizada.push(inputValor);
                            });
                            // Enviar os dados reordenados para o arquivo PHP
                            $.ajax({
                                url: '<?php echo base_url('gestao_corporativa/Categorias_campos/change_order_table_campos') ?>',
                                method: 'POST',
                                data: {
                                    ordem: ordemAtualizada
                                },
                                success: function(data) {
                                    var obj = JSON.parse(data);
                                    alert_float(obj.alert, obj.message);
                                },
                                error: function(xhr, status, error) {
                                    alert("Erro ao atualizar a ordem: " + error);
                                }
                            });
                        }
                    });
                });
            <?php
            }
        } else {
            ?>
            $(document).ready(function() {
                $('.table-campos tbody').sortable({
                    update: function(event, ui) {
                        //var inputValue = ui.item.find('#categoria_ordem').val();
                        var ordemAtualizada = [];
                        $('.table-campos tbody tr').each(function() {
                            var inputValor = $(this).find('#campo_ordem').val();
                            ordemAtualizada.push(inputValor);
                        });
                        // Enviar os dados reordenados para o arquivo PHP
                        $.ajax({
                            url: '<?php echo base_url('gestao_corporativa/Categorias_campos/change_order_table_campos') ?>',
                            method: 'POST',
                            data: {
                                ordem: ordemAtualizada
                            },
                            success: function(data) {
                                var obj = JSON.parse(data);
                                alert_float(obj.alert, obj.message);
                            },
                            error: function(xhr, status, error) {
                                alert("Erro ao atualizar a ordem: " + error);
                            }
                        });
                    }
                });
            });
        <?php } ?>

        function delete_campo(id) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('gestao_corporativa/Categorias_campos/delete_campo'); ?>",
                data: {
                    id: id,
                    categoria_id: '<?php echo $tipo->id ?>',
                    rel_type: '<?php echo $rel_type; ?>',
                    <?php
                    if ($rel_type == 'workflow') {
                        if ($fluxo) {
                    ?>preenchido_por: '<?php echo $fluxo->id; ?>'
                <?php } else { ?> preenchido_por: '0'
                <?php
                        }
                    }
                ?>
                },
                success: function(data) {
                    reload_campos();
                    var obj = JSON.parse(data);
                    alert_float(obj.alert, obj.message);
                }
            });
        }

        <?php if ($rel_type == 'ro_more') { ?>
            $(function() {
                var CustomersServerParams = {};
                CustomersServerParams['rel_id'] = '[id="rel_id<?php echo $rel_type; ?>"]';
                CustomersServerParams['rel_type'] = '[name="rel_type<?php echo $rel_type; ?>"]';
                initDataTable('.table-values<?php echo $rel_type; ?>', '<?php echo base_url(); ?>' + 'gestao_corporativa/Categorias_campos/table_values', [3], [3], CustomersServerParams, [1, 'desc']);
            });

            function create() {
                var nome = document.querySelector("#nome_campo_novo");
                var nome = nome.value;
                var select = document.getElementById('type_campo_novo');
                var type = select.options[select.selectedIndex].value;
                let campo_portal = document.getElementById('portal');
                if (campo_portal.checked) {
                    var portal = 1;
                } else {
                    var portal = 0;
                }
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('gestao_corporativa/Categorias_campos/create_campo'); ?>",
                    data: {
                        rel_id: '<?php echo $rel_id; ?>',
                        rel_type: 'ro',
                        nome: nome,
                        type: type,
                        portal: portal
                    },
                    success: function(data) {
                        document.getElementById("create_campo_div").innerHTML = data;
                    }
                });
            }
        <?php } ?>
    </script>