 <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("auth/troca_perfil_dashboard/".$projeto_selecionado, $attrib);
                ?>
        <?php
                    $usuario = $this->session->userdata('user_id');
                    $projetos = $this->site->getProjetoAtualByID_completo($usuario);
                    
                    ?>
                <!-- begin TOP NAVIGATION  style="background-color: seagreen; position: relative; width: 100%; height: 50px;"-->
                <nav style="background-color: <?php echo $projetos->botao; ?>;" class="navbar-top"  role="navigation">

                    <div class="navbar-header">
                           
                                        <a  href="<?= site_url('Login_Projetos/menu_sistemas'); ?>" >
                                            <img width="170px" height="50px" src="<?= base_url() ?>assets/uploads/logos/LogoUnimed1.png " >
                                        </a>  
                     
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
                    
                    
                     
                    
                    
                    <div class="nav-top">
                        <ul class="nav navbar-right">
                            
                            <li>
                                <img style="width: 50px; height: 50px; margin-top: -5px;" alt="" src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="mini_avatar img-rounded">
                            </li>
                          <li style="text-decoration: none"  >
                                    <span style="text-decoration: none" >
                                        <p style="font-size: 14px; color: #ffffff;">Olá <?= $this->session->userdata('username'); ?>, Bem Vindo </p>
                                        <p style="font-size: 14px; color: #ffffff;"><?php if($qtde_perfis_user > 1){ ?> :seu perfil atual <?php } ?></p> </span>
                            </li>
                            <li class="dropdown">
                                       <?php
                                       
                                       if ($qtde_perfis_user > 1) {
                                           $usuario = $this->session->userdata('user_id');
                                           $perfis_user = $this->site->getPerfilusuarioByID($usuario);

                                           foreach ($perfis_user as $item) {
                                                if($item->grupo_id == 1){
                                                 $wu4[$item->grupo_id] = $item->name;
                                               }
                                               if($item->grupo_id == 2){
                                                   // Verifica se ele ta na tabela de gestores
                                                   $perfis_user = $this->site->getPerfilGestorByIDandProjeto($usuario, $projetos->projeto_atual);
                                                   
                                                   $qtde_gestor = $perfis_user->quantidade;
                                                   
                                                  if($qtde_gestor > 0){
                                                    $wu4[$item->grupo_id] = $item->name;
                                                  }
                                               }
                                               if($item->grupo_id == 3){
                                                   // Verifica se ele ta na tabela de gestores
                                                   $perfis_user = $this->site->getPerfilSuperintendenteByIDandProjeto($usuario, $projetos->projeto_atual);
                                                   
                                                   $qtde_gestor = $perfis_user->quantidade;
                                                   
                                                  if($qtde_gestor > 0){
                                                    $wu4[$item->grupo_id] = $item->name;
                                                  }
                                               }
                                                if($item->grupo_id == 5){
                                                    $wu4[$item->grupo_id] = $item->name;
                                                }
                                           }
                                           echo form_dropdown('perfil_usuario', $wu4, (isset($_POST['perfil_usuario']) ? $_POST['perfil_usuario'] : $perfil_atual), '  class="form-control selectpicker  select" style="width:100%; height: 50px;" ');
                                       
                                       ?>
                               
                                    
                                    
                                    <li>
                                        <?php echo form_submit('add_projeto', lang("Trocar"), 'id="add_projeto" class="btn btn-flickr" style= "height: 50px;"'); ?>
                                    </li>
                                    
                                    <?php
                                    }
                                    
                                    ?>
                            </li>
                            
                            
                            <?php
                         $quantidadePendente = $this->site->getAllPlanosPendenteUser($usuario);
                         $quantidadeAvalidacao = $this->site->getAllPlanosAguardandoValidacao($projetos->id);
                         
                         $acoes_pendentes_alerta = $quantidadePendente->quantidade;
                         if ($permissao_avalidacao )   { 
                         $acoes_aguardando_validacao = $quantidadeAvalidacao->quantidade;
                         }else{
                             $acoes_aguardando_validacao = 0;
                         }
                         
                        ?>
                          <li class="dropdown ">
                            <a class="btn btn-orange tip" title="<?= lang('alerts') ?>" 
                                data-placement="left" data-toggle="dropdown" href="#">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="number blightOrange black"><?php echo $acoes_pendentes_alerta + $acoes_aguardando_validacao; ?></span>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="<?= site_url('welcome') ?>" class="">
                                        <span class="label label-danger pull-right" style="margin-top:3px;"><?php echo $quantidadePendente->quantidade; ?></span>
                                        <span style="padding-right: 35px;"><?= lang('Suas ações Pendentes') ?></span>
                                    </a>
                                </li>
                                <?php if ($permissao_avalidacao )   {  ?>
                                <li>
                                    <a href="<?= site_url('Planos/planosAguardandoValidacao') ?>" class="">
                                        <span class="label label-danger pull-right" style="margin-top:3px;"><?php echo $quantidadeAvalidacao->quantidade; ?></span>
                                        <span style="padding-right: 35px;"><?= lang('Ações Aguardando Validação') ?></span>
                                    </a>
                                </li>
                                <?php } ?>
                               
                            </ul>
                        </li>
                        <li class="dropdown ">
                            <a class="btn tip btn-twitter" title="<?= lang('calendar') ?>" data-placement="bottom" href="#" data-toggle="dropdown">
                                <i class="fa fa-calendar"></i>
                                 <?php
                       $usuario = $this->session->userdata('user_id');
                        $projetos_usuario = $this->site->getProjetoAtualByID_completo($usuario);
                        $eventos_projetos = $this->projetos_model->getAllMarcosProjetoByProjetoNaoVencido($projetos_usuario->projeto_atual,'start', 'asc');
                   ?>
                                <span class="number blightOrange black"><?= sizeof($eventos_projetos) ?></span>
                            </a>
                            <ul class="dropdown-menu pull-right content-scroll">
                                <li class="dropdown-header">
                                <i class="fa fa-calendar"></i> <?= lang('upcoming_events'); ?>
                                </li>
                                <li class="dropdown-content">
                                    <div class="top-menu-scroll">
                                        <ol class="oe">
                                            <?php foreach ($eventos_projetos as $event) {
                                                echo '<li>' . date($dateFormats['php_ldate'], strtotime($event->start)) . ' <strong>' . $event->title . '</strong>'.'</li>';
                                            } ?>
                                        </ol>
                                    </div>
                                </li>
                                <li class="dropdown-footer">
                                    <a href="<?= site_url('calendar') ?>" class="btn-block link">
                                        <i class="fa fa-calendar"></i> <?= lang('calendar') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="dropdown ">
                            <a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>" title="<?= lang('Perfil') ?>" class="btn">
                            <span class="fa fa-user"></span>
                        </a>
                       </li>
                        
                            <li class="dropdown">
                                <a class="red dropdown-toggle" href="<?= site_url('Auth/logout'); ?>">
                                    <i class="fa fa-sign-out"></i> 
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                
                
                <?php echo form_close(); ?>
        <!-- /.navbar-top -->
        <!-- end TOP NAVIGATION -->