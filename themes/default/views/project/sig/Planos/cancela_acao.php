
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"><p class="introtext">MUDAR STATUS DA AÇÃO</p>

            <h4 class="modal-title">
                <?php echo 'Ação : '.$id; ?>
            </h4>
        </div>
        <div class="modal-body">
            <div class="row">
            <div class="col-lg-12">

                
                
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("Planos/mudarStatuaAcao", $attrib);
                echo form_hidden('id_acao', $id);
                ?>
               
                    
                     
                    <div class="col-md-12">
                            <div class="form-group">
                                <?= lang("Status", "slprojeto"); ?>
                                <select class="form-control" name="status" required="true" >
                                    <option value="">SELECIONE O STATUS QUE DESEJA</option>
                                    <option value="CONCLUÍDO">CONCLUÍDO</option>
                                    <option value="CANCELADO">CANCELADO</option>
                                </select>
                            </div>
                        </div>
                    
                    <?php //$date_inicio = "01/02/2018 09:00:00";
                            //$date_fim = "30/12/2019 17:00:00";?>
                    
                    
                  
                    <div class="col-md-12">    
                    

                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Justificativa", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" required="true" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>
                       


                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_projeto', lang("submit"), 'id="add_projeto" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <a  class="btn btn-danger"   href="<?= site_url('Projetos/Item_evento_index/'.$id); ?>"> <div ><?= lang('Sair ') ?></div>  </a></div>
                        </div>
                    </div>
                
                

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
        
    </div>
</div>

