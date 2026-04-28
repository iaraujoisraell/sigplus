<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/lte/plugins/select2/css/select2.min.css">
<style>
    body {
        font-family:'Open Sans';
        background:#f1f1f1;
    }
    h3 {
        margin-top: 7px;
        font-size: 18px;
    }

    .install-row.install-steps {
        margin-bottom:15px;
        box-shadow: 0px 0px 1px #d6d6d6;
    }

    .control-label {
        font-size:13px;
        font-weight:600;
    }
    .padding-10 {
        padding:10px;
    }
    .mbot15 {
        margin-bottom:15px;
    }
    .bg-default {
        background: #03a9f4;
        border:1px solid #03a9f4;
        color:#fff;
    }
    .bg-success {
        border: 1px solid #dff0d8;
    }
    .bg-not-passed {
        border:1px solid #f1f1f1;
        border-radius:2px;
    }
    .bg-not-passed {
        border-right:0px;
    }
    .bg-not-passed.finish {
        border-right:1px solid #f1f1f1 !important;
    }
    .bg-not-passed h5 {
        font-weight:normal;
        color:#6b6b6b;
    }
    .form-control {
        box-shadow:none;
    }
    .bold {
        font-weight:600;
    }
    .col-xs-5ths,
    .col-sm-5ths,
    .col-md-5ths,
    .col-lg-5ths {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }
    .col-xs-5ths {
        width: 20%;
        float: left;
    }
    b {
        font-weight:600;
    }
    .bootstrap-select .btn-default {
        background: #fff !important;
        border: 1px solid #d6d6d6 !important;
        box-shadow: none;
        color: #494949 !important;
        padding: 6px 12px;
    }
</style>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Ged'); ?>"><i class="fa fa-backward"></i> Gestão Eletrônica de Documentos </a></li>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
            <div class="panel_s">

                <div class="panel-body">
                    <div class="horizontal-scrollable-tabs">
                        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#contact_info1" aria-controls="contact_info1" role="tab" data-toggle="tab">
                                        GESTÃO ELETRÔNICA DE DOCUMENTOS
                                    </a>
                                    <input type="hidden" value="<?php echo get_staff_user_id(); ?>" name="id_user">
                                </li>
                                <li role="presentation" class="">
                                    <a href="#contact_info2" aria-controls="contact_info2" role="tab" data-toggle="tab">
                                        DOCUMENTOS CADASTRADOS POR MIM
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div class="tab-content mtop15">

                        <div role="tabpanel" class="tab-pane active" id="contact_info1">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    $data['tipo'] = 1;
                                    $this->load->view('gestao_corporativa/registro_ocorrencia/summary', $data);
                                    ?>
                                    <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="clearfix"></div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select id="categoria" name="categoria" class="selectpicker" data-width="100%" data-none-selected-text="Categoria" data-live-search="true">

                                                <option value=""></option>
                                                <?php foreach ($categorias as $categoria) { ?>

                                                    <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['titulo']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-8" id="trocar">

                                        </div>
                                    </div>
                                    <div class="row" id="trocar_table">
                                        <div class="col-md-12" >
                                            <?php
                                            $table_data = [];

                                            $table_data = array_merge($table_data, array(
                                                'ID',
                                                'Título',
                                                'Categoria',
                                            ));
                                            render_datatable($table_data, 'table');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpane2" class="tab-pane" id="contact_info2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="_buttons">
                                        <?php if (has_permission_intranet('ged', '', 'create') || is_admin()) { ?>
                                            <a href="<?php echo base_url('gestao_corporativa/Ged/add'); ?>" class="btn btn-info pull-left display-block mright5">
                                                Novo Arquivo
                                            </a>
                                        <?php } ?>
                                        <?php if (has_permission_intranet('ged', '', 'view_categoria') || is_admin()) { ?>
                                            <a onclick="mudar('tipos');" class="btn btn-info pull-left display-block mright5">
                                                Categorias
                                            </a>
                                        <?php } ?>
                                        <a href="#" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" data-placement="bottom" data-title="<?php echo _l('tickets_chart_weekly_opening_stats'); ?>" onclick="slideToggle('.weekly-ticket-opening', init_tickets_weekly_chart); return false;"><i class="fa fa-bar-chart"></i></a>

                                        <?php if (has_permission_intranet('ged', '', 'view_categoria') || is_admin()) { ?>
                                            <div class="panel_s" style="margin-top: 10px; display: none;" id="tipos">
                                                <div class="panel-body">
                                                    <?php if (has_permission_intranet('registro_ocorrencia', '', 'create_categoria') || is_admin()) { ?>

                                                        <div class="_buttons">
                                                            <a data-toggle="modal" data-target="#add_tipo"" class="btn btn-info pull-left" ><?php echo 'Nova Categoria'; ?></a>    

                                                            <div class="clearfix"></div>
                                                            <hr class="hr-panel-heading" />
                                                        </div>
                                                    <?php } ?>


                                                    <?php
                                                    $table_data = [];
                                                    $table_data = array_merge($table_data, array(
                                                        'Título',
                                                        'Cadastro',
                                                        'Campos',
                                                        'Opções'
                                                    ));
                                                    render_datatable($table_data, 'ged_categorias');
                                                    ?>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                    <hr class="hr-panel-heading" />
                                    <?php
                                    ?>
                                    <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="clearfix"></div>
                                    <?php
                                    $table_data = [];

                                    $table_data = array_merge($table_data, array(
                                        'ID',
                                        'Título',
                                        'Categoria',
                                    ));
                                    render_datatable($table_data, 'ged');
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
<?php if (has_permission_intranet('ged', '', 'create_categoria') || is_admin()) { ?>
    <div class="modal fade" id="add_tipo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <span class="edit-title text-white"><?php echo 'Nova Categoria'; ?></span>
                    </h4>
                </div>



                <div class="modal-body">
                    <?php echo render_input('titulo_nova_categoria', 'Titulo', '', 'text', array('required' => 'true')); ?>
                    <div class="form-group" style="margin-top: 10px;">

                        <label>CC</label>
                        <div class="select2-blue">
                            <select class="select2" multiple data-placeholder="Selecione" data-dropdown-css-class="select2-blue" style="width: 100%;" name="cc[]">
                                <?php foreach ($staffs as $staff) { ?>
                                    <option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
                                <?php } ?>

                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button onclick="add_categoria();" class="btn btn-primary"><?php echo _l('submit'); ?></button>

                </div>
            </div>
        </div>
    </div>
<?php } ?>



<div id="modal_wrapper"></div>
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
</script>
<script>

    function delete_categoria(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/ged/delete_tipo'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                reload_categoria();
            }
        });
    }

    function reload_categoria() {
        var CustomersServerParams = {};
        if ($.fn.DataTable.isDataTable('.table-ged_categorias')) {
            $('.table-ged_categorias').DataTable().destroy();
        }
        var tAPI = initDataTable('.table-ged_categorias', '<?php echo base_url(); ?>' + 'gestao_corporativa/Ged/table_categorias', [0], [0], CustomersServerParams, [1, 'desc']);
    }


    function Update_categoria(el) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/ged/modal", {
            slug: 'update_categoria',
            id: el
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#edit_categoria').is(':hidden')) {
                $('#edit_categoria').modal({
                    show: true
                });
            }
        });
    }

    function Campos(el) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Ged/modal", {
            slug: 'campos_categoria',
            id: el
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#Campos').is(':hidden')) {
                $('#Campos').modal({
                    show: true
                });
            }
        });
    }

    function add_categoria() {
        var titulo = document.querySelector("#titulo_nova_categoria");
        var titulo = titulo.value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Ged/add_tipo'); ?>",
            data: {
                titulo: titulo
            },
            success: function (data) {
                $('#add_tipo').modal('hide');
                reload_categoria();
            }
        });
    }

</script>

<script>

    function add_opcoes() {
        var div = document.getElementById('duplicar'),
                clone = div.cloneNode(true); // true means clone all childNodes and all event handlers
        $('.caixadefilhos').append(clone);
        //deletclass('duplicar' + id);

    }

    $(function () {
        var CustomersServerParams = {};
        var tAPI = initDataTable('.table-ged_categorias', '<?php echo base_url(); ?>' + 'gestao_corporativa/Ged/table_categorias', [0], [0], CustomersServerParams, [1, 'desc']);
    });
    $(function () {
        var CustomersServerParams = {};
        CustomersServerParams['id_user'] = '[name="id_user"]';
        var tAPI = initDataTable('.table-table', '<?php echo base_url(); ?>' + 'gestao_corporativa/Ged/table', [0], [0], CustomersServerParams, [1, 'desc']);
    });

    $(function () {
        var CustomersServerParams = {};
        CustomersServerParams['department'] = '[name="id_user"]';
        var tAPI = initDataTable('.table-ged', '<?php echo base_url(); ?>' + 'gestao_corporativa/Ged/table', [0], [0], CustomersServerParams, [1, 'desc']);
    });





</script>

<script src="<?php echo base_url() ?>assets/lte/plugins/select2/js/select2.full.min.js"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    });

    // DropzoneJS Demo Code End
</script>
<script>


    $(document).on('change', "#categoria", function () {
        var select = document.getElementById("categoria");
        var opcaoValor = select.options[select.selectedIndex].value;

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/ged/retorno_categoria'); ?>",
            data: {
                categoria_id: opcaoValor
            },
            success: function (data) {
                $('#trocar').html(data);
            }
        });

    });
    $(document).on('change', "#categoria", function () {
        var select = document.getElementById("categoria");
        var opcaoValor = select.options[select.selectedIndex].value;

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/ged/retorno_table_categorias'); ?>",
            data: {
                categoria_id: opcaoValor
            },
            success: function (data) {
                $('#trocar_table').html(data);
                var CustomersServerParams = {};
                CustomersServerParams['categoria_id'] = '[name="categoria"]';
                initDataTable('.table-table', '<?php echo base_url(); ?>' + 'gestao_corporativa/Ged/table_categorias_dinamic', [0], [0], CustomersServerParams, [1, 'desc']);
            }
        });

    });
</script>
</body>
</html>
