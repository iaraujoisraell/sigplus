
<div class="modal fade" id="internal_request" tabindex="-1" role="dialog">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="delete_external_request('<?php echo $request_id;?>');"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">NOVA SOLICITAÇÃO DE PARECER INTERNO</span>
                </h4>
            </div>
            <?php echo form_open_multipart("gestao_corporativa/Workflow/edit_internal_request/".$id , array("id" => "workflow-form")); ?>

            <div class="modal-body">
                <input name="id" type="hidden" value="<?php echo $request_id;?>">
                <?php echo render_select('departmentid_staffid', $staffs, array('staffid', 'firstname', 'lastname'), 'Selecione o Colaborador'); ?>
                <?php
                echo render_textarea('description', 'Descrição da Solicitação', '', array("placeholder" => 'Descreva o motivo da solicitação..'));
                
                $data['rel_type'] = 'internal_request_workflow';
                $data['tipo'] = $request;
                $this->load->view('gestao_corporativa/categorias_campos/Campos', $data);
                ?>
                
            </div>
            <div class="modal-footer" id="">

                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="delete_external_request('<?php echo $request_id;?>');">Cancelar</button>
                <button type="submit" class="btn btn-info">Solicitar</button>

            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>
<script>
    $(document).ready(function () {
        init_selectpicker();
    });
</script>