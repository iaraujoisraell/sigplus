<h4 class="customer-profile-group-heading"><?php echo 'Publicações'; ?></h4>
<div class="mtop15">
    
                    <?php hooks()->do_action('before_items_page_content'); ?>
                    <?php if (has_permission_intranet('publicacoes', '', 'create') || is_admin()) { ?>
                        <div class="_buttons">
                            <a href="<?php echo base_url('gestao_corporativa/intra/Pubs/novo'); ?>" class="btn btn-info pull-left" ><?php echo 'Add Publicação'; ?></a>    

                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                    <?php } ?>


                    <?php
                    $table_data = [];

                    $table_data = array_merge($table_data, array(
                        'Título',
                        'Destino',
                        'Cadastro',
                        'Expiração',
                        'Imagem',
                        'Tipo',
                        'Ações'
                    ));
                    render_datatable($table_data, 'publicacoes');
                    ?>
               
</div>


<?php init_tail(); ?>
<script>
    $(function () {
        var CustomersServerParams = {};
        initDataTable('.table-publicacoes','<?php echo base_url();?>' + 'gestao_corporativa/intra/intranet/table_publicacao', [0], [0], CustomersServerParams, [1, 'desc']);

    });
</script>