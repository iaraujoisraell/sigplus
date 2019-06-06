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
           
            <?php echo lang('AÇÃO : '); ?> <?php echo $acao->sequencial.' - '. $acao->descricao; ?>
            <font style="font-size: 12px"><p><?php echo $acao->observacao; ?></p></font>
        </div>
       

        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("project/retorno_acao/" . $acao->idplanos, $attrib);
        echo form_hidden('prazo_atual', $acao->data_termino);
        echo form_hidden('validar', 1);
        echo form_hidden('sequencial', $acao->sequencial);
        ?>
        <div class="modal-body">
            <p>Início: <?php echo date('d/m/Y', strtotime($acao->data_entrega_demanda)); ?> |  Prazo Término : <?php echo date('d/m/Y', strtotime($acao->data_termino)); ?> | Entregue em : <?php echo date('d/m/Y', strtotime($acao->data_retorno_usuario)); ?></p>

            <div class="row">
                        
                 <div id="blanket"></div>
                <div id="aguarde">Aguarde...</div>

                <input type="hidden" value="<?php echo $acao->idplanos; ?>" name="idplano"/>
            </div>
            <div class="clearfix"></div>
            <div id="payments">
                
                <div class="well well-sm well_1">
                    <div class="col-md-12">
                           <div class="col-sm-12">
                            <div class="form-group">
                                <?= lang("Status da ação :", "slretorno"); ?>
                                <?php 
                                  $pst['PENDENTE'] = lang('PENDENTE');  
                                  $pst['CONCLUÍDO'] = lang('CONCLUÍDO');
                                  echo form_dropdown('status', $pst, (isset($_POST['status']) ? $_POST['status'] : $acao->status), 'id="slretorno" class="form-control "  data-placeholder="' . lang("select") . ' ' . lang("o STATUS") . '" required="required"   style="width:100%;" '); ?>
                                           
                            </div>
                        </div>
                           <div class="col-sm-12">
                                <div class="form-group">
                                <?= lang("Observação (para o Responsável pela ação)", "slobservacao"); ?>
                                      <?php echo form_textarea('observacao', (isset($_POST['observacao']) ? $_POST['observacao'] : ""), 'class="form-control"   style="height: 120px;" id="slobservacao" equired="required" '); ?>

                            
                            </div>
                            </div>
                        
                        

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
            <?php echo form_close(); ?>
          
           

        </div>
        
    </div>    