
  
<?php 
    
    $usuario =  $this->session->userdata('user_id'); 
      function exibirData($data){
	$rData = explode("-", $data);
	$rData = $rData[2].'/'.$rData[1].'/'.$rData[0];
	return $rData;
   }
   
   $users_dados = $this->site->geUserByID($convite->user_origem);
   
?>
  
<div  class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo ' Convite para '.$convite->tipo; ?></h4>
            <h6><?php echo ' Enviado por :  <b>'.$users_dados->first_name.'</b>'; ?></h6>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
            echo form_open_multipart("project/editar_evento_projetos", $attrib); 
            echo form_hidden('convite_id', $convite->id); 
        ?>
        <div class="modal-body">
           
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group company">
                        <font style="font-size: 18px;">  <?php echo $convite->texto_detalhe; ?> </font>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
         <?php if($convite->confirmacao == 3){ ?>         
           <?php if($convite->obrigatorio == 1){ ?>        
            <a href="<?= site_url('welcome/responderConvite/'.$convite->id.'/1'); ?>" class="btn btn-success">Confirmar Presença</a>
            <a href="<?= site_url('welcome/responderConvite/'.$convite->id.'/0'); ?>" class="btn btn-danger">Não poderei comparecer</a>
           <?php }else if($convite->obrigatorio == 0){ ?>
             <a href="<?= site_url('welcome/responderConvite/'.$convite->id.'/1'); ?>" class="btn btn-success">Confirmar Presença</a>
            <a href="<?= site_url('welcome/responderConvite/'.$convite->id.'/2'); ?>" class="btn btn-primary">Estou ciente</a>
           <?php } ?> 
        <?php } ?>     
        </div>
    </div>
    <?php echo form_close(); ?>
            <!-- /.modal-content -->
</div>


