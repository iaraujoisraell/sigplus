  
<div  class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
           <?php 
                $responsavel = $acao->responsavel;
                $dados = $this->site->getUser($responsavel);
               
                ?>
            <h3 class="modal-title" id="myModalLabel"><?php echo 'Gostaria de enviar a ação para o usuário : '. lang($dados->first_name).' ?'; ?></h3>
            
            
            <br>
            <h3>Ação  <br><br><?php echo  $acao->descricao;  ?></h3>
            
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("admin/enviarAcaoAtaresponsavel", $attrib); 
         
            echo form_hidden('id_acao', $acao->idplanos); 
            echo form_hidden('envia_acao', 1); 
        ?>
        
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('Enviar'), 'class="btn btn-success"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
</div>

