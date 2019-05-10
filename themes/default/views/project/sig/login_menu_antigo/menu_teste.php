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
                                        <!-- FIM DASHBOARD -->
                                           <?php if ($permissao_dashboard){ ?>
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="circle-tile">
                                                <a href="<?= site_url('Login_Projetos/marcos_projeto/' . $projetos->projeto_atual) ?>">
                                                    <div class="circle-tile-heading btn-orange">
                                                        <i class="fa fa-calendar fa-fw fa-3x"></i>
                                                    </div>
                                                </a>
                                                <div class="circle-tile-content btn-orange">
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
                                        <!-- FIM CRONOGRAMA -->
                                        <?php if ($permissao_gestao_custo){ ?>
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
                                   
                                        
                                    </div>
                                   
                                   
                              
                                </div>
                            </div>
                        </div>
                        
                   
                   
                    <!-- /LADO ESQUERDO -->
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
                                                        <i class="fa fa-book fa-fw fa-3x"></i>
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

</body>

</html>
