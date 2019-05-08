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
               
                    <?php // $this->load->view($this->theme . 'usuarios/menus'); ?>
                
                <br><br>
               <!-- DIV TABLE AÇÕES PENDENTES -->  
                
                    <div class="row">
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                     
                                    
                                     <h3>Registrar R.A.T. (Regitro de Apontamento de Trabalho)</h3>
                                   
                                   
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                               
                                <div class="box">
                                  
                                    <div class="box-content">
                                        <div class="row">
                                            <div class="col-lg-12">

                                          
                                               
                                                <div class="row">
                                                    <?PHP
                                                     foreach ($dados_equipe as $equipe) {
                                                        $id_membro = $equipe->id;
                                                    ?>
                                                    <div class="col-lg-12">
                                                        
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <?= lang("Projeto :", "slProjeto"); ?>
                                                                      <?php
                                                                    

                                                                     echo $equipe->projeto;

                                                                ?>

                                                               
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <?= lang("Equipe : ", "ata"); ?>
                                                                <?php
                                                                   echo $equipe->equipe;
                                                                ?>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <?= lang("Papel/Função :", "facilitador"); ?>
                                                                                <?php
                                                                                
                                                                                     echo $equipe->papel;
                                                                                ?>
                                                                          </div>
                                                                    </div>
                                                     
                                                       
                                                            
                                                    
                                                        </div>   
                                                    <?php
                                                    }
                                                    ?>
                                                    
                                                     <?php
                                                                function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
                                                                          if (strlen($var) > $limite)	{
                                                                                $var = substr($var, 0, $limite);		
                                                                                $var = trim($var) . "...";	

                                                                          }return $var;

                                                                          }
                                                                      ?>
                                                    <div class="col-md-12">
                                                       
                                                            </div>
                                                    
                                                   
                                                    <hr><hr>
                                                     
                                                    
                                                    
                                                   
                                                        <?php
                                                            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                                            echo form_open_multipart("welcome/abrir_rat", $attrib);
                                                            echo form_hidden('id_membro', $id);
                                                          //  echo form_hidden('id_ata_facilitador', $id_ata_facilitador);
                                                        ?>
                                                     
                                                        <div class="col-lg-12">
                                                            
                                                            <div class="col-md-12"  >
                                                                <h4>1 - Selecione a Fase/ Evento do Escopo do projeto que se refere a RAT</h4>
                                                                <div class="form-group">
                                                                     
                                                                <?php
                                                                    
                                                                
                                                                        //$wu4[''] = 'Selecione a Fase/Evento que se refere a RAT';
                                                                            
                                                                          $cont_evento = 1;
                                                                            $intes_eventos = $this->projetos_model->getAllFasesAndItemEventosProjeto($projeto,'tipo','asc');
                                                                            
                                                                            foreach ($intes_eventos as $item) {
                                                                                
                                                                                
                                                                                    $wu4[$item->id] = $cont_evento++.' - '. $item->fase.' / '. $item->evento.' - '. resume($item->item, 100);
                                     
                                                                             
                                                                            }
                                                                     
                                                                                 echo form_dropdown('eventos_item[]', $wu4, (isset($_POST['eventos_item']) ? $_POST['eventos_item'] : ""), 'id="slResponsavel"  class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%; height: 200px;" required="true" multiple ');
                                                                 
                                                                
                                                                    ?>
                                                            </div>
                                                            </div>
                                                        </div>
                                                
                                                
                                                        <div class="col-lg-12">
                                                             <div class="col-lg-4">
                                                                <h4>2 - Data</h4>
                                                                <input type="date" name="data_registro" required="true" class="form-control" placeholder="Data RAT">
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <h4>3 - H. Início</h4>
                                                                <input type="time" name="hora_inicio" required="true" class="form-control" placeholder="Hora Início">
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <h4>4 - H. Término</h4>
                                                                <input type="time" name="hora_termino" required="true" class="form-control" placeholder="Hora Término">
                                                            </div>
                                                            
                                                            <div class="col-lg-12">
                                                                <h4>5 - Descrição da Atividade</h4>
                                                                <textarea  rows="10" name="conteudo" required="true" class="form-control" placeholder="Descreva a Atividade">
                                                                </textarea>
                                                            </div>
                                                        </div>
                                                        <center>
                                                            <div class="col-lg-12" style="margin-top:15px;">
                                                          <?php echo form_submit('add_treinamento', lang("Salvar RAT"), 'id="add_treinamento" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                                                 <a  class="btn btn-danger" href="<?= site_url('welcome/abrir_rat/'.$id); ?>"><?= lang('Voltar') ?></a>
                                                         </div>  
                                                        </center>
                                                      <?php echo form_close(); ?>
                                                   
                                            
                                                     
                                                    
                                                     
                                                    






                                           

                                        </div>
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
