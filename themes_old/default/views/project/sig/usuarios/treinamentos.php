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
                                    <h4>Meus Treinamentos</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover" style="background-color:#205081; ">
                                        <thead>
                                            <tr>
                                                <th style="color: #ffffff;">ID</th>
                                                <th style="color: #ffffff;">PROJETO</th>
                                                <th style="color: #ffffff;">ATA</th>
                                                <th style="color: #ffffff;"><i class="fa fa-download"></i></th>
                                                <th style="color: #ffffff;">DESCRIÇÃO</th>
                                                <th style="color: #ffffff;">DT INÍCIO</th>
                                                <th style="color: #ffffff;">DT TÉRMINO</th>
                                                <th style="color: #ffffff;">STATUS</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                                $wu4[''] = '';
                                               $cont = 1;
                                                foreach ($treinamentos as $treinamento) {
                                                   $idata = $treinamento->ata;
                                                   $idusuario = $treinamento->usuario;
                                                   
                                                   $status_treinamento = $treinamento->status;
                                                   
                                                   $ata = $this->atas_model->getAtaByID($idata);
                                                   $status = $ata->status;
                                                   if($status_treinamento == 0){
                                                       $n_status = 'ABERTO';
                                                   }else if($status_treinamento == 1){
                                                       $n_status = 'FECHADO';
                                                   }
                                                   /*
                                                    * VERIFICA SE O USUÁRIO PODE VER A ATA
                                                    */
                                                    $ata_user = $this->atas_model->getAtaUserByAtaUser($idata, $usuario);
                                                    $result = $ata_user->id;
                                                    
                                                     $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($idata);
                                                ?>   
                                           
                                                <tr >
                                                <td><?php echo $cont++; ?></td>
                                                <td><?php echo $projetos_usuario->projetos; ?></td> 
                                                <td><?php echo $idata; ?></td>
                                                <td><?php if($result){ ?> <a title="Download ATA" href="<?= site_url('welcome/pdf/'.$idata); ?> "><i class="fa fa-file-pdf-o"></i></a> <?php }else{ ?> - <?php } ?></th> 
                                                <td><?php echo $ata->pauta; ?></td>
                                                <td><?php echo $this->sma->hrld($ata->data_ata);  ?></td>
                                                <td class="center"><?php echo $this->sma->hrld($ata->data_termino); ?></td>
                                                <td class="center">
                                                    <?php if($status_treinamento == 0){ ?>
                                                        <a href="<?= site_url('welcome/abrir_treinamento/'.$treinamento->id); ?>"  class="btn btn-block btn-social btn-lg btn-orange">
                                                            <i class="fa fa-circle-o fa-fw fa-3x"></i>
                                                          <?php echo $n_status; ?>
                                                        </a>
                                                    <?php }else{ ?>
                                                        <a href="<?= site_url('welcome/abrir_treinamento/'.$treinamento->id); ?>"  class="btn btn-block btn-social btn-lg btn-green">
                                                            <i class="fa fa-check fa-fw fa-3x"></i>
                                                          <?php echo $n_status; ?>
                                                        </a>
                                                    <?php } ?>
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
