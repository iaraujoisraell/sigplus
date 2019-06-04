
  
<div  class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Cadastro de Categoria'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("project/novaCategoriaPlano", $attrib); 
            echo form_hidden('id_cadastro', '1'); 
            echo form_hidden('plano_id', $plano_id); 
            
            $date_hoje = date('Y-m-d');   
            
        ?>
        <div class="modal-body">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group company">
                         <?= lang("Categoria *", "titulo"); ?>
                              <?php echo form_input('assunto', "", 'class="form-control input" maxlength="250" id="assunto" required '); ?>
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

