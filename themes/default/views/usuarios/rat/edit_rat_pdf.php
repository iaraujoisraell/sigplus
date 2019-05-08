<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html lang="en">

<?php  //$this->load->view($this->theme . 'usuarios/header'); ?>
 <title>Controle de Projetos - TI UnimedManaus</title>

     <!-- GLOBAL STYLES - Include these on every page. -->
            <link href="<?= $assets ?>dashboard/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic' rel="stylesheet" type="text/css">
            <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel="stylesheet" type="text/css">
            <link href="<?= $assets ?>dashboard/icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">

            
            
             <!-- PAGE LEVEL PLUGIN STYLES -->
            <link href="<?= $assets ?>dashboard/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
            <link href="<?= $assets ?>dashboard/css/plugins/bootstrap-social/bootstrap-social.css" rel="stylesheet">
            <link href="<?= $assets ?>dashboard/css/plugins/bootstrap-multiselect/bootstrap-multiselect.css" rel="stylesheet">

            <!-- THEME STYLES - Include these on every page. -->
            <link href="<?= $assets ?>dashboard/css/styles.css" rel="stylesheet">
            <link href="<?= $assets ?>dashboard/css/plugins.css" rel="stylesheet">

            <!-- THEME DEMO STYLES - Use these styles for reference if needed. Otherwise they can be deleted. -->
            <link href="<?= $assets ?>dashboard/css/demo.css" rel="stylesheet">
              
            <!-- CALENDAR. -->
            <link href="<?= $assets ?>dashboard/css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->

</head>
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
                                      <?php
                                    $usuario = $this->session->userdata('user_id');
                                    $res_assinar = $this->site->geUserByID($usuario);
                                    $nome_emitiu = $res_assinar->first_name.' '.$res_assinar->last_name;
                                   ?>
                                    
                                     <h3>LANÇAMENTO DE R.A.T. (Regitro de Atividade de Trabalho) <?php echo $nome_emitiu; ?></h3>
                                   
                                  
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="portlet-body">
                               
                                <div class="box">
                                  
                                    <div class="box-content">
                                        <div class="row">
                                            <div class="col-lg-12">

                                          
                                               
                                                <div class="row">
                                                  
                                                    
                                                     <?php
                                                                function resume( $var, $limite ){	// Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.	
                                                                          if (strlen($var) > $limite)	{
                                                                                $var = substr($var, 0, $limite);		
                                                                                $var = trim($var) . "...";	

                                                                          }return $var;

                                                                          }
                                                                          
                                                                          
                                                                          
                                                                          
                                                                          
                                                                      ?>
                                                    
                                                      
                                                   
                                            
                                                     <div style="margin-top:100px;" class="col-lg-12">
                                                         <div class="portlet portlet-default">
                                                             
                                                        
                                                     <div class="portlet-body">
                                                                <div class="table-responsive">
                                                                    <table style="width: 100%;" class="table table-striped table-bordered table-hover table-green">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="width: 5%;">ID</th>
                                                                                <th style="width: 10%;">DATA</th>
                                                                                <th style="width: 5%;">INÍCIO</th>
                                                                                <th style="width: 5%;">TÉRMINO</th>
                                                                                <th style="width: 5%;">TEMPO</th>
                                                                                <th style="width: 5%;">TIPO</th>
                                                                                <th style="width: 25%;">DESCRICAO</th>
                                                                                <th style="width: 15%;">EVENTO/ITEM</th>
                                                                            
                                                                               
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                             <?php
                                                                             
                                                                             function dateDiff( $dateStart, $dateEnd, $format = '%a' ) {

                                                                                $d1     =   new DateTime( $dateStart );

                                                                                $d2     =   new DateTime( $dateEnd );

                                                                                //Calcula a diferença entre as datas
                                                                                $diff   =   $d1->diff($d2, true);   

                                                                                //Formata no padrão esperado e retorna
                                                                                return $diff->format( $format );

                                                                            }
                                                                             $cont_planoContinuo = 1;
                                                                             foreach ($dados_equipe as $dado) {
                                                                                $id = $dado->id;
                                                                                
                                                                           
                                                                             
                                                                                $wu4[''] = '';
                                                                               
                                                                                $total_tempo = '00:00:00';
                                                                                $cont_array = 0;
                                                                                $minhas_rats = $this->atas_model->getRatMebrosEquipeByIdMembro($id, $data_inicio, $data_fim);
                                                                                foreach ($minhas_rats as $rat) {
                                                                                    $hora_inicio = $rat->hora_inicio;
                                                                                    $hora_fim = $rat->hora_fim;
                                                                                     $tempo = gmdate('H:i:s', strtotime( $hora_fim) - strtotime( $hora_inicio  ) );
                                                                                    
                                                                                    $times[] = $tempo;
                                                                                ?>   
                                                                                    <tr  class="odd gradeX">
                                                                                    <td style="width: 5%;" class="center"><?php echo $cont_planoContinuo++; ?></td>
                                                                                    <td style="width: 10%;" class="center"><?php echo date("d/m/Y", strtotime($rat->data_rat)); ?></td>
                                                                                    <td style="width: 5%;" class="center"><?php echo $hora_inicio; ?></td>
                                                                                    <td style="width: 5%;" class="center"><?php echo $hora_fim; ?></td>
                                                                                    <td style="width: 10%;" class="center"><?php echo $tempo; ?></td>
                                                                                     <td style="width: 10%;" class="center"><?php echo $rat->tipo_hora; ?></td>
                                                                                    <td style="width: 25%;" class="center"><?php echo $rat->descricao; ?></td>
                                                                                    <td style="width: 15%;" class="center">
                                                                                        <?php
                                                                                        $itens_rats = $this->atas_model->getEventoItemByRat($rat->id);
                                                                                        foreach ($itens_rats as $item_rat) {
                                                                                        ?>
                                                                                        <p>  <?php echo $item_rat->evento.'/'.$item_rat->item; ?></p>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                   
                                                                                    
                                                                                   
                                                                                    </tr>
                                                                                <?php
                                                                               
                                                                                }
                                                                                
                                                                             }
                                                                                //$times = array($hora1, $hora2);
                                                                               // print_r($times);
                                                                                    $seconds = 0;
                                                                                    foreach ( $times as $time ){   
                                                                                    list( $g, $i ) = explode( ':', $time );   
                                                                                    $seconds += $g * 3600;   
                                                                                    $seconds += $i * 60;   
                                                                                    }
                                                                                    $hours = floor( $seconds / 3600 );
                                                                                    $seconds -= $hours * 3600;
                                                                                    $minutes = floor( $seconds / 60 );
                                                                                    $seconds -= $minutes * 60;
                                                                                   
                                                                                    
                                                                                ?>


                                                                                   

                                                                        </tbody>
                                                                        
                                                                    </table>
                                                                </div>
                                                                <!-- /.table-responsive --> <?php echo 'TOTAL DE HORAS APONTADAS : '. $hours.':'.$minutes; ?>
                                                            </div>
                                                          </div>    
                                                     </div>  
                                                    
                                                     
                                                    






                                           

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
