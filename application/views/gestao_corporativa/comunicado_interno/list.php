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


    $(function () {

        var Params = {};
        var tAPI = initDataTable('.table-comunicados', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_comunicado', [0], [0], Params, [1, 'desc']);

    });
    $(function () {

        var Params = {};
        Params['my'] = '[name="id_user"]';
        initDataTable('.table-comunicados_my', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_comunicado', [0], [0], Params, [1, 'desc']);

    });

</script>
</body>
</html>
