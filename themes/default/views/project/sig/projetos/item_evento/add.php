
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"><p class="introtext">NOVO ITEM DO EVENTO</p>

            <h4 class="modal-title">
                <?php
                       echo 'Período de '.$this->sma->hrld($projetos->data_inicio).'  até '.$this->sma->hrld($projetos->data_fim);
                        ?>
            </h4>
        </div>
        <div class="modal-body">
            <div class="row">
            <div class="col-lg-12">

                
                
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("projetos/add_item_evento_form", $attrib);
                echo form_hidden('evento', $id);
                ?>
               
                    
                     
                    <div class="col-md-12">
                            <div class="form-group">
                                <?= lang("Descrição", "slprojeto"); ?>
                                <?php echo form_input('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $slnumber), 'maxlength="500" class="form-control input-tip" required="required" id="slprojeto"'); ?>
                            </div>
                        </div>
                    
                    <?php //$date_inicio = "01/02/2018 09:00:00";
                            //$date_fim = "30/12/2019 17:00:00";?>
                    <div class="col-lg-12">
                        <div class="col-sm-4">
                                <div class="form-group">
                                 <?= lang("Data Início", "start_date"); ?>
                                    <input type="date" name="dateInicial" class="form-control">
                                 </div>
                        </div>
                        <div class="col-sm-4">
                               <div class="form-group">
                                 <?= lang("Data Término", "dateEntregaDemanda"); ?>
                                   <input type="date" name="dateFim" id='dateFim' class="form-control">
                                  </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("Horas Previstas", "sltipo"); ?>
                                <input type="number" name="horas" id='horas' class="form-control">
                            </div>
                        </div>
                    </div>
                    
                  
                    <div class="col-md-12">    
                    

                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Observação", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

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

