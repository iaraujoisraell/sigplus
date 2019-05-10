
<div  class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("owner/deletarModuloForm", $attrib); 
         echo form_hidden('id_modulo', $modulos->id);
        ?>
        <div class="modal-body">
           <?php
           $id = $modulos->id;
           $descricao = $modulos->descricao;
           $icon = $modulos->icon;
           ?>

            <h4 class="modal-title" id="myModalLabel"><?php echo 'Deseja deletar o Módulo '. $modulos->descricao.' ?'; ?></h4>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Deletar'), 'class="btn btn-danger"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
          </div>  
