<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">

<?php $this->load->view('gestao_corporativa/css_background'); ?>



<div class="content">
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight: bold;">INTRANET - WORKFLOW</h5>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li>
                    <li><a href="<?= base_url('gestao_corporativa/Workflow'); ?>"><i class="fa fa-backward"></i> Workflow </a></li>
                </ol>
            </div>

            <div class="panel_s ">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (has_permission_intranet('workflow', '', 'create') || is_admin()) { ?>
                                <a href="<?php echo base_url('gestao_corporativa/workflow/add'); ?>" class="btn btn-info pull-rigth btn-with-tooltip">
                                    <i class="fa fa-plus"></i> NOVO WORKFLOW
                                </a>
                            <?php } ?>
                            <button type="button" class="btn btn-default " onclick="toggleDiv();">
                                SOLICITAÇÕES DE PARECER
                            </button>

                            <a href="<?php echo base_url('gestao_corporativa/Workflow/reports'); ?>" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" data-title="Criar Relatório de Workflow's" data-placement="bottom">
                                <i class="fa fa-plus"></i>
                                <i class="fa fa-bar-chart"></i>
                            </a>

                            <a href="<?php echo base_url('gestao_corporativa/Workflow/reports_setores'); ?>" class="btn btn-warning btn-with-tooltip" data-toggle="tooltip" data-title="Criar Relatório de Workflow's" data-placement="bottom">
                                <i class="fa fa-list"></i> Rel. por setores
                            </a>

                            <a href="<?php echo base_url('gestao_corporativa/Workflow/reports_setores_resumo'); ?>" class="btn btn-danger btn-with-tooltip" data-toggle="tooltip" data-title="Criar Relatório de Workflow's" data-placement="bottom">
                                <i class="fa fa-list"></i> Rel. por setores / Resumido
                            </a>

                            <button type="button" class="btn btn-default dropdown-toggle pull-right mleft4" onclick="filters();">
                                <i class="fa fa-filter" aria-hidden="true"></i>
                            </button>

                        </div>

                    </div>
                </div>
            </div>


        </div>
        <div class="col-md-12">


            <div class="panel_s">

                <div class="panel-body">

                    <div class="mtop15">

                        <div class="row" id="div_table">


                            <div class="col-md-12">
                                <?php
                                $data['tipo'] = 1;
                                //$this->load->view('gestao_corporativa/workflow/summary', $data);
                                ?>
                                <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                <div class="clearfix"></div>
                                <?php
                                $table_data = [];

                                $table_data = array(
                                    '<strong style="font-weight: bold">ID</strong>',
                                    '<strong style="font-weight: bold">RECEBIDO</strong>',
                                    '<strong style="font-weight: bold">STATUS</strong>',
                                );
                                render_datatable($table_data, 'workflow');
                                ?>

                            </div>
                        </div>



                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<div id="modal_wrapper"></div>


                            <!-- MODAL DE FILTROS -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="modal fade" id="filters" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <?php echo form_open($this->uri->uri_string(), array('id' => 'filters_form', 'onsubmit' => "document.getElementById('btn_submit').disabled = true;")); ?>
            <input name="filter" type="hidden" value="<?php echo get_staff_user_id(); ?>">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    FILTROS WORKFLOW

                </h4>
                <div class="clearfix"></div>
                <div class="radio radio-primary radio-inline">
                    <input type="radio" required="true" class="ays-ignore" name="type" id="type_0" value="0" <?php
                                                                                                                if (!$filters['type']) {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                                ?>>
                    <label for="type_0">Busca Geral</label>
                </div>
                <div class="radio radio-primary radio-inline">
                    <input type="radio" required="true" class="ays-ignore" name="type" id="type_1" value="1" <?php
                                                                                                                if ($filters['type'] == 1) {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                                ?>>
                    <label for="type_1">Meus (Cadastrados)</label>
                </div>
                <div class="radio radio-primary radio-inline">
                    <input type="radio" required="true" class="ays-ignore" name="type" id="type_2" value="2" <?php
                                                                                                                if ($filters['type'] == 2) {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                                ?>>
                    <label for="type_2">Objetivos Assumidos</label>
                </div>
                <div class="radio radio-primary radio-inline">
                    <input type="radio" required="true" class="ays-ignore" name="type" id="type_3" value="3" <?php
                                                                                                                if ($filters['type'] == 3) {
                                                                                                                    echo 'checked';
                                                                                                                }
                                                                                                                ?>>
                    <label for="type_3">Objetivos (Setor)</label>
                </div>

            </div>
            <div class="modal-body" id="by_id" style="<?php if ($filters['type']) { ?>display: none;<?php } ?>">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <?php echo render_input('id', '', $filters['id'], 'number', array('placeholder' => 'ID')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-body" id="by_filters" style="<?php if (!$filters['type']) { ?>display: none;<?php } ?>">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <span class="bold" style="font-size: 10px;">
                            <!--<select name="medico_id" id='medico_id' class="form-control col-md-6 pull-right"></select>-->
                            <span class="label label-default mright5 inline-block"><i class="fa fa-filter" aria-hidden="true"></i></span>

                            <!--<div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="Atalhos de filtro">
                                <button class="label label-default mright5 inline-block  pull-right  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"">Atalhos de filtro</button>
                                <ul class="dropdown-menu dropdown-menu-right width300">

                            <!--<div class="clearfix"></div>
                            <li class="divider"></li>
                            <li class="">
                                <a href="#" data-cview="ticket_status_6" onclick="dt_custom_view('ticket_status_6', '.tickets-table', 'ticket_status_6'); return false;">
                                    Atalho1
                                </a>
                            </li>
                            <li class="">
                                <a href="#" data-cview="ticket_status_6" onclick="dt_custom_view('ticket_status_6', '.tickets-table', 'ticket_status_6'); return false;">
                                    Atalho2
                                </a>
                            </li>
                        </ul>
                    </div>
                    <button data-toggle="tooltip" data-title="Salvar novo atalho de filtro" class="label label-success mright5 inline-block pull-right "><i class="fa fa-check" aria-hidden="true"></i></button>!-->
                            FILTROS PRINCIPAIS</span>
                        <hr style="padding: 0; margin: 0 !important;" />

                    </div>
                    <?php $cat_filter = json_decode($filters['categoria_id']); ?>
                    <div class="col-md-12 col-sm-12 mtop10">

                        <div class="radio radio-warning radio-inline">
                            <input type="radio" class="ays-ignore" name="type_search_category" id="type_search_category_1" value="1" <?php
                                                                                                                                        if (is_array($cat_filter)) {
                                                                                                                                            echo 'checked';
                                                                                                                                        }
                                                                                                                                        ?>>
                            <label for="type_search_category_1">Multicategoria</label>
                        </div>
                        <div class="radio radio-warning radio-inline">
                            <input type="radio" class="ays-ignore" name="type_search_category" id="type_search_category_2" value="2" <?php
                                                                                                                                        if (is_numeric($cat_filter)) {
                                                                                                                                            echo 'checked';
                                                                                                                                        }
                                                                                                                                        ?>>
                            <label for="type_search_category_2">Detalhamento (Somente uma categoria)</label>
                        </div>
                        <div class="radio radio-warning radio-inline">
                            <input type="radio" class="ays-ignore" name="type_search_category" id="type_search_category_3" value="3" <?php
                                                                                                                                        if (!$cat_filter) {
                                                                                                                                            echo 'checked';
                                                                                                                                        }
                                                                                                                                        ?>>
                            <label for="type_search_category_3">Todos</label>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 ">
                        <?php
                        if (is_array($cat_filter)) {
                            $array_settings = array('multiple' => true, 'data-none-selected-text' => 'CATEGORIAS');
                        } elseif (is_numeric($cat_filter)) {
                            $array_settings = array('data-none-selected-text' => 'CATEGORIAS');
                        } else {
                            $array_settings = array('disabled' => true, 'data-none-selected-text' => 'CATEGORIAS');
                        }
                        echo render_select('categoria_id', $categorias, array('id', 'titulo'), '', $cat_filter, $array_settings);
                        ?>


                    </div>
                    <div class="col-md-4 col-sm-4 ">
                        <?php
                        echo render_select('departments', $departments, array('departmentid', 'name'), '', json_decode($filters['departments']), array('data-none-selected-text' => 'SETOR ATUAL', 'multiple' => 'true'));
                        ?>
                    </div>
                    <div class="col-md-4 col-sm-4 ">
                        <?php
                        //print_R($statuses); exit;
                        echo render_select('status', $statuses, array('id', 'label'), '', $filters['status'] == '' ? [1, 2] : json_decode($filters['status']), array('data-none-selected-text' => 'STATUS', 'required' => 'true', 'multiple' => 'true', 'onchange' => ""), [], '', '', false);
                        ?>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <div class="form-group">
                            <input type="number" class="form-control" value="<?php echo $filters['workflow_id']; ?>" name="workflow_id" placeholder="ID">
                        </div>
                    </div>
                    <div class="col-md-10 col-sm-12">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 ">
                                <div class="form-group">
                                    <input class="form-control" type="number" name="protocolo" value="<?php echo $filters['protocolo']; ?>" onkeyup="" placeholder="PROTOCOLO">
                                </div>
                            </div>

                            <div class=" col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="client" value="<?php echo $filters['client']; ?>" placeholder="CLIENTE">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="number" name="carteirinha" value="<?php echo $filters['carteirinha']; ?>" placeholder="CARTEIRINHA">
                                </div>
                            </div>
                        </div>
                    </div>



                </div>

                <div id="by_goal" class="row" style="<?php if (!in_array($filters['type'], array(3, 4))) { ?>display: none;<?php } ?>">
                    <div class="col-md-2 col-sm-6">
                        <?php echo render_input('phase', '', $filters['phase'], 'text', array('placeholder' => 'N° DA FASE')); ?>
                    </div>
                    <div class="col-md-3 col-sm-6" id="div_concluido" style="<?php if ($filters['type'] != 3) { ?>display: none;<?php } ?>">
                        <div class="select-placeholder form-group" app-field-wrapper="concluido">
                            <select onchange="change_status(this.value);" id="concluido" name="concluido" class="selectpicker" data-none-selected-text="STATUS OBJETIVO" data-width="100%" data-live-search="true">
                                <option value="0" <?php if ($filters['concluido'] == '0') {
                                                        echo 'selected';
                                                    } ?>>PENDENTE</option>
                                <option value="1" <?php if ($filters['concluido'] == '1') {
                                                        echo 'selected';
                                                    } ?>>CONCLUÍDO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-12">
                        <?php echo render_textarea('goal', '', $filters['goal'], array('placeholder' => 'OBJETIVO DA FASE'), array()); ?>
                    </div>
                </div>

                <div class="row ">

                    <div class="col-md-12 col-sm-12 mtop10">
                        <span class="bold" style="font-size: 10px;">
                            <!--<select name="medico_id" id='medico_id' class="form-control col-md-6 pull-right"></select>-->
                            <span class="label label-default mright5 inline-block"><i class="fa fa-calendar" aria-hidden="true"></i></span>DATAS</span>
                        <hr style="padding: 0; margin: 0 !important;" />

                    </div>
                    <div class="col-md-12 col-sm-12 mtop10">


                        <div class="radio radio-success radio-inline" id="div_date_type_1">
                            <input type="radio" class="ays-ignore" name="date_type" id="date_type_1" value="1" <?php if ($filters['date_type'] == '1') {
                                                                                                                    echo 'checked';
                                                                                                                } ?>>
                            <label for="date_type_1">Cadastrado</label>
                        </div>
                        <div class="radio radio-success radio-inline" id="div_date_type_2">
                            <input type="radio" class="ays-ignore" name="date_type" id="date_type_2" value="2" <?php if ($filters['date_type'] == '2') {
                                                                                                                    echo 'checked';
                                                                                                                } ?>>
                            <label for="date_type_2">Recebido</label>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="select-placeholder form-group" app-field-wrapper="period">
                            <select id="period" name="period" class="selectpicker" onchange="" data-width="100%"
                                data-none-selected-text="PERÍODO" data-live-search="true">
                                <option value=""></option>
                                <option value="d" <?php if ($filters['period'] == 'd') {
                                                        echo 'selected';
                                                    } ?>>Hoje</option>
                                <option value="s" <?php if ($filters['period'] == 's') {
                                                        echo 'selected';
                                                    } ?>>Semana</option>
                                <option value="m" <?php if ($filters['period'] == 'm') {
                                                        echo 'selected';
                                                    } ?>>Mês</option>
                                <option value="Y" <?php if ($filters['period'] == 'Y') {
                                                        echo 'selected';
                                                    } ?>>Ano</option>
                                <option value="-" <?php if ($filters['period'] == '-' || !$filters['period']) {
                                                        echo 'selected';
                                                    } ?>>Todos</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 ">
                                <div class="form-group">
                                    <input type="date" name="de" id="data_de" class="form-control" value="<?php if ($filters['de']) {
                                                                                                                echo $filters['de'];
                                                                                                            } else {
                                                                                                                echo date('Y-m-d');
                                                                                                            } ?>">
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6 ">
                                <div class="form-group">
                                    <input type="date" name="ate" id="data_ate" class="form-control" value="<?php if ($filters['ate']) {
                                                                                                                echo $filters['ate'];
                                                                                                            } else {
                                                                                                                echo date('Y-m-d');
                                                                                                            } ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mtop10">

                    <div class="col-md-12 col-sm-12">
                        <div id="detail" <?php
                                            if (!is_numeric($filters['categoria_id'])) {
                                                echo 'style="display:none"';
                                            }
                                            ?>>
                            <span class="bold" style="font-size: 10px;">
                                <span class="label label-default mright5 inline-block"><i class="fa fa-search" aria-hidden="true"></i></span>
                                FILTROS DETALHADOS POR CATEGORIA
                            </span>
                            <hr style="margin: 0 !important;" />
                            <div id="trocar" class="mtop10">
                                <?php
                                if (is_numeric($filters['categoria_id'])) {
                                    if ($this->load->is_loaded('Categorias_campos_model')) {
                                        $this->load->model('Categorias_campos_model');
                                    }
                                    $campos = $this->Categorias_campos_model->get_categoria_campos($filters['categoria_id'], 0, false, '', '', '', ['select', 'multiselect', 'text', 'textarea', 'list', 'valor', 'date']);
                                    $data['campos'] = $campos;
                                    $data['filters'] = $filters['cat'];
                                    //print_r($data['filters']);
                                    $data['just_campos'] = true;
                                    $data['by_id'] = true;
                                    $data['clean'] = true;
                                ?>


                                    <?php
                                    if ($filters['categoria_id']) {
                                        $this->load->view('gestao_corporativa/categorias_campos/retorno_categoria', $data);
                                    }
                                    ?>

                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12 col-sm-12">
                        <span class="bold" style="font-size: 10px;">
                            <span class="label label-default mright5 inline-block"><i class="fa fa-table" aria-hidden="true"></i></span>ORGANIZAÇÃO DA TABELA</span>
                        <hr style="margin: 0 !important;" />
                        <div class="mtop10">
                            <div class="checkbox checkbox-inline">
                                <input value="ID" type="checkbox" id="settings_id" name="settings_id" disabled checked>
                                <label for="settings_id">ID</label>
                            </div>
                            <div class="checkbox checkbox-inline">
                                <input value="DATA" type="checkbox" id="settings_date" name="settings_date" disabled checked>
                                <label for="settings_created">Cadastrado/Recebido</label>
                            </div>
                            <div class="checkbox checkbox-inline">
                                <input value="STATUS" type="checkbox" id="settings_status" name="settings_status" disabled checked>
                                <label for="settings_status">Status</label>
                            </div>
                            <div class="checkbox checkbox-inline">
                                <input value="CATEGORIA" type="checkbox" id="settings_categoria_id" name="settings_categoria_id">
                                <label for="settings_categoria_id">Categoria</label>
                            </div>
                            <div class="checkbox checkbox-inline">
                                <input value="CAMPOS_CHAVES" type="checkbox" id="settings_campos_chaves_id" name="settings_campos_chaves_id">
                                <label for="settings_campos_chaves_id">Campos Chaves (Consulta Lenta)</label>
                            </div>
                            <div class="checkbox checkbox-inline">
                                <input value="CLIENTE" type="checkbox" id="settings_client" name="settings_client">
                                <label for="settings_client">Cliente</label>
                            </div>
                            <div class="checkbox checkbox-inline">
                                <input value="ATRIBUÍDO" type="checkbox" id="settings_staff_responsible" name="settings_staff_responsible">
                                <label for="settings_staff_responsible">Colaborador Responsável</label>
                            </div>
                            <div class="checkbox checkbox-inline">
                                <input value="OBJETIVO ATUAL" type="checkbox" id="settings_current_goal" name="settings_current_goal">
                                <label for="settings_current_goal">Objetivo Atual</label>
                            </div>
                            <div class="checkbox checkbox-inline">
                                <input value="OBJETIVO(DATAS)" type="checkbox" id="settings_goal_dates" name="settings_goal_dates">
                                <label for="settings_goal_dates">Objetivo(Datas/Prazos)</label>
                            </div>
                            <div class="checkbox checkbox-inline">
                                <input value="PRAZO" type="checkbox" id="settings_time_user_created" name="settings_time_user_created">
                                <label for="settings_time_user_created">Prazo(Solicitante)</label>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info" id="btn_submit">Buscar</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        init_selectpicker();

        $('input[name="type_search_category"]').change(function() {
            updateSelect();
        });


        document.querySelectorAll('input[name="type"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Obtém o valor do tipo selecionado
                var type = this.value;

                // Exibe os filtros correspondentes
                showFilter(type);
            });
        });

        function updateSelect() {
            var type = $('input[name="type_search_category"]:checked').val();
            var select = $('#categoria_id');

            select.find('option[value=""]').remove();

            // Se 'Multicategoria' estiver selecionado
            if (type === '1') {
                select.prop('multiple', true);
                $('#trocar').html('');
                document.getElementById("detail").style.display = "none";
                select.removeAttr('disabled');
            }
            // Se 'Detalhamento' estiver selecionado
            else if (type === '2') {
                select.prop('multiple', false);
                $('#trocar').html('');
                select.removeAttr('disabled');
            } else {
                select.prop('disabled', true);
                select.prop('multiple', false);
                document.getElementById("detail").style.display = "none";
                select.append($('<option>', {
                    value: '',
                    text: ''
                }));


            }

            select.val('');

            select.selectpicker('destroy');
            // Inicializa o Selectpicker novamente
            select.selectpicker();
        }


        $(document).on('change', "#categoria_id", function() {
            var select = document.getElementById("categoria_id");


            var opcaoValor = select.options[select.selectedIndex].value;
            var type = $('input[name="type_search_category"]:checked').val();
            if (opcaoValor != "" && type === '2') {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('gestao_corporativa/categorias_campos/retorno_categoria_campos?rel_type=workflow'); ?>",
                    data: {
                        categoria_id: opcaoValor,
                        just_campos: true,
                        by_id: true,
                        clean: true,
                        types: "select,multiselect,text,textarea,list,valor,date]"
                    },
                    success: function(data) {
                        document.getElementById("detail").style.display = "block";
                        $('#trocar').html(data);
                    }
                });
            }
        });
    });

    $(function() {
        filters();
    });

    function filters() {
        if ($('#filters').is(':hidden')) {
            // Se estiver oculto, exiba-o
            $('#filters').modal({
                show: true,
                backdrop: 'static'
            });
        }

    }

    function change_status(value) {
        if (value == '1') {
            document.getElementById("settings_staff_responsible").disabled = true;
            document.getElementById("settings_staff_responsible").checked = false;

            document.getElementById("settings_goal_dates").disabled = true;
            document.getElementById("settings_goal_dates").checked = false;
        } else {
            document.getElementById("settings_staff_responsible").disabled = false;
            document.getElementById("settings_goal_dates").disabled = false;
        }

    }

    // Função para exibir os campos de filtro correspondentes ao tipo selecionado
    function showFilter(type) {
        // Oculta todos os filtros
        if (type == '1' || type == '0') {
            document.getElementById("by_goal").style.display = "none";
            document.getElementById("date_type_2").checked = false;
            document.getElementById("date_type_2").disabled = true;
        } else {
            document.getElementById("by_goal").style.display = "block";
            document.getElementById("date_type_2").checked = true;
            document.getElementById("date_type_2").disabled = false;

        }

        if (type == '0') {
            document.getElementById("by_id").style.display = "block";
            document.getElementById("by_filters").style.display = "none";
        } else {
            document.getElementById("by_id").style.display = "none";
            document.getElementById("by_filters").style.display = "block";
        }


        if (type == '1') {
            document.getElementById("settings_staff_responsible").disabled = true;
            document.getElementById("settings_staff_responsible").checked = false;

            document.getElementById("settings_goal_dates").disabled = true;
            document.getElementById("settings_goal_dates").checked = false;

            var select = $('#departments');

            select.prop('disabled', true);
            select.val('');

            select.selectpicker('destroy');
            // Inicializa o Selectpicker novamente
            select.selectpicker();
        } else {
            document.getElementById("settings_staff_responsible").disabled = false;

            document.getElementById("settings_goal_dates").disabled = false;
            var select = $('#departments');

            select.prop('disabled', false);
            select.selectpicker('destroy');
            // Inicializa o Selectpicker novamente
            select.selectpicker();


        }
        var concluido = document.querySelector("#concluido").value;
        if (type == '1' || (type == '3' && concluido == '1')) {
            document.getElementById("settings_staff_responsible").disabled = true;
            document.getElementById("settings_staff_responsible").checked = false;
            if (type == '3' && concluido == '1') {
                document.getElementById("settings_goal_dates").disabled = true;
                document.getElementById("settings_goal_dates").checked = false;
            }

        } else {
            document.getElementById("settings_staff_responsible").disabled = false;
            document.getElementById("settings_goal_dates").disabled = false;
        }

        if (type == '3') {
            document.getElementById("div_concluido").style.display = "block";
        } else {
            document.getElementById("div_concluido").style.display = "none";
        }

    }

    // Adiciona um listener para cada checkbox
</script>



<script>
    $(function() {
        var CustomersServerParams = {};
        CustomersServerParams['clientid'] = '[name="clientid"]';
        CustomersServerParams['carteirinha'] = '[name="carteirinha"]';
        CustomersServerParams['id'] = '[name="id"]';
        CustomersServerParams['protocolo'] = '[name="protocolo"]';
        CustomersServerParams['categoria_id'] = '[name="categoria_id"]';
        CustomersServerParams['status'] = '[name="status"]';
        CustomersServerParams['departments'] = '[name="departments"]';
        CustomersServerParams['data_de'] = '[name="data_de"]';
        CustomersServerParams['data_ate'] = '[name="data_ate"]';
        CustomersServerParams['data_prazo_de'] = '[name="data_prazo_de"]';
        CustomersServerParams['data_prazo_ate'] = '[name="data_prazo_ate"]';
        //initDataTable('.table-internal_requests', '<?php echo base_url(); ?>' + 'gestao_corporativa/workflow/table_internal_request', [5], [5], CustomersServerParams);
    });

    function Open_internal_request(el) {
        //alert(el + fluxo_andamento_id + fluxo_andamento_id_old); exit;
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: 'open_internal_request',
            id: el
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#open_internal_request').is(':hidden')) {
                $('#open_internal_request').modal({
                    show: true
                });
            }
        });
    }

    function toggleDiv() {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: 'requests'
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#requests').is(':hidden')) {
                $('#requests').modal({
                    show: true
                });
            }
        });
    }

    function take_responsability(id) {
       // alert("aqui"); exit;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/take_responsability'); ?>",
            data: {
                id: id
            },
            success: function(data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
                reload_tables_all();
            }
        });
    }

    function change_responsability(id) {
        var select = document.getElementById('atribuido_a');
        var atribuido_a = select.options[select.selectedIndex].value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/change_responsability'); ?>",
            data: {
                id: id,
                atribuido_a: atribuido_a
            },
            success: function(data) {
                $('#change_responsability').modal('hide');
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
                reload_tables_all();
            }
        });
    }

    function Change_responsability(el) {
        //alert(el + fluxo_andamento_id + fluxo_andamento_id_old); exit;
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: 'change_responsability',
            id: el
        }, function() {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#change_responsability').is(':hidden')) {
                $('#change_responsability').modal({
                    show: true
                });
            }
        });
    }



    //    function reload_tables(tipo_atraso = '', filter = true) {
    //
    ////        var radios = document.getElementsByName('type');
    ////        var type;
    ////
    ////        for (var i = 0; i < radios.length; i++) {
    ////            if (radios[i].checked) {
    ////                type = radios[i].value;
    ////                break;
    ////            }
    ////        }
    ////
    ////        if (type) {
    //
    //        document.getElementById('disabled').disabled = true;
    //
    //        var radios = document.getElementsByName('type');
    //        var type;
    //
    //        for (var i = 0; i < radios.length; i++) {
    //            if (radios[i].checked) {
    //                type = radios[i].value;
    //                break;
    //            }
    //        }
    //
    //        if ($.fn.DataTable.isDataTable('.table-table_' + type)) {
    //            $('.table-table_' + type).DataTable().destroy();
    //        }
    //
    //        var Params = {};
    //
    //        Params['type'] = '[name="type"]:checked';
    //        Params['client'] = '[name="' + type + '-client"]';
    //        if (type == '1' || type == '4') {
    //            // Params['data_de'] = '[name="' + type + '-data_de"]';
    //            //Params['data_ate'] = '[name="' + type + '-data_ate"]';
    //        }
    //        if (type == '2' || type == '3' || type == '4') {
    //
    //
    //            Params['id'] = '[name="' + type + '-id"]';
    //            Params['protocolo'] = '[name="' + type + '-protocolo"]';
    //            Params['carteirinha'] = '[name="' + type + '-carteirinha"]';
    //            if (type == '2' || type == '3') {
    //                // Params['period'] = '[name="' + type + '-period"]';
    //            }
    //
    //        }
    //
    //        if (filter == true) {
    //            Params['filter'] = '[name="staff_id"]';
    //        }
    //
    //        Params['clientid'] = '[name="clientid"]';
    //        Params['categoria_id'] = '[name="categoria_id"]';
    //        Params['status'] = '[name="status"]';
    //        Params['departments'] = '[name="departments"]';
    //
    //
    //        document.querySelectorAll('.div_').forEach(function (el) {
    //            el.style.display = 'none';
    //        });
    //
    //        // Exibe o filtro correspondente ao tipo selecionado
    //        document.getElementById('div_' + type).style.display = 'block';
    //
    //        var div = document.getElementById('trocar');
    //        if (div) {
    //            // Pegando todos os inputs dentro da div
    //            var inputs = div.querySelectorAll('input, textarea, select');
    //
    //            // Iterando sobre os inputs encontrados
    //            inputs.forEach(function (input) {
    //
    //                Params[input.name] = '[name="' + input.name + '"]';
    //
    //            });
    //        }
    //
    //        initDataTable('.table-table_' + type, '<?php echo base_url(); ?>' + 'gestao_corporativa/workflow/table', [0], [0], Params, <?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0, 'desc'))); ?>);
    //
    //        $('#filters').modal('hide');
    //        document.getElementById('disabled').disabled = false;
    ////        } else {
    ////            alert('Selecione a tabela desejada!');
    ////    }
    //
    //    }


    $(document).ready(function() {
        $('#filters_form').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            var type = document.querySelector('input[name="type"]:checked').value;
            if ($.fn.DataTable.isDataTable('.table-workflow')) {
                $('.table-workflow').DataTable().destroy();
            }

            var Params = {}; // Cria um objeto Params vazio
            if (type != '0') {
                // Itera sobre todos os elementos de formulário
                $('#filters_form :input').each(function() {
                    var name = $(this).attr('name');
                    var value = $(this).val();

                    // Verifica se o elemento tem um valor
                    if (($(this).is(':checkbox') && $(this).is(':checked')) || (!$(this).is(':checkbox') && value !== '')) {
                        var selector = '[name="' + name + '"]'; // Cria o seletor jQuery
                        Params[name] = selector; // Atribui ao Params o seletor
                    }
                });
            } else {
                Params['id'] = '[name="id"]';
            }

            Params['type'] = '[name="type"]:checked';

            var checkboxes = document.querySelectorAll('input[type="checkbox"]');



            // Localiza a tabela pela classe
            var table = document.querySelector('.table-workflow');

            // Localiza o cabeçalho da tabela (thead) dentro da tabela
            var tableHead = table.querySelector('thead');

            tableHead = tableHead.querySelector('tr');

            var thElements = tableHead.querySelectorAll('th');

            // Iterar sobre os elementos th a partir do índice 3 (quarto th) e removê-los
            for (var i = 3; i < thElements.length; i++) {
                thElements[i].remove();
            }

            if (type != '0') {
                checkboxes.forEach(function(checkbox) {
                    // Verifica se o checkbox está marcado
                    if (checkbox.checked && checkbox.disabled == false) {
                        var th = document.createElement('th');
                        // Adiciona algum conteúdo ao <th> (por exemplo, o texto do checkbox)
                        th.textContent = checkbox.value;

                        // Adiciona o <th> à tabela desejada (substitua 'tabela' pelo ID da sua tabela)
                        tableHead.appendChild(th);
                        //                   / Params['settings[]'] = '[name="'+ checkbox.name + '"]';

                    }
                });

            } else {
                var
                let = 2;
            }



            initDataTable('.table-workflow', '<?php echo base_url(); ?>' + 'gestao_corporativa/workflow/table', [let], [let], Params, <?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(1, 'desc'))); ?>);

            $('#filters').modal('hide');
            document.getElementById('btn_submit').disabled = false;
        });
    });
</script>
</body>

</html>