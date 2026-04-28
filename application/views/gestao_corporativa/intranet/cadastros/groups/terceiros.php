<h4 class="customer-profile-group-heading"><?php echo 'Empresas Tercerizadas'; ?></h4>
<?php if (has_permission_intranet('terceiros', '', 'create')) { ?>
<div class="_buttons">
    <a  class="btn btn-info pull-left display-block" onclick="add_terceiro();">Adicionar Empresa </a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<?php }?>
<div class="mtop15">
    <?php
    
    $table_data = [];

    $table_data = array_merge($table_data, array(
        'Nome',
        'Ações'
    ));

   

    render_datatable($table_data, 'terceiros');
    ?>

</div>
<div id="modal_wrapper"></div>


<?php init_tail(); ?>
<script>

    $(function () {
        var CustomersServerParams = {};
        initDataTable('.table-terceiros', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/Intranet/table_terceiros', [0], [0]);
    });
    
    function add_terceiro(id) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Company/modal", {
            slug: 'add_terceiro',
            id: id
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#add_terceiro').is(':hidden')) {
                $('#add_terceiro').modal({
                    show: true
                });
            }

        });
    }

    function view_terceiros(id) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Company/modal", {
            slug: 'view_terceiros',
            id: id
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#view_terceiros').is(':hidden')) {
                $('#view_terceiros').modal({
                    show: true
                });
            }

        });
    }
    
</script>

