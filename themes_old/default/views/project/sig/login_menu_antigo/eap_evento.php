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
                    
                     /*
                      * VERIFICA SE O USUÁRIO TEM PERMISSAO PARA ACESSAR O MENU EXIBIDO
                      */
                     $permissoes           = $this->projetos_model->getPermissoesByPerfil($projetos->group_id);   
                     $permissao_projetos   = $permissoes->projetos_index;
                     $permissao_atas       = $permissoes->atas_index;
                     $permissao_participantes   = $permissoes->participantes_index;
                     $permissao_eventos    = $permissoes->eventos_index;
                     
                     $permissao_acoes      = $permissoes->acoes_index;
                     $permissao_avalidacao = $permissoes->acoes_aguardando_validacao_index;
                     $permissao_apendentes = $permissoes->acoes_pendentes_index;
                     
                     
                     $permissao_dashboard   = $permissoes->dashboard_index;
                     
                     /*
                      * CADASTRO
                      */
                     $permissao_cadastro              = $permissoes->cadastro;
                     $permissao_pesquisa_satisfacao   = $permissoes->pesquisa_satisfacao_index;
                     $permissao_categoria_financeira  = $permissoes->categoria_financeira_index	;
                     $permissao_setores               = $permissoes->setores_index;
                     $permissao_perfil_acesso         = $permissoes->perfil_acesso;
                     /*
                      * RELATÓRIO
                      */
                     $permissao_relatorios             = $permissoes->relatorios;
                     $permissao_status_report          = $permissoes->status_report;
                     $permissao_users_acoes_atrasadas  = $permissoes->users_acoes_atrasadas;
                     /*
                      * PESSOAS
                      */
                     $permissao_cadastro_pessoas    = $permissoes->cadastro_pessoas;
                     $permissao_usuarios            = $permissoes->users_index;
                     $permissao_gestores            = $permissoes->lista_gestores;
                     $permissao_suporintendentes    = $permissoes->lista_superintendente;
                     $permissao_fornecedor          = $permissoes->fornecedores_index;
                     $lista_participantes          = $permissoes->lista_participantes;
                     
                     
                     /*
                      * GESTAO DE CUSTO
                      */
                     $permissao_gestao_custo          = $permissoes->gestao_custo;
                     $permissao_contas_pagar          = $permissoes->contas_pagar;
                     
                     /*
                      * CALENDÁRIO
                      */
                     $permissao_calendario          = $permissoes->calendario;
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
    width: 0; height: 100%;
}

.tree li a{
    height: 80px;
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
                <div class="row">
                    
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
                                                                        <a href="<?= site_url('Login_Projetos/eap/'.$projeto->id) ?>">
                                                                            <div   class="container-fluid">
                                                                                <div class="row">
                                                                                    <?php echo $evento->nome_evento; ?>
                                                                                </div>
                                                                                <div class="row" style="margin-top: 5px;">
                                                                                    <i class="fa fa-exclamation-circle fa-2x"></i>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                        <ul>
                                                                            <?php
                                                                            $itens_evento = $this->projetos_model->getAllItemEventosProjeto($evento->id);
                                                                            foreach ($itens_evento as $item) {
                                                                               
                                                                                 $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item->id);
                                                                                             //Qtde de Ações concluídas
                                                                                            $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item->id);
                                                                                            
                                                                                            //Qtde de ações Pendentes
                                                                                            $item_pendente =    $this->projetos_model->getAcoesPendentesByItemEvento($item->id);
                                                                                            $item_avalidacao =  $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item->id);
                                                                                           
                                                                                            $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item->id);
                                                                                            
                                                                                            //Total de ações
                                                                                            $total_acoes = $quantidade_acoes_item->quantidade;
                                                                                         //   $soma_total_acao += $total_acoes;
                                                                                            //CONCLUÍDAS
                                                                                            $quantidade_acoes_concluidas_item =  $concluido->quantidade;
                                                                                         //   $soma_total_concluida += $quantidade_acoes_concluidas_item;
                                                                                            //PENDENTES
                                                                                             $itens_pendentes =  $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                                                          //   $soma_total_pendente += $itens_pendentes;
                                                                                             //ATRASADAS
                                                                                             $quantidade_atrasadas = $atrasadas->quantidade;
                                                                                            // $soma_total_atrasada += $quantidade_atrasadas;
                                                                                             
                                                                                              if($total_acoes > 0){
                                                                                                 
                                                                                                 if ($quantidade_atrasadas > 0){
                                                                                                     $cor = "red";
                                                                                                     $color = "#ffffff";
                                                                                                 }else if($quantidade_atrasadas == 0){
                                                                                                    
                                                                                                     if($itens_pendentes > 0){
                                                                                                          $cor = "orange";
                                                                                                          $color = "#ffffff";
                                                                                                     }else if($itens_pendentes == 0){
                                                                                                         
                                                                                                         if($total_acoes == $quantidade_acoes_concluidas_item ){
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
                                                                            <li>
                                                                                <a style="background-color: <?php echo $cor; ?>; color: <?php echo $color; ?>;">

                                                                                    <div  class="container-fluid">
                                                                                        <div class="row">
                                                                                           <?php echo $item->descricao; ?>
                                                                                        </div>
                                                                                        <div class="row" style="margin-top: 15px;">
                                                                                            
                                                                                        </div>
                                                                                        
                                                                                    </div>

                                                                                </a>
                                                                               
                                                                            </li>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                               
                                               
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
