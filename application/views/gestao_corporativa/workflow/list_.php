<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(false); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/menu/bower_components/Ionicons/css/ionicons.min.css">

<?php $this->load->view('gestao_corporativa/css_background'); ?>





<input name="staff_id" type="hidden" value="<?php echo get_staff_user_id(); ?>">

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


            <div class="row" >
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
                </div>
            </div> 
            <br>

            <div class="panel_s ">
                <div class="panel-body">
                    <div class="row">


                        <?php
                        ?>
                        <div class="col-md-4 col-sm-4 "  >
                            <?php
                            echo render_select('categoria_id', $categorias, array('id', 'titulo'), '', json_decode($filters[0]['categoria_id']), array('data-none-selected-text' => 'CATEGORIA', 'required' => 'true', 'multiple' => 'true', 'onchange' => ""), [], '', '', false);
                            ?>
                        </div>
                        <div class="col-md-4 col-sm-4 " >
                            <?php
                            echo render_select('departments', $departments, array('departmentid', 'name'), '', json_decode($filters[0]['departments']), array('data-none-selected-text' => 'SETOR RESPONSÁVEL', 'required' => 'true', 'multiple' => 'true', 'onchange' => ""), [], '', '', false);
                            ?>
                        </div>

                        <div class="col-md-4 col-sm-4 " >
                            <?php
                            //print_R($statuses); exit;
                            echo render_select('status', $statuses, array('id', 'label'), '', $filters[0]['status'] == '' ? [1,2] : json_decode($filters[0]['status']), array('data-none-selected-text' => 'STATUS', 'required' => 'true', 'multiple' => 'true', 'onchange' => ""), [], '', '', false);
                            ?>
                        </div>




                    </div>

                </div>
            </div>


        </div>
        <div class="col-md-12">

            <div class="panel_s mbot10" style="display: none;" id="id_internal">
                <div class="panel-heading">
                    SOLICITAÇÕES DE PARECER
                </div>
                <div class="panel-body">
                    <div class="row ">
                        <?php
                        $table_data = [];

                        $table_data = array_merge($table_data, array(
                            'WORKFLOW',
                            'SOLICITADO POR',
                            'DATA',
                            'DESCRIÇÃO',
                            'STATUS'
                        ));
                        render_datatable($table_data, 'internal_requests');
                        ?>
                    </div>


                </div>
            </div>


            <div class="panel_s">

                <div class="panel-body">
                    <div class="horizontal-scrollable-tabs">
                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">

                                <li role="presentation" >
                                    <a href="#meus_workflows" aria-controls="meus_workflows" role="tab" data-toggle="tab">
                                        Meus
                                    </a>
                                </li>
                                <li role="presentation" class="active">
                                    <a href="#assumidos" aria-controls="assumidos" role="tab" data-toggle="tab">
                                        Setor - Assumidos
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#setor_pendentes" aria-controls="setor_pendentes" role="tab" data-toggle="tab">
                                        Setor - Pendentes
                                    </a>
                                </li>

                                <li role="presentation" class="">
                                    <a href="#setor_finalizados" aria-controls="setor_finalizados" role="tab" data-toggle="tab">
                                        Setor - Finalizados
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="tab-content mtop15">


                        <div role="meus_workflows" class="tab-pane " id="meus_workflows">
                            <div class="row">
                                <div class="col-md-11 col-sm-11 "  >


                                    <div class="row ">
                                        <input name="1-type" type="hidden" value="1">
                                        <div class=" col-md-6 col-sm-4" >
                                            <div class="form-group">
                                                <input class="form-control"  type="text" name="1-client" value="<?php echo $filters[1]['client']; ?>" placeholder="NOME CLIENTE">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-4 ">
                                            <div class="form-group ">
                                                <input type="date"  name="1-data_de" id="1-data_de" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-4 ">
                                            <div class="form-group">

                                                <input type="date" name="1-data_ate" id="1-data_ate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-1 ">                    
                                    <button type="button" class="btn btn-warning dropdown-toggle w-100" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="reload_tables('workflow_my', '1');">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>


                                </div> 

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
                                        '<strong style="font-weight: bold">CATEGORIA</strong>',
                                        '<strong style="font-weight: bold">CLIENTE</strong>',
                                        '<strong style="font-weight: bold">CADASTRO</strong>',
                                        '<strong style="font-weight: bold">STATUS</strong>',
                                        '<strong style="font-weight: bold">RESPONSÁVEL</strong>',
                                        '<strong style="font-weight: bold">PRAZO</strong>',
                                    );
                                    render_datatable($table_data, 'workflow_my');
                                    ?>

                                </div>
                            </div>
                        </div>
                        <div role="assumidos" class="tab-pane active" id="assumidos">
                            <div class="row">
                                <div class="col-md-11 col-sm-11 "  >


                                    <div class="row">
                                        <input name="2-type" type="hidden" value="2">
                                        <div class="col-md-2 col-sm-4 "  >
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">WF #</span>
                                                    <input type="number"  class="form-control" value="<?php echo $filters[2]['id']; ?>" name="2-id" required="true" onkeyup="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-4 "  >
                                            <div class="form-group">
                                                <input class="form-control" type="number" name="2-protocolo" value="<?php echo $filters[2]['protocolo']; ?>" onkeyup="" placeholder="PROTOCOLO">
                                            </div>
                                        </div>

                                        <div class=" col-md-3 col-sm-4" >
                                            <div class="form-group">
                                                <input class="form-control"  type="text" name="2-client" value="<?php echo $filters[2]['client']; ?>" placeholder="CLIENTE">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6"  >
                                            <div class="form-group">
                                                <input class="form-control"  type="number" name="2-carteirinha" value="<?php echo $filters[2]['carteirinha']; ?>" onkeyup="" placeholder="CARTEIRINHA">
                                            </div>
                                        </div> 
                                        <div class="col-md-2 col-sm-6">
                                            <div class="select-placeholder form-group" app-field-wrapper="2-period">

                                                <select id="2-period" name="2-period" class="selectpicker"  required="true"  onchange="" data-width="100%" 
                                                        data-none-selected-text="PERÍODO" data-live-search="true">

                                                    <option value="d" <?php
                                                    if ($filters[2]['period'] == 'd' || $filters[2]['period'] == '') {
                                                        echo 'selected';
                                                    }
                                                    ?>>Recebidos hoje</option>
                                                    <option value="s" <?php
                                                    if ($filters[2]['period'] == 's') {
                                                        echo 'selected';
                                                    }
                                                    ?>>Recebidos na semana</option>
                                                    <option value="m" <?php
                                                    if ($filters[2]['period'] == 'm') {
                                                        echo 'selected';
                                                    }
                                                    ?>>Recebidos no mês</option>
                                                    <option value="-" <?php
                                                    if ($filters[2]['period'] == '-') {
                                                        echo 'selected';
                                                    }
                                                    ?>>Todos</option>
                                                </select>
                                            </div>                    
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-1 ">                    
                                    <button type="button" class="btn btn-warning dropdown-toggle w-100" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="reload_tables('workflow_assumidos', '2');">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>


                                </div>   
                                <div class="col-md-12">
                                    <?php
                                    $data['tipo'] = 2;
//$this->load->view('gestao_corporativa/workflow/summary', $data);
                                    ?>
                                    <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="clearfix"></div>
                                    <?php
                                    $table_data = [];

                                    $table_data = array_merge($table_data, array(
                                        '<strong style="font-weight: bold">ID</strong>',
                                        '<strong style="font-weight: bold">CATEGORIA</strong>',
                                        '<strong style="font-weight: bold">CLIENTE</strong>',
                                        '<strong style="font-weight: bold">CADASTRO</strong>',
                                        '<strong style="font-weight: bold">STATUS</strong>',
                                        '<strong style="font-weight: bold">ATRIBUÍDO</strong>',
                                        '<strong style="font-weight: bold">OBJETIVO</strong>',
                                        '<strong style="font-weight: bold">RESPONSABILIDADE</strong>',
                                    ));
                                    render_datatable($table_data, 'workflow_assumidos');
                                    ?>

                                </div>
                            </div>
                        </div>
                        <div role="setor_pendentes" class="tab-pane" id="setor_pendentes">
                            <div class="row">
                                <div class="col-md-11 col-sm-11 "  >

                                    <input name="3-type" type="hidden" value="3">
                                    <div class="row">

                                        <div class="col-md-2 col-sm-4 "  >
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">WF #</span>
                                                    <input type="number" class="form-control" value="<?php echo $filters[3]['id']; ?>" name="3-id" required="true" onkeyup="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-4 "  >
                                            <div class="form-group">
                                                <input class="form-control"  type="number" name="3-protocolo" value="<?php echo $filters[3]['protocolo']; ?>" onkeyup="" placeholder="PROTOCOLO">
                                            </div>
                                        </div>
                                        <div class=" col-md-3 col-sm-4" >
                                            <div class="form-group">
                                                <input class="form-control"  type="text" name="3-client" value="<?php echo $filters[3]['client']; ?>" placeholder="NOME CLIENTE">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6"  >
                                            <div class="form-group">
                                                <input class="form-control" type="number" name="3-carteirinha" value="<?php echo $filters[3]['carteirinha']; ?>" onkeyup="" placeholder="CARTEIRINHA">
                                            </div>
                                        </div> 
                                        <div class="col-md-2 col-sm-6">
                                            <div class="select-placeholder form-group" app-field-wrapper="3-period">
                                                <select id="3-period" name="3-period" class="selectpicker" required="true" onchange="" data-width="100%" 
                                                        data-none-selected-text="PERÍODO" data-live-search="true">
                                                    <option value="d" <?php
                                                    if ($filters[3]['period'] == 'd' || $filters[2]['period'] == '') {
                                                        echo 'selected';
                                                    }
                                                    ?>>Recebidos hoje</option>
                                                    <option value="s" <?php
                                                    if ($filters[3]['period'] == 's') {
                                                        echo 'selected';
                                                    }
                                                    ?>>Recebidos na semana</option>
                                                    <option value="m" <?php
                                                    if ($filters[3]['period'] == 'm') {
                                                        echo 'selected';
                                                    }
                                                    ?>>Recebidos no mês</option>
                                                    <option value="Y" <?php
                                                    if ($filters[3]['period'] == 'Y') {
                                                        echo 'selected';
                                                    }
                                                    ?>>Recebidos no ano</option>
                                                    <option value="-" <?php
                                                    if ($filters[3]['period'] == '-') {
                                                        echo 'selected';
                                                    }
                                                    ?>>Todos</option>

                                                </select>
                                            </div>                    
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-1 ">                    
                                    <button type="button" class="btn btn-warning dropdown-toggle w-100" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  onclick="reload_tables('workflow_setor', '3');">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>


                                </div>     
                                <div class="col-md-12">
                                    <?php
                                    $data['tipo'] = 3;
//$this->load->view('gestao_corporativa/workflow/summary', $data);
                                    ?>
                                    <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="clearfix"></div>
                                    <?php
                                    $table_data = [];

                                    $table_data = array_merge($table_data, array(
                                        '<strong style="font-weight: bold">ID</strong>',
                                        '<strong style="font-weight: bold">CATEGORIA</strong>',
                                        '<strong style="font-weight: bold">CLIENTE</strong>',
                                        '<strong style="font-weight: bold">CADASTRO</strong>',
                                        '<strong style="font-weight: bold">STATUS</strong>',
                                        '<strong style="font-weight: bold">ATRIBUÍDO</strong>',
                                        '<strong style="font-weight: bold">OBJETIVO</strong>',
                                        '<strong style="font-weight: bold">RESPONSABILIDADE</strong>',
                                    ));
                                    render_datatable($table_data, 'workflow_setor');
                                    ?>

                                </div>
                            </div>
                        </div>

                        <div role="setor_finalizados" class="tab-pane" id="setor_finalizados">
                            <div class="row">
                                <div class="col-md-11 col-sm-11 "  >


                                    <div class="row">
                                        <input name="4-type" type="hidden" value="4">
                                        <div class="col-md-2 col-sm-4 "  >
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-addon">WF #</span>
                                                    <input type="number"class="form-control" value="<?php echo $filters[4]['id']; ?>" name="4-id" required="true" onkeyup="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-4 "  >
                                            <div class="form-group">
                                                <input class="form-control" type="number" name="4-protocolo" value="<?php echo $filters[4]['protocolo']; ?>" onkeyup="" placeholder="PROTOCOLO">
                                            </div>
                                        </div>

                                        <div class=" col-md-2 col-sm-4" >
                                            <div class="form-group">
                                                <input class="form-control"  type="text" name="4-client" value="<?php echo $filters[4]['client']; ?>" placeholder="NOME CLIENTE">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 "  >
                                            <div class="form-group">
                                                <input class="form-control"type="number" name="4-carteirinha" value="<?php echo $filters[4]['carteirinha']; ?>" onkeyup="" placeholder="CARTEIRINHA">
                                            </div>
                                        </div> 
                                        <div class="col-md-2 col-sm-4 ">
                                            <div class="form-group">
                                                <input type="date"  name="4-data_de" id="4-data_de" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-4 ">
                                            <div class="form-group">

                                                <input type="date" name="4-data_ate" id="4-data_ate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-1 ">                    
                                    <button type="button" class="btn btn-warning dropdown-toggle w-100" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="reload_tables('workflow_setor_finalizados', '4');">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>


                                </div>  

                                <div class="col-md-12">
                                    <?php
                                    $data['tipo'] = 4;
//$this->load->view('gestao_corporativa/workflow/summary', $data);
                                    ?>
                                    <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="clearfix"></div>
                                    <?php
                                    $table_data = [];

                                    $table_data = array_merge($table_data, array(
                                        '<strong style="font-weight: bold">ID</strong>',
                                        '<strong style="font-weight: bold">CATEGORIA</strong>',
                                        '<strong style="font-weight: bold">CLIENTE</strong>',
                                        '<strong style="font-weight: bold">CADASTRO</strong>',
                                        '<strong style="font-weight: bold">STATUS</strong>',
                                        '<strong style="font-weight: bold">OBJETIVO</strong>',
                                        '<strong style="font-weight: bold">RESPONSABILIDADE</strong>',
                                        '<strong style="font-weight: bold">FLUXO ATUAL</strong>',
                                    ));
                                    render_datatable($table_data, 'workflow_setor_finalizados');
                                    ?>

                                </div>
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


<script>


    function reload_tables(table, type, tipo_atraso = '', filter = true) {

        if ($.fn.DataTable.isDataTable('.table-' + table)) {
            $('.table-' + table).DataTable().destroy();
        }

        var Params = {};
<?php foreach ($statuses as $status) { ?>
            Params['status_<?php echo $status['id']; ?>'] = '[name="status_<?php echo $status['id']; ?>"]';
<?php } ?>

        Params['type'] = '[name="' + type + '-type"]';
        Params['client'] = '[name="' + type + '-client"]';
        if (type == '1' || type == '4') {
            Params['data_de'] = '[name="' + type + '-data_de"]';
            Params['data_ate'] = '[name="' + type + '-data_ate"]';
        }
        if (type == '2' || type == '3' || type == '4') {


            Params['id'] = '[name="' + type + '-id"]';
            Params['protocolo'] = '[name="' + type + '-protocolo"]';
            Params['carteirinha'] = '[name="' + type + '-carteirinha"]';
            if (type == '2' || type == '3') {
                Params['period'] = '[name="' + type + '-period"]';
            }

        }
        if (type == '1') {
            var search = 6;
        }
        if (type == '2' || type == '3' || type == '4') {
            var search = 7;

        }

        if (tipo_atraso == '1') {
            // Params['atrasado'] = '[name="staff_id"]';
        }
        if (tipo_atraso == '2') {
            // Params['em_dia'] = '[name="staff_id"]';
        }

        if (filter == true) {
            Params['filter'] = '[name="staff_id"]';
        }

        Params['clientid'] = '[name="clientid"]';
        Params['categoria_id'] = '[name="categoria_id"]';
        Params['status'] = '[name="status"]';
        Params['departments'] = '[name="departments"]';



        initDataTable('.table-' + table, '<?php echo base_url(); ?>' + 'gestao_corporativa/workflow/table', [0], [0], Params, <?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0, 'desc'))); ?>);
    }


    $(function () {
        reload_tables_all();
    });
    function novo_change(novo) {
        if (novo) {
            if ($('input[name="' + novo + '"]').val() === '') {
                $('input[name="' + novo + '"]').val('1');
            } else {
                $('input[name="' + novo + '"]').val('');
            }
        }
    }


    function mudar_class(el) {
        element = document.getElementById(el + '_div');
        var display = element.style.display;
        if (display == "none") {
            element.style.display = 'block';
        } else {
            element.style.display = 'none';
            document.getElementsByName(el)[0].value = '';
            reload_tables_all();
        }
    }

    function reload_tables_all() {


        //  document.getElementById('button_search').style.display = 'none';
        reload_tables('workflow_assumidos', '2', '', false);
        reload_tables('workflow_setor', '3', '', false);
        reload_tables('workflow_setor_finalizados', '4', '', false);
        reload_tables('workflow_my', '1', '', false);

    }

</script>

<script>
    $(function () {
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
        initDataTable('.table-internal_requests', '<?php echo base_url(); ?>' + 'gestao_corporativa/workflow/table_internal_request', [5], [5], CustomersServerParams);
    });
    function Open_internal_request(el) {
        //alert(el + fluxo_andamento_id + fluxo_andamento_id_old); exit;
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Workflow/modal", {
            slug: 'open_internal_request',
            id: el
        }, function () {
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
        var div = document.getElementById("id_internal");
        div.style.display = (div.style.display === "none") ? "block" : "none";
    }

    function take_responsability(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Workflow/take_responsability'); ?>",
            data: {
                id: id
            },
            success: function (data) {
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
                id: id, atribuido_a: atribuido_a
            },
            success: function (data) {
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
        }, function () {
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

    // DropzoneJS Demo Code End
</script>
<script src="http://[::1]/sigplus/modules/prchat/assets/js/pr-chat.js?v=2.7.0"></script>
</body>
</html>
