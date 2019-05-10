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
    
  
    
    

   

    <!-- THEME STYLES - Include these on every page. -->
    <link href="<?= $assets ?>dashboard/css/styles.css" rel="stylesheet">
    
    <!-- GLOBAL STYLES - Include these on every page. -->
    <link href="<?= $assets ?>dashboard/css/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  
       <style>
        body { background-color: cadetblue; }
    </style>
    
    
    
</head>
<nav class="navbar-top" role="navigation">
          
               <div class="navbar-header">
               
                <div class="navbar-brand">
                    
                </div>
            </div>
                    
                    
                    
                  
            <div class="nav-top">
                
            
            <ul class="nav navbar-right">
                <li class="dropdown">
                        <a href="#" class="green dropdown-toggle" data-toggle="dropdown">
                            <font style="font-size: 14px; color: #ffffff;">  <?= $Settings->site_name ?> (TI - EDP)  </font>
                        </a>
                    
                </li>
                <li class="dropdown">
                        <a href="#" class=" dropdown-toggle" data-toggle="dropdown">
                            <span style="font-size: 14px; color: #ffffff;"> <?= $this->session->userdata('username'); ?></span>
                        </a>
                    
                </li>
                <li class="dropdown">
                        <a href="#" class="green dropdown-toggle" data-toggle="dropdown">
                                  <img style="width: 40px; height: 40px; margin-top: -5px;" alt="" src="<?= $this->session->userdata('avatar') ? $assets.'../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets.'images/' . $this->session->userdata('gender') . '.png'; ?>" class="mini_avatar img-rounded">
                     
                        </a>
                    
                </li>
                            
                             
                        
                      </ul>
             
            </div>
           
        
        
    </nav>
<br>
 <div class="row">
                    <div class="col-lg-12">
                        <div class="page-title">
                            
                            <ol class="breadcrumb">
                                <li class="active"><i class="fa fa-dashboard"></i><h1>Selecione o Projeto que deseja acessar
                               
                            </h1></li>
                                
                            </ol>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
<body>

              


    <div style="width: 95%;" class="row">
                    <div style=" margin-left: 50px;" class="col-lg-12">
                        
                       
                            <div class="row pricing-circle">                 
                                <?php
                                
                                $usuario = $this->session->userdata('user_id');
                                $projetos_user = $this->site->getAllProjetosUsers($usuario);
                                $cont = 1;
                                $qtde_perfis_user = 0;
                                 foreach ($projetos_user as $item){
                                   $id_projeto = $item->projeto;
                                
                                
                                
                                
                                $wu3[''] = '';
                                
                                $projeto = $this->atas_model->getProjetoByID($id_projeto);
                               // foreach ($projetos as $projeto) {
                                   // $wu3[$projeto->id] = $projeto->projeto;
                                ?>
                             <a href="<?= site_url('Login_Projetos/projeto_ata/'.$projeto->id); ?>" >   
                            <div class="col-md-3">
                                <ul class="plan <?php if($cont % 2 == 0){ ?> plan1 <?php }else{ ?> plan2 featured <?php } ?>">
                                    <li class="plan-name">
                                        <h3><?php echo $projeto->projeto; ?></h3>
                                    </li>
                                    <br><br><br>
                                    
                                    <li>
                                        <strong>Data Início: </strong> <?php echo $this->sma->hrld($projeto->dt_inicio); ?>
                                    </li>
                                    <li>
                                        <strong>Gerente do Projeto: </strong> <?php echo $projeto->gerente_area ?>
                                    </li>
                                    <li>
                                        <strong>Gerente da EDP:</strong> <?php echo $projeto->gerente_edp ?>
                                    </li>
                                  
                                    
                                  
                                </ul>
                            </div>
                           </a>
                           
                       
                              <?php
                              $cont++;
                              }
                              //  }
                                ?>
                         </div>
                        
                     <?php   //echo form_dropdown('projeto', $wu3, (isset($_POST['projeto']) ? $_POST['projeto'] : $Settings->default_supplier), 'id="slProjeto" required="required" class="form-control  select" data-placeholder="' . lang("Selecione") . ' "  style="width:100%;" ');
                     // ?>
                        <br><br><br><br><br><br>
                        <center>                      
                            <div class="col-md-12">
                                <div class="fprom-group"><a  class="btn btn-danger" href="<?= site_url('logout'); ?>"><?= lang('SAIR') ?></a></div>
                            </div>                 
                        </center>                   
                        
                        <!--/.pricing-circle-->

                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->



<footer style="background-color: seagreen; 
               box-shadow: 0px 0px 10px black;
               
   
    position: absolute; 
    bottom: 0px; 
    width: 100%; 
    height: 40px; 
   ">
<a href="#" id="toTop" class="blue" style="position: fixed; bottom: 30px; right: 30px; font-size: 30px; display: none;">
    <i class="fa fa-chevron-circle-up"></i>
</a>

    <p style="text-align:center;">&copy; <?= date('Y') . " Controle de Projetos - TI UNIMED MANAUS "  ?> (v<?= $Settings->version; ?>
        ) <?php 
            echo ' - Todos os Direitos';
        ?></p>
</footer>
<?= '</div>'; ?>
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true"></div>
<div id="modal-loading" style="display: none;">
    <div class="blackbg"></div>
    <div class="loader"></div>
</div>
   
    
    <!-- /#logout -->
    <!-- Logout Notification jQuery -->
    <script src="<?= $assets ?>dashboard/js/plugins/popupoverlay/logout.js"></script>
    <!-- HISRC Retina Images -->
    <script src="<?= $assets ?>dashboard/js/plugins/hisrc/hisrc.js"></script>

    <!-- PAGE LEVEL PLUGIN SCRIPTS -->

    <!-- THEME SCRIPTS -->
    <script src="<?= $assets ?>dashboard/js/flex.js"></script>

</body>

</html>
