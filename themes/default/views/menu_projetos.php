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

        <?php  $this->load->view($this->theme . 'top'); ?>

       
        <!-- end SIDE NAVIGATION -->

        <!-- begin MAIN PAGE CONTENT -->
        <div id="page-wrapper">

            <div class="page-content">

                <!-- begin PAGE TITLE ROW -->
               
               
                
                <!-- ATALHOS RÁPIDO -->
                <?php  $this->load->view($this->theme . 'atalhos'); ?>
                
                
                
                <?php
                     $usuario = $this->session->userdata('user_id');
                     $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    
                    ?>
                <br><br>
                <!-- /ATALHOS RÁPIDO -->
                <div class="row">
                    
                   <!-- LADO ESQUERDO -->
                    <div class="col-lg-4">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Projetos Ativos</h4>
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
                                    <div class="social-sizes">
                                    <?php
                                    $usuario = $this->session->userdata('user_id');
                                    $projetos_user = $this->site->getAllProjetosStatusUsers($usuario, 'ATIVO');
                                    $cont = 1;
                                    $qtde_perfis_user = 0;
                                    foreach ($projetos_user as $item) {
                                        $id_projeto = $item->projeto;
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
                                            $id_projeto = $projeto->id;
                                            $tipos = $this->projetos_model->getAllTipoEventosProjeto($id_projeto,'ordem','asc');
                                            foreach ($tipos as $tipo) {
                                                $tipo_evento = $tipo->tipo;


                                                $ordem = 'ordem';
                                                $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento, $id_projeto, $ordem, 'asc');
                                                foreach ($eventos as $evento) {


                                                    $soma_acoes_evento = 0;
                                                    $cont_qtde_item_evento = 0;
                                                    $intes_eventos2 = $this->projetos_model->getAllItemEventosProjeto($evento->id, 'tipo', 'asc');
                                                    foreach ($intes_eventos2 as $item2) {

                                                        $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item2->id);

                                                        //Qtde de Ações concluídas
                                                        $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item2->id);
                                                        $quantidade_acoes_concluidas_item = $concluido->quantidade;
                                                        $coma_total_acoes_concluidas_itens += $quantidade_acoes_concluidas_item;

                                                        //Qtde de ações Pendentes
                                                        $item_pendente = $this->projetos_model->getAcoesPendentesByItemEvento($item2->id);
                                                        $item_avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item2->id);
                                                        $itens_pendentes = $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                        $soma_porc_acoes_pendentes_fase += $itens_pendentes;

                                                        $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item2->id);
                                                        $acoes_atrasadas = $atrasadas->quantidade;

                                                        $soma_porc_acoes_atrasadas_fase += $acoes_atrasadas;

                                                        $cont_qtde_item_fase += $quantidade_acoes_item->quantidade;



                                                        /*
                                                         *  SOMA AS PORCENTAGENS DO ÍTEM PARA GERAR A MÉDIA DO EVENTO
                                                         */


                                                        $cont_qtde_item_evento ++;
                                                        // $soma_valores_zerado ++;
                                                        $cont_qtde_item_evento_fase ++;

                                                        if ($quantidade_acoes_item->quantidade == 0) {
                                                            //$cont_qtde_item_fase += 1;
                                                        }
                                                    }


                                                    if ($cont_qtde_item_evento == 0) {

                                                        $soma_valores_zerado += 1;
                                                    } else {
                                                        $soma_itens_sem_acao += $cont_qtde_item_evento;
                                                    }
                                                }
                                            }
                                            //echo $soma_porc_acoes_atrasadas_fase;

                                            $total_acoes_projeto = $cont_qtde_item_fase + $soma_itens_sem_acao + $soma_valores_zerado;
                                            $total_acoes_pendentes = $soma_porc_acoes_pendentes_fase + $soma_valores_zerado + $soma_itens_sem_acao;

                                            // $total_atrasado = $coma_total_acoes_concluidas_itens - $total_acoes_pendentes;


                                            $porc_concluido = ($coma_total_acoes_concluidas_itens * 100) / $total_acoes_projeto;
                                            $porc_pendente = ($total_acoes_pendentes * 100) / $total_acoes_projeto;
                                            $porc_atrasado = ($soma_porc_acoes_atrasadas_fase * 100) / $total_acoes_projeto;
                                            ?>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido; ?>%">
                                        <?php if ($porc_concluido != 100) {
                                            echo substr($porc_concluido, 0, 2);
                                        } else {
                                            echo $porc_concluido;
                                        } ?> % Concluído
                                            </div>
                                            <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente; ?>%">
                                        <?php if ($porc_pendente != 100) {
                                            echo substr($porc_pendente, 0, 2);
                                        } else {
                                            echo $porc_pendente;
                                        } ?>% Pendente
                                            </div>
                                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $porc_atrasado; ?>%">
                                        <?php if ($porc_atrasado != 100) {
                                            echo substr($porc_atrasado, 0, 2);
                                        } else {
                                            echo $porc_atrasado;
                                        } ?>% Atrasado
                                            </div>
                                        </div>
                                       
                                          <hr>
                                        <?php
                                        $cont++;
                                    }
                                    //  }
                                    ?>   
                                        </div>
                                </div>
                            </div>
                        </div>
                        <!-- /MEUS PROJETOS -->
                        <!-- MENU DE ACESSO -->
                        
                        <!-- /MENU DE ACESSO -->
                              
                      
                    </div>
                   
                   
                   
                   <div class="col-lg-4">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Projetos em Stand By</h4>
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
                                    <div class="social-sizes">
                                        <?php
                                    $usuario = $this->session->userdata('user_id');
                                    $projetos_user = $this->site->getAllProjetosStatusUsers($usuario, 'AGUARDANDO');
                                    $cont = 1;
                                    $qtde_perfis_user = 0;
                                    foreach ($projetos_user as $item) {
                                        $id_projeto = $item->projeto;
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
                                            $id_projeto = $projeto->id;
                                            $tipos = $this->projetos_model->getAllTipoEventosProjeto($id_projeto,'ordem','asc');
                                            foreach ($tipos as $tipo) {
                                                $tipo_evento = $tipo->tipo;


                                                $ordem = 'ordem';
                                                $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento, $id_projeto, $ordem, 'asc');
                                                foreach ($eventos as $evento) {


                                                    $soma_acoes_evento = 0;
                                                    $cont_qtde_item_evento = 0;
                                                    $intes_eventos2 = $this->projetos_model->getAllItemEventosProjeto($evento->id, 'tipo', 'asc');
                                                    foreach ($intes_eventos2 as $item2) {

                                                        $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item2->id);

                                                        //Qtde de Ações concluídas
                                                        $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item2->id);
                                                        $quantidade_acoes_concluidas_item = $concluido->quantidade;
                                                        $coma_total_acoes_concluidas_itens += $quantidade_acoes_concluidas_item;

                                                        //Qtde de ações Pendentes
                                                        $item_pendente = $this->projetos_model->getAcoesPendentesByItemEvento($item2->id);
                                                        $item_avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item2->id);
                                                        $itens_pendentes = $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                        $soma_porc_acoes_pendentes_fase += $itens_pendentes;

                                                        $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item2->id);
                                                        $acoes_atrasadas = $atrasadas->quantidade;

                                                        $soma_porc_acoes_atrasadas_fase += $acoes_atrasadas;

                                                        $cont_qtde_item_fase += $quantidade_acoes_item->quantidade;



                                                        /*
                                                         *  SOMA AS PORCENTAGENS DO ÍTEM PARA GERAR A MÉDIA DO EVENTO
                                                         */


                                                        $cont_qtde_item_evento ++;
                                                        // $soma_valores_zerado ++;
                                                        $cont_qtde_item_evento_fase ++;

                                                        if ($quantidade_acoes_item->quantidade == 0) {
                                                            //$cont_qtde_item_fase += 1;
                                                        }
                                                    }


                                                    if ($cont_qtde_item_evento == 0) {

                                                        $soma_valores_zerado += 1;
                                                    } else {
                                                        $soma_itens_sem_acao += $cont_qtde_item_evento;
                                                    }
                                                }
                                            }
                                            //echo $soma_porc_acoes_atrasadas_fase;

                                            $total_acoes_projeto = $cont_qtde_item_fase + $soma_itens_sem_acao + $soma_valores_zerado;
                                            $total_acoes_pendentes = $soma_porc_acoes_pendentes_fase + $soma_valores_zerado + $soma_itens_sem_acao;

                                            // $total_atrasado = $coma_total_acoes_concluidas_itens - $total_acoes_pendentes;


                                            $porc_concluido = ($coma_total_acoes_concluidas_itens * 100) / $total_acoes_projeto;
                                            $porc_pendente = ($total_acoes_pendentes * 100) / $total_acoes_projeto;
                                            $porc_atrasado = ($soma_porc_acoes_atrasadas_fase * 100) / $total_acoes_projeto;
                                            ?>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido; ?>%">
                                        <?php if ($porc_concluido != 100) {
                                            echo substr($porc_concluido, 0, 2);
                                        } else {
                                            echo $porc_concluido;
                                        } ?> % Concluído
                                            </div>
                                            <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente; ?>%">
                                        <?php if ($porc_pendente != 100) {
                                            echo substr($porc_pendente, 0, 2);
                                        } else {
                                            echo $porc_pendente;
                                        } ?>% Pendente
                                            </div>
                                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $porc_atrasado; ?>%">
                                        <?php if ($porc_atrasado != 100) {
                                            echo substr($porc_atrasado, 0, 2);
                                        } else {
                                            echo $porc_atrasado;
                                        } ?>% Atrasado
                                            </div>
                                        </div>
                                       
                                          <hr>
                                        <?php
                                        $cont++;
                                    }
                                    //  }
                                    ?>   
                                        </div>
                                </div>
                            </div>
                        </div>
                   </div>
                 
                   
                   <div class="col-lg-4">
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Projetos Cancelados</h4>
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
                                    <div class="social-sizes">
                                        <?php
                                    $usuario = $this->session->userdata('user_id');
                                    $projetos_user = $this->site->getAllProjetosStatusUsers($usuario, 'CANCELADO');
                                    $cont = 1;
                                    $qtde_perfis_user = 0;
                                    foreach ($projetos_user as $item) {
                                        $id_projeto = $item->projeto;
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
                                            $id_projeto = $projeto->id;
                                            $tipos = $this->projetos_model->getAllTipoEventosProjeto($id_projeto,'ordem','asc');
                                            foreach ($tipos as $tipo) {
                                                $tipo_evento = $tipo->tipo;


                                                $ordem = 'ordem';
                                                $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento, $id_projeto, $ordem, 'asc');
                                                foreach ($eventos as $evento) {


                                                    $soma_acoes_evento = 0;
                                                    $cont_qtde_item_evento = 0;
                                                    $intes_eventos2 = $this->projetos_model->getAllItemEventosProjeto($evento->id, 'tipo', 'asc');
                                                    foreach ($intes_eventos2 as $item2) {

                                                        $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item2->id);

                                                        //Qtde de Ações concluídas
                                                        $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item2->id);
                                                        $quantidade_acoes_concluidas_item = $concluido->quantidade;
                                                        $coma_total_acoes_concluidas_itens += $quantidade_acoes_concluidas_item;

                                                        //Qtde de ações Pendentes
                                                        $item_pendente = $this->projetos_model->getAcoesPendentesByItemEvento($item2->id);
                                                        $item_avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item2->id);
                                                        $itens_pendentes = $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                        $soma_porc_acoes_pendentes_fase += $itens_pendentes;

                                                        $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item2->id);
                                                        $acoes_atrasadas = $atrasadas->quantidade;

                                                        $soma_porc_acoes_atrasadas_fase += $acoes_atrasadas;

                                                        $cont_qtde_item_fase += $quantidade_acoes_item->quantidade;



                                                        /*
                                                         *  SOMA AS PORCENTAGENS DO ÍTEM PARA GERAR A MÉDIA DO EVENTO
                                                         */


                                                        $cont_qtde_item_evento ++;
                                                        // $soma_valores_zerado ++;
                                                        $cont_qtde_item_evento_fase ++;

                                                        if ($quantidade_acoes_item->quantidade == 0) {
                                                            //$cont_qtde_item_fase += 1;
                                                        }
                                                    }


                                                    if ($cont_qtde_item_evento == 0) {

                                                        $soma_valores_zerado += 1;
                                                    } else {
                                                        $soma_itens_sem_acao += $cont_qtde_item_evento;
                                                    }
                                                }
                                            }
                                            //echo $soma_porc_acoes_atrasadas_fase;

                                            $total_acoes_projeto = $cont_qtde_item_fase + $soma_itens_sem_acao + $soma_valores_zerado;
                                            $total_acoes_pendentes = $soma_porc_acoes_pendentes_fase + $soma_valores_zerado + $soma_itens_sem_acao;

                                            // $total_atrasado = $coma_total_acoes_concluidas_itens - $total_acoes_pendentes;


                                            $porc_concluido = ($coma_total_acoes_concluidas_itens * 100) / $total_acoes_projeto;
                                            $porc_pendente = ($total_acoes_pendentes * 100) / $total_acoes_projeto;
                                            $porc_atrasado = ($soma_porc_acoes_atrasadas_fase * 100) / $total_acoes_projeto;
                                            ?>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido; ?>%">
                                        <?php if ($porc_concluido != 100) {
                                            echo substr($porc_concluido, 0, 2);
                                        } else {
                                            echo $porc_concluido;
                                        } ?> % Concluído
                                            </div>
                                            <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente; ?>%">
                                        <?php if ($porc_pendente != 100) {
                                            echo substr($porc_pendente, 0, 2);
                                        } else {
                                            echo $porc_pendente;
                                        } ?>% Pendente
                                            </div>
                                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $porc_atrasado; ?>%">
                                        <?php if ($porc_atrasado != 100) {
                                            echo substr($porc_atrasado, 0, 2);
                                        } else {
                                            echo $porc_atrasado;
                                        } ?>% Atrasado
                                            </div>
                                        </div>
                                       
                                          <hr>
                                        <?php
                                        $cont++;
                                    }
                                    //  }
                                    ?>   
                                        </div>
                                </div>
                            </div>
                        </div>
                   </div>
                </div>
                <!-- /.row -->

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
