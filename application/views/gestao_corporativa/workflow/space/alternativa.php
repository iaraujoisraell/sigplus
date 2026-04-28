<?php
if(!is_array($setores)){
   $this->load->model('Departments_model');
$setores = $this->Departments_model->get(); 
}

?>
<div class="panel_s" id="fluxo_<?php echo $fluxo['id']; ?>">
    <div class="panel-heading">
        <?php echo $fluxo['codigo_sequencial']; ?> - <?php echo get_departamento_nome($fluxo['setor']); ?> 
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <?php echo render_input('alternativa_' . $fluxo['id'], 'Alternativa', $fluxo['alternativa'], 'text', array('required' => 'true', 'disabled' => 'true')); ?>
            </div>
            <div class="col-md-6">
                <?php echo render_select('setor_' . $fluxo['id'], $setores, array('departmentid', 'name'), 'Setor', $fluxo['setor'], array('required' => 'true', 'disabled' => 'true')); ?>


            </div>
            
            <div class="col-md-12" style="text-align: right;">

                <a id="pencil<?php echo $fluxo['id']; ?>" class="btn btn-info btn-xs mleft5" data-toggle="tooltip" onclick="edit_fluxo_change('<?php echo $fluxo['id']; ?>');"><i class="fa fa-pencil"></i></a>

                <a id="save<?php echo $fluxo['id']; ?>" class="btn btn-success btn-xs mleft5 hide" data-toggle="tooltip" onclick="edit_fluxo_seguinte('<?php echo $fluxo['id']; ?>');"><i class="fa fa-check"></i></a>

                <?php
                $this->load->model('Workflow_model');
                $seguintes = $this->Workflow_model->get_fluxos_seguintes($fluxo['id']);
                if (count($seguintes) == 0) {
                    ?>
                    <a class="btn btn-danger btn-xs mleft5 _delete" data-toggle="tooltip" onclick="delete_fluxo_seguinte('<?php echo $fluxo['id']; ?>');"><i class="fa fa-trash"></i></a>
                <?php } else { ?>   
                    <a class="btn btn-danger btn-xs mleft5 _delete tooltip" disabled><i class="fa fa-trash"></i><span class="tooltiptext">Este fluxo tem um fluxo seguinte.</span></a>
                <?php } ?>
            </div>
        </div>
    </div>

</div>
<script>

    $(document).ready(function () {
        init_selectpicker();
    });

</script>