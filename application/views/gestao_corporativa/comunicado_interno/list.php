<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<input name="staff_id" type="hidden" value="<?php echo get_staff_user_id(); ?>">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight: bold;">INTRANET - COMUNICADOS INTERNOS</h5>
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/intra/Comunicado'); ?>"> Comunicados Internos </a></li>
                </ol>
            </div>
        </div>
        
        <div class="col-md-12">
            <?php if (has_permission_intranet('comunicado_interno', '', 'create') || is_admin()) { ?>
            <div class="panel_s mbot10">
                <div class="panel-body _buttons">
                    
                        <a href="<?php echo base_url('gestao_corporativa/intra/comunicado/add'); ?>" class="btn btn-info pull-left display-block mright5">
                            <i class="fa fa-plus"></i> NOVO COMUNICADO
                        </a>
                    

                </div>
            </div>
            <?php } ?>
            <input type="hidden" value="<?php echo get_staff_user_id(); ?>" name="id_user">

            <div class="panel_s mbot10">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="filtro_titulo" placeholder="Buscar título...">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="filtro_dt_ini" placeholder="Início">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" name="filtro_dt_fim" placeholder="Fim">
                        </div>
                        <div class="col-md-3">
                            <select name="filtro_status" class="form-control">
                                <option value="">Todos</option>
                                <option value="1">Apenas lidos</option>
                                <option value="0">Apenas não lidos</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-default btn-block" id="btn_limpar_filtros">Limpar</button>
                        </div>
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

                                <li role="presentation" class="active">
                                    <a href="#recebidos" aria-controls="recebidos" role="tab" data-toggle="tab">
                                        Recebidos
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#meus" aria-controls="meus" role="tab" data-toggle="tab">
                                        Cadastrados
                                    </a>
                                </li>


                            </ul>
                        </div>
                    </div>
                    <div class="tab-content mtop15">
                        <div role="recebidos" class="tab-pane active" id="recebidos">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    ?>
                                    <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="clearfix"></div>
                                    <?php
                                    $table_data = [];

                                    $table_data = array_merge($table_data, array(
                                        'ID',
                                        'Título',
                                        'Autor',
                                        'Setor',
                                        'Recebido em',
                                        'Lido em',
                                        'Ciente em',
                                        'PDF',
                                    ));
                                    render_datatable($table_data, 'comunicados');
                                    ?>

                                </div>
                            </div>
                        </div>

                        <div role="meus" class="tab-pane" id="meus">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    ?>
                                    <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="clearfix"></div>
                                    <?php
                                    $table_data = [];

                                    $table_data = array_merge($table_data, array(
                                        'ID',
                                        'Título',
                                        'Setor',
                                        'Data de Envio',
                                        'Cientes',
                                        'Ações'
                                    ));
                                    render_datatable($table_data, 'comunicados_my');
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


    var FiltroParams = {
        filtro_titulo:  '[name="filtro_titulo"]',
        filtro_dt_ini:  '[name="filtro_dt_ini"]',
        filtro_dt_fim:  '[name="filtro_dt_fim"]',
        filtro_status:  '[name="filtro_status"]',
    };

    $(function () {
        initDataTable('.table-comunicados', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_comunicado', [0], [0], FiltroParams, [1, 'desc']);

        var ParamsMy = $.extend({}, FiltroParams, { my: '[name="id_user"]' });
        initDataTable('.table-comunicados_my', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_comunicado', [0], [0], ParamsMy, [1, 'desc']);
    });

    var filtroTimer = null;
    function reloadTabelas() {
        if ($.fn.DataTable.isDataTable('.table-comunicados')) $('.table-comunicados').DataTable().ajax.reload(null, false);
        if ($.fn.DataTable.isDataTable('.table-comunicados_my')) $('.table-comunicados_my').DataTable().ajax.reload(null, false);
    }
    $(document).on('keyup', '[name="filtro_titulo"]', function () {
        clearTimeout(filtroTimer);
        filtroTimer = setTimeout(reloadTabelas, 300);
    });
    $(document).on('change', '[name="filtro_dt_ini"], [name="filtro_dt_fim"], [name="filtro_status"]', reloadTabelas);
    $('#btn_limpar_filtros').on('click', function () {
        $('[name="filtro_titulo"], [name="filtro_dt_ini"], [name="filtro_dt_fim"]').val('');
        $('[name="filtro_status"]').val('').trigger('change');
        reloadTabelas();
    });
</script>
</body>
</html>
