<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head_intranet_novo(); ?>
<?php $this->load->view('gestao_corporativa/css_background'); ?>
<input name="staff_id" type="hidden" value="<?php echo get_staff_user_id(); ?>">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div>
                <ol class="breadcrumb" style="background-color: white;">
                    <li><a href="<?= base_url('gestao_corporativa/intranet'); ?>"><i class="fa fa-home"></i> Home </a></li> 
                    <li><a href="<?= base_url('gestao_corporativa/Cdc/list_'); ?>"><i class="fa fa-backward"></i> CDC </a></li>
                    <li><i class="fa fa-file"></i> <?php echo $cdc->titulo; ?> </li>
                    <li> Destinatários e Cientes </li>
                </ol>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel_s">
                <div class="panel-heading">
                    Setores Destinatários
                </div>
                <div class="panel-body">
                    <?php echo form_open('gestao_corporativa/cdc/edit_send/'.$cdc->id); ?>
                    <?php
                    //print_r($destiny);
                    foreach ($departments as $department) {
                        $checked = '';
                        if (is_array($destiny)) {
                            if (in_array($department['departmentid'], $destiny)) {
                                $checked = 'checked';
                            }
                        }
                        ?>
                        <div class="checkbox checkbox-primary col-md-4">

                            <input type="checkbox" id="dep_<?php echo $department['departmentid']; ?>" name="departments[]" value="<?php echo $department['departmentid']; ?>" <?php echo $checked; ?>>
                            <label for="dep_<?php echo $department['departmentid']; ?>"><?php echo $department['name']; ?></label>
                        </div>
                    <?php } ?>
                </div>
                <div class="panel-footer" style="text-align: right;">
                    <button class="btn-sm btn-warning" type="submit"> EDITAR <i class="fa fa-edit"></i></button>

                </div>
                <?php form_close(); ?>
            </div>

        </div>
        <div class="col-md-6">
            <div class="panel_s">
                <div class="panel-heading">
                    Colaboradores Cientes
                </div>
                <div class="panel-body">
                    <a download="CDC<?php echo $cdc->id; ?>-<?php echo $cdc->titulo; ?>.xls" class="btn btn-default pull-left mright10" href="#" onclick="return ExcellentExport.excel(this, 'expenses-report-table', 'Expenses Report 2023');"><i class="fa fa-file-excel-o"></i></a>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover expenses-report" id="expenses-report-table">
                            <thead>

                                <tr> 

                                    <th class="bold" colspan="3">VISUALZAÇÕES CDC #<?php echo $cdc->id; ?> </th>



                                </tr>
                                <tr> 

                                    <th class="bold">COLABORADOR</th>
                                    <th class="bold">STATUS</th>
                                    <th class="bold">DATA</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($viewed as $view) { ?>
                                    <tr class="">
                                        <td class="bold bg-odd"> <?php echo get_staff_full_name($view['destino']); ?></td>
                                        <td class="bold"> <span class="ticket-label label label-success inline-block">
                                                CIENTE
                                            </span></td>
                                        <td> <?php echo _d($view['dt_read']); ?></td>


                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
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
        initDataTable('.table-documentos_andamento', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento_andamento', [0], [0], CustomersServerParams, [1, 'desc']);
    });
<?php foreach ($responsavel as $categoria) { ?>
        $(function () {
            var CustomersServerParams = {};
            CustomersServerParams['categoria_id'] = '[name="categoria_id_<?php echo $categoria['id']; ?>"]';
            initDataTable('.table-documentos_<?php echo $categoria["id"]; ?>', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento', [0], [0], CustomersServerParams, [1, 'desc']);
        });
<?php } ?>
    $(function () {
        var CustomersServerParams = {};
        var tAPI = initDataTable('.table-documentos', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_documento', [0], [0], CustomersServerParams, [1, 'desc']);
    });



</script>
<script src="<?php echo base_url('assets/plugins/excellentexport/excellentexport.min.js'); ?>"></script>
</body>
</html>
