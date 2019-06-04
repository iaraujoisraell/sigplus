
  
  
<?php 
    
    $usuario =  $this->session->userdata('user_id'); 
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
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Novo Plano de Ação '); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("project/novoPlano", $attrib); 
            echo form_hidden('id_cadastro', '1'); 

            $date_hoje = date('Y-m-d');   
            
        ?>
        <div class="modal-body">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group company">
                         <?= lang("Título do Plano *", "titulo"); ?>
                              <?php echo form_input('assunto', "", 'class="form-control input" maxlength="250" id="assunto" required '); ?>
                    </div>
                     <div class="form-group company">
                         <?= lang("Responsável Elaboração *", "responsavel"); ?>
                              <?php echo form_input('responsavel', "", 'class="form-control input" maxlength="250" id="responsavel" required '); ?>
                    </div>
                    <div class="form-group company">
                         <?= lang("Responsável Aprovação*", "responsavel_aprovacao"); ?>
                              <?php echo form_input('responsavel_aprovacao', "", 'class="form-control input" maxlength="250" id="responsavel_aprovacao" required '); ?>
                    </div>
                    <div class="form-group company">
                         <?= lang("Início *", "periodo"); ?>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="date" name="periodo_de" value="<?php echo $date_hoje; ?>" class="form-control">
                        
                        </div>
                    </div>
                    <div class="form-group company">
                         <?= lang("Término *", "periodo"); ?>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="date" name="periodo_ate" value="<?php echo $date_hoje; ?>" class="form-control">
                        
                        </div>
                    </div>
                   <div  class="form-group">
                        <?= lang("Observações Gerais", "objetivos"); ?><small>(Objetivos, Detalhes do Plano)</small>
                        <?php echo form_textarea('objetivos', (isset($_POST['objetivos']) ? $_POST['objetivos'] : ''), 'class="form-control"   style="height: 120px;" id="objetivos"  '); ?>
                  </div>
                    
                    
                    
                    
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
