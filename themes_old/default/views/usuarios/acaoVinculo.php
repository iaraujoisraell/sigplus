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

<body>

    <div id="wrapper">

       
      
        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">
                              
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
                                    <h4>Detalhes da Ação</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                   <?php           
         
        $acao =  $this->atas_model->getPlanoByID($idplano);                    
        $usuario = $this->session->userdata('user_id');   
        $users = $this->site->geUserByID($acao->responsavel);                              
            ?>

                <div class="row">
                   
                     
            
                       <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("Planos/manutencao_acao_av/" . $acao->idplanos, $attrib); 
                            echo form_hidden('id', $acao->idplanos);
                        ?>
                    <div style="text-align: right">
                                 <div  class="col-md-12">
                                     <a  style="width:50px; height: 50px; " class="btn btn-danger" href="<?= site_url('welcome'); ?>"> <div style="margin-top: 8px;"></div> <i class="fa fa-close"></i>  </a>  
                                </div>
                            </div> 
                    <br>
                    <div style="margin-left: 10px; margin-right: 10px;" class="row pricing-circle">
                            <br>
                            
                            <div class="col-md-12">
                                <div class="col-md-2">
                                    <ul style="background-color: darkgoldenrod" class="plan plan1 ">
                                        <li  class="plan-name">
                                            <h3 style="color: #FFFFFF;">   AÇÃO : <?PHP ECHO $idplano; ?> </h3>

                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-2">

                                    <ul <?php if(($acao->status == 'PENDENTE')||($acao->status == 'AGUARDANDO VALIDAÇÃO')){ ?> style="background-color: darkgoldenrod" <?php }else{ ?>  style="background-color: green" <?php } ?> class="plan plan1 ">
                                        <li  class="plan-name">
                                            <h3 style="color: #FFFFFF;">    <?PHP echo $acao->status; ?> </h3>

                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <ul style="background-color: darkcyan" class="plan plan ">
                                        <li class="plan-name">
                                            <h3>  <?php echo $users->first_name .' '. $users->last_name ; ?>
                                               </h3>

                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="col-md-4">
                                    <ul class="plan plan2 featured ">
                                        <li class="plan-name">
                                            <h3>  <?php echo lang('Prazo : '); ?> <?php if($acao->data_termino != '0000-00-00 00:00:00'){ 
                                                echo $this->sma->hrld($acao->data_termino); }else{ 
                                                ?>
                                                <input style="color: #000000" disabled type="datetime-local" id="prazo" name="prazo">
                                                <?php } ?>
                                            </h3>
                                        </li>
                                    </ul>
                                </div>
                                
                            </div>
                            
                            
                            <div style="float: left" class="col-md-12">
                        
                            <?php //O QUE ?>    
                            <div class="col-md-6">
                                <ul class="plan plan2 featured ">
                                    <li style="height: 150px;"   class="plan-name">
                                        <h3>Descrição</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                <?php echo form_textarea('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->descricao), 'class="form-control" disabled  style="height: 120px;" id="sldescricao" equired="required" '); ?>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                           
                            <?php //CUSTO ?>           
                            <div class="col-md-3">
                                <ul  class="plan plan2 featured ">
                                    <li style="height: 150px;" class="plan-name">
                                        <h3>Custo</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                <?php echo form_textarea('custo', (isset($_POST['custo']) ? $_POST['custo'] : $acao->custo), 'class="form-control" disabled style="height: 120px;"  id="slcusto" equired="required" '); ?>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            
                                 <?php //ONDE ?>
                            <div class="col-md-3">
                                <ul class="plan plan1 ">
                                    <li style="height: 150px;" class="plan-name">
                                        <h3>Onde</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                <?php echo form_textarea('onde', (isset($_POST['onde']) ? $_POST['onde'] : $acao->onde), 'class="form-control" disabled style="height: 120px;" id="slonde" equired="required" '); ?>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                             
                            </div>
                            
                            <div style="float: left" class="col-md-12">
                                
                             <?php //PORQUE ?>         
                            <div style="float: left" class="col-md-3">
                                <ul  class="plan  plan1">
                                    <li style="height: 150px;" class="plan-name">
                                        <h3>Por que</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                               
                                                <?php echo form_textarea('porque', (isset($_POST['porque']) ? $_POST['porque'] : $acao->porque), 'class="form-control" disabled style="height: 120px;" id="slporque" equired="required" '); ?>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                         
                            <?php //COMO ?>           
                            <div style="float: left" class="col-md-4">
                                <ul class="plan  plan1">
                                    <li style="height: 150px;" class="plan-name">
                                        <h3>Como</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                <?php echo form_textarea('como', (isset($_POST['como']) ? $_POST['como'] : $acao->como), 'class="form-control" disabled style="height: 120px;" id="slcomo" equired="required" '); ?>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                
                            </div>
                                <?php //MACRO PROCESSO ?>   
                            <div  class="col-md-2">
                                <ul  class="plan plan1  ">
                                    <li style="height: 150px;"   class="plan-name">
                                        <h3>Macro Processo</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                           
                                            <?php
                                                $wu4[''] = '';
                                                foreach ($macro as $mp) {
                                                    $wu4[$mp->id] = $mp->item;
                                                }
                                                echo form_dropdown('macroprocesso', $wu4, (isset($_POST['macroprocesso']) ? $_POST['macroprocesso'] : $acao->macroprocesso),  'id="slResponsavel" disabled  class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                            ?>
                                        </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                          
                            <?php //historico ?>   
                            <div  class="col-md-3">
                                    <ul style="background-color: darkgray; height: 410px; overflow:scroll ;  " class="plan  plan1">
                                    <li class="plan-name">
                                        <h3>Histórico</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                     <?php

                                                            $observacoes =  $this->atas_model->getAllHistoricoAcoes($acao->idplanos);

                                                             foreach ($observacoes as $observacao) {

                                                                 if($observacao->observacao){
                                                            ?>
                                                              <p style="text-align: justify"><font style="font-size: 12px;">
                                                                 
                                                                  <?php echo  $observacao->username; ?>   : </font>
                                                                <font style="font-size: 12px;"><?php echo  $observacao->observacao; ?> </font> <font style="font-size: 12px;">( <?php echo $this->sma->hrld( $observacao->data_envio); ?> )</font></p>
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
                    
                            <?php //OBS ?>    
                            <div style="float: left; margin-top: -200px;" class="col-md-9">
                                <ul style="background-color: darkcyan" class="plan plan2 featured ">
                                    <li style="height: 150px;" class="plan-name">
                                        <h3>Observação</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                <?php echo form_textarea('observacao', (isset($_POST['observacao']) ? $_POST['observacao'] :  $acao->observacao), 'class="form-control" disabled id="slpauta" style="height: 120px;" equired="required" '); ?>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                             <center>
                                 <div  class="col-md-12">
                                 <a  style="width:150px; height: 50px; " class="btn btn-danger" href="<?= site_url('welcome'); ?>"> <div style="margin-top: 8px;"><?= lang('Fechar ') ?></div>  </a>  
                                </div>
                            </center>
                           
                            
                    </div>
                     <br>
                   
                       <?php echo form_close(); ?>
                    </div>
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
</body>

</html>
