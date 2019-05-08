
<div style="width: 700px;" class="modal-dialog">
        <div class="modal-content">
        <div class="portlet portlet-default">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Atualiza Tabelas e Campos do BD para o SIG'); ?></h4>
        </div>
            
            <div class="portlet-body">
                <div class="table-responsive">
                    
                       <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("owner/atualizaTabelas/" . $acao->idplanos, $attrib); 
                            echo form_hidden('importar', 'sim');
                        ?>

                    <?php 
                     foreach ($tabelas_bd as $table_bd) {
                         
                         
                         $verifica_tabela = $this->owner_model->getTableByTabela($table_bd->Tables_in_sig_plus);
                         $tabela_no_sig = $verifica_tabela->tabela;
                         $tabela_id_sig = $verifica_tabela->id;
                         
                        
                          if(!$tabela_no_sig){
                             echo $table_bd->Tables_in_sig_plus.'<br>';
                         }
                         
                       $verifica_campos = $this->owner_model->getAllColumns_BD($table_bd->Tables_in_sig_plus);
                       foreach ($verifica_campos as $campo) {
                           $campo_nome = $campo->Field;
                           $campo_tipo = $campo->Type;
                           
                            
                               
                           $verifica_campos = $this->owner_model->getCampoByCampo($campo_nome, $tabela_id_sig);
                           $campo_no_sig = $verifica_campos->campo;
                           
                           
                            if(!$campo_no_sig){
                             echo $table_bd->Tables_in_sig_plus.' > '.$campo_nome.'<br>';
                         }
                           
                       }   
                         
                         
                     }
                     
                    ?>
                        <div class="modal-footer">
                              <?php echo form_submit('add_customer', lang('Atualizar'), 'class="btn btn-primary"'); ?>
                        </div>
   
                    <?php echo form_close(); ?>

                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.portlet-body -->
        </div>
      </div>
</div>    
