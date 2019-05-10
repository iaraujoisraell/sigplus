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
<script>
           function imprime() {
            setTimeout(function () { window.print(); }, 500);
                   
           setTimeout("window.close()",50);
    }
    window.onafterprint = e;
    </script>
             <script language='JavaScript'>
function ClosePrint() {
      setTimeout(function () { window.print(); }, 500);
      window.onfocus = function () { setTimeout(function () { window.close(); }, 500); }
}
</script>
        
</head>

<STYLE media=print>.noprint {
DISPLAY: none
}
</STYLE>
<body onload="ClosePrint();">

    <div id="wrapper">

        <!-- begin TOP NAVIGATION -->
          <?php // $this->load->view($this->theme . 'top'); ?>
        <!-- end TOP NAVIGATION -->

       
        <!-- end SIDE NAVIGATION -->

        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                
                <!-- ATALHOS RÁPIDO -->
               <?php
                    $usuario = $this->session->userdata('user_id');
                    $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    $perfil_atual = $projetos->group_id;
                    $perfis_user = $this->site->getUserGroupAtual($perfil_atual);

                   $perfis_user = $this->site->getPerfilusuarioByID($usuario);
                   $qtde_perfis_user = 0;
                       foreach ($perfis_user as $item) {
                           $qtde_perfis_user++;
                       }
                    ?> 
                <div class="row">
                    <div class="col-lg-12">
                        <center>
                            <br><br>
                              <div style="width: 70%;" >
                                    <?php
                                    $usuario = $this->session->userdata('user_id');
                                    $projetos_user = $this->site->getAllProjetosUsers($usuario);
                                    $cont = 1;
                                    $qtde_perfis_user = 0;
                                 //   foreach ($projetos_user as $item) {
                                        $id_projeto = $projetos->projeto_atual;
                                        $wu3[''] = '';
                                        $projeto = $this->atas_model->getProjetoByID($id_projeto);
                                        
                                        
                                        /*
                                         * VERIFICA SE TEM AÇÕES AGUARDANDO VALIDAÇÃO
                                         */
                                        $quantidadeAvalidacao = $this->site->getAllPlanosAguardandoValidacao($id_projeto);
                         
                                        
                                        
                                         $acoes_aguardando_validacao = $quantidadeAvalidacao->quantidade;
                                         
                                        
                                        ?>
                                        <a href="<?= site_url('Login_Projetos/projeto_ata/'.$projeto->id); ?>" class="btn btn-block btn-social btn-lg " style="background-color: <?php echo $projeto->botao; ?>">
                                            <i style="color:#ffffff;" class="fa fa-tasks fa-fw fa-3x"></i>
                                            <font style="color:#ffffff; font-weight:bold;">  <?php echo $projeto->projeto; ?>  </font>  
                                    <?php if($acoes_aguardando_validacao > 0){  ?>  <font style="color:#ffffff; font-size: 14px; margin-left: 15px;"><?php if($acoes_aguardando_validacao > 1){ ?>  <?php echo $acoes_aguardando_validacao; ?> Ações A.Validação <?php }else{ ?>  <?php echo $acoes_aguardando_validacao; ?> Ação A. Validação <?php } ?></font>  <?php } ?>
                                        </a>
                                      <?php
                                       //Qtde de AÇÕES
                                        $total_acoes =  $this->projetos_model->getQtdeAcoesByProjeto($id_projeto);
                                        $total_acoes = $total_acoes->total_acoes;
                                        //Qtde de Ações concluídas
                                        $concluido = $this->projetos_model->getStatusAcoesByProjeto($id_projeto, 'CONCLUÍDO');
                                        $concluido =  $concluido->status;
                                        //Qtde de ações Pendentes
                                        $pendente = $this->projetos_model->getAcoesPendentesByProjeto($id_projeto, 'PENDENTE');
                                        $avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByProjeto($id_projeto, 'AGUARDANDO VALIDAÇÃO');
                                        $pendente =  $pendente->pendente + $avalidacao->avalidacao;
                                        //Qtde de Ações Atrasadas
                                        $atrasadas = $this->projetos_model->getAcoesAtrasadasByProjeto($id_projeto, 'PENDENTE');
                                        $atrasadas =  $atrasadas->atrasadas;
                                        
                                        if($concluido){
                                            $porc_concluido = ($concluido * 100)/$total_acoes;
                                        }else{
                                            $porc_concluido = 0;
                                        }
                                        if($pendente){
                                            $porc_pendente = ($pendente * 100)/$total_acoes;
                                        }else{
                                            $porc_pendente = 0;
                                        }
                                        
                                        if($atrasadas){
                                            $porc_atrasado = ($atrasadas * 100)/$total_acoes;
                                        }else{
                                            $porc_atrasado = 0;
                                        }
                                      ?>
                                        <div class="progress">
                                          <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido;  ?>%">
                                           <?php if($porc_concluido != 100){ echo  substr($porc_concluido,0,2); }else{ echo $porc_concluido; } ?> % Concluído
                                          </div>
                                          <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente;  ?>%">
                                           <?php if($porc_pendente != 100){ echo  substr($porc_pendente,0,2); }else{ echo $porc_pendente; } ?>% Em Andamento
                                          </div>
                                          <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php  echo $porc_atrasado;  ?>%">
                                           <?php if($porc_atrasado != 100){ echo  substr($porc_atrasado,0,2); }else{ echo $porc_atrasado; } ?>% Atrasado
                                          </div>
                                        </div>
                                         
                                        <?php
                                        $cont++;
                                   // }
                                    //  }
                                    ?>   
                                          <?php
                    $usuario = $this->session->userdata('user_id');
                    $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    $perfil_atual = $projetos->group_id;
                    $perfis_user = $this->site->getUserGroupAtual($perfil_atual);

                   $perfis_user = $this->site->getPerfilusuarioByID($usuario);
                   $qtde_perfis_user = 0;
                       foreach ($perfis_user as $item) {
                           $qtde_perfis_user++;
                       }
                       
                      // if($qtde_perfis_user > 1){
                    ?>
                                          
                                          
                       <?php //} ?>         
                         
                                        </div>   
                            
                           
                         
                        </center>
                        <div class="page-title">
                            
                            <ol class="breadcrumb">
                                <li><i class="fa fa-user"></i>  Gestor do Projeto:    <?php echo $projetos->gerente_area; ?>
                                </li>
                                <li class="active"><i class="fa fa-calendar"></i>Início do Projeto: <?php echo date("d/m/Y", strtotime($projetos->dt_inicio)); ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                
                
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
    text-decoration: none;
}

.tree li a{
    height: 30px;
    width: 65px;
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
                                                                        <a href="#">
                                                                            
                                                                            <div   class="container-fluid">
                                                                                <div class="row">
                                                                                    <?php echo $projeto->projeto; ?>
                                                                                </div>
                                                                                <div class="row" style="margin-top: 5px;">
                                                                                    <i class="fa fa-exclamation-circle fa-2x"></i>
                                                                                </div>
                                                                                
                                                                            </div>

                                                                        </a>
                                                                        <ul>
                                                                            <?php
                                                                            foreach ($tipos as $tipo) {
                                                                                $tipo_evento = "$tipo->tipo";
                                                                                
                                                                                $tipo_evento2 = urlencode($tipo_evento);

                                                                            ?>
                                                                            <li>
                                                                                <a href="#">

                                                                                    <div  class="container-fluid">
                                                                                        <div class="row">
                                                                                            <font style="font-size:10px;"> <?php echo $tipo->tipo; ?> </font>
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
                                                                                
                                                                                 <ul>
                                                                                   
                                                                                        <a href="#">

                                                                                            <div class="container-fluid">
                                                                                                <div class="row">
                                                                                                    <font style="font-size:10px;"> <?php echo $evento->nome_evento; ?> </font>
                                                                                                </div>
                                                                                                <div class="row" style="margin-top: 10px;">
                                                                                                    <i class="fa fa-exclamation-circle fa-2x"></i>
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
                                                                            ?>
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                               
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

                                                                <li><font style="font-size:10px;"><?php echo $evento->nome_evento; ?></font>
                                                                    <ul>
                                                                        <?php
                                                                    $intes_eventos = $this->projetos_model->getAllItemEventosProjeto($evento->id,'tipo','asc');
                                                                    $cont = 1;
                                                                    ?>
                                                                        <table style="width:100%;">

                                                                        <?php    
                                                                        foreach ($intes_eventos as $item) {
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
