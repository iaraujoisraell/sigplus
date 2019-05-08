<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html lang="en">
 <?php  $this->load->view($this->theme . 'usuarios/header'); ?>

<script type="text/javascript">
$(document).ready(function() {
  $('#botao').on("click",  funcTeste);
 
  $( "#process" ).dialog({
    autoOpen: false,
    width: 400,
    resizable: false,
    draggable: false,
    close: function(){
        // executa uma ação ao fechar
        //alert("você fechou a janela");
    }
  });
 
   
});		
</script>
<script>
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
<body onload="document.getElementById('carregando').style.display='none'" >

    <div id="wrapper">

       
      
        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">
                <div id="carregando"> Carregando aguarde... </div>              
                <br><br>
                 <?php //  $this->load->view($this->theme . 'usuarios/menus'); ?>
                <br><br>
                
               <!-- DIV TABLE AÇÕES PENDENTES -->  
                <div id="acoes_pendentes">
                    <div class="row">
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Retorno da Ação</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                       
                                 
                                         <?php           
                                                     foreach ($acoes as $acao) {



                                                ?>
                                        
                                            <div class="modal-header">


                                                <h3>  <?php echo lang('AÇÃO: '); ?> <?php echo $acao->idplanos.' - '. $acao->descricao; ?></h3>
                                                <font style="font-size: 12px;"><p><?php echo $acao->observacao; ?></p></font>
                                            </div>
                                            <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                            echo form_open_multipart("welcome/retornoFormNew/", $attrib); ?>
                                            <div class="modal-body">
                                                <p>Prazo para Entrega : <?php if($acao->data_termino != '0000-00-00 00:00:00'){ echo $this->sma->hrld($acao->data_termino); }else{ echo 'NÃO DEFINIDO';} ?></p>

                                                <div class="row">

                                                    <div id="blanket"></div>
                                                    <div id="aguarde">Aguarde...Enviando Email</div>

                                                    <input type="hidden" value="<?php echo $acao->idplanos; ?>" name="id"/>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div id="payments">

                                                    <div  class="well well-sm well_1">
                                                        <div class="col-md-12">
                                                            <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        <?= lang("Tipo de Retorno", "slobservacao"); ?>
                                                                        <select name="tipo"  class="form-control" >
                                                                            
                                                                            <option value="RETORNO DA AÇÃO"> CONCLUSÃO DA AÇÃO</option>
                                                                            <option value="PRORROGAÇÃO DO PRAZO">PRORROGAÇÃO DO PRAZO</option>
                                                                            <option value="TROCA DE RESPONSÁVEL">TROCA DE RESPONSÁVEL</option>
                                                                            <option value="ENVIAR MENSAGEM"> ENVIAR MENSAGEM</option>
                                                                        </select>
                                                                        </div>
                                                                </div>
                                                               <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                    <?= lang("Mensagem (para o EDP)", "slobservacao"); ?>
                                                                          <?php echo form_textarea('observacao', (isset($_POST['observacao']) ? $_POST['observacao'] : ""), 'class="form-control"   style="height: 120px;" id="slobservacao" equired="required" '); ?>


                                                                </div>
                                                                </div>
                                                                <?php 
                                                                 ?>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <?= lang("Anexar Documento", "document") ?>
                                                                    <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                                                           data-show-preview="false" class="form-control file">
                                                                </div>
                                                            </div>
                                                            <?php  ?>
                                                            </div>
                                                        <?php //historico ?>   
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
                                                                                                  <p style="text-align: justify"><font style="font-size: 12px; font-weight: bold">

                                                                                                      <?php echo  $observacao->username; ?>   : </font>
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
                                                                    
                                                                    <button type="submit" style="padding: 6px 15px; margin:15px 0;" onclick="alertas();" class="btn btn-primary btn-theme" name="cdi" >Enviar</button>
                                                                                 <a  class="btn btn-danger" href="<?= site_url('welcome/controleAtividades'); ?>"><?= lang('Cancelar') ?></a>
                                                                </div>
                                                            </div>
                                                                </center>

                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>

                                                </div>




                                            
                                            <?php echo form_close(); 
                                          }
                                        ?>
   
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.portlet-body -->
                        </div>
                        <!-- /.portlet -->

                    </div>
                    <!-- /.col-lg-12 -->

                </div>
                </div>
                <!-- /.FIM AÇÕES PENDENTES -->
                
               
            </div>
            <!-- /.page-content -->

        </div>
        <!-- /#page-wrapper -->
        <!-- end MAIN PAGE CONTENT -->

    </div>
    <!-- /#wrapper -->

    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->
    
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?= $assets ?>dashboard/js/plugins/hisrc/hisrc.js"></script>

    <script src="<?= $assets ?>dashboard/js/plugins/datatables/jquery.dataTables.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/datatables/datatables-bs3.js"></script>
    
    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/spin.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/ladda.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap-multiselect/bootstrap-multiselect.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/flex.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/buttons-demo.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/advanced-tables-demo.js"></script>

    <script src="<?= $assets ?>dashboard/js/demo/calendar-demo.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/fullcalendar.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/notifications-demo.js"></script>
         
         

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/plugins/messenger/messenger.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/messenger/messenger-theme-flat.js"></script>

  
</body>

</html>
