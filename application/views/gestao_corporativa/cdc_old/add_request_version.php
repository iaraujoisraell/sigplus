<div class="modal fade" id="add_request_version" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">Solicitar Versionamento #CDC <?php echo $cdc->codigo;?></span>
                </h4>
            </div>

            <form onsubmit="return false;" id="cdcForm">
            <div class="modal-body">
                <?php echo render_input('departmentid_version', 'Setor Responsável', get_departamento_nome($categoria->responsavel), 'text', array('required' => 'true', 'readonly' => 'true')); ?>
                <?php echo render_textarea('description_version', 'Descrição da Solicitação', '', array(), array(), '', 'tinymce'); ?>

            </div>
            <div class="modal-footer">
                <button class="btn btn-success"   onclick="this.disabled=true; add_request_cdc('<?php echo $link_id;?>', '<?php echo $cdc->id;?>', '<?php echo $categoria->responsavel;?>', '<?php echo $cdc_;?>');"> Enviar Solicitação</button>

            </div>
                 </form>

        </div>
    </div>
</div>