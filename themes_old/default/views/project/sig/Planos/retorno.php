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
            
            $(document).ready(function() {
    $('.btn-theme').click(function(){
        $('#aguarde, #blanket').css('display','block');
    });
});
</script>
<style>
    #blanket,#aguarde {
    position: fixed;
    display: none;
}

#blanket {
    left: 0;
    top: 0;
    background-color: #f0f0f0;
    filter: alpha(opacity =         65);
    height: 100%;
    width: 100%;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=65)";
    opacity: 0.65;
    z-index: 9998;
}

#aguarde {
    width: auto;
    height: 30px;
    top: 40%;
    left: 45%;
    background: url('http://i.imgur.com/SpJvla7.gif') no-repeat 0 50%; // o gif que desejar, eu geralmente uso um 20x20
    line-height: 30px;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    z-index: 9999;
    padding-left: 27px;
}
</style> 
    
<div class="modal-dialog">
     <?php           
         
              $acao =  $this->atas_model->getPlanoByID($idplano);                    
                                            
            ?>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
           
            <?php echo lang('AÇÃO : '); ?> <?php echo $idplano.' - '. $acao->descricao; ?>
            <font style="font-size: 12px"><p><?php echo $acao->observacao; ?></p></font>
        </div>
       

        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("planos/retorno/" . $acao->idplanos, $attrib);
        echo form_hidden('prazo_atual', $acao->data_termino);
        ?>
        <div class="modal-body">
            <p>Prazo : <?php echo $this->sma->hrld($acao->data_termino); ?> | Entregue em : <?php echo $this->sma->hrld($acao->data_retorno_usuario); ?></p>

            <div class="row">
                        
                 <div id="blanket"></div>
                <div id="aguarde">Aguarde...</div>

                <input type="hidden" value="<?php echo $acao->idplanos; ?>" name="id"/>
            </div>
            <div class="clearfix"></div>
            <div id="payments">
                
                <div class="well well-sm well_1">
                    <div class="col-md-12">
                       
                        <div class="col-sm-12">
                                    <div class="form-group">
                                        <?= lang("Responsável", "slResponsavel2"); ?>
                                     
                                        <?php
                                        //$wu4[''] = '';
                                        foreach ($users as $user) {
                                            $wu4[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                        }
                                       $id_usu_setor =  $this->atas_model->getUserSetorByUsuarioAndSetor($acao->responsavel, $acao->setor);
                                      //  echo form_dropdown('responsavel[]', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "  style="width:100%;" multiple  required="required"');
                                        echo form_dropdown('responsavel', $wu4, (isset($_POST['responsavel']) ? $_POST['responsavel'] : $id_usu_setor->id), 'id="slResponsavel2" required="required"  class="form-control  select" data-placeholder="' . lang("Selecione o(s) Responsavel(eis)") . ' "   style="width:100%;"   ');
                              
                                        ?>
                                    </div>
                                </div>
                        
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <?= lang("Prazo da ação", "start_date"); ?>
                                    <?php echo form_input('prazo_novo', (isset($_POST['start_date']) ? $_POST['start_date'] : $this->sma->hrld($acao->data_termino)), 'class="form-control datetime" id="start_date"'); ?>
                                </div>
                             </div>
                        
                            <div class="col-sm-12">
                            <div class="form-group">
                                <?= lang("Mudar o Status para :", "slretorno"); ?>
                                <?php 
                                  $pst['PENDENTE'] = lang('PENDENTE');  
                                  $pst['CONCLUÍDO'] = lang('CONCLUÍDO');
                                  $pst['CANCELADO'] = lang('CANCELADO');
                                    
                                  echo form_dropdown('status', $pst, (isset($_POST['status']) ? $_POST['status'] : $acao->status), 'id="slretorno" class="form-control "  data-placeholder="' . lang("select") . ' ' . lang("o STATUS") . '" required="required"   style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
                           <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("Observação (para o Usuário)", "slobservacao"); ?>
                                      <?php echo form_textarea('observacao', (isset($_POST['observacao']) ? $_POST['observacao'] : ""), 'class="form-control"   style="height: 120px;" id="slobservacao" equired="required" '); ?>

                            
                            </div>
                            </div>
                        <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("Justificativa", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= lang("Anexar Documento", "document") ?>
                                <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>

                        </div>
                    <div  class="col-md-12">
                                    <ul style=" height: 250px; overflow:scroll ;  " class="plan  plan1">
                                    <li class="plan-name">
                                        <h3>Histórico</h3>
                                        <br>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                     <?php

                                                            $observacoes =  $this->atas_model->getAllHistoricoAcoes($acao->idplanos);

                                                             foreach ($observacoes as $observacao) {

                                                                 if($observacao->observacao){
                                                            ?>
                                                              <p style="text-align: justify">
                                                                  <font style="font-size: 12px; font-weight: bold"><?php echo  $observacao->username; ?>   : </font>
                                                                <font style="font-size: 12px;"><?php echo  $observacao->observacao; ?> </font> <font style="font-size: 12px; font-style: italic">( <?php echo $this->sma->hrld( $observacao->data_envio); ?> )</font></p>
                                                              <?php if($observacao->anexo != null){  ?> 
                                                              <font style="font-size: 12px;"><a href="<?= base_url() ?>assets/uploads/historico_acoes/<?php echo $observacao->anexo; ?>" target="_blank"><i class="fa fa-chain"></i>Ver Anexo</a></font><?php } ?>
                                                         <?php }
                                                             }
                                                             ?>   
                                
                                               
                                               

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                </div>
                        <div class="clearfix"></div>
                        
                      <center>
                            <div class="col-md-12">
                            <div
                                class="fprom-group center">
                                 <button type="submit" style="padding: 6px 15px; margin:15px 0;" class="btn btn-primary btn-theme" name="cdi" onclick="javascript:document.getElementById('blanket').style.display = 'block';document.getElementById('aguarde').style.display = 'block';">Enviar</button>
                             
                                <a  class="btn btn-danger" href="<?= site_url('planos/planosAguardandoValidacao'); ?>"><?= lang('Cancelar') ?></a>
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

