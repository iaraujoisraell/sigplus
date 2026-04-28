
<div class="modal fade" id="view_backs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog" role="document">
        <?php echo form_open("gestao_corporativa/Workflow/cancel/" . $id.'?fluxo_andamento_id='. $fluxo_andamento_id, array("id" => "workflow-form")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title text-white">Processos Estornados</span>
                </h4>
            </div>

            <div class="modal-body">
            <?php foreach ($backs as $back) { ?>

<div class="bg-light-gray" style="margin-left: 20px; margin-top: 5px; margin-right: 20px; padding: 5px; border-radius: 6px">


    <span class="label label-default mright5 inline-block">PROCESSO ESTORNADO</span>
    <span class="label label-default mright5 inline-block">DE: <?php echo get_staff_full_name($back['user_created']) ?> </span>
    <span class="label label-default mright5 inline-block">DATA: <?php echo date('d/m/Y H:i:s', strtotime($back['date_created'])); ?></span>

    <hr style="margin-top: 0; margin-bottom: 1px;">
    <p style="padding-left: 4px; margin-bottom:0;"> <span class="bold">Motivo:</span> <?php echo $back['obs']; ?></p>
    <span class="bold" style="padding-left: 4px; margin-top:0;">Formulário estornado:</span>
    <div class="row" style="">
        <div class="col-md-12" style="">
            <?php
            $campos = [];
            $values_info['rel_type'] = 'workflow';
            $values_info['color'] = 'danger';
            $values_info['campos'] = $this->Categorias_campos_model->get_values($back['id'], 'workflow_back');
            $this->load->view('gestao_corporativa/categorias_campos/values_info3', $values_info);
            ?>
        </div>

    </div>
</div>
<?php } ?>
            </div>
         
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
