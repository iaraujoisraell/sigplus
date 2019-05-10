
<div  class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Editar Cadastro'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("owner/editarModulo", $attrib); 
         echo form_hidden('id_modulo', $modulos->id);
        ?>
        <div class="modal-body">
           <?php
           $id = $modulos->id;
           $descricao = $modulos->descricao;
           $icon = $modulos->icon;
           ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group company">
                        <?= lang("Descrição", "descricao"); ?>
                        <?php echo form_input('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $descricao), 'maxlength="200" class="form-control input-tip" required="required" id="descricao"'); ?>
                    </div>
                    <div class="form-group company">
                        <?= lang("Fa icon", "fantazia"); ?>
                        <?php echo form_input('icon', $icon, 'class="form-control tip" maxlength="150" id="icon" '); ?>
                    </div>
                    
                    
                </div>
                
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Salvar'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
          </div>  
