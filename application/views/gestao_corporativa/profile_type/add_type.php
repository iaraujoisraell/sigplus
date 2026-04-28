<div class="modal fade" id="add_type" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">ADD TIPO DE USUÁRIO
                </h4>
            </div>
            <?php echo form_open(base_url('gestao_corporativa/profile_type/profile_type_add/' . $role->id)); ?>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12">
                        <?php $attrs = (isset($role) ? array() : array('autofocus' => true)); ?>
                        <?php $value = (isset($role) ? $role->name : ''); ?>
                        <?php echo render_input('name', 'role_add_edit_name', $value, 'text', $attrs); ?>

                        <div class="checkbox">
                            <input type="checkbox"
                                   class="capability"
                                   id="update_staff_permissions"
                                   name="update_staff_permissions"
                                   value="<?php echo true; ?>"
                                   checked >
                            <label for="update_staff_permissions">
                                Editar Permissões de Colaboradores Vinculados
                            </label>

                            <i class="fa fa-question-circle" data-toggle="tooltip" data-title="Ao marcar essa opção, as permissões de todos os colaboradores com este tipo de usuário serão atualizadas (respeitando as particularidades). ESSE PROCESSO PODE SER DEMORADO"></i>

                        </div>
                        <?php
                        $permissionsData = ['funcData' => ['role' => isset($role) ? $role : null]];
                        $this->load->view('gestao_corporativa/profile_type/permissions', $permissionsData);
                        ?>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>