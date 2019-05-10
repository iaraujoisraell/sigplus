<?php $data_hoje = date('Y-m-d H:i:s'); 

?>
<script>
  localStorage.setItem('date', '<?= $this->sma->hrld($data_hoje) ?>');
  
    $("#date").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'sma',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
</script>
    
<div class="modal-dialog">
     <?php           
                 foreach ($acoes as $acao) {
                                                       
                                                    
                                              
            ?>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
           
            <?php echo lang('AÇÃO: '); ?> <?php echo $acao->descricao; ?>
            <font style="font-size: 12px"><p><?php echo $acao->observacao; ?></p></font>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("welcome/retorno/" . $acao->idplanos, $attrib); ?>
        <div class="modal-body">
            <p>Prazo para Entrega : <?php echo $this->sma->hrld($acao->data_termino); ?></p>

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
                                <?= lang("Informe o Por quê da sua ação *", "slporque"); ?>
                                <?php echo form_input('porque', (isset($_POST['porque']) ? $_POST['porque'] : $acao->porque), 'maxlength="200" style="text-transform: uppercase;" disabled class="form-control input-tip" required="required" id="slporque"'); ?>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("Informe onde aconteceu a ação *", "slonde"); ?>
                                <?php echo form_input('onde', (isset($_POST['onde']) ? $_POST['onde'] : $acao->onde), 'maxlength="200" style="text-transform: uppercase;" disabled class="form-control input-tip" required="required" id="slonde"'); ?>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("Informe como foi seita a sua ação *", "slcomo"); ?>
                                <?php echo form_input('como', (isset($_POST['como']) ? $_POST['como'] : $acao->como), 'maxlength="200" style="text-transform: uppercase;" disabled class="form-control input-tip" required="required" id="slcomo"'); ?>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("Informe o Macro Processo referente a ação", "slmacroprocesso"); ?>
                                <?php echo form_input('macroprocesso', (isset($_POST['macroprocesso']) ? $_POST['macroprocesso'] : $acao->macroprocesso), 'maxlength="200" disabled style="text-transform: uppercase;" class="form-control input-tip" id="slmacroprocesso"'); ?>
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

                        </div>
                        <div class="clearfix"></div>
                        
                      <center>
                            <div class="col-md-12">
                            <div
                                class="fprom-group center">
                                <a  class="btn btn-danger" href="<?= site_url('welcome/acoesConcluidas'); ?>"><?= lang('Fechar') ?></a>
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
      }
    ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>

