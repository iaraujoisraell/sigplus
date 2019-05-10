 
  <?php 
    $usuario =  $this->session->userdata('user_id'); 
    $data_cadastro = date('Y-m-d'); 
    $date_cadastro = date('Y-m-d H:i:s'); 
    
    function exibirData($data){
	$rData = explode("-", $data);
	$rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
	return $rData;
   }
?>
  
<div  class="modal-dialog">
            <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Novo Compromisso '); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("welcome/novoCompromisso", $attrib); 
            echo form_hidden('id_cadastro', '1'); 
            
            
        ?>
        <div class="modal-body">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group company">
                         <?= lang("Descrição", "evento"); ?>
                          <?php echo form_input('descricao', "", 'class="form-control input" title="Descrição do compromisso" maxlength="250" id="evento"  required="true" '); ?>
                    </div>
                </div>   
                <div class="col-md-6">
                    <div class="form-group company">
                         <?= lang("Tipo", "evento"); ?>
                          <?php echo form_input('tipo', "", 'class="form-control input" placeholder="Ex: Reunião, Viagem, Consultoria, etc." title="Ex: Tipo do Compromisso"  maxlength="250" id="evento"  required="true" '); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group company">
                         <?= lang("Data", "periodo"); ?>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            
                            <input type="date" value="<?php echo $data_cadastro ?>" required="true"  name="data_de" class="form-control pull-right" id="reservation">
                        </div>
                    </div>
                 </div>   
                <div class="col-md-6">
                    <div class="form-group company">
                         <?= lang("local", "periodo"); ?>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            
                            <input type="text"    name="local" class="form-control pull-right" required="true" id="reservation">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group company">
                         <?= lang("Hora Início", "periodo"); ?>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            
                            <input type="time"  name="hora_inicio" required="true" class="form-control pull-right" id="reservation">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group company">
                         <?= lang("Hora Término", "periodo"); ?>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            
                            <input type="time"  name="hora_termino" required="true" class="form-control pull-right" id="reservation">
                        </div>
                    </div>
                </div>    
                    
                
            </div>
           <div class="modal-footer">
                <?php echo form_submit('add_customer', lang('Salvar'), 'class="btn btn-primary"'); ?>
                </div>      
        </div>


        </div>
        
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
          </div>
