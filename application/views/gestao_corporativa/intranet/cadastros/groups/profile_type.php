
<h4 class="customer-profile-group-heading"><?php echo 'Tipos de Usuários'; ?></h4>
<?php if (has_permission_intranet('tipos_usuarios', '', 'create')) { ?>
<div class="_buttons">
    <a href="#" class="btn btn-info pull-left display-block" onclick="add_type();">Add Tipo</a>
</div>
<div class="clearfix"></div>
<hr class="hr-panel-heading" />
<?php }?>
<div class="mtop15">


    
    <?php
    $table_data = [];

    $table_data = array_merge($table_data, array(
        'Tipo de Usuário',
        'Ações'
    ));
    render_datatable($table_data, 'profile_types');
    ?>

</div>
<div id="modal_wrapper"></div>


<?php init_tail(); ?>
<script>

    $(function () {
        var CustomersServerParams = {};
        initDataTable('.table-profile_types', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/Intranet/table_profile_type', [0], [0]);
    });
    
    function add_type(el) {
        $("#modal_wrapper").load("<?php echo base_url(); ?>gestao_corporativa/Profile_type/modal", {
            slug: 'add_type',
            id: el
        }, function () {
            if ($('.modal-backdrop.fade').hasClass('in')) {
                $('.modal-backdrop.fade').remove();
            }
            if ($('#add_type').is(':hidden')) {
                $('#add_type').modal({
                    show: true
                });
            }
        });
    }
    
</script>
