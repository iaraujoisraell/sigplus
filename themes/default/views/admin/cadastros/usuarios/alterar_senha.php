  
<div  class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
           
            <h3 class="modal-title" id="myModalLabel"><?php echo 'Alterar Senha de : '. lang($dados->first_name); ?></h3>
            
            <p><small>A senha deve conter no mínimo: 8 Caracteres, 1 Maiúscula, 1 Minúscula</small></p>
            
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("admin/alterarSenhaUsuario", $attrib); 
           
            echo form_hidden('id_usuario', $dados->id); 
        ?>
        <div class="modal-body">
           
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group company">
                         <?= lang("Nova Senha", "nova_Senha"); ?>
                        <input type="password" name="nova_Senha" id="nova_Senha" class="form-control input" title="Informar a nova senha" maxlength="15" required="true">
                    </div>
                    
                    <div class="form-group company">
                         <?= lang("Confirmar Senha", "confirmar_senha"); ?>
                        <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control input" title="Confirmar a nova senha" maxlength="15" required="true">
                      
                    </div>
                    
                    </div>
                
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Confirmar'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
</div>

