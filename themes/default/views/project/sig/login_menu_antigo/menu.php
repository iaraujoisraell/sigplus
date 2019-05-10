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
                    <div class="col-lg-6">
                        
                        <div class="portlet portlet-green">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Monitoramento e Controle</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#monitoramentoControle"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="monitoramentoControle" class="panel-collapse collapse in">
                                <div class="portlet-body">

                                    
                                   
                                    <div class="row">
                                     <!-- DASHBOARD -->
                                        <?php if ($permissao_dashboard){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('projetos/dashboard/' . $projetos->projeto_atual) ?>">
                                                    <div class="circle-tile-heading btn-pinterest">
                                                        <i class="fa fa-dashboard fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-pinterest">
                                                    <div class="circle-tile-description text-faded">
                                                        Dashboard
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('projetos/dashboard/' . $projetos->projeto_atual) ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                      <?php if ($projetos->projeto_atual == 4){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/dashboard_helpdesk/' . $projetos->projeto_atual) ?>">
                                                    <div class="circle-tile-heading btn-facebook">
                                                        <i class="fa fa-dashboard fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-facebook">
                                                    <div class="circle-tile-description text-faded">
                                                        Dashboard Helpdesk
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/dashboard_helpdesk/' . $projetos->projeto_atual) ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                     
                                     <?php if (($usuario == 2)||($usuario == 18)){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/controleAtividades') ?>">
                                                    <div style="background-color: orange;" class="circle-tile-heading ">
                                                        <i class="fa fa-flash fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div style="background-color: orange;" class="circle-tile-content ">
                                                    <div class="circle-tile-description text-faded">
                                                        Controle das Atividades
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/controleAtividades') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                     
                                        <!-- FIM DASHBOARD -->
                                           <?php if ($permissao_dashboard){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/marcos_projeto/' . $projetos->projeto_atual) ?>">
                                                    <div class="circle-tile-heading btn-green">
                                                        <i class="fa fa-calendar fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-green">
                                                    <div class="circle-tile-description text-faded">
                                                        Calendário dos Marcos do Projeto
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/marcos_projeto/' . $projetos->projeto_atual) ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <!-- CRONOGRAMA -->
                                        <?php if ($permissao_projetos){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/escopo/'. $projetos->projeto_atual) ?>">
                                                    <div class="circle-tile-heading gray">
                                                        <i class="fa fa-list-ol fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content gray">
                                                    <div class="circle-tile-description text-faded">
                                                        Escopo
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                        <span id="sparklineA"></span>
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/escopo/'. $projetos->projeto_atual) ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        <!-- CRONOGRAMA -->
                                        <?php if ($permissao_projetos){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/escopo_slider/'. $projetos->projeto_atual) ?>">
                                                    <div class="circle-tile-heading purple">
                                                        <i class="fa fa-list-ol fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content purple">
                                                    <div class="circle-tile-description text-faded">
                                                        Escopo/ Cronograma 
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                        <span id="sparklineA"></span>
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/escopo_slider/'. $projetos->projeto_atual) ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                         <?php if ($permissao_dashboard){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/escopo_resumido/'. $projetos->projeto_atual) ?>">
                                                    <div class="circle-tile-heading gray">
                                                        <i class="fa fa-list fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content gray">
                                                    <div class="circle-tile-description text-faded">
                                                        Cronograma Resumido
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                        <span id="sparklineA"></span>
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/escopo_resumido/'. $projetos->projeto_atual) ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <!-- FIM CRONOGRAMA -->
                                        <?php if ($permissao_dashboard){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/eap/'.$projetos->projeto_atual) ?>">
                                                    <div class="circle-tile-heading btn-primary">
                                                        <i class="fa fa-sitemap fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-primary">
                                                    <div class="circle-tile-description text-faded">
                                                       EAP
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/eap/'.$projetos->projeto_atual) ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    <!-- FIM CRONOGRAMA -->
                                        <?php if ($permissao_dashboard){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/treinamentos_projeto/'.$projetos->projeto_atual) ?>">
                                                    <div class="circle-tile-heading btn-green">
                                                        <i class="fa fa-pencil fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-green">
                                                    <div class="circle-tile-description text-faded">
                                                       TREINAMENTOS
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/treinamentos_projeto/'.$projetos->projeto_atual) ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                         <?php if ($permissao_pesquisa_satisfacao){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/equipe') ?>">
                                                    <div class="circle-tile-heading btn-facebook">
                                                        <i class="fa fa-users fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-facebook">
                                                    <div class="circle-tile-description text-faded">
                                                       Equipe
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/equipe') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    
                                      <?php if ($projetos->projeto_atual == 1){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/migracao') ?>">
                                                    <div class="circle-tile-heading btn-facebook">
                                                        <i class="fa fa-crosshairs fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-facebook">
                                                    <div class="circle-tile-description text-faded">
                                                       MIGRAÇÃO
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/migracao') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    
                                      <?php if ($projetos->projeto_atual == 13){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/reajuste_cadastro') ?>">
                                                    <div class="circle-tile-heading btn-facebook">
                                                        <i class="fa fa-crosshairs fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-facebook">
                                                    <div class="circle-tile-description text-faded">
                                                       CADASTRO MATERIAIS - ACOPANHAMENTO
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/reajuste_cadastro') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    
                                     <?php if ($permissao_pesquisa_satisfacao){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/documentos') ?>">
                                                    <div class="circle-tile-heading btn-green">
                                                        <i class="fa fa-clipboard fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-green">
                                                    <div class="circle-tile-description text-faded">
                                                       DOCUMENTOS
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/documentos') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    
                                         <?php if ($projetos->projeto_atual == 1){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/inconsistencias') ?>">
                                                    <div class="circle-tile-heading btn-pinterest">
                                                        <i class="fa fa-crosshairs fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-pinterest">
                                                    <div class="circle-tile-description text-faded">
                                                       MIGRAÇÃO INCONSISTÊNCIAS
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/inconsistencias') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                   
                                   
                              
                                </div>
                            </div>
                        </div>
                        
                              
                         <?php if ($permissao_gestao_custo){ ?>
                            <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Gestão de Contratos</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#buttonDropdowns"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="buttonDropdowns" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div class="row">
                                     
                                        <?php if ($permissao_contas_pagar){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('financeiro') ?>">
                                                    <div class="circle-tile-heading btn-vk">
                                                        <i class="fa fa-list fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-vk">
                                                    <div class="circle-tile-description text-faded">
                                                       TÍTULOS
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('financeiro') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_contas_pagar){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Contratos') ?>">
                                                    <div class="circle-tile-heading btn-google-plus">
                                                        <i class="fa fa-pencil fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-google-plus">
                                                    <div class="circle-tile-description text-faded">
                                                       Contratos
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Contratos') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                         <?php if ($permissao_fornecedor){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('suppliers') ?>">
                                                    <div class="circle-tile-heading btn-facebook">
                                                        <i class="fa fa-users fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-facebook">
                                                    <div class="circle-tile-description text-faded">
                                                       Fornecedores
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('suppliers') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                            <div class="portlet portlet-green">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Gestão de Custo</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#gestaoCusto"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="gestaoCusto" class="panel-collapse collapse in">
                                <div class="portlet-body">

                                    
                                   
                                    <div class="row">
                                     <?php if ($permissao_categoria_financeira){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="#">
                                                    <div class="circle-tile-heading btn-github">
                                                        <i class="fa fa-list-alt fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-github">
                                                    <div class="circle-tile-description text-faded">
                                                       Categorias
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="#" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_contas_pagar){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('financeiro') ?>">
                                                    <div class="circle-tile-heading btn-green">
                                                        <i class="fa fa-money fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-green">
                                                    <div class="circle-tile-description text-faded">
                                                       Despesas
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('financeiro') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                   
                                        
                                    </div>
                                   
                                   
                              
                                </div>
                            </div>
                        </div>
                             <?php } ?>
                          <?php if ($permissao_cadastro){ ?>
                            <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Cadastros</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#cadastro"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="cadastro" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div class="row">
                                     <?php if ($permissao_pesquisa_satisfacao){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>">
                                                    <div class="circle-tile-heading btn-google-plus">
                                                        <i class="fa fa-bar-chart-o fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-google-plus">
                                                    <div class="circle-tile-description text-faded">
                                                       Avaliação
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_setores){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>">
                                                    <div class="circle-tile-heading btn-github">
                                                        <i class="fa fa-crosshairs fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-github">
                                                    <div class="circle-tile-description text-faded">
                                                       Setores
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_setores){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/modulos') ?>">
                                                    <div class="circle-tile-heading btn-vk">
                                                        <i class="fa fa-puzzle-piece fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-vk">
                                                    <div class="circle-tile-description text-faded">
                                                       Módulos
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/modulos') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                         <?php if ($permissao_perfil_acesso){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>">
                                                    <div class="circle-tile-heading btn-github">
                                                        <i class="fa fa-unlock-alt fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-github">
                                                    <div class="circle-tile-description text-faded">
                                                       Perfil/Acesso
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                         <?php if ($permissao_pesquisa_satisfacao){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('cadastros/equipe') ?>">
                                                    <div class="circle-tile-heading btn-facebook">
                                                        <i class="fa fa-users fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-facebook">
                                                    <div class="circle-tile-description text-faded">
                                                       Equipe
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('cadastros/equipe') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_setores){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/post_index') ?>">
                                                    <div class="circle-tile-heading btn-green">
                                                        <i class="fa fa-inbox fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-green">
                                                    <div class="circle-tile-description text-faded">
                                                       Postagem
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Login_Projetos/post_index') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                          <?php if ($permissao_pesquisa_satisfacao){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>">
                                                    <div class="circle-tile-heading btn-google-plus">
                                                        <i class="fa fa-bar-chart-o fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-google-plus">
                                                    <div class="circle-tile-description text-faded">
                                                       Período H. Extra
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('cadastros/pesquisa_satisfacao') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <?php } ?>
                        
                         <?php if ($permissao_relatorios){ ?>
                            <div class="portlet portlet-orange">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Relatórios</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#relatorio"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="relatorio" class="panel-collapse collapse in">
                                <div class="portlet-body">

                                    
                                   
                                     
                                   
                                    
                                    <div class="row">
                                        <?php if ($permissao_status_report){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Reports/status_report') ?>">
                                                    <div class="circle-tile-heading orange">
                                                        <i class="fa fa-signal fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content orange">
                                                    <div class="circle-tile-description text-faded">
                                                       Status Report
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Reports/status_report') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                     
                                     <?php if ($permissao_users_acoes_atrasadas){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a title="Usuários com Ações Atrasadas" href="<?= site_url('Reports/usuariosComAcoesAtrasadas') ?>">
                                                    <div class="circle-tile-heading btn-pinterest">
                                                        <i class="fa fa-warning fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-pinterest">
                                                    <div class="circle-tile-description text-faded">
                                                       Ações Atrasadas
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Reports/usuariosComAcoesAtrasadas') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                      </div>  
                                    
                                    <?php } ?>
                                   
                                   
                              
                                </div>
                            </div>
                        </div>
                    </div>
                      <?php } ?>
                   
                   
                   <!-- /LADO ESQUERDO -->
                    <div class="col-lg-6">
                          <div class="col-lg-12">
                        <!-- MEUS PROJETOS -->
                        <div class="portlet portlet-default">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>CALENDÁRIO DE MARCOS DO PROJETO</h4>
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
                                    <div class="col-lg-12">
                                        <div class="portlet portlet-default">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                  
                                </div>
                                </div>
                            </div>
                         </div>
                     </div>
                        </div>
                   
                   
                   
                <?php if ($permissao_dashboard){ ?>
                    <div class="col-lg-6">
                          <div class="portlet portlet-green">
                            <div class="portlet-heading">
                                <div class="portlet-title">
                                    <h4>Meus Acessos</h4>
                                </div>
                                <div class="portlet-widgets">
                                    <a href="#"><i class="fa fa-gear"></i></a>
                                    <span class="divider"></span>
                                    <a href="javascript:;"><i class="fa fa-refresh"></i></a>
                                    <span class="divider"></span>
                                    <a data-toggle="collapse" data-parent="#accordion" href="#buttonGroups"><i class="fa fa-chevron-down"></i></a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div id="buttonGroups" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                  
                                    <div class="row">
                                       
                                        <!-- PROJETO -->
                                        <?php //if (($usuario == 2) || ($usuario == 18)){ ?>
                                         <?php if ($permissao_atas){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('projetos') ?>">
                                                    <div class="circle-tile-heading btn-tumblr">
                                                        <i class="fa fa-folder fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-tumblr">
                                                    <div class="circle-tile-description text-faded">
                                                        Projetos
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                        <span id="sparklineA"></span>
                                                    </div>
                                                    <a href="<?= site_url('projetos') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                         <?php //} ?>
                                        <!-- /PROJETO -->
                                        <?php if ($permissao_atas){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('atas') ?>">
                                                    <div class="circle-tile-heading btn-bitbucket">
                                                        <i class="fa fa-list fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-bitbucket">
                                                    <div class="circle-tile-description text-faded">
                                                        Atas
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                        <span id="sparklineA"></span>
                                                    </div>
                                                    <a href="<?= site_url('atas') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_eventos){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Projetos/Eventos_index') ?>">
                                                    <div class="circle-tile-heading btn-facebook">
                                                        <i class="fa fa-list-alt fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-facebook">
                                                    <div class="circle-tile-description text-faded">
                                                       Eventos
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Projetos/Eventos_index') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        <?php if ($permissao_eventos){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Projetos/Marcos_eventos_index') ?>">
                                                    <div class="circle-tile-heading btn-dropbox">
                                                        <i class="fa fa-calendar fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-dropbox">
                                                    <div class="circle-tile-description text-faded">
                                                       Marcos
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Projetos/Marcos_eventos_index') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        <?php if ($permissao_eventos){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Projetos/gestao_documentacao_index') ?>">
                                                    <div class="circle-tile-heading btn-orange">
                                                        <i class="fa fa-book fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-orange">
                                                    <div class="circle-tile-description text-faded">
                                                       Gestão de Documentação
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Projetos/gestao_documentacao_index') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        
                                        <?php if ($permissao_eventos){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Projetos/gestao_documentos_index') ?>">
                                                    <div class="circle-tile-heading btn-green">
                                                        <i class="fa fa-paperclip fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-green">
                                                    <div class="circle-tile-description text-faded">
                                                       Gestão de Documentos
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Projetos/gestao_documentos_index') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        
                                         <?php if ($permissao_eventos){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Projetos/gestao_melhorias_index') ?>">
                                                    <div class="circle-tile-heading btn-danger">
                                                        <i class="fa fa-smile-o fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-danger">
                                                    <div class="circle-tile-description text-faded">
                                                       Gestão de Melhorias
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Projetos/gestao_melhorias_index') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                    </div>
                                    <hr>
                                    <?php if ($permissao_acoes){ ?>
                                   <h3>Ações</h3>
                                    <div class="row">
                                         <?php if ($permissao_acoes){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('planos') ?>">
                                                    <div class="circle-tile-heading btn-dropbox">
                                                        <i class="fa fa-list fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-dropbox">
                                                    <div class="circle-tile-description text-faded">
                                                       Lista de Ações
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('planos') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_avalidacao){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Planos/planosAguardandoValidacao') ?>">
                                                    <div class="circle-tile-heading btn-pinterest">
                                                        <i class="fa fa-clock-o fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-pinterest">
                                                    <div class="circle-tile-description text-faded">
                                                        A. Validação
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Planos/planosAguardandoValidacao') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($permissao_apendentes){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Planos/planosPendentes') ?>">
                                                    <div class="circle-tile-heading orange">
                                                        <i class="fa fa-exclamation fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content orange">
                                                    <div class="circle-tile-description text-faded">
                                                        Pendentes
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                    </div>
                                                    <a href="<?= site_url('Planos/planosPendentes') ?>" class="circle-tile-footer"> Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Planos/planosConcluidos') ?>">
                                                    <div class="circle-tile-heading green">
                                                        <i class="fa fa-check fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content green">
                                                    <div class="circle-tile-description text-faded">
                                                        Concluídas
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                        
                                                    </div>
                                                    <a href="<?= site_url('Planos/planosConcluidos') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <hr>
                                    <?php } ?>
                                    <?php if ($permissao_cadastro_pessoas){ ?>
                                    <h3>Pessoas</h3>
                                    <div class="row">
                                      <?php if ($permissao_participantes){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('atas/index_participantes') ?>">
                                                    <div class="circle-tile-heading btn-vk">
                                                        <i class="fa fa-users fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-vk">
                                                    <div class="circle-tile-description text-faded">
                                                       Participantes
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('atas/index_participantes') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>  
                                    <?php if ($permissao_usuarios){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('users') ?>">
                                                    <div class="circle-tile-heading btn-dropbox">
                                                        <i class="fa fa-user fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-dropbox">
                                                    <div class="circle-tile-description text-faded">
                                                       Usuários
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('users') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    
                                    <?php if ($permissao_gestores){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Gestores/index') ?>">
                                                    <div class="circle-tile-heading btn-facebook">
                                                        <i class="fa fa-user fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-facebook">
                                                    <div class="circle-tile-description text-faded">
                                                       Gestores
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Gestores/index') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    <?php if ($permissao_suporintendentes){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Gestores/superintendentes') ?>">
                                                    <div class="circle-tile-heading btn-tumblr">
                                                        <i class="fa fa-user fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-tumblr">
                                                    <div class="circle-tile-description text-faded">
                                                       Superintendentes
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Gestores/superintendentes') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if ($lista_participantes){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Gestores/usuarios_listas') ?>">
                                                    <div class="circle-tile-heading btn-tumblr">
                                                        <i class="fa fa-list-alt fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-tumblr">
                                                    <div class="circle-tile-description text-faded">
                                                       Listas 
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Gestores/usuarios_listas') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        </div>
                                    
                                    <hr>
                                    <?php } ?>
                                 
                                  
                                   <?php if ($projetos->projeto_atual == 4){ ?>
                                    <h3>Controle de Horas</h3>
                                    <div class="row">
                                      <?php if ($permissao_participantes){ ?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Projetos/horarios_usuarios') ?>">
                                                    <div class="circle-tile-heading btn-warning">
                                                        <i class="fa fa-clock-o fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div  class="circle-tile-content btn-warning">
                                                    <div class="circle-tile-description text-faded">
                                                       Horário Expediente
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Projetos/horarios_usuarios') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="col-lg-3 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Projetos/requisicao_horas_index') ?>">
                                                    <div class="circle-tile-heading btn-tumblr">
                                                        <i class="fa fa-clock-o fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div  class="circle-tile-content btn-tumblr">
                                                    <div class="circle-tile-description text-faded">
                                                       Requisição de horas
                                                    </div>
                                                    <div class="circle-tile-number text-faded">
                                                      
                                                    </div>
                                                    <a href="<?= site_url('Projetos/requisicao_horas_index') ?>" class="circle-tile-footer">Acessar <i class="fa fa-chevron-circle-right"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>  
                                    <?php } ?>  
                                </div>
                            </div>
                        </div>
                        
                        
                    <!-- /.col-lg-6 -->

                </div>
                    <!-- /.col-lg-6 -->
                    <?php } ?>
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
    
    
    
    
        <script src="<?= $assets ?>dashboard/js/plugins/fullcalendar/fullcalendar.min.js"></script>
        
        
        <script>
    //Calendar Demo
$(function() {


    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    /* initialize the calendar
        -----------------------------------------------------------------*/

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        editable: true,
        droppable: true, // this allows things to be dropped onto the calendar !!!
        drop: function(date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                $(this).remove();
            }
        },

        

        events: [
            <?php
            $usuario = $this->session->userdata('user_id');
            $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
            $eventos = $this->projetos_model->getAllMarcosProjetoByProjeto($projetos_usuario->projeto_atual,'start', 'asc');
            foreach ($eventos as $evento) {
       
            $data_ini = substr("$evento->start", 0, 10);
            $data_ini_n = str_replace("-", ",", $data_ini);

            $partes_ini_n = explode("-", $data_ini);
            $dia_ini_n = $partes_ini_n[2];
            $mes_ini_n = $partes_ini_n[1]-1;
            $ano_ini_n = $partes_ini_n[0];
            $nova_data_ini = $ano_ini_n . ',' . $mes_ini_n . ',' . $dia_ini_n;

            $data_fim = substr("$evento->end", 0, 10);
            $data_fim_n = str_replace("-", ",", $data_fim);

            $partes_fim_n = explode("-", $data_fim);
            $dia_fim_n = $partes_fim_n[2];
            $mes_fim_n = $partes_fim_n[1] - 1;
            $ano_fim_n = $partes_fim_n[0];
            $nova_data_fim = $ano_fim_n . ',' . $mes_fim_n . ',' . $dia_fim_n;   
           ?>
                           
        {
            title: '<?php echo $evento->title; ?>',
            start: new Date(<?php echo $ano_ini_n; ?>, <?php echo $mes_ini_n; ?>, <?php echo $dia_ini_n; ?> ),
            end: new Date(<?php echo $ano_fim_n; ?>, <?php echo $mes_fim_n; ?>, <?php echo $dia_fim_n; ?>),
            className: 'fc-<?php echo $evento->color; ?>'
        }, 
        
        <?php
         }
        ?>
    ]
    });

    /* initialize the external events
     * {
            title: 'Long Event',
            start: new Date(y, m, d - 5),
            end: new Date(y, m, d - 2),
            className: 'fc-orange'
        }, {
            id: 999,
            title: 'Repeating Event',
            start: new Date(y, m, d - 3, 16, 0),
            allDay: false,
            className: 'fc-blue'
        }, {
            id: 999,
            title: 'Repeating Event',
            start: new Date(y, m, d + 4, 16, 0),
            allDay: false,
            className: 'fc-red'
        }, {
            title: 'Meeting',
            start: new Date(y, m, d, 10, 30),
            allDay: false,
            className: 'fc-purple'
        }, {
            title: 'Lunch',
            start: new Date(y, m, d, 12, 0),
            end: new Date(y, m, d, 14, 0),
            allDay: false,
            className: 'fc-default'
        }, {
            title: 'Birthday Party',
            start: new Date(y, m, d + 1, 19, 0),
            end: new Date(y, m, d + 1, 22, 30),
            allDay: false,
            className: 'fc-white'
        }
     * 
        -----------------------------------------------------------------*/

    $('#external-events div.external-event').each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true, // will cause the event to go back to its
            revertDuration: 0 //  original position after the drag
        });

    });


});

    </script>
</body>

</html>
