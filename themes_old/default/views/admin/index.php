<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div style="background-color: #f2f2f2" class="content-wrapper">
    
    <?php 
    $usuario =  $this->session->userdata('user_id'); 

    ?>
  
    <!-- Content Header (Page header) -->
     <section class="content-header">
      
      <ol class="breadcrumb">
        <li><a href="<?= site_url('welcome/home'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <br>
    <!-- Main content -->
    <section class="content">
      
        
      <div class="row">
        
          
        <!-- Left col -->
        <section class="col-lg-12 " >
            <div class="box box-widget">
                <div class="box box-warning">
                <h3></h3>
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
        </section>
        
        
        
        
            
        
        
      </div>
        
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
 
 </div>
  
 