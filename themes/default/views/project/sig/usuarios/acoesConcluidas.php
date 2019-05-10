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
                                    <h4>Minhas Ações Concluídas</h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>PROJETO</th>
                                                <th>ATA</th>
                                                <th>EVENTO</th>
                                                <th>DESCRIÇÃO</th>
                                                <th>DATA QUE EU RECEBI</th>
                                                <th>DATA DO PRAZO</th>
                                                <th>DATA DE CONCLUSÃO</th>
                                                <th>OPÇÕES</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                                $wu4[''] = '';
                                                //$cont = 1; href="<?= site_url('Login_Projetos/projeto_ata/'.$projeto->id); >"
                                                foreach ($planos as $plano) {
                                                    $evento =  $this->atas_model->getAllitemEventoByID($plano->eventos);   
                                                    $ata_user = $this->atas_model->getAtaUserByAtaUser($plano->idatas, $usuario);
                                                    $result = $ata_user->id;
                                                    
                                                    //
                                                    
                                                      $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($plano->idatas);
                                                ?>   
                                           
                                                <tr class="odd gradeX">
                                                <td><?php echo $plano->idplanos; ?></td>
                                                <td><?php echo $projetos_usuario->projetos; ?></td> 
                                                  <td><?php echo $plano->idatas; ?> <?php if($result){ ?> <a title="Download ATA" href="<?= site_url('welcome/pdf/'.$plano->idatas); ?> "><i class="fa fa-file-pdf-o"></i></a> <?php }else{ ?>  <?php } ?></td>     
                                                      
                                                <th><?php echo $evento->evento.'/'.$evento->item; ?></th>       
                                                <td><?php echo $plano->descricao; ?>
                                                <p><font  style="font-size: 10px; "><?php echo $plano->observacao; ?></font></p>
                                                </td>
                                                <td><?php if($this->sma->hrld($plano->data_elaboracao) != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_elaboracao); }else{ echo $this->sma->hrld($projetos_usuario->data_ata); } ?></td>
                                                <td class="center"><?php echo $this->sma->hrld($plano->data_termino); ?></td>
                                                <td class="center"><?php if($plano->data_retorno_usuario != '0000-00-00 00:00:00'){echo $this->sma->hrld($plano->data_retorno_usuario);}else{ echo $this->sma->hrld($plano->data_termino);} ?></td>
                                               
                                                
                                                 <td class="center"><a style="color: blue;" class="btn fa fa-folder-open-o"  href="<?= site_url('welcome/manutencao_acao_disable/'.$plano->idplanos); ?>"><?= lang('Abrir') ?></a></td>
                                             
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
