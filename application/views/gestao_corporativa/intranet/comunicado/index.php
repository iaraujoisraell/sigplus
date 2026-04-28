<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Intra/Comunicado'); ?>"><i class="fa fa-backward"></i> Comunicados Internos </a></li>
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
                                        Comunicados Recebidos
                                    </a>
                                    <input type="hidden" value="<?php echo get_staff_user_id(); ?>" name="id_user">
                                </li>

                                <li role="presentation" class="">
                                    <a href="#contact_info2" aria-controls="contact_info2" role="tab" data-toggle="tab">
                                        Meus Comunicados
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
                                    $this->load->model('Registro_ocorrencia_model');
                                    $data['tipo'] = 1;
                                    ?>
                                    <?php if (has_permission_intranet('comunicado_interno', '', 'create') || is_admin()) {?>
                                    <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="clearfix"></div>
                                    <?php }?>

                                    <?php
                                    $table_data = [];

                                    $table_data = array_merge($table_data, array(
                                        'ID',
                                        'Título',
                                        'Enviado em:',
                                        'Ciente em:',
                                    ));
                                    render_datatable($table_data, 'comunicados');
                                    ?>

                                </div>
                            </div>
                        </div>
                        <div role="tabpane2" class="tab-pane" id="contact_info2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="_buttons">
                                        <?php if (has_permission_intranet('ci', '', 'create') || is_admin()) { ?>
                                            <a href="<?php echo base_url('gestao_corporativa/intra/Comunicado/index'); ?>" class="btn btn-info pull-left display-block mright5">
                                                Novo Comunicado
                                            </a>
                                        <?php } ?>
                                        <a href="#" class="btn btn-default btn-with-tooltip" data-toggle="tooltip" data-placement="bottom" data-title="<?php echo _l('tickets_chart_weekly_opening_stats'); ?>" onclick="slideToggle('.weekly-ticket-opening', init_tickets_weekly_chart); return false;"><i class="fa fa-bar-chart"></i></a>

                                    </div>
                                    <hr class="hr-panel-heading" />
                                    <?php
                                    $data['tipo'] = 2;
                                    ?>
                                    <a href="#" data-toggle="modal" data-target="#tickets_bulk_actions" class="bulk-actions-btn table-btn hide" data-table=".table-tickets"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="clearfix"></div>
                                    <?php
                                    $table_data = [];
                                    
                                    $table_data = array_merge($table_data, array(
                                        'ID',
                                        'Título',
                                        'Data de Cadastro',
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

        var CustomersServerParams = {};
        var tAPI = initDataTable('.table-comunicados', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_comunicado', [0], [0], CustomersServerParams, [1, 'desc']);

    });
    $(function () {

        var CustomersServerParams = {};
        CustomersServerParams['my'] = '[name="id_user"]';
        var tAPI = initDataTable('.table-comunicados_my', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_comunicado', [0], [0], CustomersServerParams, [1, 'desc']);

    });

</script>

</body>
</html>
