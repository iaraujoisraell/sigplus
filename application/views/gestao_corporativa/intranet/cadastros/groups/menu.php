
<h4 class="customer-profile-group-heading"><?php echo 'Menus / Paginas'; ?></h4>
<?php //print_r($menus); //exit;?>
<div class="mtop15">
    <?php if (has_permission_intranet('menus_paginas', '', 'create') || is_admin()) { ?>
        <div class="_buttons">
            <a href="<?php echo base_url('gestao_corporativa/intra/Menus/index'); ?>" class="btn btn-info mtop15 mbot10"><?php echo 'ADD MENU + PAGINA' ?></a>
        </div>
        <div class="clearfix"></div>
        <hr class="hr-panel-heading" />
    <?php } ?>
    <?php foreach ($menus as $menu): ?>
        <div class="modal fade" id="add_menu<?php echo $menu['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">
                            <span class="edit-title text-white"><?php echo 'Submenus'; ?></span>
                        </h4>
                    </div>






                    <div class="modal-body">
                        <?php if (has_permission_intranet('menus_paginas', '', 'create')) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="btn btn-info mtop15 mbot10" href="<?php echo base_url('gestao_corporativa/intra/Menus/index/' . $menu['id']); ?>"><?php echo 'ADD Submenu' ?></a>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="container-fluid">

                                <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
                                    <thead>
                                        <tr>
                                            <th><?php echo 'Ordem'; ?></th>
                                            <th><?php echo 'Nome'; ?></th>
                                            <th><?php echo 'Visualizar'; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($menu['submenus'] as $submenu) { ?>
                                            <tr class="row-has-options" data-group-row-id="<?php echo $submenu['id']; ?>">
                                                <td data-order="<?php echo $submenu['ordem']; ?>"><?php
                                                    echo $submenu['ordem'];
                                                    ?></td>
                                                <td data-order="<?php echo $submenu['ordem']; ?>">
                                                    <span class="group_name_plain_text" ><?php echo $submenu['nome_menu']; ?></span>

                                                    <div class="row-options">
                                                        <?php if (has_permission_intranet('menus_paginas', '', 'edit')) { ?>
                                                            <a href="<?php echo base_url('gestao_corporativa/intra/Menus/edit_menu' . '?id=' . $submenu['id']); ?>" class="edit-item-group">
                                                                <?php echo _l('edit'); ?>
                                                            </a>
                                                        <?php } ?> | 
                                                        <?php if (has_permission_intranet('menus_paginas', '', 'delete')) { ?>
                                                            <a href="<?php echo base_url('gestao_corporativa/intra/Menus/delete_menu' . '?id=' . $submenu['id']); ?>" class="edit-item-group text-danger">
                                                                <?php echo _l('delete'); ?>
                                                            </a>
                                                        <?php } ?>

                                                    </div>

                                                </td>
                                                <td data-order="<?php echo $submenu['id']; ?>">
                                                    <a class="btn btn-info btn-icon" href="<?php echo $submenu['urk'] ?>" target="_blank"><i class="<?php echo $submenu['icon'] ?>"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-dark close"  data-dismiss="modal"> <?php echo _l('close'); ?></button>

                    </div>

                </div>
            </div>
        </div>
       
    <?php endforeach; ?>

    <?php
    $table_data = [];
    $table_data = array_merge($table_data, array(
        'Ordem',
        'Nome',
        'Url',
        'Cadastro',
        'Submenus',
        'Opções'
    ));
    render_datatable($table_data, 'menus');
    ?>

</div>


<?php init_tail(); ?>
<script>

    $(function () {
        var CustomersServerParams = {};
        var tAPI = initDataTable('.table-menus', '<?php echo base_url(); ?>' + 'gestao_corporativa/intra/intranet/table_menu', [0], [0], CustomersServerParams, [1, 'desc']);
    });
</script> <script>

            function addClass(id, classe) {
                var elemento = document.getElementById(id);
                var classes = elemento.className.split(' ');
                var getIndex = classes.indexOf(classe);

                if (getIndex === -1) {
                    classes.push(classe);
                    elemento.className = classes.join(' ');
                }
            }

            function delClass(id, classe) {
                var elemento = document.getElementById(id);
                var classes = elemento.className.split(' ');
                var getIndex = classes.indexOf(classe);

                if (getIndex > -1) {
                    classes.splice(getIndex, 1);
                }
                elemento.className = classes.join(' ');
            }

        </script>


