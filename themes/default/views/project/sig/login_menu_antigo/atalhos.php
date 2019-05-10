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
                
                 <?php  $this->load->view($this->theme . 'status_projeto'); ?>
        
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
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
                            <?php if ($permissao_dashboard) { ?>    
                                <a title="MENU" href="<?= site_url('Login_Projetos/menu') ?>" class="btn btn-social-icon orange"><i class="fa fa-qrcode"></i></a>
                            <?php } ?>
                            <?php if ($permissao_dashboard) { ?>    
                                <a title="DASHBOARD" href="<?= site_url('projetos/dashboard/' . $projetos->projeto_atual) ?>" class="btn btn-social-icon btn-google-plus"><i class="fa fa-dashboard"></i></a>
                            <?php } ?> 
                            <?php if ($permissao_atas) { ?>
                                <a title="LISTA DE ATAS" href="<?= site_url('atas/add') ?>" class="btn btn-social-icon btn-bitbucket"><i class="fa fa-book"></i></a>
                            <?php } ?>
                            <?php if ($permissao_avalidacao) { ?> 
                                <a title="AÇÕES AGUARDANDO VALIDAÇÃO"  href="<?= site_url('planos/planosAguardandoValidacao') ?>" class="btn btn-social-icon btn-pinterest"><i class="fa fa-clock-o"></i></a>
                            <?php } ?>     
                            <?php if ($permissao_acoes) { ?>     
                                <a title="LISTA DE AÇÕES" href="<?= site_url('planos') ?>" class="btn btn-social-icon btn-dropbox"><i class="fa fa-list"></i></a>
                            <?php } ?>
                            <?php if ($permissao_apendentes) { ?>     
                                <a title="AÇÕES PENDENTES" href="<?= site_url('Planos/planosPendentes') ?>" class="btn btn-social-icon btn-orange"><i class="fa fa-exclamation"></i></a>
                            <?php } ?>
                            <?php if ($permissao_projetos) { ?>
                                <a title="LISTA DE PROJETOS" href="<?= site_url('projetos/add') ?>" class="btn btn-social-icon btn-tumblr"><i class="fa fa-folder"></i></a>
                            <?php } ?>     
                             <?php if ($permissao_eventos) { ?>
                                <a title="LISTA DE EVENTOS" href="<?= site_url('projetos/add_evento') ?>" class="btn btn-social-icon btn-facebook"><i class="fa fa-calendar-o"></i></a>
                            <?php } ?>
                            <?php if ($permissao_participantes) { ?>    
                                <a title="LISTA DE PARTICIPANTES" href="<?= site_url('Atas/index_participantes') ?>" class="btn btn-social-icon btn-github"><i class="fa fa-users"></i></a>
                            <?php } ?>  
                            <?php if ($permissao_usuarios) { ?>     
                                <a title="LISTA DE USUÁRIOS" href="<?= site_url('users') ?>" class="btn btn-social-icon btn-vk"><i class="fa fa-user"></i></a>
                            <?php } ?>
                            <?php if ($permissao_calendario) { ?>    
                                <a title="CALENDÁRIO" href="<?= site_url('calendar') ?>" class="btn btn-social-icon btn-instagram"><i class="fa fa-calendar"></i></a>
                            <?php } ?>
                            <?php if ($permissao_contas_pagar) { ?>    
                                <a title="CUSTOS" href="<?= site_url('financeiro') ?>" class="btn btn-social-icon btn-green"><i class="fa fa-money"></i></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>