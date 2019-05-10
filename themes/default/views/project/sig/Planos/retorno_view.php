<?php $data_hoje = date('Y-m-d H:i:s'); 

?>

    
<div class="modal-dialog">
     <?php           
         
        $acao =  $this->atas_model->getPlanoByID($idplano);                    
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($acao->responsavel);                              
            ?>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
           <?php echo lang('Responsável: '); ?> <?php echo $users->first_name .' '. $users->last_name .' | SETOR : '.$users->company ; ?>
            <br> <?php echo ' | GESTOR DIRETO : '.$users->gestor .' | SUPERINTENDENTE : '.$users->award_points; ?>
          
            <br>
            
        </div>
        <div class="modal-header">
            <?php echo lang('AÇÃO: '); ?> <?php echo $acao->descricao; ?>
            <font style="font-size: 12px"><p>Obs: <?php echo $acao->observacao; ?></p></font>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("planos/retorno/" . $acao->idplanos, $attrib); ?>
        <div class="modal-body">
            <p>Prazo : <?php echo $this->sma->hrld($acao->data_termino); ?> | Entregue em : <?php echo $this->sma->hrld($acao->data_retorno_usuario); ?></p>

            <div class="row">
            
                

                <input type="hidden" value="<?php echo $acao->idplanos; ?>" name="id"/>
            </div>
            <div class="clearfix"></div>
            <div id="payments">
                
                <div class="well well-sm well_1">
                    
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("O Por quê da sua ação : ", "slporque"); ?>
                                <?php echo $acao->porque; ?>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("Onde aconteceu a ação : ", "slonde"); ?>
                                <?php echo $acao->onde; ?>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("Como foi seita a sua ação : ", "slcomo"); ?>
                                <?php echo $acao->como; ?>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("Macro Processo referente a ação : ", "slmacroprocesso"); ?>
                                <?php echo $acao->macroprocesso; ?>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("Consultoria : ", "slmacroprocesso"); ?>
                                <?php echo $acao->consultoria; ?> <?php echo '   '. $acao->acaoconsultoria; ?>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("Custo : ", "slmacroprocesso"); ?>
                                <?php echo $acao->custo; ?>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("Observações : ", "slobservacao"); ?>
                                <?php
                                
                                    $observacoes =  $this->atas_model->getAllHistoricoAcoes($acao->idplanos);
                                    
                                     foreach ($observacoes as $observacao) {
                                    
                                         if($observacao->observacao){
                                    ?>
                                    <p><?php echo  $observacao->observacao; ?> -
                                    <font style="font-size: 12px;"><?php echo  $observacao->username; ?>  ( <?php echo $this->sma->hrld( $observacao->data_envio); ?> )</font></p>
                                    
                                 <?php }
                                     }
                                     ?>   
                                </div>
                            </div>
                            <div class="col-sm-12">
                            <div class="form-group">
                                <?= lang("STATUS :", "slretorno"); ?>
                                <?php echo $acao->status; ?>
                                           
                            </div>
                        </div>
                           

                        </div>
                        <div class="clearfix"></div>
                        
                      <center>
                            <div class="col-md-12">
                            <div
                                class="fprom-group center">
                                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
                                     <?= lang('Fechar') ?>
            </button>
                               
                            </div>
                        </div>
                            </center>
                       
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

          
           

        </div>
        
    </div>
    <?php echo form_close(); 
    
    ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>

