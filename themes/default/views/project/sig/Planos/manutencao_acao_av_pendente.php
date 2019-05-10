<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Projetos - TI UnimedManaus</title>
    
   

     <link href="<?= $assets ?>dashboard/css/plugins/pace/pace.css" rel="stylesheet">
    <script src="<?= $assets ?>dashboard/js/plugins/pace/pace.js"></script>

    <!-- GLOBAL STYLES - Include these on every page. -->
    <link href="<?= $assets ?>dashboard/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel="stylesheet" type="text/css">
    <link href="<?= $assets ?>dashboard/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- PAGE LEVEL PLUGIN STYLES -->
       <link href="<?= $assets ?>dashboard/css/plugins/datatables/datatables.css" rel="stylesheet">

    <!-- THEME STYLES - Include these on every page. -->
    <link href="<?= $assets ?>dashboard/css/styles.css" rel="stylesheet">
    <link href="<?= $assets ?>dashboard/css/plugins.css" rel="stylesheet">

    <!-- THEME DEMO STYLES - Use these styles for reference if needed. Otherwise they can be deleted. -->
    <link href="<?= $assets ?>dashboard/css/demo.css" rel="stylesheet">

    
 
</head>

   

<body>

              
<?php           
         
        $acao =  $this->atas_model->getPlanoByID($idplano);                    
        $usuario = $this->session->userdata('user_id');   
        //$users = $this->site->geUserByID($acao->responsavel);                              
            ?>

                <div class="row">
                   
                     
            
                       <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                            echo form_open_multipart("Planos/manutencao_acao_pendente_form", $attrib); 
                            echo form_hidden('id', $acao->idplanos);
                            echo form_hidden('status', $acao->status);
                            echo form_hidden('prazo_atual', $acao->data_termino);
                        ?>
       
                    <div style="margin-left: 10px; margin-right: 10px;" class="row pricing-circle">
                            <br>
                            
                            <div class="col-md-12">
                                <div class="col-md-3">
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
                                    <ul class="plan plan2 featured">
                                        <li style="height: 65px;  margin-top: -30px; margin-left: 15px;" class="plan-name">
                                            
                                            <div class="col-md-12"  >
                                                <div class="form-group">
                                                    <?= lang("Responsável", "Responsavel"); ?>
                                                <?php
                                                  //  print_r($users);
                                                    $wu41[''] = '';
                                                    foreach ($users as $user) {
                                                        $wu41[$user->id] = $user->nome.' '.$user->last.' - '.$user->setor;
                                                    }
                                                    $id_usu_setor =  $this->atas_model->getUserSetorByUsuarioAndSetor($acao->responsavel, $acao->setor);
                                                    echo form_dropdown('responsavel', $wu41, (isset($_POST['responsavel']) ? $_POST['responsavel'] :  $id_usu_setor->id), 'id="Responsavel"  class="form-control  select" data-placeholder="' . lang("Selecione o Responsável") . ' "    required="required"');
                                                ?>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="col-sm-3">
                                    <ul   class="plan plan2 featured ">
                                        <li style="height: 65px; width: 250px; margin-top: -30px; margin-left: 15px;" class="plan-name">
                                            <div class="form-group">
                                                <?= lang("Prazo", "start_date"); ?>
                                                
                                                <?php echo form_input('prazo_novo', (isset($_POST['start_date']) ? $_POST['start_date'] : $this->sma->hrld($acao->data_termino)), 'class="form-control datetime" id="start_date" '); ?>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                
                            </div>
                            
                            
                            <div style="float: left" class="col-md-12">
                        
                            <?php //O QUE ?>    
                            <div class="col-md-6">
                                <ul class="plan plan2  ">
                                    <li style="height: 150px;"   class="plan-name">
                                        <h3>Descrição</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                <?php echo form_textarea('descricao', (isset($_POST['descricao']) ? $_POST['descricao'] : $acao->descricao.' <br> '.$acao->tipo.' '.$acao->processo.' '.$acao->item_roteiro), 'class="form-control"   style="height: 120px;" id="sldescricao" equired="required" '); ?>
                                                           
                                          
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                           
                            <?php //CUSTO ?>           
                            <div class="col-md-3">
                                <ul  class="plan plan2  ">
                                    <li style="height: 150px;" class="plan-name">
                                        <h3>Custo</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                <?php echo form_textarea('custo', (isset($_POST['custo']) ? $_POST['custo'] : $acao->custo), 'class="form-control" style="height: 120px;"  id="slcusto" equired="required" '); ?>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            
                                 <?php //ONDE ?>
                            <div class="col-md-3">
                                <ul class="plan plan2 featured ">
                                    <li style="height: 150px;" class="plan-name">
                                        <h3>Onde</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                <?php echo form_textarea('onde', (isset($_POST['onde']) ? $_POST['onde'] : $acao->onde), 'class="form-control" style="height: 120px;" id="slonde" disabled  '); ?>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                             
                            </div>
                            
                            <div style="float: left" class="col-md-12">
                                
                             <?php //PORQUE ?>         
                            <div style="float: left" class="col-md-3">
                                <ul  class="plan  plan2 featured">
                                    <li style="height: 150px;" class="plan-name">
                                        <h3>Por que</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                               
                                                <?php echo form_textarea('porque', (isset($_POST['porque']) ? $_POST['porque'] : $acao->porque), 'class="form-control" style="height: 120px;" id="slporque" disabled  '); ?>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                         
                            <?php //COMO ?>           
                            <div style="float: left" class="col-md-4">
                                <ul class="plan  plan2 featured">
                                    <li style="height: 150px;" class="plan-name">
                                        <h3>Como</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                <?php echo form_textarea('como', (isset($_POST['como']) ? $_POST['como'] : $acao->como), 'class="form-control" style="height: 120px;" id="slcomo" disabled'); ?>

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
                                                echo form_dropdown('macroprocesso', $wu4, (isset($_POST['macroprocesso']) ? $_POST['macroprocesso'] : $acao->macroprocesso), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                                            ?>
                                        </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                          <?php //historico ?>   
                            <div  class="col-md-3">
                                    <ul style="background-color: darkgray; height: 450px; overflow:scroll ;  " class="plan plan1">
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
                                  <style>
      p {
  color: #ffffff;;
}

    </style>
                            <?php //OBS ?>    
                             <div style="float: left; margin-top: -230px;  " class="col-md-9">
                                <ul  class="plan plan2  ">
                                    <li style="height: 250px;" >
                                        <h3>Observação</h3>
                                        <div class="col-md-12"  >
                                            <div class="form-group">
                                                
                                                    <div  style="overflow:scroll; height: 250px;" name='observacao'  >
                                                        <font style="font-size: 14px; text-align: justify;">   <?php echo $acao->observacao; ?></font>
                                                </div>
                                                
                                               

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                             <center>
                                 <div  class="col-md-12">
                            
                                <?php echo form_submit('add_item', lang("Atualizar"), 'id="add_item" class="btn btn-warning"   style="padding: 6px 15px; width:150px; height: 50px; margin:15px 0;"'); ?>
                                <a  style="width:150px; height: 50px; " class="btn btn-danger" href="<?= site_url('Planos/planosPendentes'); ?>"> <div style="margin-top: 8px;"><?= lang('Fechar ') ?></div>  </a>  
                                
                           
                        </div>
                            </center>
                            
                    </div>
                   
                       <?php echo form_close(); 
      
    ?>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->


   
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

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/spin.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/ladda/ladda.min.js"></script>
    <script src="<?= $assets ?>dashboard/js/plugins/bootstrap-multiselect/bootstrap-multiselect.js"></script>

    <!-- THEME SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/flex.js"></script>
    <script src="<?= $assets ?>dashboard/js/demo/buttons-demo.js"></script>

    <script src="<?= $assets ?>dashboard/js/demo/calendar-demo.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/fullcalendar.min.js"></script>

</body>

</html>
