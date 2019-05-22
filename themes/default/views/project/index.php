    <div class="box-body">
    <!-- Content Header (Page header) -->
    
    <div class="box">   
        
     <section class="content-header">
        <h1>PORTIFÓLIO DE PROJETOS<small></small></h1>    

      <ol class="breadcrumb">
        <li><a href="<?= site_url('project'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Project</li>
      </ol>
    </section>
    
     <div class="box-header">
         <?php if($criar_projetos == 1) { ?>
        <span class="pull-right-container">
           <div class=" clearfix no-border">
               <a  title="Criar novo Projeto" class="btn btn-primary pull-right" href="<?= site_url('project/novoProjeto'); ?>">  
               <i class="fa fa-plus"></i>  Novo Projeto 
               </a> 
           </div>
        </span>
         <?php } ?>    
    </div>
    
    <!-- Main content -->
     </div>
    <div class="row">  
    <div class="col-lg-12">
        <div class="col-lg-12">
            <?php if ($Settings->mmode) { ?>
                    <div class="alert alert-warning">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <?= lang('site_is_offline') ?>
                    </div>
                <?php }
                if ($error) { ?>
                    <div class="alert alert-danger">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <ul class="list-group"><?= $error; ?></ul>
                    </div>
                <?php }
                if ($message) { ?>
                    <div class="alert alert-success">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <ul class="list-group"><?= $message; ?></ul>
                    </div>
                <?php } ?>
        </div>
    </div> 
    </div>
     <?php
     $projetos = $this->owner_model->getQtdeProjetosByUser();
     $projeto_ativo = $projetos->ativo;
     $projeto_cancelado = $projetos->cancelado;
     $projeto_concluido = $projetos->concluido;
     $projeto_aguardando = $projetos->aguardando;
     ?>
    
         
        <div class="row">   
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $projeto_ativo; ?></h3>
              <p>Projetos Ativo</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-favorite"></i>
            </div>
           
          </div>
        </div>
        
            <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $projeto_aguardando; ?></h3>

              <p>Projeto em Aguardo</p>
            </div>
            <div class="icon">
              <i class="ion ion-clock"></i>
            </div>

          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $projeto_concluido; ?><sup style="font-size: 20px"></sup></h3>

              <p>Projetos Concluído</p>
            </div>
            <div class="icon">
              <i class="ion ion-checkmark"></i>
            </div>
           
          </div>
        </div>
        <!-- ./col -->
        
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $projeto_cancelado; ?></h3>

              <p>Projetos Cancelado</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-close"></i>
            </div>
           
          </div>
        </div>
        <!-- ./col -->
        </div>
   
   
        <div class="box box-widget">
            <div class="box ">
            <div class="row">
            <div class="col-md-12">
              <!-- Custom Tabs -->
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_1" data-toggle="tab" >Projetos Ativo <small class="label label-success"><?php echo $projeto_ativo; ?></small></a></li>
                  <li><a href="#tab_2" data-toggle="tab">Projetos em Aguardo <small class="label label-warning"><?php echo $projeto_aguardando; ?></small></a></li>
                   <li><a href="#tab_4" data-toggle="tab">Projetos Concluído <small class="label label-primary"><?php echo $projeto_concluido; ?></small></a></li>
                  <li><a href="#tab_3" data-toggle="tab">Projetos Cancelado <small class="label label-danger"><?php echo $projeto_cancelado; ?></small></a></li>

                  <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <?php
                    $projetos_ativos = $this->projetos_model->getAllProjetosAtivoAcesso('ATIVO');
                    $cont_p_ativo = 1;
                    ?>
                    <table class="table table-bordered">
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Projeto</th>
                              <th style="">Início</th>
                              <th style="">Fim</th>
                              <th style="">Gerente</th>
                              <th style="">Status</th>
                              <th>Progresso</th>
                              <th style="width: 40px">%</th>
                              <th style="width: 40px">Acessar</th>
                              <th style="width: 40px">Mudar Status</th>
                            </tr>
                            <?php foreach ($projetos_ativos as $projeto) {
                                $projeto_id = $projeto->id;
                                $projeto_nome = $projeto->nome_projeto;
                                $gerente = $projeto->gerente_area;
                                $resp_tecnico_fase = $this->atas_model->getUserSetorByUserSetor($gerente);
                                $gerente_projeto = $resp_tecnico_fase->nome;
                                
                                    $status = $projeto->status;
                                    if($status == 'ATIVO'){
                                       $status_label = 'success'; 
                                    }else if($status == 'CANCELADO'){
                                        $status_label = 'danger'; 
                                    }else if($status == 'EM AGUARDO'){
                                        $status_label = 'warning'; 
                                    }else if($status == 'CONCLUÍDO'){
                                        $status_label = 'primary'; 
                                    }

                                    $data_projeto = array(
                                    'aba' => 1
                                );
                                $this->projetos_model->updateProjeto($projeto_id, $data_projeto); 
                            ?>
                            <tr>
                              <td><?php echo $cont_p_ativo++; ?></td>
                              <td><?php echo $projeto_nome; ?></td>
                               <td > <small ><?php echo exibirData($projeto->dt_inicio); ?></small></td>
                                        <td > <small ><?php echo exibirData($projeto->dt_final); ?></small></td>
                                        <td > <small ><?php echo $gerente_projeto; ?></small></td>
                                        <td > <small class="label label-<?php echo $status_label; ?>" ><?php echo $status; ?></small></td>

                              <td>
                                <div class="progress progress-xs">
                                  <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                </div>
                              </td>
                              <td><span class="badge bg-red">55%</span></td>
                              <td><a title="Para acessar as informações do projeto, deve selecionar o projeto." href="<?= site_url('Project/selecionarProjeto/'.$projeto_id); ?>" class="btn  btn-instagram"> Entrar <i class="fa fa-sign-in"></i></a></td>
                              <td> 
                                <div class="text-center"><div class="btn-group text-left">
                                        <button  type="button" class="btn btn-default  dropdown-toggle" data-toggle="dropdown">
                                    Opções <span class="caret"></span></button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                          <li><a title="Cancelar o Projeto. Todas as Ações em aberto serão canceladas." href="#"><i class="fa fa-ban"></i>Cancelar</a></li>  
                                          <li><a title="Colocar o Projeto em Aguardo. Todas as Ações em aberto serão suspensas." href="#"><i class="fa fa-clock-o"></i>Em Aguardo</a></li>  
                                    </ul>
                                </div>
                                </div>
                               </td>   
                            </tr>
                            <?php } ?> 
                          </table>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2">
                   <?php
                    $projetos_ativos = $this->projetos_model->getAllProjetosAtivoAcesso('EM AGUARDO');
                    $cont_p_ativo = 1;
                    ?>
                    <table class="table table-bordered">
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Projeto</th>
                              <th style="">Início</th>
                              <th style="">Fim</th>

                              <th style="">Gerente</th>
                              <th style="width: 30px">opção</th>
                              
                            </tr>
                            <?php foreach ($projetos_ativos as $projeto) {
                                $projeto_id = $projeto->id;
                                $projeto_nome = $projeto->nome_projeto;
                                $gerente = $projeto->gerente_area;
                                
                                $resp_tecnico_fase = $this->atas_model->getUserSetorByUserSetor($gerente);
                                $gerente_projeto = $resp_tecnico_fase->nome;

                                    $status = $projeto->status;
                                    if($status == 'ATIVO'){
                                       $status_label = 'primary'; 
                                    }else if($status == 'CANCELADO'){
                                        $status_label = 'danger'; 
                                    }else if($status == 'EM AGUARDO'){
                                        $status_label = 'warning'; 
                                    }else if($status == 'CONCLUÍDO'){
                                        $status_label = 'success'; 
                                    }

                                $data_projeto = array(
                                    'aba' => 1
                                );
                                $this->projetos_model->updateProjeto($projeto_id, $data_projeto); 


                            ?>
                            <tr>
                              <td><?php echo $cont_p_ativo++; ?></td>
                              <td><?php echo $projeto_nome; ?></td>
                               <td > <small ><?php echo exibirData($projeto->dt_inicio); ?></small></td>
                                        <td > <small ><?php echo exibirData($projeto->dt_final); ?></small></td>
                                        <td > <small ><?php echo $gerente_projeto; ?></small></td>


                              <td><a title="Para acessar as informações do projeto, deve selecionar o projeto." href="<?= site_url('Project/selecionarProjeto/'.$projeto_id); ?>" class="btn  btn-instagram"> Selecionar <i class="fa fa-sign-in"></i></a></td>
                              

                                 
                            </tr>
                            <?php } ?> 
                          </table>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_3">
                     <?php
                    $projetos_ativos = $this->projetos_model->getAllProjetosAtivoAcesso('CANCELADO');
                    $cont_p_ativo = 1;
                    ?>
                    <table class="table table-bordered">
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Projeto</th>
                              <th style="">Início</th>
                              <th style="">Fim</th>
                              <th style="">Gerente</th>
                              <th style="">Status</th>
                              <th>Progresso</th>
                              <th style="width: 40px">%</th>
                              
                              <th style="width: 40px">Opções</th>
                            </tr>
                            <?php foreach ($projetos_ativos as $projeto) {
                                $projeto_id = $projeto->id;
                                $projeto_nome = $projeto->nome_projeto;
                                $gerente = $projeto->gerente_area;
                                $resp_tecnico_fase = $this->atas_model->getUserSetorByUserSetor($gerente);
                                $gerente_projeto = $resp_tecnico_fase->nome;
                                    $status = $projeto->status;
                                    if($status == 'ATIVO'){
                                       $status_label = 'success'; 
                                    }else if($status == 'CANCELADO'){
                                        $status_label = 'danger'; 
                                    }else if($status == 'EM AGUARDO'){
                                        $status_label = 'warning'; 
                                    }else if($status == 'CONCLUÍDO'){
                                        $status_label = 'primary'; 
                                    }

                                    $data_projeto = array(
                                    'aba' => 1
                                );
                                $this->projetos_model->updateProjeto($projeto_id, $data_projeto); 
                            ?>
                            <tr>
                              <td><?php echo $cont_p_ativo++; ?></td>
                              <td><?php echo $projeto_nome; ?></td>
                               <td > <small ><?php echo exibirData($projeto->dt_inicio); ?></small></td>
                                        <td > <small ><?php echo exibirData($projeto->dt_final); ?></small></td>
                                        <td > <small ><?php echo $gerente_projeto; ?></small></td>
                                        <td > <small class="label label-<?php echo $status_label; ?>" ><?php echo $status; ?></small></td>

                              <td>
                                <div class="progress progress-xs">
                                  <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                </div>
                              </td>
                              <td><span class="badge bg-red">55%</span></td>
                               <td> 
                                <div class="text-center"><div class="btn-group text-left">
                                        <button style="color:#ffffff" type="button" class="btn btn-default  btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Selecione<span class="caret"></span></button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                          <li><a title="Editar Projeto"  href="<?= site_url('project/editarCadastro/42/'.$projeto_id.'/28/index'); ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i> Editar  </a></li>
                                          <li><a title="Colocar o Projeto em Aguardo. Todas as Ações em aberto serão suspensas." href="#"><i class="fa fa-clock-o"></i>Em Aguardo</a></li>  
                                    </ul>
                                </div>
                                </div>
                               </td>   
                            </tr>
                            <?php } ?> 
                          </table>
                  </div>
                  
                  <div class="tab-pane " id="tab_4">
                    <?php
                    $projetos_ativos = $this->projetos_model->getAllProjetosAtivoAcesso('CONCLUÍDO');
                    $cont_p_ativo = 1;
                    ?>
                    <table class="table table-bordered">
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Projeto</th>
                              <th style="">Início</th>
                              <th style="">Fim</th>
                              <th style="">Gerente</th>
                              <th style="">Status</th>
                              <th>Progresso</th>
                              <th style="width: 40px">%</th>
                              <th style="width: 40px">Selecionar</th>
                              <th style="width: 40px">Opções</th>
                            </tr>
                            <?php foreach ($projetos_ativos as $projeto) {
                                $projeto_id = $projeto->id;
                                $projeto_nome = $projeto->nome_projeto;
                                $gerente = $projeto->gerente_area;
                                   $resp_tecnico_fase = $this->atas_model->getUserSetorByUserSetor($gerente);
                                $gerente_projeto = $resp_tecnico_fase->nome;
                                    $status = $projeto->status;
                                    if($status == 'ATIVO'){
                                       $status_label = 'success'; 
                                    }else if($status == 'CANCELADO'){
                                        $status_label = 'danger'; 
                                    }else if($status == 'EM AGUARDO'){
                                        $status_label = 'warning'; 
                                    }else if($status == 'CONCLUÍDO'){
                                        $status_label = 'primary'; 
                                    }

                                    $data_projeto = array(
                                    'aba' => 1
                                );
                                $this->projetos_model->updateProjeto($projeto_id, $data_projeto); 
                            ?>
                            <tr>
                              <td><?php echo $cont_p_ativo++; ?></td>
                              <td><?php echo $projeto_nome; ?></td>
                               <td > <small ><?php echo exibirData($projeto->dt_inicio); ?></small></td>
                                        <td > <small ><?php echo exibirData($projeto->dt_final); ?></small></td>
                                        <td > <small ><?php echo $gerente_projeto; ?></small></td>
                                        <td > <small class="label label-<?php echo $status_label; ?>" ><?php echo $status; ?></small></td>

                              <td>
                                <div class="progress progress-xs">
                                  <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                </div>
                              </td>
                              <td><span class="badge bg-red">55%</span></td>
                              <td><a title="Para acessar as informações do projeto, deve selecionar o projeto." href="<?= site_url('Project/selecionarProjeto/'.$projeto_id); ?>" class="btn  btn-success"> Selecionar <i class="fa fa-sign-in"></i></a></td>
                              <td> 
                                <div class="text-center"><div class="btn-group text-left">
                                        <button style="color:#ffffff" type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Ações <span class="caret"></span></button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                          <li><a title="Editar Projeto"  href="<?= site_url('project/editarCadastro/42/'.$projeto_id.'/28/index'); ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i> Editar  </a></li>
                                          <li><a title="Excluir Projeto" href="<?= site_url('project/deletarCadastro/42/'.$projeto_id.'/28/index'); ?>"><i class="fa fa-trash"></i>Deletar </a></li>
                                          <li><a title="Cancelar o Projeto. Todas as Ações em aberto serão canceladas." href="#"><i class="fa fa-ban"></i>Cancelar</a></li>  
                                          <li><a title="Colocar o Projeto em Aguardo. Todas as Ações em aberto serão suspensas." href="#"><i class="fa fa-clock-o"></i>Em Aguardo</a></li>  
                                    </ul>
                                </div>
                                </div>
                               </td>   
                            </tr>
                            <?php } ?> 
                          </table>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>
              <!-- nav-tabs-custom -->
            </div>
            </div>
            <hr>
                <?php 
                /*
                $equipes = $this->user_model->getAllPostagemByEquipeDistinct($usuario);
                foreach ($equipes as $post) {
                  $projeto_id =   $post->id_projeto;
                  $projeto =   $post->projeto;

                  $postagens = $this->projetos_model->getAllPostagemByProjeto($projeto_id);



                        $tipo_post = $post->tipo; 
                        $usuario_post = $post->user_id; 
                        $data_post = $post->data_postagem; 

                        $partes = explode(" ", $data_post);
                        $data_postagem = $partes[0];
                        $hora_postagem = $partes[1];

                        $partes_data = explode("-", $data_postagem);
                        $ano2 = $partes_data[0];
                        $mes2 = $partes_data[1];
                        $dia2 = $partes_data[2];

                        $partes_hora = explode(":", $hora_postagem);
                        $hora = $partes_hora[0];
                        $min = $partes_hora[1];
                        $seg = $partes_hora[2];

                        $hora_post = $hora.':'.$min;


                        $titulo = $post->titulo; 
                        $descricao = $post->descricao;

                      $dados_user = $this->site->getUser($usuario_post);
                      $avatar = $dados_user->avatar;
                      $genero = $dados_user->gender;
                      $nome = $dados_user->first_name;

                  if($tipo_post == 1){


                ?>
                <div class="box box-widget">
                <div class="box-header with-border">
                  <div class="user-block">

                       <img class="img-circle" src="<?= $avatar ? $assets . '../../../assets/uploads/avatars/thumbs/' . $avatar : $assets . 'images/' . $genero . '.png'; ?>" alt="User Image">
                    <span class="username"><a href="#"><?php echo $nome; ?></a></span>
                    <span class="description">Publicado em - <?php echo $dia2.'/'.$mes2.'/'.$ano2.'  '.$hora_post; ?></span>
                  </div>
                  <!-- /.user-block -->
                  <div class="box-tools">

                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>

                  </div>
                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <?php if($tipo_post == 1){ ?>

                    <p><?php echo $titulo; ?></p>
                         <a href="<?= site_url('welcome/visualizaImagemHome/'.$post->id_post); ?>" data-toggle="modal" data-target="#myModal"> 

                            <img class="img-responsive pad" src="<?php echo base_url().'assets/uploads/projetos/'.$post->anexo; ?>" alt="Photo">
                           </a>   
                  <p><?php echo $descricao; 

                  /*
                   * <button type="button" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button>
                  <span class="pull-right text-muted">127 likes </span>


                  ?></p>

                    <?php } ?>
                </div>

              </div>

                <?php

                } 
            } 
            */
                ?>
            </div>
        </div>   
  
      
    </div>
    <!-- /.content -->
 