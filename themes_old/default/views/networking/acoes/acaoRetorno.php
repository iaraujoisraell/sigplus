<div  class="modal-dialog">
    <div class="modal-content">
         <?php           
                    foreach ($acoes as $acao) {
                ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo 'Retorno da Ação : '.$acao->idplanos.' | '.$acao->descricao; ?></h4>
        </div>
         <div class="modal-body">
        
            

                <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("welcome/retorno_new/", $attrib); ?>
                <input type="hidden" value="<?php echo $acao->idplanos; ?>" name="idplano"/>
                <input type="hidden" value="<?php echo $acao->sequencial; ?>" name="sequencial"/>
                <input type="hidden" value="1" name="cadastrar"/>
             
                   
                    <div id="payments">

                        <div  class=" well-sm well_1">
                            <div class="col-md-12">
                                <div class="col-sm-12">
                                        <div class="form-group">
                                            <?= lang("Tipo de Retorno", "slobservacao"); ?>
                                            <select name="tipo"  class="form-control" >
                                                <option value="CONCLUSÃO DA AÇÃO"> CONCLUSÃO DA AÇÃO</option>
                                                <option value="PRORROGAÇÃO DO PRAZO">PRORROGAÇÃO DO PRAZO</option>
                                                <option value="TROCA DE RESPONSÁVEL">TROCA DE RESPONSÁVEL</option>
                                                
                                            </select>
                                            </div>
                                    </div>
                                <div class="col-sm-12">
                                        <div class="form-group">
                                        <?= lang("Mensagem", "slobservacao"); ?>
                                              <?php echo form_textarea('observacao', (isset($_POST['observacao']) ? $_POST['observacao'] : ""), 'class="form-control"   style="height: 120px;" id="slobservacao" required="required" '); ?>

                                    </div>
                                    </div>
                                    
                                </div>

                              <center>
                                    <div class="col-md-12">

                                    <div class="fprom-group center">
                                         <?php echo form_submit('add_customer', lang('Enviar'), 'class="btn btn-primary"'); ?>
                                      <a  class="btn btn-danger " data-dismiss="modal"><?= lang('Sair') ?></a>
                                    </div>
                                </div>
                                    </center>

                            </div>
                            <div class="clearfix"></div>
                        </div>

             
                <?php echo form_close();  ?>
                           
                    
        </div>
         <?php
              }
            ?>
  </div>
</div>      