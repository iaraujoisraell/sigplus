<?php
$empresa = $this->session->userdata('empresa');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<aside style="position: fixed; max-height: 70%; height: 70%;  overflow-x: hidden; overflow-y: scroll; white-space:nowrap;"  class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section  class="sidebar">
      <!-- Sidebar user panel -->
      <div  class="user-panel">
        <div  class="pull-left image">
            <img style="max-height: 50px; max-width: 50px;" src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/'.$empresa.'/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="img-circle"  alt="User Image">
        </div>
          <?php
                $usuario = $this->session->userdata('user_id');
                $users_dados = $this->site->getUser($usuario);
                $modulo_atual = $users_dados->modulo_atual;
                 
                //retorna o projeto atual
                $projetos = $this->projetos_model->getProjetoAtualByID_completo();
                $status_projeto = $projetos->status;
               //echo 'aqui : '.$status_projeto;
                $empresa = $this->session->userdata('empresa');
                $empresa_dados = $this->owner_model->getEmpresaById($empresa);
                $nome_empresa = $empresa_dados->razaoSocial;
                
                
                $projetos = $this->owner_model->getQtdeProjetosByUser();
                 $projeto_ativo = $projetos->ativo;
                 $projeto_cancelado = $projetos->cancelado;
                 $projeto_concluido = $projetos->concluido;
                 $projeto_aguardando = $projetos->aguardando;
                 $soma_projetos = $projeto_ativo +  $projeto_concluido +$projeto_aguardando;
                ?>
        <div class="pull-left info">
          <p><?php echo $users_dados->first_name; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
     <div class="box-footer no-padding">
         <div  class=" image">
          <ul class="nav nav-stacked">
              <li><a ><small><b>Empresa</b></small>        <span class="pull-right badge bg-blue"><?php echo $nome_empresa; ?>          </span></a></li>
          </ul>
         
         <?php  if($modulo_atual == 4) { ?>
         <ul class="nav nav-stacked">
              <li><a href="<?= site_url('project/index'); ?>" ><small><b>Portifólio</b></small>        <span class="pull-right badge bg-red"><?php echo $soma_projetos; ?>          </span></a></li>
          </ul>
         <?php } ?>
         </div>
        </div>
        
     
      
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul  class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        
        <?php
        
         
         
         /*
          * ATUALIZA AS LABELS DO MENU
          */
         // ****************  PROJETOS ********************************************
         
         //$data_menu_projeto = array('label1' => $projeto_concluido,'label2' => $projeto_aguardando,'label3' => $projeto_cancelado, 'label4' => $projeto_ativo);
         //$this->owner_model->updateLabelMenu(28, $data_menu_projeto);
        // $data_menu_portifolio = array('label4' => $soma_projetos);
        // $this->owner_model->updateLabelMenu(84, $data_menu_portifolio);
        
         // ****************  ATAS ********************************************
         $atas = $this->owner_model->getQtdeAtasByStatysAndProjeto();
         $atas_aberta = $atas->aberto;
         $atas_fechada = $atas->fechado;
         $data_menu_ata = array('label1' => $atas_fechada, 'label2' => $atas_aberta);
         $this->owner_model->updateLabelMenu(54, $data_menu_ata);
        
         // ****************  PLANO AÇÃO ********************************************
         $pa = $this->owner_model->getQtdePlanoAcaoByProjeto();
         $pa_total = $pa->total;
         $data_menu_pa = array('label4' => $pa_total);
         $this->owner_model->updateLabelMenu(55, $data_menu_pa);
         
         // ****************  AÇÕES ********************************************
         $acoes = $this->owner_model->getQtdeAcoesByProjeto();
         $acoes_pendente = $acoes->pendente;
         $acoes_atrasado = $acoes->atrasado;
         $acoes_concluido = $acoes->concluido;
         $acoes_aguardando = $acoes->aguardando;
         $total_acoes = $acoes_pendente + $acoes_atrasado + $acoes_concluido;
         $data_menu_acoes = array( 'label1' => $acoes_concluido, 'label2' => $acoes_aguardando, 'label3' => $acoes_atrasado, 'label4' => $acoes_pendente);
         $this->owner_model->updateLabelMenu(77, $data_menu_acoes);
         //AGUARDANDO VALIDAÇÕES
         $data_menu_acoes_av = array( 'label2' => $acoes_aguardando);
         $this->owner_model->updateLabelMenu(78, $data_menu_acoes_av);
         //ATRASADAS
        // $data_menu_acoes_atrasada = array( 'label3' => $acoes_atrasado);
         //$this->owner_model->updateLabelMenu(79, $data_menu_acoes_atrasada);
         //LISTA AÇÕES
         $data_menu_acoes_lista = array( 'label4' => $total_acoes);
         $this->owner_model->updateLabelMenu(51, $data_menu_acoes_lista);
         
         
        
        
         
         
         
         /******************************************************************
          ****************** MENUS NETWORKING*******************************
          ******************************************************************/
         
         
         //CONVITES
         $conv = $this->owner_model->getQtdeConvitesByUser();
         $conv_total = $conv->quantidade;
         $data_menu_conv = array('label2' => $conv_total);
         $this->owner_model->updateLabelMenu(88, $data_menu_conv);
         
         
         //MINHAS AÇÕES
         
         // AÇÕES PENDENTES
         $qtde_acoes_pendentes_users = $this->networking_model->getQuantidadeAcoesPendenteUserEmpresa();
         $qtde_acao_pendente = $qtde_acoes_pendentes_users->qtde_acoes_pendentes;
         
         // AÇÕES AGUARDANDO VALIDAÇÃO
         $qtde_acoes_aguardando_users = $this->networking_model->getQuantidadeAcoesAguardandoValidacaoUserEmpresa();
         $qtde_acao_aguardando = $qtde_acoes_aguardando_users->qtde_acoes_aguardando;

         // AÇÕES ATRASADAS
         $qtde_acoes_atrasadas_users = $this->networking_model->getQuantidadeAcoesAtrasadasUserEmpresa();
         $qtde_acao_atrasada = $qtde_acoes_atrasadas_users->qtde_acoes_atrasadas;

         // AÇÕES CONCLUÍDAS
         $qtde_acoes_concluidas_users = $this->networking_model->getQuantidadeAcoesConcluidasUserEmpresa();
         $qtde_acao_concluida = $qtde_acoes_concluidas_users->qtde_acoes_concluidas;
        
         $total_acao_pendente = $qtde_acao_pendente + $qtde_acao_aguardando + $qtde_acao_atrasada + $qtde_acao_concluida;
        // if($total_acao_pendente > 0){ 
         $data_menu_minhas_acoes = array('label4' => $total_acao_pendente);
         $this->owner_model->updateLabelMenu(66, $data_menu_minhas_acoes);
       //  }
         $total_pendente = $qtde_acao_pendente + $qtde_acao_aguardando;
         $qtde_acao = $qtde_acao_concluida + $qtde_acao_aguardando + $qtde_acao_atrasada;
        // if($qtde_acao > 0){
         $data_lista_acoes = array('label1' => $qtde_acao_concluida,'label2' => $total_pendente,'label3' => $qtde_acao_atrasada);
         $this->owner_model->updateLabelMenu(69, $data_lista_acoes);
      //   }
         
         
         /**************************************************************************************************/
         
         // PLANO DE AÇÃO
         
         // Plano de ação abertos
         $qtde_planos_acoes_a = $this->networking_model->getQuantidadePlanoAcaoAbertoByUser();
         $qtde_plano_acao_aberto = $qtde_planos_acoes_a->quantidade;
         
         $data_menu_plano_aberto = array('label2' => $qtde_plano_acao_aberto);
         $this->owner_model->updateLabelMenu(89, $data_menu_plano_aberto);
         
         // Plano de ação fechado
         $qtde_planos_acoes_f = $this->networking_model->getQuantidadePlanoAcaoFechadoByUser();
         $qtde_plano_acao_fechado = $qtde_planos_acoes_f->quantidade;
         
         $data_menu_plano_fechado = array('label1' => $qtde_plano_acao_fechado);
         $this->owner_model->updateLabelMenu(89, $data_menu_plano_fechado);
        
         
         // MINHAS ATAS
         
         // atas abertos
         $qtde_minhas_atas_a = $this->networking_model->getQuantidadeAtasAbertoByUser();
         $qtde_minhas_atas_aberto = $qtde_minhas_atas_a->quantidade;
       //  if($qtde_minhas_atas_aberto > 0){
         $data_menu_atas_aberto = array('label2' => $qtde_minhas_atas_aberto);
         $this->owner_model->updateLabelMenu(86, $data_menu_atas_aberto);
       //  }
         // atas fechado
         $qtde_atas_f = $this->networking_model->getQuantidadeAtasFechadoByUser();
         $qtde_atas_fechado = $qtde_atas_f->quantidade;
       //  if($qtde_atas_fechado){
         $data_atas_fechado = array('label1' => $qtde_atas_fechado);
         $this->owner_model->updateLabelMenu(86, $data_atas_fechado);
       //  }
         // atas vinculadas
         $qtde_atas_vinculadas_f = $this->networking_model->getQuantidadeVinculoAtasByUser();
         $qtde_atas_vinculadas = $qtde_atas_vinculadas_f->quantidade;
       //  if($qtde_atas_vinculadas){
         $data_atas_vinculadas_net = array('label4' => $qtde_atas_vinculadas);
         $this->owner_model->updateLabelMenu(87, $data_atas_vinculadas_net);
      //   }
         // Total Atas
         $total_atas = $qtde_atas_fechado + $qtde_minhas_atas_aberto + $qtde_atas_vinculadas;
      //   if($total_atas > 0){
         $data_atas_total = array('label4' => $total_atas);
         $this->owner_model->updateLabelMenu(71, $data_atas_total);
       //  }
         
         
          // MINHAS TAREFAS
         
         // tarefas abertos
         $qtde_tarefas_a = $this->networking_model->getQuantidadeTarefasAbertasByUser();
         $qtde_tarefa_aberto = $qtde_tarefas_a->quantidade;
       //  if($qtde_tarefa_aberto > 0){
         $data_qtde_aberto = array('label2' => $qtde_tarefa_aberto);
         $this->owner_model->updateLabelMenu(72, $data_qtde_aberto);
        // }
         
         // tarefas fechadas
         $qtde_tarefas_f = $this->networking_model->getQuantidadeTarefasFechadaByUser();
         $qtde_tarefa_fechada = $qtde_tarefas_f->quantidade;
       //  if($qtde_tarefa_fechada > 0){
         $data_qtde_fechada = array('label1' => $qtde_tarefa_fechada);
         $this->owner_model->updateLabelMenu(72, $data_qtde_fechada);
       //  }
         
         
         // Mensagens
         $qtde_mensagens = $this->networking_model->getQtdeMensagensNaoLidasByUsuario();
         $qtde_mensagem_aberto = $qtde_mensagens->quantidade;
         if($qtde_mensagem_aberto > 0){
         $msg_qtde_aberto = array('label1' => $qtde_mensagem_aberto);
         $this->owner_model->updateLabelMenu(93, $msg_qtde_aberto);
         }
         //***************************************************************************
         $modulo_atual = $users_dados->modulo_atual;
         $menu_atual = $users_dados->menu_atual;
         $pai_menu_atual = $this->owner_model->getMenuById($menu_atual);
         $pai_menu = $pai_menu_atual->pai;
        
         $menus_pai = $this->owner_model->getMenusPaiByModulo($modulo_atual);
          foreach ($menus_pai as $pai) {
            $menu_id =   $pai->id;
            $menu_descricao =   $pai->descricao;
            $menu_controller =   $pai->controller;
            $menu_funcao =   $pai->funcao;
            $menu_tabela =   $pai->tabela;
            $menu_icone =   $pai->icone;
            $blank =   $pai->blank;
            
            $label1 =   $pai->label1;
            $label2 =   $pai->label2;
            $label3 =   $pai->label3;
            $label4 =   $pai->label4;
            
            $title1 =   $pai->title1;
            $title2 =   $pai->title2;
            $title3 =   $pai->title3;
            $title4 =   $pai->title4;
            
             if (($menu_atual == $menu_id)||($pai_menu == $menu_id)){
                $ativo = "active";
            }else{
                $ativo = "";
            }
            
            $sub_menus = $this->owner_model->getSubMenusByPai($menu_id);
            $cont_sub = 0;
            foreach ($sub_menus as $sub) {
            $cont_sub++;    
            }
        ?> 
        
        <li  class="<?php echo $ativo; ?> <?php  if($cont_sub > 0){  ?> treeview <?php } ?>">
            <?php if($modulo_atual == 4) { ?>
             <a <?php if($blank == 1){ ?> target="_blank"  <?php } ?> href="<?= site_url($menu_controller.'/'.$menu_funcao.'/'.$menu_id); ?>">
            <?php }else{ ?>
             <a <?php if($blank == 1){ ?> target="_blank"  <?php } ?> href="<?= site_url($menu_controller.'/'.$menu_funcao.'/'.$menu_tabela.'/'.$menu_id); ?>">
            <?php } ?>
                <i class="fa fa-<?php echo $menu_icone; ?>"></i>
                <span><?php echo $menu_descricao; ?></span>
                <span class="pull-right-container">
               <?php if($cont_sub > 0){ ?>   <i class="fa fa-angle-left pull-right"></i> <?php } ?>



               <?php if($label4 > 0){ ?> <small title="<?php echo $title4; ?>" class="label pull-right bg-blue"><?php echo $label4; ?></small> <?php } ?>
                <?php if($label3 > 0){ ?> <small title="<?php echo $title3; ?>" class="label pull-right bg-red"><?php echo $label3; ?></small> <?php } ?>
               <?php if($label2 > 0){ ?> <small title="<?php echo $title2; ?>" class="label pull-right bg-orange"><?php echo $label2; ?></small> <?php } ?>
               <?php if($label1 > 0){ ?> <small title="<?php echo $title1; ?>" class="label pull-right bg-green"><?php echo $label1; ?></small> <?php } ?>
                </span>
            </a>
            <?php 
            if($cont_sub > 0){
                
            ?>
          <ul class="treeview-menu">
              <?php
            
             foreach ($sub_menus as $sub) {   
                $smenu_id         =  $sub->id;
                $smenu_descricao  =  $sub->descricao;
                $smenu_controller =  $sub->controller;
                $smenu_funcao     =  $sub->funcao;
                $smenu_tabela     =  $sub->tabela;
                $smenu_icone      =  $sub->icone;
                $smenu_blank =   $sub->blank;
                
                $slabel1 =   $sub->label1;
                $slabel2 =   $sub->label2;
                $slabel3 =   $sub->label3;
                $slabel4 =   $sub->label4;

                $stitle1 =   $sub->title1;
                $stitle2 =   $sub->title2;
                $stitle3 =   $sub->title3;
                $stitle4 =   $sub->title4;
            
                 if($menu_atual == $smenu_id){
                $ativo = "active";
            }else{
                $ativo = "";
            }
            
            
            
              ?>
              <li class=" <?php echo $ativo; ?>"> 
                  
                   <?php if($modulo_atual == 4) { ?>
                    <a <?php if($smenu_blank == 1){ ?> target="_blank"  <?php } ?> href="<?= site_url($smenu_controller.'/'.$smenu_funcao.'/'.$smenu_id); ?>"><i class="fa fa-<?php echo $smenu_icone; ?>"></i> <?php echo $smenu_descricao; ?>
                    <?php }else{ ?>
                    <a <?php if($smenu_blank == 1){ ?> target="_blank"  <?php } ?> href="<?= site_url($smenu_controller.'/'.$smenu_funcao.'/'.$smenu_tabela.'/'.$smenu_id); ?>"><i class="fa fa-<?php echo $smenu_icone; ?>"></i> <?php echo $smenu_descricao; ?>
                    <?php } ?>
                  
                  
                      <span class="pull-right-container">
                          <?php if($slabel4 > 0){ ?> <small title="<?php echo $stitle4; ?>" class="label pull-right bg-blue"><?php echo $slabel4; ?></small> <?php } ?>
                        <?php //if($label3 > 0){ ?> <small title="<?php echo $stitle3; ?>" class="label pull-right bg-red"><?php echo $slabel3; ?></small> <?php// } ?>
                       <?php //if($label2 > 0){ ?> <small title="<?php echo $stitle2; ?>" class="label pull-right bg-orange"><?php echo $slabel2; ?></small> <?php// } ?>
                       <?php //if($label1 > 0){ ?> <small title="<?php echo $stitle1; ?>" class="label pull-right bg-green"><?php echo $slabel1; ?></small> <?php //} ?>
                      </span>
                  </a>
              </li>
             <?php  }  ?>
          </ul>
             <?php  } ?>
        </li>
        
       
        <?php    
          }              
        ?>
        
        
        
        
      
            
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>