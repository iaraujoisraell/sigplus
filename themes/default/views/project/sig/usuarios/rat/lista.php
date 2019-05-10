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
               
                    <?php  //$this->load->view($this->theme . 'usuarios/menus'); ?>
                
                <br><br>
               <!-- DIV TABLE AÇÕES PENDENTES -->  
                
                    <div class="row">
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Selecione o projeto para lançar a RAT</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>        
                             <?php
                                 $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                                 echo form_open_multipart("welcome/abrir_rat_pdf", $attrib);
                              ?>
                            <div class="col-lg-12">
                                         <div class="col-lg-2">
                                            <h4> Data Início</h4>
                                            <input type="date" name="data_inicio" required="true" class="form-control" placeholder="Data RAT">
                                        </div>
                                        <div class="col-lg-2">
                                            <h4>Data Fim</h4>
                                               <input type="date" name="data_fim" required="true" class="form-control" placeholder="Data RAT">
                                      </div>
                                        <div class="col-lg-2">
                                            <h4>Pesquisar</h4>
                                           
                                              <div class="col-lg-12" style="margin-top:0px;">
                                                <?php echo form_submit('add_treinamento', lang("BUSCAR RAT"), 'id="add_treinamento" class="btn btn-primary" "'); ?>
                                              </div>  
                                            
                                        </div>


                                    </div>
                            
                                                      <?php echo form_close(); ?>
                            
                            <br><br><br><br>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover" style=" width: 100%; ">
                                        <thead>
                                            <tr>
                                                <th style="color: #ffffff; width: 5%;">ID</th>
                                                <th style="color: #ffffff; width: 20%;">PROJETO</th>
                                                <th style="color: #ffffff; width: 15%;">EQUIPE</th>
                                                <th style="color: #ffffff; width: 10%;">PAPEL/FUNÇÃO</th>
                                                <th style="color: #ffffff; width: 30%;">DESCRIÇÃO DO PAPEL</th>
                                                <th style="color: #ffffff; width: 10%;">HORAS REGISTRADAS</th>
                                                <th style="color: #ffffff; width: 10%;">OPÇÃO</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                                $wu4[''] = '';
                                               $cont = 1;
                                             
                                                foreach ($equipes as $equipe) {
                                                   $id_membro = $equipe->id;
                                                   
                                                 
                                                ?>   
                                           
                                                <tr >
                                                <td style="width: 5%;"><?php echo $cont++; ?></td>
                                                <td style="width: 20%;"><?php echo $equipe->projeto; ?></td> 
                                                <td style="width: 15%;"><?php echo $equipe->equipe; ?></td>
                                                <td style="width: 10%;"><?php echo $equipe->papel;  ?></td>
                                                <td style="width: 30%;"><p style="text-align: justify"><?php echo $equipe->descricao;  ?></p></td>
                                                <td style="width: 10%;"></td>
                                                <td style="width: 10%;" class="center">
                                                   
                                                        <a href="<?= site_url('welcome/abrir_rat/'.$id_membro); ?>"  class="btn btn-block btn-social btn-lg btn-bitbucket">
                                                            <i class="fa fa-pencil fa-fw fa-3x"></i>
                                                          RAT's
                                                        </a>
                                                   
                                                </td>
                                               
                                                
                                               
                                              
                                                </tr>
                                                <?php
                                                
                                                }
                                                ?>
                                            
                                            
                                            
                                        </tbody>
                                    </table>
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
