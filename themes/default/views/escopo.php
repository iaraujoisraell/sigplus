<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<!DOCTYPE html>
<html lang="en">

    <?php $this->load->view($this->theme . 'menu_head'); ?>
   
 
  <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/AdminLTE.min.css">
 
  
    
    <body>

        <div id="wrapper">

            <div id="page-wrapper">

                <div class="page-content">

                    <div class="row">
                        <?php  $this->load->view($this->theme . 'status_projeto'); ?>
                    </div>



                <?php
                $usuario = $this->session->userdata('user_id');
                $projetos = $this->site->getProjetoAtualByID_completo($usuario);

                /*
                 * VERIFICA SE O USUÁRIO TEM PERMISSAO PARA ACESSAR O MENU EXIBIDO
                 */
                $permissoes = $this->projetos_model->getPermissoesByPerfil($projetos->group_id);
                $permissao_projetos = $permissoes->projetos_index;
                $permissao_atas = $permissoes->atas_index;
                $permissao_participantes = $permissoes->participantes_index;
                $permissao_eventos = $permissoes->eventos_index;

                $permissao_acoes = $permissoes->acoes_index;
                $permissao_avalidacao = $permissoes->acoes_aguardando_validacao_index;
                $permissao_apendentes = $permissoes->acoes_pendentes_index;


                $permissao_dashboard = $permissoes->dashboard_index;

                /*
                 * CADASTRO
                 */
                $permissao_cadastro = $permissoes->cadastro;
                $permissao_pesquisa_satisfacao = $permissoes->pesquisa_satisfacao_index;
                $permissao_categoria_financeira = $permissoes->categoria_financeira_index;
                $permissao_setores = $permissoes->setores_index;
                $permissao_perfil_acesso = $permissoes->perfil_acesso;
                /*
                 * RELATÓRIO
                 */
                $permissao_relatorios = $permissoes->relatorios;
                $permissao_status_report = $permissoes->status_report;
                $permissao_users_acoes_atrasadas = $permissoes->users_acoes_atrasadas;
                /*
                 * PESSOAS
                 */
                $permissao_cadastro_pessoas = $permissoes->cadastro_pessoas;
                $permissao_usuarios = $permissoes->users_index;
                $permissao_gestores = $permissoes->lista_gestores;
                $permissao_suporintendentes = $permissoes->lista_superintendente;
                $permissao_fornecedor = $permissoes->fornecedores_index;
                $lista_participantes = $permissoes->lista_participantes;


                /*
                 * GESTAO DE CUSTO
                 */
                $permissao_gestao_custo = $permissoes->gestao_custo;
                $permissao_contas_pagar = $permissoes->contas_pagar;

                /*
                 * CALENDÁRIO
                 */
                $permissao_calendario = $permissoes->calendario;
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
                                        <h4>Escopo do Projeto</h4>
                                    </div>
                                    <div class="portlet-widgets">


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
                                                foreach ($tipos as $tipo) {

                                                    $tipo_evento = $tipo->tipo;

                                                    $soma_porc_acoes_concluidas_fase = 0;
                                                    $soma_porc_acoes_pendentes_fase = 0;
                                                    $soma_porc_acoes_atrasadas_fase = 0;
                                                    $cont_qtde_item_fase = 0;
                                                    $cont_qtde_item_evento_fase = 0;
                                                    $coma_total_acoes_itens = 0;
                                                    $coma_total_acoes_concluidas_itens = 0;
                                                    $soma_valores_zerado = 0;


                                                    $ordem = 'ordem';
                                                    $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento, $projetos->projeto_atual, $ordem, 'asc');
                                                    foreach ($eventos as $evento) {
                                                        $id_evento = $evento->id;
                                                        $data_inicio_evento = $this->sma->hrld($evento->data_inicio);
                                                        $data_fim_evento = $this->sma->hrld($evento->data_fim);
                                                        $responsavel_tecnico_id = $evento->responsavel;
                                                        $resp_tecnico = $this->site->getUser($responsavel_tecnico_id);

                                                        $soma_acoes_item2 = 0;
                                                        $soma_acoes_evento = 0;
                                                        $cont_qtde_item_evento = 0;
                                                        $intes_eventos2 = $this->projetos_model->getAllItemEventosProjeto($evento->id, 'tipo', 'asc');
                                                        foreach ($intes_eventos2 as $item2) {

                                                            $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item2->id);

                                                            //Qtde de Ações concluídas
                                                            $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item2->id);
                                                            $quantidade_acoes_concluidas_item = $concluido->quantidade;
                                                            $porc_acoes_concluidas_itens = ($quantidade_acoes_concluidas_item * 100) / $quantidade_acoes_item->quantidade;

                                                            $coma_total_acoes_concluidas_itens += $quantidade_acoes_concluidas_item;


                                                            //Qtde de ações Pendentes
                                                            $item_pendente = $this->projetos_model->getAcoesPendentesByItemEvento($item2->id);
                                                            $item_avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item2->id);
                                                            $itens_pendentes = $item_pendente->quantidade + $item_avalidacao->quantidade;

                                                            if ($quantidade_acoes_item->quantidade == 0) {
                                                                $itens_pendentes = 1;
                                                            }

                                                            $porc_itens_pendentes = ($itens_pendentes * 100) / $quantidade_acoes_item->quantidade;


                                                            $soma_porc_acoes_pendentes_fase += $itens_pendentes;

                                                            $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item2->id);
                                                            if ($atrasadas->quantidade) {
                                                                $porc_atrasado_itens = ($atrasadas->quantidade * 100) / $quantidade_acoes_item->quantidade;
                                                                $acoes_atrasadas = $atrasadas->quantidade;
                                                                $soma_porc_acoes_atrasadas_fase += $acoes_atrasadas;
                                                            } else {
                                                                $porc_atrasado_itens = 0;
                                                            }


                                                            $cont_qtde_item_fase += $quantidade_acoes_item->quantidade;
                                                            if ($quantidade_acoes_item->quantidade == 0) {
                                                                $cont_qtde_item_fase = $cont_qtde_item_fase + 1;
                                                            }




                                                            /*
                                                             *  SOMA AS PORCENTAGENS DO ÍTEM PARA GERAR A MÉDIA DO EVENTO
                                                             */

                                                            //   $soma_porc_acoes_pendentes_eventos  += $porc_itens_pendentes;
                                                            //  $soma_porc_acoes_atrasadas_eventos  += $porc_atrasado_itens;
                                                            $cont_qtde_item_evento ++;

                                                            $cont_qtde_item_evento_fase ++;
                                                        }




                                                        if ($cont_qtde_item_evento == 0) {

                                                            $soma_valores_zerado += 1;
                                                        } else {
                                                            //   $soma_itens_sem_acao += $cont_qtde_item_evento;
                                                        }

                                                        $soma_acoes_evento += $cont_qtde_item_fase + $soma_valores_zerado;
                                                    }

                                                    // echo $soma_valores_zerado.'<br>';

                                                    $total_acoes_projeto = $soma_acoes_evento + $soma_itens_sem_acao + $soma_valores_zerado;

                                                    // $total_acoes_projeto = $cont_qtde_item_fase + $soma_itens_sem_acao + $soma_valores_zerado;
                                                    $total_acoes_pendentes = $soma_porc_acoes_pendentes_fase + $soma_valores_zerado;

                                                    // $porc_concluido = ($coma_total_acoes_concluidas_itens * 100)/$soma_acoes_evento;
                                                    $porc_pendente = ($total_acoes_pendentes * 100) / $soma_acoes_evento;
                                                    $porc_atrasado = ($soma_porc_acoes_atrasadas_fase * 100) / $soma_acoes_evento;
                                                    //  echo $porc_atrasado;
                                                    $porc_concluido = ($coma_total_acoes_concluidas_itens * 100) / $soma_acoes_evento;
                                                    ?>


                                                    <div class="box box-default collapsed-box box-solid">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title"><?php echo $tipo->tipo; ?></h3>
                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div  class="progress">
                                                            <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_concluido; ?>%">
                                                                <?php
                                                                if ($porc_concluido != 100) {
                                                                    echo substr($porc_concluido, 0, 4);
                                                                } else {
                                                                    echo $porc_concluido;
                                                                }
                                                                ?> % Concluído
                                                            </div>
                                                            <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_pendente; ?>%">
                                                                <?php
                                                                if ($porc_pendente != 100) {
                                                                    echo substr($porc_pendente, 0, 4);
                                                                } else {
                                                                    echo $porc_pendente;
                                                                }
                                                                ?>% Pendente
                                                            </div>
                                                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $porc_atrasado; ?>%">
                                                                <?php
                                                                if ($porc_atrasado != 100) {
                                                                    echo substr($porc_atrasado, 0, 4);
                                                                } else {
                                                                    echo $porc_atrasado;
                                                                }
                                                                ?>% Atrasado
                                                            </div>
                                                        </div>
                                                        <!-- /.box-header -->
                                                        <div id="<?php echo $tipo_evento . $id_evento; ?>" class="box-body">
                                                            <div class="portlet-body">

                                                                <?php
                                                                $cont_evento = 1;
                                                                 $somaqtde_pendente = 0;
                                                                $ordem = 'ordem';
                                                                $eventos = $this->projetos_model->getAllEventosProjetoByTipo($tipo_evento, $projetos->projeto_atual, $ordem, 'asc');
                                                                foreach ($eventos as $evento) {

                                                                    $data_inicio_evento = $this->sma->hrld($evento->data_inicio);
                                                                    $data_fim_evento = $this->sma->hrld($evento->data_fim);
                                                                    $responsavel_tecnico_id = $evento->responsavel;
                                                                    $resp_tecnico = $this->site->getUser($responsavel_tecnico_id);
                                                                    
                                                                    $soma_porc_acoes_aguardandovalidacao = 0;
                                                                    $soma_porc_acoes_concluidas_eventos = 0;
                                                                    $soma_porc_acoes_pendentes_eventos = 0;
                                                                    $soma_porc_acoes_atrasadas_eventos = 0;
                                                                    $cont_qtde_item_evento = 0;
                                                                    $soma_acoes_itens = 0;
                                                                   
                                                                    $intes_eventos2 = $this->projetos_model->getAllItemEventosProjeto($evento->id, 'tipo', 'asc');
                                                                    foreach ($intes_eventos2 as $item2) {
                                                                        $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item2->id);

                                                                        //Qtde de Ações concluídas
                                                                        $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item2->id);
                                                                        $quantidade_acoes_concluidas_item = $concluido->quantidade;
                                                                        //$porc_acoes_concluidas_itens = ($quantidade_acoes_concluidas_item * 100)/$quantidade_acoes_item->quantidade;
                                                                        //Qtde de ações Pendentes
                                                                        $item_avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item2->id);
                                                                        if($item_avalidacao){
                                                                            $avaliacao_item = $item_avalidacao->quantidade;
                                                                        }
                                                                        
                                                                        $item_pendente = $this->projetos_model->getAcoesPendentesByItemEvento($item2->id);
                                                                        if($item_pendente){
                                                                            $qtde_pendente = $item_pendente->quantidade;
                                                                        }
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        //$porc_itens_pendentes = ($itens_pendentes2 * 100) / $quantidade_acoes_item->quantidade;
                                                                        
                                                                        $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item2->id);
                                                                        $quantidade_acoes_atrasadas = $atrasadas->quantidade;
                                                                        if ($atrasadas->quantidade) {
                                                                            $porc_atrasado_itens = ($atrasadas->quantidade * 100) / $quantidade_acoes_item->quantidade;
                                                                            $acoes_atrasadas = $atrasadas->quantidade;
                                                                            $soma_porc_acoes_atrasadas_fase += $acoes_atrasadas;
                                                                        } else {
                                                                            $porc_atrasado_itens = 0;
                                                                        }

                                              


                                                                        $data_inicio = $this->sma->hrld($item2->dt_inicio);

                                                                        $soma_acoes_itens += $quantidade_acoes_item->quantidade;

                                                                        /*
                                                                         *  SOMA AS PORCENTAGENS DO ÍTEM PARA GERAR A MÉDIA DO EVENTO
                                                                         */



                                                                        if ($quantidade_acoes_item->quantidade == 0) {
                                                                            $soma_acoes_itens += 1;
                                                                            $qtde_pendente = 1;
                                                                        }
                                                                        //echo $cont_qtde_item_evento_fase.'<br>';
                                                                        /*
                                                                         *  SOMA AS PORCENTAGENS DO ÍTEM PARA GERAR A MÉDIA DO EVENTO
                                                                         */
                                                                        
                                                                        $soma_porc_acoes_aguardandovalidacao += $avaliacao_item;
                                                                        
                                                                        $soma_porc_acoes_concluidas_eventos += $quantidade_acoes_concluidas_item;
                                                                        $soma_porc_acoes_pendentes_eventos += $qtde_pendente + $avaliacao_item;
                                                                        $soma_porc_acoes_atrasadas_eventos += $quantidade_acoes_atrasadas;
                                                                        $cont_qtde_item_evento ++;
                                                                    }
                                                                    
                                                                    
                                                                    $total_acoes = $soma_acoes_itens;

                                                                    if ($soma_acoes_itens == 0) {
                                                                    //    $total_acoes++;
                                                                    }



                                                                    $porcentagem_concluido_evento = ($soma_porc_acoes_concluidas_eventos * 100) / $total_acoes;
                                                                    $porcentagem_pendente_evento = ($soma_porc_acoes_pendentes_eventos * 100) / $total_acoes;
                                                                    $porcentagem_atrasado_evento = ($soma_porc_acoes_atrasadas_eventos * 100) / $total_acoes;
                                                                    ?>
                                                                
                                                                <div class="box box-default collapsed-box box-solid">
                                                                    <div class="box-header with-border">
                                                                      <h3 class="box-title"><font style="font-size: 18px;"><?php echo $cont_evento . ' - ' . $evento->nome_evento; ?></font>  <?php echo '  ( de :  ' . substr($data_inicio_evento, 0, 10) . ' à  ' . substr($data_fim_evento, 0, 10) . ').  Resp. Técnico : ' . $resp_tecnico->first_name . ' ' . $resp_tecnico->last_name; ?></h3>

                                                                      <div class="box-tools pull-right">
                                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                        </button>
                                                                      </div>
                                                                      <!-- /.box-tools -->
                                                                    </div>
                                                                    <div class="progress">
                                                                                        <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porcentagem_concluido_evento; ?>%">
                                                                                            <?php
                                                                                            if ($porcentagem_concluido_evento != 100) {
                                                                                                echo substr($porcentagem_concluido_evento, 0, 2);
                                                                                            } else {
                                                                                                echo $porcentagem_concluido_evento;
                                                                                            }
                                                                                            ?> % Concluído
                                                                                        </div>
                                                                                        <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porcentagem_pendente_evento; ?>%">
                                                                                            <?php
                                                                                            if ($porcentagem_pendente_evento != 100) {
                                                                                                echo substr($porcentagem_pendente_evento, 0, 2);
                                                                                            } else {
                                                                                                echo $porcentagem_pendente_evento;
                                                                                            }
                                                                                            ?>% Em Andamento
                                                                                        </div>
                                                                                        <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $porcentagem_atrasado_evento; ?>%">
                                                                                            <?php
                                                                                            if ($porcentagem_atrasado_evento != 100) {
                                                                                                echo substr($porcentagem_atrasado_evento, 0, 2);
                                                                                            } else {
                                                                                                echo $porcentagem_atrasado_evento;
                                                                                            }
                                                                                            ?>% Atrasado
                                                                                        </div>

                                                                                    </div>
                                                                    <!-- /.box-header -->
                                                                    <div class="box-body">
                                                                            <?php
                                                                            $intes_eventos = $this->projetos_model->getAllItemEventosProjeto($evento->id, 'tipo', 'asc');
                                                                            $cont = 1;
                                                                            ?>
                                                                         <div class="portlet-body">
                                                                             <div class="table-responsive">
                                                                                <table style="width:100%;" id="table" class="table" >
                                                                        <thead>
                                                                            <tr>

                                                                                <td style="width:20%;  font-size: 16px; font-weight: bold; "> Itens </td>
                                                                                <td style="width:45%; font-size: 16px; font-weight: bold;">Status </td>
                                                                                <td style="width:5%; font-size: 16px; font-weight: bold; ">T. Ação </td>
                                                                                <td style="width:5%; font-size: 16px; font-weight: bold;"> Concluídas </td>
                                                                                <td style="width:5%; font-size: 16px; font-weight: bold; ">Pendentes </td>
                                                                                <td style="width:5%; font-size: 16px; font-weight: bold; ">Atrasadas </td>
                                                                                <td style="width:5%; font-size: 16px; font-weight: bold;">Visualizar </td>
                                                                            </tr>
                                                                        </thead>
                                                                    

                                                                        <?php
                                                                        $cont_item_evento = 1;
                                                                        foreach ($intes_eventos as $item) {
                                                                        $quantidade_acoes_item = $this->projetos_model->getQuantidadeAcaoByItemEvento($item->id);

                                                                        //Qtde de Ações concluídas
                                                                        $concluido = $this->projetos_model->getAcoesConcluidasByPItemEvento($item->id);
                                                                        $quantidade_acoes_concluidas_item = $concluido->quantidade;
                                                                        $porc_acoes_concluidas_itens = ($quantidade_acoes_concluidas_item * 100) / $quantidade_acoes_item->quantidade;

                                                                        //Qtde de ações Pendentes
                                                                        $item_pendente = $this->projetos_model->getAcoesPendentesByItemEvento($item->id);
                                                                        $item_avalidacao = $this->projetos_model->getAcoesAguardandoValidacaoByItemEvento($item->id);
                                                                        $itens_pendentes = $item_pendente->quantidade + $item_avalidacao->quantidade;
                                                                        $porc_itens_pendentes = ($itens_pendentes * 100) / $quantidade_acoes_item->quantidade;


                                                                        $atrasadas = $this->projetos_model->getAcoesAtrasadasByItemEvento($item->id);
                                                                        if ($atrasadas->quantidade) {
                                                                            $porc_atrasado_itens = ($atrasadas->quantidade * 100) / $quantidade_acoes_item->quantidade;
                                                                        } else {
                                                                            $porc_atrasado_itens = 0;
                                                                        }
                                                                        
                                                                        
                                                                        
                                                                        if($porc_itens_pendentes){
                                                                            $porc_itens_pendentes = 100 - $porc_acoes_concluidas_itens - $porc_atrasado_itens;
                                                                        }

                                                                        $data_inicio = $this->sma->hrld($item->dt_inicio);
                                                                        $data_fim = $this->sma->hrld($item->dt_fim);
                                                                        ?>
                                                                                        <tr>
                                                                                            <td style="width:20%; text-align: justify  ">
                                                                                                <font style="width: 70%; text-align: justify;"><?php echo $cont_evento . '.' . $cont_item_evento . ' - ' . $item->descricao; ?> </font>
                                                                                            </td>
                                                                                             <td style="width:45%; ">
                                                                                                <div class="progress">
                                                                                                                <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?php echo $porc_acoes_concluidas_itens; ?>%">
                                                                                                                    <?php
                                                                                                                    if ($porc_acoes_concluidas_itens != 100) {
                                                                                                                        echo substr($porc_acoes_concluidas_itens, 0, 2);
                                                                                                                    } else {
                                                                                                                        echo $porc_acoes_concluidas_itens;
                                                                                                                    }
                                                                                                                    ?> % Concluído
                                                                                                                </div>
                                                                                                                <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?php echo $porc_itens_pendentes; ?>%">
                                                                                                                    <?php
                                                                                                                    if ($porc_itens_pendentes != 100) {
                                                                                                                        echo substr($porc_itens_pendentes, 0, 2);
                                                                                                                    } else {
                                                                                                                        echo $porc_itens_pendentes;
                                                                                                                    }
                                                                                                                    ?>% Em Andamento
                                                                                                                </div>
                                                                                                                <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?php echo $porc_atrasado_itens; ?>%">
                                                                                                                    <?php
                                                                                                                    if ($porc_atrasado_itens != 100) {
                                                                                                                        echo substr($porc_atrasado_itens, 0, 2);
                                                                                                                    } else {
                                                                                                                        echo $porc_atrasado_itens;
                                                                                                                    }
                                                                                                                    ?>% Atrasado
                                                                                                                </div>
                                                                                                            </div>
                                                                                            </td>
                                                                                            <td style="width:5%; ">
                                                                                                <font style="color: #000000; font-weight: bold"><?php echo $quantidade_acoes_item->quantidade; ?></font>
                                                                                            </td>
                                                                                            <td style="width:5%; ">
                                                                                                <font style="color: green; font-weight: bold"><?php echo  $quantidade_acoes_concluidas_item; ?></font>
                                                                                            </td>
                                                                                            <td style="width:5%;">
                                                                                              <font style="color: orange; font-weight: bold"><?php echo $itens_pendentes; ?></font>
                                                                                            </td>
                                                                                            <td style="width:5%;">
                                                                                               <font style="color: red; font-weight: bold"><?php echo $atrasadas->quantidade; ?></font>
                                                                                            </td>
                                                                                            <td style="width:5%;">
                                                                                               <a target="_blank" href="<?= site_url('Login_Projetos/eventos_acoes/' . $item->id) ?>" class="btn btn-default">Visualizar <i class="fa fa-chevron-circle-right"></i></a>
                                                                                            </td>

                                                                                        </tr>


            <?php
            $cont_item_evento++;
        }
        ?>


                                                                    </table>
                                                                             </div>
                                                                         </div>    
                                                                    </div>
                                                                    <!-- /.box-body -->
                                                                  </div>
                                                                
                                                                              
  
                                                                    <br>

        <?php
        $cont_evento++;
    }
    ?>

                                                            </div>
                                                        </div>
                                                        <!-- /.box-body -->
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
                    <script src="<?= $assets ?>bi/dist/js/adminlte.min.js"></script>
                    </body>

                    </html>
