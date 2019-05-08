
<div  class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Editar Módulos de Acesso da Empresa'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("owner/editarEmpresaModulo", $attrib); 
         echo form_hidden('id_cadastro', '1');
         echo form_hidden('empresa_id', $id);
        ?>
        <div class="modal-body">
           <?php
           
           $modulos = $this->owner_model->getAllModulos();
           ?>

            <div class="row">
                <div class="col-md-12">
                    
                    <?php
                        $wu4[''] = '';
                        $cont = 1;
                        foreach ($modulos as $modulo) {
                            $modulos_empresa = $this->owner_model->getModuloEmpresaById($id,$modulo->id );
                            
                            if($modulos_empresa){
                                $check = "checked";
                            }else{
                                $check = "";
                            }
                        ?>      
                    
                    <div class="form-group company">
                        <input type="checkbox" <?php echo $check; ?> name="modulo<?php echo $modulo->id; ?>" value="<?php echo $modulo->id; ?>" >  <?php echo $modulo->descricao; ?> <i class="fa fa-<?php echo $modulo->icon; ?>"></i>
                     </div>
                   <?php
                        }
                   ?>
                    
                    
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
