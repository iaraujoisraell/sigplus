<?php
$vowels = array(".");
$tabela = str_replace($vowels, "", $rel_type);
?>

<div class="modal fade" id="edit_categoria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <?php if ($rel_type == 'cdc') { ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <?php } ?>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white"><?php
                                                        if ($categoria->id != '') {
                                                            echo 'Editar: ' . $categoria->titulo;
                                                        } else {
                                                            echo 'Nova Categoria';
                                                        }
                                                        ?></span>
                </h4>
            </div>




            <?php //echo form_open("gestao_corporativa/registro_ocorrencia/add_tipo", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form"));    
            ?>


            <div class="modal-body">
                <?php if ($rel_type == 'atendimento') { ?>
                    <!--<div class="checkbox checkbox-primary no-mtop checkbox-inline">
                        <input type="checkbox" id="is_portal" name="is_portal" <?php
                                                                                if ($categoria->is_portal == 1) {
                                                                                    echo 'checked';
                                                                                }
                                                                                ?>>
                        <label for="is_portal" data-toggle="tooltip" data-placement="bottom" title="Se você definir essa categoria como portal ela será visível na tela inicial de Multi-portal do sistema.">PORTAL</label>
                    </div>
                    <br>
                    <hr class="no-mtop"/>-->
                <?php } ?>

                <?php
                if ($rel_type != 'autosservico') {
                    echo render_input("titulo", 'Titulo', $categoria->titulo, 'text', array('required' => 'true'));
                }
                ?>
                <?php if ($rel_type == 'api' || $rel_type == 'cdc') { ?>
                    <div class="form-group">
                        <label class="control-label">Descrição</label>
                        <textarea class="form-control" rows="3" placeholder="" id="description"><?php echo $categoria->description; ?></textarea>
                    </div>
                <?php } ?>

                <?php if ($rel_type == 'ra_atendimento_rapido') { ?>
                    <div class="form-group">
                        <label class="control-label">Descrição / Orientação <small class="text-muted">(opcional, aparece pro atendente)</small></label>
                        <textarea class="form-control" rows="3" name="descricao" id="descricao" placeholder="Ex: detalhe sobre quando usar este tipo, informações que devem ser coletadas, etc."><?php echo html_escape($categoria->descricao ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><i class="fa fa-paperclip"></i> Anexo fixo da categoria <small class="text-muted">(opcional — modelo de doc, instrução, etc.)</small></label>
                        <input type="hidden" name="anexo" id="anexo_filename" value="<?php echo html_escape($categoria->anexo ?? ''); ?>">

                        <div id="anexo-atual" style="<?php echo empty($categoria->anexo) ? 'display:none;' : ''; ?>margin-bottom:6px;padding:8px 12px;background:#f0f9ff;border:1px solid #bae6fd;border-radius:6px;display:flex;align-items:center;justify-content:space-between;gap:8px;">
                            <span><i class="fa fa-file"></i> <a id="anexo-link" href="<?php echo !empty($categoria->anexo) ? base_url('assets/intranet/arquivos/categorias_anexos/' . $categoria->anexo) : '#'; ?>" target="_blank"><?php echo html_escape($categoria->anexo ?? ''); ?></a></span>
                            <button type="button" class="btn btn-default btn-xs" onclick="removerAnexoCategoria()"><i class="fa fa-times"></i> Remover</button>
                        </div>

                        <div style="display:flex;gap:6px;align-items:center;">
                            <input type="file" id="anexo_file" class="form-control" style="flex:1;">
                            <button type="button" class="btn btn-info btn-sm" onclick="enviarAnexoCategoria()"><i class="fa fa-upload"></i> Enviar</button>
                            <span id="anexo-status" style="font-size:12px;color:#94a3b8;"></span>
                        </div>
                    </div>
                <?php } ?>
                <?php
                if ($rel_type == 'api') {
                    echo render_input("caminho", 'Caminho', $categoria->caminho, 'text', array('required' => 'true'));
                }
                ?>
                <?php if ($rel_type == 'r.o' || $rel_type == 'workflow') { ?>
                    <?php echo render_input("titulo_abreviado", 'Titulo Portal', $categoria->titulo_abreviado, 'text', array('required' => 'true')); ?>
                <?php } ?>
                <input type="hidden" name="id" value="<?php echo $categoria->id; ?>">

                <?php if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'cdc') { ?>
                    <?php echo render_select("responsavel", $departments, array('departmentid', 'name'), 'Setor Responsável', $categoria->responsavel, array('required' => 'true')); ?>
                    <?php echo render_select("staff_responsavel", $staffs, array('staffid', 'firstname', 'lastname'), 'Pessoa Responsável', $categoria->staff_responsavel); ?>

                    <input type="checkbox" name="exclusivo_responsavel" id="exclusivo_responsavel" <?php if ($categoria->exclusivo_responsavel == 1) { ?> checked <?php } ?>> EXCLUSIVO DO SETOR RESPONSÁVEL <br>
                    <input type="checkbox" name="permite_pessoas" id="permite_pessoas" <?php if ($categoria->permite_pessoas == 1) { ?> checked <?php } ?>> PERMITE ESCOLHER PESSOAS NO FLUXO

                <?php } ?>
                <?php if ($rel_type == 'workflow') { ?>
                    <?php echo render_select("staff_responsavel", $staffs, array('staffid', 'firstname', 'lastname'), 'Colaborador(a) Responsável', $categoria->staff_responsavel, array('required' => 'true')); ?>
                    <?php echo render_input('prazo', 'Prazo em dias', $categoria->prazo, 'number', array('required' => 'true')); ?>
                    <?php echo render_input('prazo_cliente', 'Prazo Final Cliente', $categoria->prazo_cliente, 'number', array('required' => 'true')); ?>

                    <div class="form-group">
                        <label class="control-label">Orientação Portal:</label>
                        <textarea class="form-control" rows="5" placeholder="Orientações para o portal do cliente.." id="orientacoes_client"><?php echo $categoria->orientacoes_client; ?></textarea>
                    </div>


                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="aprovacao" value="1" <?php
                                                                        if ($categoria->aprovacao == 1) {
                                                                            echo 'checked';
                                                                        }
                                                                        ?>>
                        <label for="checkboxPrimary1" class="control-label">
                            EXIGIR APROVAÇÃO DE GESTOR DO SOLICITANTE
                        </label>

                    </div>
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="confidential" value="1" <?php
                                                                            if ($categoria->confidential == 1) {
                                                                                echo 'checked';
                                                                            }
                                                                            ?>>
                        <label for="confidential" class="control-label">
                            RELATÓRIO SIGILOSO (somente para o setor responsável)
                        </label>

                    </div>
                <?php } ?>
                <?php if ($rel_type == 'autosservico') { ?>
                    <?php echo render_input("titulo", 'Ação', $categoria->titulo, 'text', array('required' => 'true')); ?>
                    <?php //echo render_input("titulo_error", 'Tentativa falha', $categoria->titulo, 'text', array('required' => 'true'));      
                    ?>
                    <div class="form-group">
                        <label class="control-label">Orientação:</label>
                        <textarea class="form-control" rows="5" placeholder="Orientações para essa categoria..." id="orientacoes"><?php echo $categoria->orientacoes; ?></textarea>
                    </div>
                    <div class="select-placeholder form-group" app-field-wrapper="responsavel">
                        <label for="linked_to" class="control-label">Vincular a:</label>
                        <select id="linked_to" name="linked_to" class="selectpicker" required="true" data-width="100%" data-none-selected-text="Nada selecionado" data-live-search="true">
                            <option value=""></option>
                            <option value="at_cadastral" <?php
                                                            if ($categoria->linked_to == 'at_cadastral') {
                                                                echo 'selected';
                                                            }
                                                            ?>>Atualização Cadastral</option>
                            <option value="menu_boletos" <?php
                                                            if ($categoria->linked_to == 'menu_boletos') {
                                                                echo 'selected';
                                                            }
                                                            ?>>Menu Boletos</option>
                            <option value="menu_irpf" <?php
                                                        if ($categoria->linked_to == 'menu_irpf') {
                                                            echo 'selected';
                                                        }
                                                        ?>>Menu IRPF</option>
                            <option value="gera_cooparticipacao" <?php
                                                                    if ($categoria->linked_to == 'gera_cooparticipacao') {
                                                                        echo 'selected';
                                                                    }
                                                                    ?>>Botão visualizar competência Cooparticipação</option>
                            <option value="gera_boleto" <?php
                                                        if ($categoria->linked_to == 'gera_boleto') {
                                                            echo 'selected';
                                                        }
                                                        ?>>Botão gerar boleto</option>
                            <option value="gera_historico_boleto" <?php
                                                                    if ($categoria->linked_to == 'gera_historico_boleto') {
                                                                        echo 'selected';
                                                                    }
                                                                    ?>>Botão consultar período histórico de Pagamento</option>
                            <option value="gera_historico_boleto_pdf" <?php
                                                                        if ($categoria->linked_to == 'gera_historico_boleto_pdf') {
                                                                            echo 'selected';
                                                                        }
                                                                        ?>>PDF histórico de Pagamento</option>
                        </select>
                    </div>
                <?php } ?>
                <?php if ($rel_type == 'r.o') { ?>

                    <?php
                    $selected = '';

                    echo render_select("atuantes_categoria", $atuantes, array('id', 'titulo'), 'Informe os atuantes', explode(',', $categoria->atuantes), array('required' => 'true', 'multiple' => 'true'));
                    ?>
                    <div class="form-group">
                        <label class="control-label">Orientação:</label>
                        <textarea class="form-control" rows="5" placeholder="Orientações para essa categoria..." id="orientacoes"><?php echo $categoria->orientacoes; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Orientação Portal:</label>
                        <textarea class="form-control" rows="5" placeholder="Orientações para o portal do cliente.." id="orientacoes_client"><?php echo $categoria->orientacoes_client; ?></textarea>
                    </div>
                    <?php
                    echo render_input("head_1", 'Padrão N°', $categoria->head_1, 'text', array('required' => 'true'));
                    echo render_input("head_2", 'Estabelecido Em', $categoria->head_2, 'text', array('required' => 'true'));
                    echo render_input("head_3", 'Revisão', $categoria->head_3, 'text', array('required' => 'true'));
                    ?>
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="anonimo" value="1"
                            <?php
                            if ($categoria->anonimo == 1) {
                                echo 'checked';
                            }
                            ?>>
                        <label for="checkboxPrimary1" class="control-label">
                            PERMITIR REGISTRO ANÔNIMO
                        </label>

                    </div>

                <?php } ?>
                <?php if ($rel_type == 'r.o' || $rel_type == 'workflow') { ?>
                    <div class="icheck-primary d-inline" onclick="habilitar_portal();">
                        <input type="checkbox" id="ra" value="1"
                            <?php
                            if ($categoria->ra == 1) {
                                echo 'checked';
                            }
                            ?>>
                        <label for="checkboxPrimary1" class="control-label">
                            OBRIGATÓRIO REGISTRO DE ATENDIMENTO
                        </label>

                    </div>


                <?php } ?>
                <?php if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'atendimento') { ?>
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="portal" value="1" <?php
                                                                        if ($rel_type == 'r.o' || $rel_type == 'workflow') {
                                                                            if ($categoria->ra != 1) {
                                                                                echo 'disabled';
                                                                            }
                                                                        }
                                                                        ?>
                            <?php
                            if ($categoria->portal == 1) {
                                echo 'checked';
                            }
                            ?>>
                        <label for="checkboxPrimary1" class="control-label">
                            DISPONÍVEL NO PORTAL
                        </label>

                    </div>

                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="portal_paciente" value="1" <?php
                                                                        /*if ($rel_type == 'r.o' || $rel_type == 'workflow') {
                                                                            if ($categoria->ra != 1) {
                                                                                echo 'disabled';
                                                                            }
                                                                        }*/
                                                                        ?>
                            <?php
                            if ($categoria->portal_paciente == 1) {
                                echo 'checked';
                            }
                            ?>>
                        <label for="checkboxPrimary1" class="control-label">
                            DISPONÍVEL NO PORTAL DO PACIENTE
                        </label>

                    </div>
                <?php } ?>
                <?php if ($rel_type == 'cdc') { ?>
                    <div>
                        <div class="form-group" app-field-wrapper="codigo">
                            <label for="codigo" class="control-label">Formatar código <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Arraste os ítens para formatar o código"></i></label>
                            <br>
                            <span draggable="true" class="badge bg-gradient-primary" key="<<d>>">[DIA]</span>
                            <span draggable="true" class="badge bg-gradient-primary" key="<<m>>">[MÊS]</span>
                            <span draggable="true" class="badge bg-gradient-primary" key="<<Y>>">[ANO]</span>
                            <span draggable="true" class="badge bg-gradient-primary" key="<<sequencial>>">[SEQUÊNCIAL]</span>
                            <span draggable="true" class="badge bg-gradient-primary" key="<<titulo>>">[TÍTULO CDC]</span>
                            <span draggable="true" class="badge bg-gradient-primary" key="<<titulo_c>>">[TÍTULO CATEGORIA]</span>
                            <span draggable="true" class="badge bg-gradient-primary" key="<<name>>">[DEPARTAMENTO(SÍGLA)]</span>
                            <span draggable="true" class="badge bg-gradient-primary" key="<<numero_versao>>">[VERSÃO]</span>
                            <span draggable="true" class="badge bg-gradient-primary" key="<<desc_categoria>>">[DESCRIÇÃO CATEGORIA]</span>
                            <input type="text" id="codigo" name="codigo" class="form-control" required="true" value="<?php echo $categoria->codigo; ?>">
                        </div>

                    </div>


                    <div class="" style="">
                        <button class="btn btn-success p8-half" type="button" onclick="more_files();"><i class="fa fa-plus"></i> Adicionar Fluxo</button>
                        <div id="attachmentContainer" style='margin: 20px;'>

                            <?php
                            if ($categoria->id) {
                                $this->load->model('Categorias_campos_model');
                                $flows = $this->Categorias_campos_model->get_categoria_fluxos($categoria->id);
                                $qtd_flows = count($flows);
                            } else {
                                $qtd_flows = 0;
                            }
                            ?>
                            <div id="sortable-container">
                                <?php foreach ($flows as $index => $flow) : ?>
                                    <div class="attachment" id="attachment_<?php echo $flow['codigo_sequencial']; ?>">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <span class="btn btn-default p8-half handle" type="button">
                                                        <i class="fa fa-arrows"></i> <span class="order-number"><?php echo $index + 1; ?></span>
                                                    </span>
                                                </span>
                                                <input type="text" value="<?php echo $flow['titulo']; ?>" class="form-control" name="flows[]">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-danger p8-half addAttachment" type="button"
                                                        onclick="removeAttachment('<?php echo $flow['codigo_sequencial']; ?>')">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>



                        </div>
                    </div>

                <?php } ?>
            </div>
            <div class="modal-footer" id="teste">

                <button onclick="add_categoria<?php echo $tabela; ?>();" class="btn bg-info" style="color: white;"><?php echo _l('submit'); ?></button>

            </div>
        </div>
    </div>
</div>
<script>
    $("#ra").change(function() {
        if ($(this).prop("checked") == true) {
            document.getElementById("portal").disabled = false;
        } else {
            document.getElementById("portal").disabled = true;
            document.getElementById("portal").checked = false;
        }
    });
    $("#selecionado").trigger("change");
    $(document).ready(function() {



        init_selectpicker();
    });

    function add_categoria<?php echo $tabela; ?>() {
        var titulo = document.querySelector("#titulo");
        var titulo = titulo.value;
        var id = '<?php echo $categoria->id; ?>';
        <?php if ($rel_type == 'atendimento') { ?>
            //let checkbox_is_portal = document.getElementById('is_portal');
            //if (checkbox_is_portal.checked) {
            //var is_portal = 1;
            //} else {
            //var is_portal = 0;
            //}
        <?php } ?>
        <?php if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'cdc') { ?>
            var select = document.getElementById('responsavel');
            var responsavel = select.options[select.selectedIndex].value;

            var select = document.getElementById('staff_responsavel');
            var staff_responsavel = select.options[select.selectedIndex].value;

            let checkbox_exclusivo_responsavel = document.getElementById('exclusivo_responsavel');
            if (checkbox_exclusivo_responsavel.checked) {
                var exclusivo_responsavel = 1;
            }else{
                var exclusivo_responsavel = 0;
            }
            
            let checkbox_permite_pessoas = document.getElementById('permite_pessoas');
            if (checkbox_permite_pessoas.checked) {
                var permite_pessoas = 1;
            }else{
                var permite_pessoas = 0;
            }

            <?php if ($rel_type == 'r.o' || $rel_type == 'workflow') { ?>
                let checkbox2 = document.getElementById('ra');
                if (checkbox2.checked) {
                    var ra = 1;
                } else {
                    var ra = 0;
                }

                var orientacoes_client = document.getElementById("orientacoes_client").value;
                var titulo_abreviado = document.getElementById("titulo_abreviado").value;
            <?php } ?>
        <?php } ?>
        <?php if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'atendimento') { ?>
            let checkbox3 = document.getElementById('portal');
            if (checkbox3.checked) {
                var portal = 1;
            } else {
                var portal = 0;
            }

             let checkbox4 = document.getElementById('portal_paciente');
            if (checkbox4.checked) {
                var portal_paciente = 1;
            } else {
                var portal_paciente = 0;
            }
        <?php } ?>
        <?php if ($rel_type == 'autosservico') { ?>
            var orientacoes = document.getElementById("orientacoes").value;
            var select = document.getElementById('linked_to');
            var linked_to = select.options[select.selectedIndex].value;
        <?php } ?>
        <?php if ($rel_type == 'api' || $rel_type == 'cdc') { ?>
            var description = document.getElementById("description").value;
        <?php } ?>
        <?php if ($rel_type == 'api') { ?>
            var caminho = document.getElementById("caminho").value;
        <?php } ?>
        <?php if ($rel_type == 'workflow') { ?>
            var select = document.getElementById('staff_responsavel');
            var staff_responsavel = select.options[select.selectedIndex].value;
            var prazo = document.querySelector("#prazo");
            prazo = prazo.value;
            var prazo_cliente = document.querySelector("#prazo_cliente");
            prazo_cliente = prazo_cliente.value;
            let checkbox = document.getElementById('aprovacao');
            if (checkbox.checked) {
                var aprovacao_gestor = 1;
            } else {
                var aprovacao_gestor = 0;
            }
            let checkbox_confidential = document.getElementById('confidential');
            if (checkbox_confidential.checked) {
                var confidential = 1;
            } else {
                var confidential = 0;
            }
        <?php } ?>
        <?php if ($rel_type == 'r.o') { ?>
            var orientacoes = document.getElementById("orientacoes").value;
            let checkbox = document.getElementById('anonimo');
            if (checkbox.checked) {
                var anonimo = 1;
            } else {
                var anonimo = 0;
            }
            var head_1 = document.getElementById("head_1").value;
            var head_2 = document.getElementById("head_2").value;
            var head_3 = document.getElementById("head_3").value;
            var atuantes = [];
            $("#atuantes_categoria :selected").each(function() {
                atuantes.push(this.value);
            });
        <?php } ?>

        <?php if ($rel_type == 'cdc') { ?>
            var flows = Array.from(document.querySelectorAll('input[name="flows[]"]')).map(elemento => elemento.value);
            const codigoInput_ = document.getElementById('codigo');
            var codigoValue = codigoInput_.value;
        <?php } ?>



        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Categorias_campos/add_tipo'); ?>",
            data: {
                id: id,
                rel_type: '<?php echo $rel_type; ?>',
                titulo: titulo,
                <?php if ($rel_type == 'ra_atendimento_rapido') { ?>
                    descricao: $('#descricao').val(),
                    anexo: $('#anexo_filename').val(),
                <?php } ?>
                <?php if ($rel_type == 'atendimento') { ?>
                    //                /is_portal: is_portal,
                <?php } ?>
                <?php if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'cdc') { ?>
                    responsavel: responsavel,
                    staff_responsavel: staff_responsavel,
                    permite_pessoas: permite_pessoas,
                    exclusivo_responsavel: exclusivo_responsavel,
                    <?php if ($rel_type == 'r.o' || $rel_type == 'workflow') { ?>
                        ra: ra,
                        orientacoes_client: orientacoes_client,
                        titulo_abreviado: titulo_abreviado,
                    <?php } ?>
                <?php } ?>
                <?php if ($rel_type == 'r.o' || $rel_type == 'workflow' || $rel_type == 'atendimento') { ?>
                    portal: portal,
                    portal_paciente: portal_paciente,
                <?php } ?>
                <?php if ($rel_type == 'autosservico') { ?>
                    orientacoes: orientacoes,
                    linked_to: linked_to,
                <?php } ?>
                <?php if ($rel_type == 'api' || $rel_type == 'cdc') { ?>
                    description: description,
                <?php } ?>
                <?php if ($rel_type == 'api') { ?>
                    caminho: caminho,
                <?php } ?>
                <?php if ($rel_type == 'workflow') { ?>
                    staff_responsavel: staff_responsavel,
                    prazo: prazo,
                    aprovacao: aprovacao_gestor,
                    prazo_cliente: prazo_cliente,
                    confidential: confidential,
                <?php } ?>
                <?php if ($rel_type == 'r.o') { ?>
                    orientacoes: orientacoes,
                    anonimo: anonimo,
                    atuantes: atuantes,
                    head_1: head_1,
                    head_2: head_2,
                    head_3: head_3
                <?php } ?>
                <?php if ($rel_type == 'cdc') { ?>
                    flows: flows,
                    codigo: codigoValue
                <?php } ?>

            },
            success: function(data) {
                $('#edit_categoria').modal('hide');
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
                reload_categoria<?php echo $tabela; ?>();
            }
        });
    }

    // ============ Anexo da categoria (Tipos de Solicitação Rápida) ============
    function enviarAnexoCategoria() {
        var input = document.getElementById('anexo_file');
        if (!input || !input.files || !input.files[0]) {
            alert('Selecione um arquivo primeiro.');
            return;
        }
        var fd = new FormData();
        fd.append('arquivo', input.files[0]);
        // CSRF token (lê do cookie atual pra evitar 419)
        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfCookie = '<?php echo $this->config->item('csrf_cookie_name'); ?>';
        var match = document.cookie.match(new RegExp('(?:^|; )' + csrfCookie + '=([^;]+)'));
        var csrfHash = match ? decodeURIComponent(match[1]) : '<?php echo $this->security->get_csrf_hash(); ?>';
        fd.append(csrfName, csrfHash);
        $('#anexo-status').text('enviando…').css('color', '#94a3b8');
        $.ajax({
            url: '<?php echo base_url('gestao_corporativa/Categorias_campos/upload_anexo_categoria'); ?>',
            type: 'POST', data: fd, processData: false, contentType: false,
            success: function (resp) {
                var r = typeof resp === 'string' ? JSON.parse(resp) : resp;
                if (r.ok) {
                    $('#anexo_filename').val(r.name);
                    $('#anexo-link').attr('href', r.url).text(r.name);
                    $('#anexo-atual').show();
                    $('#anexo_file').val('');
                    $('#anexo-status').text('arquivo carregado · salve para vincular').css('color', '#16a34a');
                } else {
                    $('#anexo-status').text('falha: ' + (r.erro || 'erro')).css('color', '#dc2626');
                }
            },
            error: function () { $('#anexo-status').text('falha no upload').css('color', '#dc2626'); }
        });
    }
    function removerAnexoCategoria() {
        $('#anexo_filename').val('');
        $('#anexo-atual').hide();
        $('#anexo-status').text('anexo removido · salve para confirmar').css('color', '#f59e0b');
    }
</script>
<?php if ($rel_type == 'cdc') { ?>
    <script>
        var attachmentCounter = <?php echo $qtd_flows; ?>;
        // Attach click event to the "Add Attachment" button
        function more_files() {
            attachmentCounter++;
            var newAttachment = `
                        <div class="attachment" id="attachment_${attachmentCounter}">
                                                                                                                                        
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="flows[]">
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger p8-half addAttachment"  type="button" onclick="removeAttachment(${attachmentCounter})"><i class="fa fa-times"></i></button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

    <script>
        $(function() {
            $("#sortable-container").sortable({
                handle: ".handle", // Use a classe ".handle" para definir a área de arrasto
                update: function(event, ui) {
                    // Esta função é chamada após a reordenação
                    $("#sortable-container .attachment").each(function(index) {
                        // Atualize os números de ordem após a reordenação
                        $(this).find(".order-number").text(index + 1);
                    });
                    // Aqui, você pode enviar os novos IDs ao servidor para atualizar a ordem no banco de dados
                    // Use sortedIDs para obter a nova ordem dos elementos
                }
            });
        });

        function prepareDrag(el) {
            el.addEventListener('dragstart', e => {
                const title = el.textContent; // Get the text content of the <span> as the title
                e.dataTransfer.setData('text/plain', title); // Set the title as data
            });
        }

        const codigoInput = document.getElementById('codigo');
        [...document.querySelectorAll('span[key]')].forEach(prepareDrag);
        document.addEventListener('dragover', e => {
            if (e.target !== codigoInput && e.target.nodeName !== 'INPUT') {
                e.preventDefault();
            }
        });
        codigoInput.addEventListener('drop', e => {
            e.preventDefault();
            const title = e.dataTransfer.getData('text/plain'); // Get the title
            codigoInput.value += title; // Display the title in the input field


        });
    </script>

<?php } ?>