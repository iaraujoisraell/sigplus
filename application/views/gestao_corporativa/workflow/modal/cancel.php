<?php
if ($slug == 'cancel') {
    $title = 'CANCELAR WORKFLOW';
    $class = 'danger';
    $label = 'Cancelar';
} elseif ($slug == 'finish') {
    $title = 'FINALIZAR WORKFLOW';
    $class = 'success';
    $label = 'Finalizar';
}
?>
<div class="modal fade" id="cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <?php echo form_open("gestao_corporativa/Workflow/cancel/" . $id . '?fluxo_andamento_id=' . $fluxo_andamento_id.'&slug='.$slug, array("id" => "workflow-form")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">
                        <?php
                        echo $title;
                        ?>
                    </span>
                </h4>
            </div>

            <div class="modal-body">
                <?php
                if($slug == 'cancel'){
                $this->load->model('Company_model');

                $cancels = $this->Company_model->get_cancel_workflow();

                echo render_select("cancel_id", $cancels, array('id', 'cancellation'), 'Motivo', '', array('required' => 'true'));
                }

                echo render_textarea('obs', 'Observações');
                ?>
            </div>
            <div class="modal-footer" id="">

                <button type="submit" class="btn btn-xs btn-<?php echo $class;?>"> <?php echo $label;?></button>

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