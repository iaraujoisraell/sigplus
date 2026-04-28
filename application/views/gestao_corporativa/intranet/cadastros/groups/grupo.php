
<h4 class="customer-profile-group-heading"><?php echo 'Grupos'; ?></h4>

<div class="mtop15">
    
                    <?php if (has_permission_intranet('grupos', '', 'create') || is_admin()) {?>
                        <div class="_buttons">
                            <a href="<?php echo base_url('gestao_corporativa/intra/Grupos/index'); ?>" class="btn btn-info pull-left" ><?php echo 'Add Grupo'; ?></a>

                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                    <?php } ?>


                    <?php
                    $table_data = [];

                    $table_data = array_merge($table_data, array(
                        'Nome',
                        'Cadastro',
                        'Qtd de integrantes',
                        'Ações'
                    ));
                    render_datatable($table_data, 'grupos');
                    ?>
               
</div>


<?php init_tail(); ?>
<script>

    $(function () {
        var CustomersServerParams = {};
        initDataTable('.table-grupos', '<?php echo base_url();?>' + 'gestao_corporativa/intra/intranet/table_grupo', [0], [0], CustomersServerParams, [1, 'desc']);
    });

    

</script>
