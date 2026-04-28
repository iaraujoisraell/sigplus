
<div class="modal fade" id="categorias_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo 'Categorias'; ?>
                </h4>
            </div>
            <div class="modal-body">

                <?php if (has_permission_intranet('lisks_externos', '', 'crud_categoria')) { ?>
                <?php echo form_open("gestao_corporativa/intra/Links/add_categoria", array("id" => "ci-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>
                <div class="input-group">
                    <input type="text" name="titulo" id="categoria_name" class="form-control" placeholder="<?php echo 'Nome'; ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-info p7" type="submit" id="new-categoria-insert"><?php echo 'Add Categoria'; ?></button>
                    </span>
                </div>

                <?php echo form_close(); ?>
                <?php }?>

                <br>
                <div class="row">
                    <div class="container-fluid">

                        <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
                            <thead>
                                <tr>
                                    <th><?php echo 'Sequência'; ?></th>
                                    <th><?php echo 'Nome da Categoria'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categorias_link as $group) { ?>
                                    <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                                        <td data-order="<?php echo $group['id']; ?>"><?php
                                            echo $group['id'];
                                            ?></td>
                                        <td data-order="<?php echo $group['titulo']; ?>">
                                            <span class="group_name_plain_text" id="sair<?php echo $group['id']; ?>"><?php echo $group['titulo']; ?></span>
                                            <div class="group_edit hide" id="entrar<?php echo $group['id']; ?>">
                                                <?php echo form_open("gestao_corporativa/intra/Links/edit_categoria", array("id" => " cate-form", "enctype" => "multipart/form-data", "class" => "general-form", "role" => "form")); ?>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="titulo" value="<?php echo $group['titulo']; ?>">
                                                    <input type="hidden" class="form-control" name="id" value="<?php echo $group['id']; ?>">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-info p8 update-item-group" type="submit"><?php echo _l('submit'); ?></button>
                                                    </span>
                                                </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                            
                                            <div class="row-options">
                                                <a id="edit<?php echo $group['id']; ?>" onClick="addClass('sair<?php echo $group['id']; ?>', 'hide'); delClass('entrar<?php echo $group['id']; ?>', 'hide'); addClass('edit<?php echo $group['id']; ?>', 'hide');" class="edit-item-group">
                                                    <?php echo _l('edit'); ?>
                                                </a> |
                                                <a href="<?php echo base_url('gestao_corporativa/intra/Links/delete_categoria' . '?id=' . $group['id']); ?>" class="edit-item-group text-danger">
                                                    <?php echo _l('delete'); ?>
                                                </a>

                                            </div>
                                            
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div>
    </div>
</div>