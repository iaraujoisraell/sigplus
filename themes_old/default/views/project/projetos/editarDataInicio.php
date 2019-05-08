
<div  class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Alteração do Projeto | Alterar Data Inicial do Projeto'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("project/editarCadastro", $attrib); 
            echo form_hidden('id_cadastro', $cadastro_id); 
            echo form_hidden('tabela_id', $tabela_id); 
            echo form_hidden('tabela_nome', $tabela_nome); 
            echo form_hidden('menu_id', $menu_id); 
            echo form_hidden('funcao', $funcao); 
            echo form_hidden('cadastrosHabilitados', $cadastrosHabilitados);
        ?>
        <div class="modal-body">
           
            
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?= lang("Data Início *", "data_inicio"); ?> 
                           
                            <input name="data_inicio"  required="true" value="<?php echo $projeto->dt_inicio; ?>" class="form-control" type="date" >
                        </div>
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
