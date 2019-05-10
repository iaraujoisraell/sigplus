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
                 <?php  $this->load->view($this->theme . 'usuarios/menus'); ?>
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
                                            echo form_open_multipart("welcome/retornoForm/", $attrib); ?>
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
                                                                            <option value="RETORNO DA AÇÃO"> RETORNO DA AÇÃO</option>
                                                                            <option value="PRORROGAÇÃO DO PRAZO">PRORROGAÇÃO DO PRAZO</option>
                                                                            <option value="TROCA DE RESPONSÁVEL">TROCA DE RESPONSÁVEL</option>
                                                                          
                                                                        </select>
                                                                        </div>
                                                                </div>
                                                               <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                    <?= lang("Mensagem de Retorno (para o EDP)", "slobservacao"); ?>
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
                                                                                 <a  class="btn btn-danger" href="<?= site_url('welcome'); ?>"><?= lang('Cancelar') ?></a>
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
<script>
 
    
    function alertas(){
             Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
        theme: 'flat'
    }
    Messenger().post({
        message: 'Estamos avisando o Gestor do projeto sobre o seu retorno!',
        type: 'error',
        showCloseButton: true
    });
    
    
           Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
        theme: 'flat'
    }

    Messenger().post({
        message: 'AGUARDE!Estamos enviando sua mensagem. ',
        type: 'info',
        showCloseButton: true
    }); 
    
    document.getElementById('blanket').style.display = 'block';
    document.getElementById('aguarde').style.display = 'block';
   
    
    }
    
    
    
    
    
    





$("#success-message-demo").click(function() {

    Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
        theme: 'flat'
    }

    Messenger().post({
        message: '3 - This is an example of a success notification. You can close this message using the box to the right.',
        type: 'success',
        showCloseButton: true
    });

});

$("#bottom-right-message-demo").click(function() {

    Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
        theme: 'flat'
    }

    Messenger().post({
        message: 'This is an example of a bottom right notification!',
        id: "Only-one-message",
        type: 'success',
        showCloseButton: true
    });

});

$("#bottom-left-message-demo").click(function() {

    Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-left',
        theme: 'flat'
    }

    Messenger().post({
        message: 'This is an example of a bottom left notification!',
        id: "Only-one-message",
        type: 'success',
        showCloseButton: true
    });

});

$("#top-right-message-demo").click(function() {

    Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-top messenger-on-right',
        theme: 'flat'
    }

    Messenger().post({
        message: 'This is an example of a top right notification!',
        id: "Only-one-message",
        type: 'success',
        showCloseButton: true
    });

});

$("#top-left-message-demo").click(function() {

    Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-top messenger-on-left',
        theme: 'flat'
    }

    Messenger().post({
        message: 'This is an example of a top left notification!',
        id: "Only-one-message",
        type: 'success',
        showCloseButton: true
    });

});

$("#cancel-message-demo").click(function() {

    Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
        theme: 'flat'
    }

    msg = Messenger().post({
        message: 'You can cancel this message and it will disappear completely.',
        id: "Only-one-message",
        type: 'success',
        showCloseButton: true,
        actions: {
            cancel: {
                action: function() {
                    msg.hide()
                }
            }
        }
    });

});

$("#interactive-message-demo").click(function() {

    Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
        theme: 'flat'
    }

    msg = Messenger().post({
        message: 'This message in interactive! Cancel this message to see what happens.',
        id: "Only-one-message",
        type: 'error',
        showCloseButton: true,
        actions: {
            cancel: {
                action: function() {
                    msg.update({
                        message: 'Message cancelled!',
                        type: 'success',
                        actions: false
                    })
                }
            }
        }
    });

});

$("#clear-message-demo").click(function() {

    Messenger().hideAll();

});
 </script>  
    <!-- GLOBAL SCRIPTS -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap/bootstrap.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/jquery.popupoverlay.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/defaults.js"></script>
    <!-- Logout Notification Box -->
    <div id="logout">
        <div class="logout-message">
            <img class="img-circle img-logout" src="img/profile-pic.jpg" alt="">
            <h3>
                <i class="fa fa-sign-out text-green"></i> Ready to go?
            </h3>
            <p>Select "Logout" below if you are ready<br> to end your current session.</p>
            <ul class="list-inline">
                <li>
                    <a href="login.html" class="btn btn-green">
                        <strong>Logout</strong>
                    </a>
                </li>
                <li>
                    <button class="logout_close btn btn-green">Cancel</button>
                </li>
            </ul>
        </div>
    </div>
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
