<?php
$active = false;
$have = false;
?>
<h4 class="customer-profile-group-heading"><?php echo 'Portal - Configurações'; ?></h4>

<div class="mtop15">
    <div class="horizontal-scrollable-tabs">
        <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
        <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
        <div class="horizontal-tabs">
            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal" role="tablist">
                <?php if (has_permission_intranet('portal', '', 'autosservicos') || is_admin()) { ?>
                    <li role="presentation" class="active">
                        <a href="#autosservicos" aria-controls="autosservicos" role="tab" data-toggle="tab">
                            Autosserviços 
                        </a>
                    </li>
                <?php } ?>
                <?php if (has_permission_intranet('portal', '', 'tickets') || is_admin()) { ?>
                    <li role="presentation" class="">
                        <a href="#tickets" aria-controls="tickets" role="tab" data-toggle="tab">
                            Datas Boletos Disponíveis
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="tab-content mtop15">
        <?php if (has_permission_intranet('portal', '', 'autosservicos') || is_admin()) { ?>
            <div role="tabpane2" class="tab-pane active" id="autosservicos">
                <?php
                $data['rel_type'] = 'autosservico';
                $this->load->view('gestao_corporativa/categorias_campos/admin_categoria_tab', $data);
                ?>
            </div>
        <?php } ?>
        <?php if (has_permission_intranet('portal', '', 'tickets') || is_admin()) { ?>
            <div role="tabpane2" class="tab-pane " id="tickets">
                <div class="row">
                    <div class="col-md-12">

                        <div class="_buttons">
                            <?php echo render_input("date", 'DATA QUE O BOLETO PODE ESTAR DISPONÍVEL', '', 'date', array('required' => 'true')); ?>
                            <a onclick="save();" class="btn btn-info pull-left w-100" ><?php echo 'Salvar Data'; ?></a>

                        </div>
                        <div class="clearfix"></div>

                        <hr class="hr-panel-heading" />

                        <?php
                        $table_data = [];
                        array_push($table_data, 'Data');
                        array_push($table_data, 'Cadastro');
                        array_push($table_data, 'Ativo');
                        array_push($table_data, 'Deletar');
                        render_datatable($table_data, 'dates_ticket');
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

</div>
<div id="modal_wrapper"></div>


<script>

    function active(active, id) {
       
        if (active.checked) {
           var active = 1;
        } else {
           var active = 0;
        }
        //alert(active);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/date_active'); ?>",
            data: {
                active: active, id: id
            },
            success: function (data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }


    $(function () {
        var Params = {};
     
        initDataTable('.table-dates_ticket', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_dates_ticket', [0], [0], Params, [1, 'desc']);
    });

    function _delete(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/delete_date_ticket'); ?>",
            data: {
                id: id
            },
            success: function (data) {
                reload();
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
            }
        });
    }



    function reload() {
        var Params = {};
        if ($.fn.DataTable.isDataTable('.table-dates_ticket')) {
            $('.table-dates_ticket').DataTable().destroy();
        }
         initDataTable('.table-dates_ticket', '<?php echo base_url(); ?>' + 'gestao_corporativa/Company/table_dates_ticket', [0], [0], Params, [1, 'desc']);
    }
    

     function save() {
        var data_ = document.getElementById("date").value;
        //alert(data_); exit;

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('gestao_corporativa/Company/add_date_ticket'); ?>",
            data: {
                date: data_
            },
            success: function (data) {
                var obj = JSON.parse(data);
                alert_float(obj.alert, obj.message);
                reload();
            }
        });
    }
</script>

<?php init_tail(); ?>

