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
                                      
                                                $soma_porc_acoes_concluidas_fase = 0;
                                                $soma_porc_acoes_pendentes_fase = 0;
                                                $soma_porc_acoes_atrasadas_fase = 0;
                                                $cont_qtde_item_fase = 0;
                                                $cont_qtde_item_evento_fase = 0;
                                                $coma_total_acoes_itens = 0;
                                                $coma_total_acoes_concluidas_itens = 0;
                                                $soma_valores_zerado = 0;
                                                $soma_itens_sem_acao = 0;
                                                
                                                foreach ($tipos as $tipo) {
                                                        $tipo_evento = $tipo->tipo;
                                                        
                                                       
                                                          $ordem = 'ordem';
                                                             $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento, $projetos->projeto_atual , $ordem,'asc');
                                                             foreach ($eventos as $evento) {
                                                             
                                                                  
                                                                   $soma_acoes_evento = 0;
                                                                   $cont_qtde_item_evento = 0;
                                                                   $intes_eventos2 = $this->projetos_model->getAllItemEventosProjeto($evento->id,'tipo','asc');
                                                                   foreach ($intes_eventos2 as $item2) {
                                                                       
                                                                            $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item2->id);
                                                                            
                                                                            //Qtde de Ações concluídas
                                                                            $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item2->id);
                                                                            $quantidade_acoes_concluidas_item =  $concluido->quantidade;
                                                                            $coma_total_acoes_concluidas_itens += $quantidade_acoes_concluidas_item;
                                                                            
                                                                            //Qtde de ações Pendentes
                                                                            $item_pendente =    $this->projetos_model->getAcoesPendentesByItemEvento($item2->id);
                                                                            $item_avalidacao =  $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item2->id);
                                                                            $itens_pendentes =  $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                                            $soma_porc_acoes_pendentes_fase += $itens_pendentes;
                                                                            
                                                                            $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item2->id);
                                                                           $acoes_atrasadas = $atrasadas->quantidade;;
                                                                            $soma_porc_acoes_atrasadas_fase += $acoes_atrasadas;
                                                                            
                                                                           $cont_qtde_item_fase += $quantidade_acoes_item->quantidade;
                                                                           
                                                                           
                                                                           
                                                                           /*
                                                                             *  SOMA AS PORCENTAGENS DO ÍTEM PARA GERAR A MÉDIA DO EVENTO
                                                                             */
                                                                            
                                                                         
                                                                            $cont_qtde_item_evento ++;
                                                                           // $soma_valores_zerado ++;
                                                                           $cont_qtde_item_evento_fase ++;
                                                                           
                                                                             if ($quantidade_acoes_item->quantidade == 0){
                                                                                //$cont_qtde_item_fase += 1;
                                                                        
                                                                            }
                                                                            
                                                                   }
                                                                   
                                                                     
                                                                     if($cont_qtde_item_evento == 0){
                                                                        
                                                                         $soma_valores_zerado += 1;
                                                                        
                                                                         
                                                                    }else{
                                                                        $soma_itens_sem_acao += $cont_qtde_item_evento;
                                                                    }
                                                                    
                                                                     
                                                                   
                                                                
                                                               
                                                             }
                                                             
                                                         
                                                } 
                                                           //echo $soma_porc_acoes_atrasadas_fase;
                                                
                                                             $total_acoes_projeto = $cont_qtde_item_fase + $soma_itens_sem_acao + $soma_valores_zerado;
                                                             $total_acoes_pendentes =  $soma_porc_acoes_pendentes_fase + $soma_valores_zerado + $soma_itens_sem_acao;
                                                            
                                                            // $total_atrasado = $coma_total_acoes_concluidas_itens - $total_acoes_pendentes;
                                                             
                                                            
                                                             $porc_concluido = ($coma_total_acoes_concluidas_itens * 100)/$total_acoes_projeto;
                                                             $porc_pendente = ($total_acoes_pendentes * 100)/$total_acoes_projeto;
                                                             $porc_atrasado = ($soma_porc_acoes_atrasadas_fase * 100)/$total_acoes_projeto;
                                                        
                                        
                                        
                                        
                                      ?>
                                        <div class="progress">
                                          <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido;  ?>%">
                                           <?php if($porc_concluido != 100){ echo  substr($porc_concluido,0,2); }else{ echo $porc_concluido; } ?> % Concluído
                                          </div>
                                          <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente;  ?>%">
                                           <?php if($porc_pendente != 100){ echo  substr($porc_pendente,0,2); }else{ echo $porc_pendente; } ?>% Pendente
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
                       
                      
                    ?>
                                       
                         
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
                <div class="row">
                    
                   <!-- LADO ESQUERDO -->
                    <div class="col-lg-12">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Documentos do Projeto </h4>
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

                                                        $grupo_documento = $this->projetos_model->getAllGrupoDocumentoByProjeto($projetos->projeto_atual);
                                                      
                                                    foreach ($grupo_documento as $grupo) {
                                                    
                                                    ?>
                                                   <table style="width:100%;">
                                                                    <tr>
                                                                        <td>
                                                                             <div class="portlet portlet-default">
                                                                                <div class="portlet-heading">
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
                                                                                 
                                                                                 
                                                                                 </div>
                                                                    </tr>
                                                                </table>
                                                
                                                       <div id="buttons" class="panel-collapse collapse in">
                                                           <div class="portlet-body">
                                                                
                                                            <?php
                                                            
                                                            
                                                            
                                                            $cont_evento = 1;
                                                           
                                                           
                                                             $eventos = $this->projetos_model->getAllDocumentosProjetoByGrupo($grupo->grupo);
                                                             foreach ($eventos as $evento) {
                                                                 
                                                                 $res_tec = $this->site->geUserByID($evento->user);
                                                                 
                                                        
                                                                    ?>
                                                                <table style="width:100%;" class="table table-striped table-bordered table-hover table-green" >
                                                                     <thead>
                                                                    <tr>
                                       
                                                                        <th>ID</th>
                                                                        <th>CÓD.DOC.</th>
                                                                        <th>DOCUMENTO</th>
                                                                        <th>GRUPO</th>
                                                                        <th>REVISÃO</th>
                                                                        <th>DATA REVISÃO</th>
                                                                        <th>DATA VALIDADE</th>
                                                                        <th>ANEXO DOC</th>
                                                                        <th>QUEM PODE VER</th>
                                                                        <th>STATUS</th>     
                                                                      
                                                                    </tr>
                                                                     </thead>
                                                                     <tbody>
                                                                         <tr   class="odd gradeX">
                                                                            <td><?php echo $cont_evento++; ?></td>  

                                                                            <td><?php echo $evento->codigo_documento; ?></td>
                                                                            <td><?php echo $evento->nome_documento; ?></td>
                                                                            <td><?php echo $evento->grupo_documento; ?></td>

                                                                            <td>
                                                                               <?php echo $evento->revisao; ?>
                                                                            </td>
                                                                            <td><?php echo $this->sma->hrld($evento->data_revisao); ?></td>
                                                                            <td><?php echo $this->sma->hrld($evento->data_validade); ?></td>

                                                                           <th>
                                                                               <?php if($evento->anexo){ ?>
                                                                               <a target='_blank' href="<?= site_url('../assets/uploads/projetos/' . $evento->anexo) ?>" class="tip btn btn-file" title="<?= lang('Arquivo em Anexo') ?>">
                                                                                    <i class="fa fa-chain"></i>
                                                                                    <span class="hidden-sm hidden-xs"><?= lang('Ver Anexo') ?></span>
                                                                               </a>
                                                                               <?php } ?>
                                                                           </th> 
                                                                            <td class="center">
                                                                                <?php
                                                                                  $usuarios_setor  = $this->projetos_model->getAllUserSetor($evento->id);
                                                                                    foreach ($usuarios_setor as $usuario_setor) {

                                                                                        $id_usu_set_doc = $usuario_setor->usuario_setor;

                                                                                        if($id_usu_set_doc == 0){
                                                                                            $nome = 'PÚBLICO';
                                                                                        }else{

                                                                                        $setor_user = $this->atas_model->getUserSetorBYid($id_usu_set_doc);
                                                                                        $setor = $this->atas_model->getSetorByID($setor_user->setor);

                                                                                        $usu_setor = $this->site->geUserByID($setor_user->usuario);
                                                                                        $nome = $usu_setor->first_name.' '.$usu_setor->last_name.' - '.$setor->nome;
                                                                                        }
                                                                                    ?>

                                                                                <table  >
                                                                                    <tr><td><font style="font-size: 12px;"><?php  echo $nome; ?></font></td></tr>
                                                                                </table>

                                                                                    <?php  
                                                                                    }

                                                                                ?>
                                                                            </td>    
                                                                           
                                                                            <td <?php if ($evento->status == "ATUALIZADO"){ ?> style='background-color: green; color: #ffffff;'   <?php }else{ ?> style='background-color: orange;'   <?php } ?>>
                                                                                <p><?php echo $evento->status; ?></p>
                                                                            </td>

                                                                        </tr>
                                                                     </tbody>
                                                                </table>       
                                                                
                                               
                                                            
                                                             <?php //$cont_evento++; 
                                                                   
                                                             
                                                                     }
                                                            
                                                             ?>
                                                                        
                                                       </div>
                                                    </div>
                                                   
                                                    <?php } ?>
                                                   
                                                
                                               
                                                        
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
