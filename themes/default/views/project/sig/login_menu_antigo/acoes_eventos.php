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
               
                    <?php 
                    $evento = $this->projetos_model->getEventoItemEventoByIdItemEvento($id);
// $this->load->view($this->theme . 'usuarios/menus'); ?>
                
                <br><br>
                
               <!-- DIV TABLE AÇÕES PENDENTES -->  
                <div id="acoes_pendentes">
                    <div class="row">
                    <div class="col-lg-12">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Fase: <?php echo $evento->fase ?>/ Evento: <?php echo $evento->evento ?>/ Item: <?php echo $evento->item ?> </h4>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-responsive">
                                    <table id="example-table" class="table table-striped table-bordered table-hover table-green">
                                        <thead>
                                            <tr style="background-color: orange;">
                                                <th>ID</th>
                                                <th>PROJETO</th>
                                                <th>ATA</th>
                                                
                                                <th>DESCRIÇÃO</th>
                                                <th>ENVIADO EM</th>
                                                <th>PRAZO</th>
                                                <th>STATUS</th>
                                                <th>Responsável</th>
                                                <th>Opções</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                                              <?php

                                function geraTimestamp($data) {
                                    $partes = explode('/', $data);
                                    return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
                                }
                                ?>
                                            <?php
                                                $wu4[''] = '';
                                                //$cont = 1; 
                                                foreach ($planos as $plano) {
                                                    
                                                    $user = $this->projetos_model->getUsuarioSetorById($plano->responsavel);
                                                    
                                                    
                                                     $status = $plano->status;
                                                     $data_prazo = $plano->data_termino;
                                          

                                                    if ($status == 'PENDENTE') {
                                                        $dataHoje = date('Y-m-d H:i:s');
                                                        /*
                                                         * SE A DATA ATUAL FOR < A DATA DO PRAZO
                                                         * PENDENTE
                                                         */
                                                        if ($dataHoje <= $data_prazo) {
                                                            $novo_status = 'PENDENTE';
                                                        }
                                                        
                                                        /*
                                                         * SE A DATA ATUAL FOR > A DATA DO PRAZO
                                                         * ATRASADO (X DIAS)
                                                         * +5 DIAS
                                                         * +10 DIAS
                                                         * 
                                                         */
                                                        if ($dataHoje > $data_prazo) {
                                                            $novo_status = 'ATRASADO';


                                                            // Usa a função criada e pega o timestamp das duas datas:
                                                            $time_inicial = geraTimestamp($this->sma->hrld($dataHoje));
                                                            $time_final = geraTimestamp($this->sma->hrld($data_prazo));
                                                            // Calcula a diferença de segundos entre as duas datas:
                                                            $diferenca = $time_final - $time_inicial; // 19522800 segundos
                                                            // Calcula a diferença de dias
                                                            $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias

                                                            if ($dias >= '-5') {
                                                                $qtde_dias = $dias;
                                                            } else if (($dias < '-5') && ($dias >= '-10')) {
                                                                $qtde_dias = $dias;
                                                            } else if ($dias < '-10') {
                                                                $qtde_dias = $dias;
                                                            }else if ($dias < '-15') {
                                                                $qtde_dias = '+15';
                                                            }
                                                            $qtde_dias = str_replace('-', '', $qtde_dias);
                                                        }
                                                    } else if ($status == 'AGUARDANDO VALIDAÇÃO') {
                                                        $novo_status = 'AGUARDANDO VALIDAÇÃO';
                                                    }else if ($status == 'CONCLUÍDO') {
                                                        $novo_status = 'CONCLUÍDO';
                                                    }else if ($status == 'CANCELADO') {
                                                        $novo_status = 'CANCELADO';
                                                    }
                                                    
                                                      $projetos_usuario = $this->atas_model->getAtaProjetoByID_ATA($plano->idatas);
                                                ?>   
                                           
                                            <tr   class="odd gradeX">
                                                        <td><?php echo $plano->idplanos; ?>   </td> 
                                                        <td><?php echo $projetos_usuario->projetos; ?></td> 
                                                        <td><?php echo $plano->idatas; ?></td>     
                                                         <td><?php echo $plano->descricao; ?>
                                                         <p><font  style="font-size: 10px; "><?php echo $plano->tipo; ?> <?php echo $plano->processo; ?> <?php echo $plano->item_roteiro; ?></font></p>
                                               </td>     
                                                        <td><?php if($this->sma->hrld($plano->data_elaboracao) != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_elaboracao); }else{ echo  $this->sma->hrld($projetos_usuario->data_ata); } ?> </td>    
                                                        <td class="center"><font style="font-size: 16px;font-weight: bold"> <?php if($plano->data_termino != '0000-00-00 00:00:00'){ echo $this->sma->hrld($plano->data_termino); } ?></font></td>     


                                                        
                                                             <?php if ($novo_status == 'PENDENTE') {
                                                                        ?>
                                                                <td style="background-color: #CB3500;color: #ffffff" class="center"><?php echo $novo_status; ?></td>

                                                                    <?php
                                                                } else if ($novo_status == 'ATRASADO') {

                                                                    if ($dias >= '-5') {
                                                                        ?>
                                                                    <td style=" background-color: #c7254e; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></td>
                                                                <?php } else if (($dias < '-5') && ($dias >= '-10')) { ?> 
                                                                    <td style=" background-color: #d2322d; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></td>

                                                                <?php } else if ($dias < '-10') { ?> 
                                                                    <td style=" background-color: #000000; color: #ffffff;" class="center"><?php echo $novo_status . '  (' . $qtde_dias . ' dias ) '; ?></td>

                                                                <?php } ?> 


                                                            <?php } else if ($novo_status == 'AGUARDANDO VALIDAÇÃO') { ?>
                                                                <td style=" background-color: orange; color: #ffffff;" class="center"><?php echo $novo_status; ?></td>
                                                                
                                                                 <?php } else if ($novo_status == 'CONCLUÍDO') { ?>
                                                                <td style=" background-color: green; color: #ffffff;" class="center"><?php echo $novo_status; ?></td>


                                                            <?php }else if ($novo_status == 'CANCELADO'){ ?> 
                                                                <td style=" background-color: black; color: #ffffff;" class="center"><?php echo $novo_status; ?></td>
                                                                
                                                                
                                                            <?php } ?>  

                                                                   <td  class="center"><?php echo $user->first_name.' '.$user->last_name ?></td>
                                                        
                                                       
                                                    
                                                     
                                                        <td class="center">
                                                            <table>
                                                                <tr>
                                                                    <td><a style="background-color: chocolate; color: #ffffff;" class="btn fa fa-folder-open-o" href="<?= site_url('Login_Projetos/manutencao_acao/'.$plano->idplanos); ?>"> </a></td>
                                                                </tr>    
                                                            </table>
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
