<?php if ($rel_type == 'ro_more') { ?>
    <?php echo form_open_multipart('gestao_corporativa/Intranet_general/add_more_info', array('id' => 'more_info_form')); ?>
<?php } ?>
<input type="hidden" id="categoria_id" value="<?php echo $tipo->id; ?>" />
<input type="hidden" id="id" value="<?php echo $campo->id; ?>" />

<div class="clearfix"></div>
<div class="col-md-<?php if ($rel_type == 'r.o' || $rel_type == 'api' || $rel_type == 'internal_request_workflow' || $rel_type == 'external_request_workflow' || $rel_type == 'ro_more') { ?>6<?php } else { ?>12<?php } ?>">
    <div class="form-group" app-field-wrapper="nome">
        <label for="name" class="control-label" >Nome do Campo</label>
        <input type="text" id="nome_campo_novo" name="nome" class="form-control"  value="<?php echo $campo->nome; ?>" <?php if ($rel_type == 'ro_more') { ?> onkeyup="create();"<?php } ?> >
    </div>
</div>  
<?php if ($rel_type != 'api') { ?>
    <div class="col-md-6">
        <div class="select-placeholder form-group" id="trocar">
            <label for="type" class="control-label">Tipo</label>
            <select id="type_campo_novo" name="type" required onchange="<?php if ($rel_type == 'ro_more') { ?> create();<?php } ?> change(this.value); change_separador(this.value); " class="selectpicker" data-width="100%"  >
                <option  value="" disabled>Selecione</option>
                <option  value="text" <?php
                if ($campo->type == 'text') {
                    echo 'selected';
                }
                ?>>Campo de texto</option>
                <option  value="textarea"  <?php
                if ($campo->type == 'textarea') {
                    echo 'selected';
                }
                ?>>Caixa de texto grande</option>
                <option  value="number"  <?php
                if ($campo->type == 'number') {
                    echo 'selected';
                }
                ?>>Número</option>
                         <?php if ($rel_type != 'ro_more') { ?>
                    <option  value="select"   <?php
                    if ($campo->type == 'select') {
                        echo 'selected';
                    }
                    ?>>Select (Apenas uma opção)</option>
                    <option  value="multiselect" <?php
                    if ($campo->type == 'multiselect') {
                        echo 'selected';
                    }
                    ?>>Multi Select (Mais de uma opção )</option>
                    <!--<option  value="checkbox" <?php
                    if ($campo->type == 'checkbox') {
                        echo 'selected';
                    }
                    ?>>Checkbox (Caixas de seleção)</option>-->

                    <option  value="date" id="date" <?php
                    if ($campo->type == 'date') {
                        echo 'selected';
                    }
                    ?>>Data(dd-mm-yyyy)</option><?php } ?>
                <option  value="time" id="datetime-local" <?php
                if ($campo->type == 'time') {
                    echo 'selected';
                }
                ?>>Hora (00:00:00)</option>
                <option  value="color" id="color" <?php
                if ($campo->type == 'color') {
                    echo 'selected';
                }
                ?>>Cor(#000000)</option>
                         <?php if ($rel_type != 'ro_more') { ?>
                    <option  value="separador" id="separador" <?php
                    if ($campo->type == 'separador') {
                        echo 'selected';
                    }
                    ?>>Separador(Barra)</option>
                    <option  value="setores" id="setores" <?php
                    if ($campo->type == 'setores') {
                        echo 'selected';
                    }
                    ?>>Setores do sistema</option><!-- comment -->
                    <option  value="funcionarios" id="funcionarios" <?php
                    if ($campo->type == 'funcionarios') {
                        echo 'selected';
                    }
                    ?>>Funcionários do sistema</option>
                    <option  value="list" id="list" <?php
                    if ($campo->type == 'list') {
                        echo 'selected';
                    }
                    ?>>Listas do sistema</option>
                    <option  value="reais" id="reais" <?php
                    if ($campo->type == 'reais') {
                        echo 'selected';
                    }
                    ?>>Valores(R$ 0,00)</option><?php } ?>
                <option  value="file" id="file" <?php
                if ($campo->type == 'file') {
                    echo 'selected';
                }
                ?>>Arquivo</option>


            </select>

            <div class="icheck-primary d-inline" id="date_limit_div" style="<?php
            if ($campo->days_max || $campo->days_min) {
                echo 'display: block;';
            } else {
                echo 'display: none;';
            }
            ?>">
                <input type="checkbox" id="check_date_limit" onchange="date_checked()" <?php
                if (!$campo->days_max && !$campo->days_min) {
                    echo '';
                } else {
                    echo 'checked';
                }
                ?>>
                <label for="check_date_limit" class="control-label">
                    Data com limite
                </label>

            </div>
            <div class="icheck-primary d-inline" id="just_one_div" style="<?php
            if ($campo->type == 'setores' || $campo->type == 'list') {
                echo 'display: block;';
            } else {
                echo 'display: none;';
            }
            ?>">
                <input type="checkbox" id="just_one"  <?php
                if ($campo->just_one == 1) {
                    echo 'checked';
                }
                ?>>
                <label for="just_one" class="control-label">
                    Selecionar somente uma alternativa
                </label>

            </div>
        </div>
    </div>





    <?php if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow' and $rel_type != 'ro_more') { ?>
        <div class="col-md-3" id="tam_ocultar">
            <div class="form-group">
                <label for="bs_column" class="control-label">Grade (Coluna Bootstrap)</label>
                <div class="input-group">
                    <span class="input-group-addon">col-md-</span>
                    <input type="number" max="12" class="form-control" id="tam_campo_novo" value="<?php
                    if ($campo->tam_coluna) {
                        echo $campo->tam_coluna;
                    } else {
                        echo '12';
                    }
                    ?>" >
                </div>
            </div>
        </div>
        <div class="col-md-3" id="">
            <div class="form-group">
                <label for="bs_column" class="control-label">Cor</label>
                <div class="input-group">
                    <input type="color" value="<?php echo $campo->color; ?>" id="color_camp" class="form-control"  />
                    <span class="input-group-addon">HEX</span>
                </div>
            </div>
        </div>
    <?php } ?>


    <?php if ($rel_type == 'r.o' || $rel_type == 'cdc') { ?>


        <div class="col-md-6" id="preenchido_ocultar">
            <div class="select-placeholder form-group" app-field-wrapper="statuses[]">
                <label for="preenchido_por" class="control-label">A ser preenchido por:</label>
                <select id="preenchido_por_campo_novo" name="preenchido_por" class="selectpicker"  data-width="100%" data-none-selected-text="Nada selecionado" data-live-search="true">
                    <?php if ($rel_type == 'r.o') { ?>
                        <option value="0" id="solicitante" <?php
                        if ($campo->preenchido_por == '0') {
                            echo 'selected';
                        }
                        ?>>Notificante</option>
                        <option value="setor_responsavel" id="setor_responsavel" <?php
                        if ($campo->preenchido_por == 'setor_responsavel') {
                            echo 'selected';
                        }
                        ?>>Setor Responsável</option>
                        <option value="classificacao" id="classificacao" <?php
                        if ($campo->preenchido_por == 'classificacao') {
                            echo 'classificacao';
                        }
                        ?>>Aba de Classificação</option>
                            <?php } else { ?>
                        <option value="0" id="solicitante" <?php
                        if ($campo->preenchido_por == '0') {
                            echo 'selected';
                        }
                        ?>>Formulário CDC</option>
                            <?php } ?>
                            <?php foreach ($categoria_atuantes as $atuante): ?>

                        <option value="<?php echo $atuante['id']; ?>" id="" <?php
                        if ($campo->preenchido_por == $atuante['id']) {
                            echo 'selected';
                        }
                        ?>><?php echo $atuante['titulo']; ?></option>
                            <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php } ?>
    <div class="form-group col-md-12" style="<?php
    if ($campo->type == 'select' || $campo->type == 'multiselect') {
        echo 'display: block;';
    } else {
        echo 'display: none;';
    }
    ?>" id="listadeopcoes">
        <div class="panel_s col-md-12">
            <div class="panel-heading">
                Opções do Select
            </div>
            <div class="panel-body">



                <button class="btn btn-success p8-half" type="button" onclick="more_files();"><i class="fa fa-plus"></i> ADD OPÇÃO</button>
                <div id="attachmentContainer" style='margin-top: 10px;'>
                    <?php
                    if ($campo->type == 'select' || $campo->type == 'multiselect') {

                        $this->load->model('Registro_ocorrencia_model');
                        $options = $this->Registro_ocorrencia_model->get_options($campo->id);

                        for ($i = 0; $i < count($options); $i++) {
                            ?>
                            <div class="attachment" id="attachment_<?php echo $i + 1; ?>">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="options[value][]" value="<?php echo $options[$i]['option']; ?>" >
                                        <input type="hidden" name="options[id][]" value="<?php echo $options[$i]['id']; ?>">
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger p8-half " type="button" onclick="removeAttachment('<?php echo $i + 1; ?>')"><i class="fa fa-times"></i></button>
                                        </span>
                                    </div>
                                </div>

                            </div>



                        <?php }
                        ?>
                        <script>
                            var attachmentCounter = <?php echo count($options); ?>;
                            // Attach click event to the "Add Attachment" button
                            function more_files() {
                                attachmentCounter++;
                                var newAttachment = `
                            <div class="attachment" id="attachment_${attachmentCounter}">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="options[value][]" >
                                            <input type="hidden" name="options[id][]" value="0">
                                            <span class="input-group-btn">
                                                <button class="btn btn-danger p8-half " type="button" onclick="removeAttachment('${attachmentCounter}')"><i class="fa fa-times"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                               
                            </div>
                                                        
                                    `;
                                $("#attachmentContainer").append(newAttachment);
                            }

                            function removeAttachment(attachmentCounter) {
                                var attachment = document.getElementById(`attachment_${attachmentCounter}`);
                                if (attachment) {
                                    attachment.remove();
                                }
                            }
                        </script>
                    <?php }
                    ?>


                </div>

            </div>
        </div>
    </div>
    <div class=" col-md-12" style="<?php
    if ($campo->days_max || $campo->days_min) {
        echo 'display: block;';
    } else {
        echo 'display: none;';
    }
    ?>" id="date_div">
        <div class="form-group col-md-6">
            <label for="bs_column" class="control-label">Data mínima (Dias Antes)</label>
            <div class="input-group">
                <span class="input-group-addon">Dias</span>
                <input type="number"  class="form-control" id="date_limit_min" value="<?php echo $campo->days_min; ?>" >
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="bs_column" class="control-label">Data máxima (Dias Depois)</label>
            <div class="input-group">
                <span class="input-group-addon">Dias</span>
                <input type="number"  class="form-control" id="date_limit_max" value="<?php echo $campo->days_max; ?>" >
            </div>
        </div>
    </div>
    <div class="form-group col-md-12" style="<?php
    if ($campo->type == 'textarea') {
        echo 'display: block;';
    } else {
        echo 'display: none;';
    }
    ?>" id="default_div">
        <label class="control-label"> Texto Padtrão</label>
        <textarea class="form-control" rows="3" placeholder="Texto Padrão..." id="default_text"><?php echo $campo->default_text; ?></textarea>
    </div>
    <div class=" col-md-12" style="<?php
    if ($campo->type == 'list') {
        echo 'display: block;';
    } else {
        echo 'display: none;';
    }
    ?>" id="lists">
        <div class="select-placeholder form-group">
            <label for="type" class="control-label">Tipo de lista</label>
            <select id="list_id" class="selectpicker" data-width="100%">
                <option  value="">Selecione</option>
                <?php foreach ($lists as $list) { ?>
                    <option  value="<?php echo $list['id']; ?>" <?php
                    if ($campo->list_id == $list['id']) {
                        echo 'selected';
                    }
                    ?>><?php echo $list['list']; ?></option>
                         <?php } ?>


            </select>
        </div>
    </div>



    <div class="clearfix"></div>


    <?php if ($rel_type != 'ro_more') { ?>
        <div class="icheck-primary d-inline col-md-12" id="check_ocultar">
            <input type="checkbox" id="obrigatorio" value="1" <?php
            if ($campo->obrigatorio != '1') {
                echo '';
            } else {
                echo 'checked';
            }
            ?>>
            <label for="checkboxPrimary1" class="control-label">
                Campo Obrigatório
            </label>

        </div>
    <?php } ?>
    <?php if ($rel_type != 'internal_request_workflow' and $rel_type != 'external_request_workflow') { ?>
        <?php if ($rel_type != 'ro_more') { ?>
            <div class="icheck-primary d-inline col-md-12" >
                <input type="checkbox" id="chave" value="1" <?php
                if ($campo->chave != '1') {
                    echo '';
                } else {
                    echo 'checked';
                }
                ?>>
                <label for="chave" class="control-label">
                    Campo Chave
                </label>

            </div>
        <?php } ?>
        <div class="icheck-primary d-inline col-md-12 <?php if ($rel_type != 'r.o' && $rel_type != 'workflow' && $rel_type != 'atendimento') { ?> hide <?php } ?>" >
            <input type="checkbox" id="portal" name="portal" value="1" <?php
            if ($campo->portal != '1') {
                echo '';
            } else {
                echo 'checked';
            }
            ?>>
            <label for="portal" class="control-label" >
                Disponível para Solicitante (considerando via portal e via módulo)
            </label>

        </div>
    <?php } ?>
<?php } else { ?>
    <div class="col-md-6">
        <div class="select-placeholder form-group" id="trocar">
            <label for="type" class="control-label">Vinculo ao cadastro</label>
            <select id="vinculo" onchange="" class="selectpicker" data-width="100%" >
                <option  value="">Selecione</option>
                <option  value="numero_carteirinha" <?php
                if ($campo->vinculo == 'numero_carteirinha') {
                    echo 'selected';
                }
                ?>>Carteirinha</option>
                <option  value="phonenumber" <?php
                if ($campo->vinculo == 'phonenumber') {
                    echo 'selected';
                }
                ?>>Telefone</option>
                <option  value="email2" <?php
                if ($campo->vinculo == 'email2') {
                    echo 'selected';
                }
                ?>>Email</option>
                <option  value="vat" <?php
                if ($campo->vinculo == 'vat') {
                    echo 'selected';
                }
                ?>>CPF</option>
                <option  value="documento_rg" <?php
                if ($campo->vinculo == 'documento_rg') {
                    echo 'selected';
                }
                ?>>RG</option>

                <option  value="zip" <?php
                if ($campo->vinculo == 'zip') {
                    echo 'selected';
                }
                ?>>CEP</option>
                <option  value="phonenumber2" <?php
                if ($campo->vinculo == 'phonenumber2') {
                    echo 'selected';
                }
                ?>>Telefone 2</option>
                <option  value="dt_nascimento" <?php
                if ($campo->vinculo == 'dt_nascimento') {
                    echo 'selected';
                }
                ?>>Data de Nascimento</option>
                <option  value="cd_pessoa" 
                    <?php if ($campo->vinculo == 'cd_pessoa') { echo 'selected'; }?>
                 >Código Pessoa</option>
                
                <option  value="company" 
                    <?php if ($campo->vinculo == 'company') { echo 'selected'; }?>
                 >Nome</option>
                
                <option  value="vat_pagador" 
                    <?php if ($campo->vinculo == 'vat_pagador') { echo 'selected'; }?>
                 >CPF PAGADOR</option>
                
                <option  value="address" 
                    <?php if ($campo->vinculo == 'address') { echo 'selected'; }?>
                 >Endereço</option>
                
                <option  value="endereco_numero" 
                    <?php if ($campo->vinculo == 'endereco_numero') { echo 'selected'; }?>
                 >Endereço N°</option>
                
                <option  value="endereco_bairro" 
                    <?php if ($campo->vinculo == 'endereco_bairro') { echo 'selected'; }?>
                 >Endereço Bairro</option>
                
                <option  value="city" 
                    <?php if ($campo->vinculo == 'city') { echo 'selected'; }?>
                 >Endereço Cidade</option>
                
                <option  value="state" 
                    <?php if ($campo->vinculo == 'state') { echo 'selected'; }?>
                 >Endereço Estado</option>
                
                <option  value="company_pagador" 
                    <?php if ($campo->vinculo == 'company_pagador') { echo 'selected'; }?>
                 >Nome Pagador</option>
                
                <option  value="cd_pagador" 
                    <?php if ($campo->vinculo == 'cd_pagador') { echo 'selected'; }?>
                 >Codigo Pagador</option>
                
                <option  value="ie_tipo_segurado" 
                    <?php if ($campo->vinculo == 'ie_tipo_segurado') { echo 'selected'; }?>
                 >IE</option>
                
                <option  value="coparticipation" 
                    <?php if ($campo->vinculo == 'coparticipation') { echo 'selected'; }?>
                 >Coparticipação</option>
                
                <option  value="type" 
                    <?php if ($campo->vinculo == 'company') { echo 'selected'; }?>
                 >TIPO (PF/PJ)</option>
                <option  value="indexador" 
                    <?php if ($campo->vinculo == 'indexador') { echo 'selected'; }?>
                 >INDEXADOR</option>

            </select>
        </div>
    </div>
    <div class="col-md-6" id="tam_ocultar">
        <div class="form-group" app-field-wrapper="

             length">
            <label for="maxlength" class="control-label" >Maxlength (Máximo de caracteres)</label>
            <input type="number" id="maxlength" name="maxlength" class="form-control"  value="<?php echo $campo->maxlength; ?>" >
        </div>
    </div><!-- comment -->
    <div class="col-md-6" id="tam_ocultar">

        <div class="form-group" app-field-wrapper="minlength">
            <label for="minlength" class="control-label" >Minlength (Minimo de caracteres)</label>
            <input type="number" id="minlength" name="minlength" class="form-control"  value="<?php echo $campo->minlength; ?>" >
        </div>
    </div>
<?php } ?>
<?php if ($rel_type == 'ro_more') { ?>
    <input type='hidden' value='<?php echo $retorno; ?>' name='retorno'>
    <input type='hidden' value='<?php echo $rel_type; ?>' name='rel_type'>
    <input type='hidden' value='<?php echo $rel_id; ?>' name='rel_id'>
    
    <div class="col-md-12">
        <div  class="panel_s col-md-12">
            <div class="panel-heading">
                Informação em desennvolvimento...
            </div>
            <div class="panel-body" id="create_campo_div">
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <button class="btn btn-info pull-right mbot15" id="btn_salvar" type="submit">
            <?php echo _l('submit'); ?>
        </button>
    </div>
   

<?php } else { ?>
    <div class="col-md-12">
        <button class="btn btn-info pull-right mbot15" onclick="add_campo();" id="btn_salvar">
            <?php echo _l('submit'); ?>
        </button>
    </div>
<?php } ?>

<?php if ($rel_type == 'ro_more') { ?>
    <?php echo form_close(); ?>
     <script>

        $(document).ready(function () {
            $('#more_info_form').submit(function (e) {
                e.preventDefault(); // Prevent the default form submission
                var uploadForm = $('#more_info_form');
                var formData = new FormData($('#more_info_form')[0]);
                // Inicialize um objeto que irá conter o mapeamento de campo de entrada para destino
                var fieldToTargetMap = {};
                var fieldValueMap = {};

                // Percorra cada campo de entrada de arquivo
                uploadForm.find('input[type="file"]').each(function () {
                    var field = $(this);
                    var fieldName = field.attr('name');
                    var target = field.data('target');
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
                        $('#more_info_form').unbind('submit').submit();

                    }
                });
            });
        });

    </script>
<?php } ?>
<script>

    $(document).ready(function () {
        init_selectpicker();
    });
    function date_checked() {

        // Obtenha a referência do elemento checkbox
        var checkbox = document.getElementById('check_date_limit');
        // Verifique se o checkbox está marcado ou não
        if (checkbox.checked) {

            document.getElementById('date_div').style.display = 'block';
        } else {
            document.getElementById('date_div').style.display = 'none';
            document.getElementById('date_limit_min').value = '';
            document.getElementById('date_limit_max').value = '';
        }
    }

</script>