  
<div  class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
           <?php 
                if($tipo == 1){ // concluir
                    $status_ata = 'Concluir';
                    $label = "success";
                }else 
                if($tipo == 2){ // pendente
                    $status_ata = 'Deixar Pendente';
                    $label = "warning";
                }else 
                if($tipo == 3){// cancelar
                    $status_ata = 'Cancelar';
                    $label = "danger";
                } ?>
            <h3 class="modal-title" id="myModalLabel"><?php echo 'Gostaria de '; ?> <label class="label label-<?php echo $label; ?>"> <?php echo $status_ata; ?> </label> <?php echo ' esta ação ?'; ?></h3>
            
            
            
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("project/mudar_status_acao_retorno", $attrib); 
            echo form_hidden('idplano', $idplano); 
            echo form_hidden('status_atual', $status); 
            echo form_hidden('alterar_status', 1); 
            echo form_hidden('tipo', $tipo);
        ?>
        
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang($status_ata), 'class="btn btn-'.$label.'"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
</div>

