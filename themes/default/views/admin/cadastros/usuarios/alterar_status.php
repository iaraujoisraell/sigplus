  
<div  class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
           <?php 
                $status = $dados->active;
               
                if($status == 0){
                    $status_ata = 'Ativar';
                    $label = "success";
                }else 
                if($status == 1){
                    $status_ata = 'Inativar';
                    $label = "danger";
                } ?>
            <h3 class="modal-title" id="myModalLabel"><?php echo 'Gostaria de '; ?> <label class="label label-<?php echo $label; ?>"> <?php echo $status_ata; ?> </label> <?php echo ' o usuário : '. lang($dados->first_name).' ?'; ?></h3>
            
            
            
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("admin/alterarStatusUsuario", $attrib); 
            echo form_hidden('id_usuario', $dados->id); 
            echo form_hidden('status_atual', $status); 
            echo form_hidden('alterar_status', 1); 
        ?>
        
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang($status_ata), 'class="btn btn-'.$label.'"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
</div>

