<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html lang="en">

<?php  $this->load->view($this->theme . 'menu_head'); ?>

<body>

    <div id="wrapper">

        <!-- begin TOP NAVIGATION -->
          <?php  $this->load->view($this->theme . 'top'); ?>
        <!-- end TOP NAVIGATION -->

       
        <!-- end SIDE NAVIGATION -->

        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                
                <!-- ATALHOS RÁPIDO -->
                <?php  $this->load->view($this->theme . 'atalhos'); ?>
                
                
                
                <?php
                     $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    
                     
                    ?>
                <br><br>
                <!-- /ATALHOS RÁPIDO -->
                <style>
    
    @import url(https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css);
@import url(https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css);

/*#region Organizational Chart*/
.tree * {
    margin: 0; padding: 0;
    height: 100%;
    
}

.tree ul {
    padding-top: 20px; position: relative;

    -transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

.tree li {
    float: left; text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 5px 0 5px;

    -transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

/*We will use ::before and ::after to draw the connectors*/

.tree li::before, .tree li::after{
    content: '';
    position: absolute; top: 0; right: 50%;
    border-top: 2px solid #696969;
    width: 50%; height: 20px;
}
.tree li::after{
    right: auto; left: 50%;
    border-left: 2px solid #696969;
}

/*We need to remove left-right connectors from elements without 
any siblings*/
.tree li:only-child::after, .tree li:only-child::before {
    display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child{ padding-top: 0;}

/*Remove left connector from first child and 
right connector from last child*/
.tree li:first-child::before, .tree li:last-child::after{
    border: 0 none;
}
/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before{
    border-right: 2px solid #696969;
    border-radius: 0 5px 0 0;
    -webkit-border-radius: 0 5px 0 0;
    -moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after{
    border-radius: 5px 0 0 0;
    -webkit-border-radius: 5px 0 0 0;
    -moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before{
    content: '';
    position: absolute; top: 0; left: 50%;
    
    border-left: 2px solid #696969;
    width: 0; height: 90%;
    text-decoration: none;
}

.tree li a{
    height: 120px;
    width: 140px;
    padding: 5px 10px;
    text-decoration: none;
    background-color: white;
    color: #8b8b8b;
    font-family: arial, verdana, tahoma;
    font-size: 11px;
    display: inline-block;  
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    background: #cbcbcb; color: #000;
    -transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

/*Time for some hover effects*/
/*We will apply the hover effect the the lineage of the element also*/
.tree li a:hover, .tree li a:hover+ul li a {
    background: #cbcbcb; color: #000;
}
/*Connector styles on hover*/
.tree li a:hover+ul li::after, 
.tree li a:hover+ul li::before, 
.tree li a:hover+ul::before, 
.tree li a:hover+ul ul::before{
    border-color:  #94a0b4;
}
/*#endregion*/
</style>

                <?php 
                $usuario = $this->session->userdata('user_id');
                $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                $id_projeto = $projetos->projeto_atual;
                //$projeto = $this->atas_model->getProjetoByID($id_projeto);

                ?>
                <div class="row">
                     <a href="<?= site_url('Login_Projetos/eap_pdf/'.$id_projeto) ?>">
                                                                            
                        <div   class="container-fluid">
                            <div class="row">
                                Imprimir <i class="fa fa-print fa-2x"></i>
                            </div>

                        </div>

                    </a>
                   <!-- LADO ESQUERDO -->
                    <div class="col-lg-12">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>EAP - Estrutura Analítica do Projeto</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#buttons"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="buttons" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div class="row">
                                            <div class="col-md-12">
                                                 
                                                <?php 
                                                $usuario = $this->session->userdata('user_id');
                                                $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                                                $id_projeto = $projetos->projeto_atual;
                                                $projeto = $this->atas_model->getProjetoByID($id_projeto);
                                                
                                                
                                                
                                                
                                                
                                                ?>
                                                
                                                <div class="container-fluid " style="margin-top:20px">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="tree">
                                                                <ul>
                                                                    <li>
                                                                        <?php
                                                                        
                                                                        
                                                
                                                                            $cont_tipo_atrasado = 0;
                                                                            $cont_tipo_pendente = 0;
                                                                            $cont_tipo_concluido = 0;
                                                                            $cont_tipo_total_acao = 0;
                                                                            foreach ($tipos as $tipo) {
                                                                                $tipo_evento = "$tipo->tipo";
                                                                                
                                                                                $tipo_evento2 = urlencode($tipo_evento);

                                                                                                                                                           
                                                                                 $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento, $projetos->projeto_atual , $ordem,'asc');
                                                                                 foreach ($eventos as $evento) {
                                                                                
                                                                                
                                                                                
                                                                                $intes_eventos = $this->projetos_model->getAllItemEventosProjeto($evento->id,'tipo','asc');
                                                                                    
                                                                                     
                                                                                        $soma_total_acao = 0;
                                                                                        $soma_total_concluida = 0;
                                                                                        $soma_total_atrasada = 0;
                                                                                        $soma_total_pendente = 0;
                                                                                        foreach ($intes_eventos as $item) {
                                                                                            
                                                                                           $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item->id);
                                                                                             //Qtde de Ações concluídas
                                                                                            $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item->id);
                                                                                            
                                                                                            //Qtde de ações Pendentes
                                                                                            $item_pendente =    $this->projetos_model->getAcoesPendentesByItemEvento($item->id);
                                                                                            $item_avalidacao =  $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item->id);
                                                                                           
                                                                                            $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item->id);
                                                                                            
                                                                                            //Total de ações
                                                                                            $total_acoes = $quantidade_acoes_item->quantidade;
                                                                                            $soma_total_acao += $total_acoes;
                                                                                            //CONCLUÍDAS
                                                                                            $quantidade_acoes_concluidas_item =  $concluido->quantidade;
                                                                                            $soma_total_concluida += $quantidade_acoes_concluidas_item;
                                                                                            //PENDENTES
                                                                                             $itens_pendentes =  $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                                                             $soma_total_pendente += $itens_pendentes;
                                                                                             //ATRASADAS
                                                                                             $quantidade_atrasadas = $atrasadas->quantidade;
                                                                                             $soma_total_atrasada += $quantidade_atrasadas;
                                                                                            //echo $quantidade_acoes_item->quantidade;
                                                                                                    
                                                                                          // $cont += $total_acoes;
                                                                                        } 
                                                                                        
                                                                                        $cont_tipo_atrasado += $soma_total_atrasada;
                                                                                        $cont_tipo_pendente += $soma_total_pendente;
                                                                                        $cont_tipo_concluido += $soma_total_concluida;
                                                                                        $cont_tipo_total_acao += $soma_total_acao;      
                                                                                             
                                                                                    }
                                                                                }
                                                                                  
                                                                                  
                                                                                   if($cont_tipo_total_acao > 0){
                                                                                                 
                                                                                                 if ($cont_tipo_atrasado > 0){
                                                                                                     $cor = "red";
                                                                                                     $color = "#ffffff";
                                                                                                 }else if($cont_tipo_atrasado == 0){
                                                                                                    
                                                                                                     if($cont_tipo_pendente > 0){
                                                                                                          $cor = "orange";
                                                                                                          $color = "#ffffff";
                                                                                                     }else if($cont_tipo_pendente == 0){
                                                                                                         
                                                                                                         if($cont_tipo_total_acao == $cont_tipo_concluido ){
                                                                                                             $cor = "green";
                                                                                                              $color = "#ffffff";
                                                                                                         }
                                                                                                         
                                                                                                     }
                                                                                                    
                                                                                                 }
                                                                                             }else{
                                                                                                  $cor = "#cbcbcb";
                                                                                                  $color = "#000000";
                                                                                             }
                                                                                             
                                                                                             
                                                                                        ?>
                                                                        <a style="background-color: <?php echo $cor; ?>; color: <?php echo $color; ?>;" href="#">
                                                                            
                                                                            <div   class="container-fluid">
                                                                                <div class="row">
                                                                                    <?php echo $projeto->projeto; ?>
                                                                                </div>
                                                                                <div class="row" style="margin-top: 5px;">
                                                                                    <i style="color: #000000;" class="fa fa-exclamation-circle fa-2x"></i>
                                                                                </div>
                                                                                
                                                                            </div>

                                                                        </a>
                                                                        
                                                                         <?php
                                                                           
                                                                            
                                                                            
                                                                           // echo $cont;
                                                                            ?>
                                                                        <ul>
                                                                            <?php
                                                                            $cont = 0;
                                                                                        
                                                                                        
                                                                            foreach ($tipos as $tipo) {
                                                                                $tipo_evento = "$tipo->tipo";
                                                                                
                                                                                $tipo_evento2 = urlencode($tipo_evento);

                                                                                
                                                                                 $soma_total_acao_e = 0;
                                                                                        $soma_total_concluida_e = 0;
                                                                                        $soma_total_atrasada_e = 0;
                                                                                        $soma_total_pendente_e = 0;                                                                          
                                                                                 $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento, $projetos->projeto_atual , $ordem,'asc');
                                                                                 foreach ($eventos as $evento) {
                                                                                
                                                                                        $intes_eventos = $this->projetos_model->getAllItemEventosProjeto($evento->id,'tipo','asc');
                                                                                        $soma_total_acoes_eventos = 0;
                                                                                        $soma_total_concluida = 0;
                                                                                        $soma_total_atrasada = 0;
                                                                                        $soma_total_pendente = 0;
                                                                                        foreach ($intes_eventos as $item) {
                                                                                            $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item->id);
                                                                                            //Qtde de Ações concluídas
                                                                                            $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item->id);
                                                                                            //Qtde de ações Pendentes
                                                                                            $item_pendente =    $this->projetos_model->getAcoesPendentesByItemEvento($item->id);
                                                                                            $item_avalidacao =  $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item->id);
                                                                                            $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item->id);
                                                                                            //Total de ações
                                                                                            $total_acoes_eventos = $quantidade_acoes_item->quantidade;
                                                                                            $soma_total_acoes_eventos += $total_acoes_eventos;
                                                                                            //CONCLUÍDAS
                                                                                            $quantidade_acoes_concluidas_item =  $concluido->quantidade;
                                                                                            $soma_total_concluida += $quantidade_acoes_concluidas_item;
                                                                                            //PENDENTES
                                                                                             $itens_pendentes =  $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                                                             $soma_total_pendente += $itens_pendentes;
                                                                                             //ATRASADAS
                                                                                             $quantidade_atrasadas = $atrasadas->quantidade;
                                                                                             $soma_total_atrasada += $quantidade_atrasadas;
                                                                                        //echo $quantidade_acoes_item->quantidade;

                                                                                      // $cont += $total_acoes;
                                                                                    }
                                                                                    
                                                                                   $soma_total_acao_e += $soma_total_acoes_eventos;
                                                                                     $soma_total_concluida_e += $soma_total_concluida;
                                                                                      $soma_total_atrasada_e += $soma_total_atrasada;
                                                                                       $soma_total_pendente_e += $soma_total_pendente;

                                                                                }
                                                                                 
                                                                                    
                                                                                 
                                                                                  if($soma_total_acao_e > 0){
                                                                                                 
                                                                                                 if ($soma_total_atrasada_e > 0){
                                                                                                     $cor_evento = "red";
                                                                                                     $color_evento = "#ffffff";
                                                                                                 }else if($soma_total_atrasada_e == 0){
                                                                                                    
                                                                                                     if($soma_total_pendente_e > 0){
                                                                                                          $cor_evento = "orange";
                                                                                                          $color_evento = "#ffffff";
                                                                                                     }else if($soma_total_pendente_e == 0){
                                                                                                         
                                                                                                         if($soma_total_acao_e == $soma_total_concluida_e ){
                                                                                                             $cor_evento = "green";
                                                                                                              $color_evento = "#ffffff";
                                                                                                         }
                                                                                                         
                                                                                                     }
                                                                                                    
                                                                                                 }
                                                                                             }else{
                                                                                                  $cor_evento = "#cbcbcb";
                                                                                                  $color_evento = "#000000";
                                                                                             }
                                                                                        ?>
                                                                            
                                                                            
                                                                            <li>
                                                                                <a style="background-color: <?php echo $cor_evento; ?>; color: <?php echo $color_evento; ?>;" href="<?= site_url('Login_Projetos/eap_tipo/'.$tipo_evento2) ?>">

                                                                                    <div  class="container-fluid">
                                                                                        <div class="row">
                                                                                           <?php echo $tipo->tipo; ?>
                                                                                        </div>
                                                                                        <div class="row" style="margin-top: 15px;">
                                                                                            
                                                                                        </div>
                                                                                        
                                                                                    </div>

                                                                                </a>
                                                                                <?php
                                                                                 $ordem = 'ordem';
                                                                                 $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento, $projetos->projeto_atual , $ordem,'asc');
                                                                                 foreach ($eventos as $evento) {
                                                                                ?>
                                                                                 <?php
                                                                                 
                                                                                $intes_eventos = $this->projetos_model->getAllItemEventosProjeto($evento->id,'tipo','asc');
                                                                                    
                                                                                    ?>
                                                                      

                                                                                        <?php    
                                                                                        $soma_total_acao = 0;
                                                                                        $soma_total_concluida = 0;
                                                                                        $soma_total_atrasada = 0;
                                                                                        $soma_total_pendente = 0;
                                                                                        foreach ($intes_eventos as $item) {
                                                                                            
                                                                                           $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item->id);
                                                                                             //Qtde de Ações concluídas
                                                                                            $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item->id);
                                                                                            
                                                                                            //Qtde de ações Pendentes
                                                                                            $item_pendente =    $this->projetos_model->getAcoesPendentesByItemEvento($item->id);
                                                                                            $item_avalidacao =  $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item->id);
                                                                                           
                                                                                            $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item->id);
                                                                                            
                                                                                            //Total de ações
                                                                                            $total_acoes = $quantidade_acoes_item->quantidade;
                                                                                            $soma_total_acao += $total_acoes;
                                                                                            //CONCLUÍDAS
                                                                                            $quantidade_acoes_concluidas_item =  $concluido->quantidade;
                                                                                            $soma_total_concluida += $quantidade_acoes_concluidas_item;
                                                                                            //PENDENTES
                                                                                             $itens_pendentes =  $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                                                             $soma_total_pendente += $itens_pendentes;
                                                                                             //ATRASADAS
                                                                                             $quantidade_atrasadas = $atrasadas->quantidade;
                                                                                             $soma_total_atrasada += $quantidade_atrasadas;
                                                                                            //echo $quantidade_acoes_item->quantidade;
                                                                                                    
                                                                                          // $cont += $total_acoes;
                                                                                        } 
                                                                                        
                                                                                        
                                                                                        
                                                                                         if($soma_total_acao > 0){
                                                                                                 
                                                                                                 if ($soma_total_atrasada > 0){
                                                                                                     $cor = "red";
                                                                                                     $color = "#ffffff";
                                                                                                     $icon = "exclamation-circle";
                                                                                                 }else if($soma_total_atrasada == 0){
                                                                                                    
                                                                                                     if($soma_total_pendente > 0){
                                                                                                          $cor = "orange";
                                                                                                          $color = "#ffffff";
                                                                                                          $icon = "exclamation-circle";
                                                                                                     }else if($soma_total_pendente == 0){
                                                                                                         
                                                                                                         if($soma_total_acao == $soma_total_concluida ){
                                                                                                             $cor = "green";
                                                                                                              $color = "#ffffff";
                                                                                                              $icon = "check";
                                                                                                         }
                                                                                                         
                                                                                                     }
                                                                                                    
                                                                                                 }
                                                                                             }else{
                                                                                                  $cor = "#cbcbcb";
                                                                                                  $color = "#000000";
                                                                                                  $icon = "exclamation-circle";
                                                                                             }
                                                                                        ?>
                                                                                
                                                                                <ul >
                                                                                   
                                                                                        <a style="background-color: <?php echo $cor; ?>; color: <?php echo $color; ?>;"  href="<?= site_url('Login_Projetos/eap_evento/'.$evento->id) ?>">
                                                                                            <div  class="container-fluid">
                                                                                                <div class="row">
                                                                                                    <?php echo $evento->nome_evento; ?>
                                                                                                </div>
                                                                                                <div class="row" style="margin-top: 10px;">
                                                                                                    <i style="color: #000000;" class="fa fa-<?php echo $icon; ?> fa-2x"></i>
                                                                                                </div>
                                                                                                
                                                                                            </div>

                                                                                        </a>
                                                                                    
                                                                                    
                                                                                </ul>
                                                                                 <?php
                                                                                 
                                                                                   
                                                                                 
                                                                                 
                                                                                 
                                                                            }
                                                                            ?>
                                                                            </li>
                                                                            <?php
                                                                            }
                                                                            
                                                                            
                                                                           // echo $cont;
                                                                            ?>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                                <br><br><br><br><br>
                                                    <ul id="tree1">
                                                     
                                                    <?php
                                                    foreach ($tipos as $tipo) {
                                                        $tipo_evento = $tipo->tipo;
                                                    
                                                    ?>
                                                    <li>
                                                        <a href="#"><?php echo $tipo->tipo; ?></a>
                                                       
                                                            <?php
                                                             $ordem = 'ordem';
                                                             $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento, $projetos->projeto_atual , $ordem,'asc');
                                                             foreach ($eventos as $evento) {
                                                            ?>
                                                            <ul>

                                                                <li><?php echo $evento->nome_evento; ?>
                                                                    <ul>
                                                                        <?php
                                                                    $intes_eventos = $this->projetos_model->getAllItemEventosProjeto($evento->id,'tipo','asc');
                                                                    $cont = 1;
                                                                    ?>
                                                                        <table style="width:100%;">

                                                                        <?php    
                                                                        foreach ($intes_eventos as $item) {
                                                                            
                                                                            
                                                                             //Qtde de Ações concluídas
                                                                            $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item->id);
                                                                            $quantidade_acoes_concluidas_item =  $concluido->quantidade;
                                                                            //Qtde de ações Pendentes
                                                                            $item_pendente =    $this->projetos_model->getAcoesPendentesByItemEvento($item->id);
                                                                            $item_avalidacao =  $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item->id);
                                                                            $itens_pendentes =  $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                                            $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item->id);
                                                                        ?>
                                                                         <tr>
                                                                             <td style="width:50%;"><?php echo $item->descricao; ?></td>
                                                                             
                                                                         </tr>


                                                                        <?php } ?>
                                                                    </table>
                                                                    </ul>
                                                                </li>

                                                            </ul>
                                                             <?php } ?>
                                                        </table>
                                                    </li>
                                                    <?php } ?>
                                                   
                                                </ul>
                                               
                                            </div>
                                            
                                        </div>
                                </div>
                            </div>
                         </div>
                     </div>
                 </div>   
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
</body>

</html>
