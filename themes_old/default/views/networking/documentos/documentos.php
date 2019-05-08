<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>

<body class="hold-transition skin-green sidebar-collapse  sidebar-mini">

 <div class="content-wrapper">
     
     <section class="content-header">
            <h1>
                Documentos dos Projetos
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>  
     
    

           <section class="col-lg-12 connectedSortable">

             
               
                
                
                <?php
                     $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    
                     
                    ?>
                <br><br>
                <!-- /ATALHOS RÁPIDO -->
                <div class="row">
                    
                   <!-- LADO ESQUERDO -->
                    <div class="col-lg-12">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-12">
                                        <?php
                                        /*
                                         * FAZ O STATUS REPORT DE TODOS OS PROJETOS
                                         */
                                        $usuario = $this->session->userdata('user_id');
                                        $allProjetos = $this->user_model->getAllProjetos();
                                        foreach ($allProjetos as $projeto) {
                                     
                                            $grupo_documento = $this->user_model->getAllGrupoDocumentoByUsuario($projeto->id, $usuario);

                                            foreach ($grupo_documento as $grupo) {

                                        ?>
                                        
                                            <div class="portlet-heading">
                                                <h3><p><?php echo $projeto->projeto; ?></p></h3>
                                                <table style="width: 100%;">
                                                        <tr>
                                                            <td style="width: 10%;">
                                                                <div class="portlet-title">
                                                                    <h4><?php echo $grupo->grupo; ?></h4>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                    </table>
                                            </div>

                                            <div class="table-responsive">

                                                <?php
                                                $cont_evento = 1;
                                                 $eventos = $this->projetos_model->getAllDocumentosProjetoByGrupo($grupo->grupo);
                                                 foreach ($eventos as $evento) {
                                                     $res_tec = $this->site->geUserByID($evento->user);
                                                        ?>
                                                <table style="width:100%;" id="example-table" class="table table-striped table-bordered table-hover table-green">
                                                         <thead>
                                                        <tr style="background-color: lightblue ;">

                                                            <th style="width:5%;">ID</th>
                                                            <th style="width:10%;">CÓD.DOC.</th>
                                                            <th style="width:35%;">DOCUMENTO</th>
                                                            <th style="width:10%;">GRUPO</th>
                                                            <th style="width:10%;">REVISÃO</th>
                                                            <th style="width:10%;">DATA REVISÃO</th>
                                                            <th style="width:10%;">DATA VALIDADE</th>
                                                            <th style="width:10%;">DOCUMENTO</th>
                                                            
                                                                

                                                        </tr>
                                                         </thead>
                                                         <tbody>
                                                             <tr   class="odd gradeX">
                                                                <td style="width:5%;"><?php echo $cont_evento++; ?></td>  

                                                                <td style="width:10%;"><?php echo $evento->codigo_documento; ?></td>
                                                                <td style="width:35%;"><?php echo $evento->nome_documento; ?></td>
                                                                <td style="width:10%;"><?php echo $evento->grupo_documento; ?></td>

                                                                <td style="width:10%;">
                                                                   <?php echo $evento->revisao; ?>
                                                                </td>
                                                                <td style="width:10%;"><?php echo $this->sma->hrld($evento->data_revisao); ?></td>
                                                                <td style="width:10%;"><?php echo $this->sma->hrld($evento->data_validade); ?></td>

                                                               <th style="width:10%;">
                                                                   <?php if($evento->anexo){ ?>
                                                                   <a target='_blank' href="<?= site_url('../assets/uploads/projetos/' . $evento->anexo) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                                                        <i class="fa fa-chain"></i>
                                                                        <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                                                                   </a>
                                                                   <?php } ?>
                                                               </th> 
                                                                    


                                                            </tr>
                                                         </tbody>
                                                    </table>       



                                                 <?php //$cont_evento++; 


                                                         }

                                                 ?>

                                           </div>
                                      

                                        <?php 
                                            } 
                                        
                                        }
                                        ?>

                            </div>
                         </div>
                     </div>
                 </div>   
                
            </section>    
                
                
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

        <!-- PAGE LEVEL PLUGIN SCRIPTS -->
        <script src="<?= $assets ?>dashboard/js/plugins/ladda/spin.min.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/ladda/ladda.min.js"></script>
        <script src="<?= $assets ?>dashboard/js/plugins/bootstrap-multiselect/bootstrap-multiselect.js"></script>

        <!-- THEME SCRIPTS -->
        <script src="<?= $assets ?>dashboard/js/flex.js"></script>
        <script src="<?= $assets ?>dashboard/js/demo/buttons-demo.js"></script>

        <script src="<?= $assets ?>dashboard/js/demo/calendar-demo.js"></script>
            <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/fullcalendar.min.js"></script>
      
 </div>     
            
</body>
