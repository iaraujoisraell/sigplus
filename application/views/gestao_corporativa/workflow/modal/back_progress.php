
<div class="modal fade" id="back" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <?php echo form_open("gestao_corporativa/Workflow/back/" . $id.'?fluxo_andamento_id='. $fluxo_andamento_id.'&fluxo_andamento_id_old='. $fluxo_andamento_id_old.'&id='. $fluxo_old->workflow_id, array("id" => "workflow-form")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">Estornar para <?php echo $fluxo_old->name;?></span>
                </h4>
            </div>

            <div class="modal-body">
                <?php
                
                
                echo render_textarea('obs', 'Motivo', '', array("placeholder" => 'Descreva o motivo de estorno para o processo anterior..'));
                ?>
            </div>
            <div class="modal-footer" id="">

                <button type="submit" class="btn bg-info" style="color: white;">Estornar</button>

            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        init_selectpicker();
    });
</script>