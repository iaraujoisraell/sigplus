  <?php
   $empresa = $this->session->userdata('empresa');
    $usuario = $this->session->userdata('user_id');
    $dados_user = $this->site->getUser($usuario);
    
    $dados_user_setor = $this->atas_model->getUserSetorByUserID($usuario);
    $setor_user_id = $dados_user_setor->setores_id;
    
    $dados_setor = $this->atas_model->getSetorByID($setor_user_id);
    $setor_usuario = $dados_setor->nome;
   
     
    ?>
<!-- Content Wrapper. Contains page content -->
  <script type="text/javascript">
/* Máscaras ER */
function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mtel(v){
    v=v.replace(/\D/g,"");             //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}
function id( el ){
	return document.getElementById( el );
}
window.onload = function(){
	id('telefone').onkeyup = function(){
		mascara( this, mtel );
	}
}
</script>
    <!-- Content Header (Page header) -->
    <div class="col-lg-12">
        <div class="box">
            <section class="content-header">
                <h1>
                    Perfil 
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Networking</a></li>
                    <li class="active">User profile</li>
                </ol>
            </section>
            <br>
            <div class="box-header">
                    <span class="pull-right-container">
                       <div class=" clearfix no-border">
                           <a  title="Fazer nova postagem" class="btn bg-olive-active pull-right" href="<?= site_url('welcome/novaPostagem'); ?>" data-toggle="modal" data-target="#myModal">  
                           <i class="fa fa-plus"></i>   Nova Publicação
                           </a> 
                         
                        </div>
                    </span>
                </div>
            <br>
        </div>     
    </div>     
    <div class="row">  
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
    
    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
                 
            <div class="widget-user-header bg-black" style="background: url('<?= $this->session->userdata('foto_capa') ? $assets . '../../../assets/uploads/'.$empresa.'/avatars/' . $this->session->userdata('foto_capa') : $assets . 'bi/dist/img/photo1.png' ?>') center center;">
              
                <h3 class="widget-user-username"><?php echo $dados_user->first_name; ?></h3>
              <h5 class="widget-user-desc"><?php echo $dados_user->cargo; ?></h5>
             
            </div>
            <div class="widget-user-image">
                <img style="width: 88px; height: 88px;" class="img-circle" src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/'.$empresa.'/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" alt="User Avatar">
            </div>
            <?php 
              $qtde_projetos_users = $this->networking_model->getQuantidadeProjetosEquipe();
              $qtde_projeto = $qtde_projetos_users->quantidade_projetos;
              ?>  
            
             <?php 
             // AÇÕES PENDENTES
             $qtde_acoes_pendentes_users = $this->networking_model->getQuantidadeAcoesPendenteUserEmpresa();
             $qtde_acao_pendente = $qtde_acoes_pendentes_users->qtde_acoes_pendentes;

             // AÇÕES AGUARDANDO VALIDAÇÃO
             $qtde_acoes_aguardando_users = $this->networking_model->getQuantidadeAcoesAguardandoValidacaoUserEmpresa();
             $qtde_acao_aguardando = $qtde_acoes_aguardando_users->qtde_acoes_aguardando;
                
             // AÇÕES ATRASADAS
             $qtde_acoes_atrasadas_users = $this->networking_model->getQuantidadeAcoesAtrasadasUserEmpresa();
             $qtde_acao_atrasada = $qtde_acoes_atrasadas_users->qtde_acoes_atrasadas;
            
             $total_acao_pendente = $qtde_acao_pendente + $qtde_acao_aguardando + $qtde_acao_atrasada;
             
             // AÇÕES CONCLUÍDAS
            $qtde_acoes_concluidas_users = $this->networking_model->getQuantidadeAcoesConcluidasUserEmpresa();
            $qtde_acao_concluida = $qtde_acoes_concluidas_users->qtde_acoes_concluidas;
              ?>
            <div class="box-footer">
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Projetos</b> <a class="pull-right"><label title="Projetos" class="label label-primary"><?php echo $qtde_projeto; ?></label></a>
                </li>
                <li class="list-group-item">
                    <b>Ações</b> <a class="pull-right"><label title="Ações Pendentes" class="label label-warning"><?php echo $total_acao_pendente; ?></label><label title="Ações Concluídas" class="label label-success"><?php echo $qtde_acao_concluida; ?></label></a>
                </li>
              </ul>
              <!-- /.row -->
              <small style="font-style: italic; text-align: justify"><?php echo "$dados_user->mensagem_perfil"; ?></small>
            </div>
          </div>
          
          <?php
            // Declara a data! :P
            $data_admissao_empresa = $dados_user->data_admissao;
            $date_hoje = date('Y-m-d');
            $date = new DateTime( $data_admissao_empresa ); // data de nascimento
            $interval = $date->diff( new DateTime( $date_hoje ) ); // data definida
           
          ?>
          <!-- /.box -->
          
          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Perfil</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-map-marker margin-r-5"></i> Setor</strong>
              <p class="text-muted"><?php echo $setor_usuario; ?></p>
              
              
              <hr>
              <strong><i class="fa fa-clock-o margin-r-5"></i> Tempo de empresa</strong>
              <?php if($data_admissao_empresa){ ?>
              <p class="text-muted"><?php echo $interval->format( '%Y Anos, %m Meses' ); ?></p>
              <?php } ?>
              <hr>
              
              <strong><i class="fa fa-book margin-r-5"></i> Formação</strong>

              <p class="text-muted">
                <?php echo $dados_user->formacao_academica; ?>
              </p>
              <hr>
              
              <?php if($dados_user->linkedin){ ?>
             
              <strong><i class="fa fa-linkedin margin-r-5"></i> <a href="<?php echo $dados_user->linkedin; ?>" target="_blank"> Perfil Linkedin</a> </strong>
              <hr>
              
              <?php } ?>
              <strong><i class="fa fa-envelope margin-r-5"></i> E-mail</strong>
              <p class="text-muted"><?php echo $dados_user->email; ?></p>
              <strong><i class="fa fa-phone-square margin-r-5"></i> Ramal</strong>
              <p class="text-muted"> <?php echo $dados_user->ramal; ?></p>
              <strong><i class="fa fa-phone margin-r-5"></i> Celular</strong>
              <p class="text-muted"><?php echo $dados_user->phone; ?> </p>
              <strong><i class="fa fa-fax margin-r-5"></i> Corporativo</strong>
              <p class="text-muted"> <?php echo $dados_user->corporativo; ?></p>
              <hr>
              

              <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>
              <?php
               $habilidades = $dados_user->habilidades;
               $array = explode(';', $habilidades);
               
              ?>
              <p>
                <?php
                $cont_skills = 1;
                foreach($array as $valores)
                {
                  
                    if($cont_skills == 1){
                        $label = "danger";
                    }else if($cont_skills == 2){
                        $label = "success";
                    }else if($cont_skills == 3){
                        $label = "info";
                    }else if($cont_skills == 4){
                        $label = "warning";
                    }else if($cont_skills == 5){
                        $label = "primary";
                        $cont_skills = 0;
                    }
                    
                  
                ?>
                  <span class="label label-<?php echo $label; ?>"><?php echo $valores; ?></span> <br>
                
                <?php $cont_skills++; 
                } ?>
              </p>

              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <script type="text/javascript">
        function exibe_mural_profile(usuario) {
              $.ajax({
                type: "POST",
                url: "themes/default/views/networking/profile/exibe_postagem_profile.php",
                data: {
                      usuario: usuario,
                      empresa: <?php echo $dados_user->empresa_id; ?>
                    },
                success: function(data) {
                  $('#mural').html(data);
                 
                }

              });

            }
    
    </script>
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#mural" data-toggle="tab">Mural</a></li>
             
              <li><a href="#perfil" data-toggle="tab">Perfil</a></li>
              <li><a href="#avatar" data-toggle="tab">Avatar</a></li>
              <li><a href="#cpassword" data-toggle="tab">Mudar Senha</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="mural">
                <script> 
                  //  exibe_mural_profile(<?php echo $usuario; ?>);
                </script>
                
                <script type="text/javascript">
    function exibe_like_post(post, usuario) {
             $.ajax({
            type: "POST",
            url: "themes/default/views/networking/profile/exibe_like_postagem_profile.php",
            data: {
                  usuario: usuario,
                  post: post
                },
            success: function(data) {
              $('#div_like'+post).html(data);
            }
          });
        }        
    
      function oculta_post(post, usuario) {
             $.ajax({
            type: "POST",
            url: "themes/default/views/networking/profile/oculta_post.php",
            data: {
                  usuario: usuario,
                  post: post
                 // comentario:  document.getElementById("comentario").value 
                },
            success: function(data) {
              $('#div_like'+post).html(data);
            }
          });
        }
    </script>
                <meta charset="utf-8">

            <?php 
            /*
                $date_hoje = date('Y-m-d');
                $sql_postagem = "SELECT u.id as user_id, p.id as post_id, u.*, p.* 
                                FROM sig_postagens p
                                inner join sig_users u on u.id = p.user_de
                                where user_para = $usuario and p.status = 1 order by data_postagem desc";
                $result = mysqli_query($link, $sql_postagem); //$link->query($sql_historico);
                if ($result->num_rows > 0) {
                     $cont_total = 1;
             * 
             */
                $empresa = $dados_user->empresa_id;
                     $posts_profile = $this->networking_model->getAllPostProfileByUser();
                     foreach ($posts_profile as $posts) {    
                         $user_id = $posts->user_id; 
                         $nome = $posts->first_name; 
                         $avatar = $posts->avatar;
                         $gender = $posts->gender;
                         $data_postagem = $posts->data_postagem;
                         $texto = $posts->descricao;
                         $post_id = $posts->post_id;

                         if($user_id != $usuario){
                             $descricao = "Enviou uma mensagem para você";
                         }else{
                             $descricao = "Publicou";
                         }


                         // CALCULA A DIFERENÇA ENTRE A DATA DA PUBLICAÇÃO E HOJE
                         $data_inicial = $data_postagem;
                         $data_final = $date_hoje;
                         // Calcula a diferença em segundos entre as datas
                         $diferenca = strtotime($data_final) - strtotime($data_inicial);
                         //Calcula a diferença em dias
                         $dias = floor($diferenca / (60 * 60 * 24)) + 1;

                         if($dias > 1){
                             $text_dias = "dias";
                         }else{
                             $text_dias = "dia";
                         }

                         // Imagens
                         $qtde_imagem = 0;
                         $imagem1 = $posts->imagem1; 
                         if($imagem1){
                            $qtde_imagem++;
                             $url_img_post1 = "assets/uploads/$empresa/posts/$imagem1";
                         }
                         $imagem2 = $posts->imagem2; 
                         if($imagem2){
                             $qtde_imagem++;
                             $url_img_post2 = "assets/uploads/$empresa/posts/$imagem2";
                         }
                         $imagem3 = $posts->imagem3; 
                         if($imagem3){
                             $qtde_imagem++;
                             $url_img_post3 = "assets/uploads/$empresa/posts/$imagem3";
                         }
                         $imagem4 = $posts->imagem4; 
                         if($imagem4){
                             $qtde_imagem++;
                             $url_img_post4 = "assets/uploads/$empresa/posts/$imagem4";
                         }
                         $imagem5 = $posts->imagem5; 
                         if($imagem5){
                             $qtde_imagem++;
                             $url_img_post5 = "assets/uploads/$empresa/posts/$imagem5";
                         }


                         $assets = "themes/default/assets/";
                         $avatar_profile = "assets/uploads/$empresa/avatars/thumbs/$avatar";
                         $avatar_genero = "assets/images/$gender".'1'.".png";


                        ?> 

                            <div class="post clearfix">
                              <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="<?php if($avatar){ echo $avatar_profile; }else{ echo $avatar_genero; }?> " alt="User Image">
                                    <span class="username">
                                      <a href="<?= site_url('welcome/profile_visitor/'.$user_id); ?>"><?php echo $nome; ?></a>
                                      <?php // if($user_id == $usuario){ ?>
                                      <a style="cursor:pointer;" onclick="oculta_post(<?php echo $post_id; ?>, <?php echo $usuario; ?>)" class="pull-right btn-box-tool"><i title="Remover" class="fa fa-trash"></i></a>
                                      <?php // } ?>
                                    </span>
                                <span class="description"><?php echo $descricao. ' em '. date('d/m/Y H:i:s', strtotime($data_postagem)); ?> </span>
                              </div>

                                 <?php
                                if($qtde_imagem > 0){ 

                                   if($qtde_imagem == 1){  
                                ?>

                                        <div class="row margin-bottom">
                                        <div class="col-sm-6">
                                            <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem1"); ?>" data-toggle="modal" data-target="#myModal">   
                                                <img style="width: 100%; " class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                            </a>
                                        </div>

                                      </div>

                                <?php 

                                   }else  if($qtde_imagem == 2){

                                        ?>

                                        <div class="row margin-bottom">

                                            <!-- /.col -->
                                            <div  class="col-sm-6">
                                               <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem1"); ?>" data-toggle="modal" data-target="#myModal">    
                                                <img style="width: 100%; height: 70%; min-height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                                </a>
                                            </div>

                                            <div class="col-sm-6">
                                                <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem2"); ?>" data-toggle="modal" data-target="#myModal">   
                                                    <img style=" width: 100%; height: 70%; min-height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                                                </a>
                                            </div>


                                        </div>

                                    <?php 

                                   }else  if($qtde_imagem == 3){

                                       ?>

                                            <div class="row margin-bottom">

                                            <!-- /.col -->
                                            <div class="col-sm-4">
                                                <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem1"); ?>" data-toggle="modal" data-target="#myModal">    
                                                <img style=" width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                                </a>
                                          
                                        </div>

                                            <div class="col-sm-4">
                                                <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem2"); ?>" data-toggle="modal" data-target="#myModal">       
                                          <img style=" width: 100%; height: 70%; max-height: 70%;"  class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                                          </a>
                                        </div>

                                            <div class="col-sm-4">
                                             <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem3"); ?>" data-toggle="modal" data-target="#myModal">        
                                          <img style=" width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post3 ?>" alt="Photo">
                                          </a>
                                        </div>
                                        <!-- /.col -->

                                            <!-- /.col -->
                                          </div>

                                    <?php 

                                   }else  if($qtde_imagem == 4){

                                       ?>

                                        <div  class="row margin-bottom">

                                        <!-- /.col -->
                                        <div class="col-sm-12">
                                          <div class="row">
                                            <div class="col-sm-3">
                                                <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem1"); ?>" data-toggle="modal" data-target="#myModal">    
                                              <img style="width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                              </a>
                                            </div>  
                                              <div class="col-sm-3">
                                                  <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem2"); ?>" data-toggle="modal" data-target="#myModal">    
                                              <img style="width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                                              </a>
                                            </div>
                                            <!-- /.col -->
                                            <div  class="col-sm-3">
                                                <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem3"); ?>" data-toggle="modal" data-target="#myModal">  
                                              <img style="width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post3 ?>" alt="Photo">
                                              </a>
                                            </div>  
                                            <div class="col-sm-3">
                                                <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem4"); ?>" data-toggle="modal" data-target="#myModal">  
                                              <img style="width: 100%; height: 70%; max-height: 70%;" class="img-responsive" src="<?= $url_img_post4 ?>" alt="Photo">
                                              </a>
                                            </div>
                                            <!-- /.col -->
                                          </div>
                                          <!-- /.row -->
                                        </div>
                                        <!-- /.col -->
                                      </div>

                                <?php 

                                   }else  if($qtde_imagem == 5){

                                       ?>

                                        <div class="row margin-bottom">
                                        <div class="col-sm-6">
                                          <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem1"); ?>" data-toggle="modal" data-target="#myModal">  
                                            <img style="width: auto; height: 71%; max-height: 71%" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                          </a>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-6">
                                          <div class="row">
                                            <div class="col-sm-6">
                                                <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem2"); ?>" data-toggle="modal" data-target="#myModal">  
                                                    <img style="width: auto; height: 34%; max-height: 34%" class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                                                </a>
                                              <br>
                                              <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem3"); ?>" data-toggle="modal" data-target="#myModal">
                                                <img style="width: auto; height: 34%; max-height: 34%" class="img-responsive" src="<?= $url_img_post3 ?>" alt="Photo">
                                              </a>
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-sm-6">
                                                <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem4"); ?>" data-toggle="modal" data-target="#myModal">  
                                              <img style="width: auto; height: 34%; max-height: 34%" class="img-responsive" src="<?= $url_img_post4 ?>" alt="Photo">
                                              </a>
                                              <br>
                                              <a href="<?= site_url('welcome/visualizaImagemHome/'.$post_id.'/'."$imagem5"); ?>" data-toggle="modal" data-target="#myModal">
                                              <img style="width: auto; height: 34%; max-height: 34%" class="img-responsive" src="<?= $url_img_post5 ?>" alt="Photo">
                                              </a>
                                            </div>
                                            <!-- /.col -->
                                          </div>
                                          <!-- /.row -->
                                        </div>
                                        <!-- /.col -->
                                      </div>

                                <?php 

                                   }



                                } ?>


                              <p>
                                <?php echo $texto; ?>;
                              </p>
                              <div id="div_like<?php echo $post_id; ?>">
                                  <script> exibe_like_post(<?php echo $post_id; ?>, <?php echo $usuario; ?>) </script>
                              </div>

                            </div>

                    <?php     
                    $cont_total++;
                    }   
           
                    //    }
                ?>
                
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-red">
                          10 Feb. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-envelope bg-blue"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                      <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                      <div class="timeline-body">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                        weebly ning heekya handango imeem plugg dopplr jibjab, movity
                        jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                        quora plaxo ideeli hulu weebly balihoo...
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs">Read more</a>
                        <a class="btn btn-danger btn-xs">Delete</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-user bg-aqua"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                      <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                      </h3>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-comments bg-yellow"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                      <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                      <div class="timeline-body">
                        Take me to your leader!
                        Switzerland is small and neutral!
                        We are more like Germany, ambitious and misunderstood!
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-green">
                          3 Jan. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-camera bg-purple"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                      <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                      <div class="timeline-body">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>
                </ul>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="perfil">
                <div class="form-horizontal">
                     
                 <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
                    echo form_open_multipart("welcome/profile", $attrib); 
                    echo form_hidden('id_cadastro_profile', '1'); 
                    echo form_hidden('id', $id);
                    
                ?>
                  <!-- NOME DO COLABORADOR -->  
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Nome</label>
                    <div class="col-sm-10">
                        <input type="text" name="nome" class="form-control" readonly="true" value="<?php echo $dados_user->first_name; ?>" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <!-- EMAIL DO COLABORADOR --> 
                  <div class="form-group">
                    <label for="inputEmail"  class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" readonly="true" value="<?php echo $dados_user->email; ?>" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <!-- SETOR DO COLABORADOR -->
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Setor</label>
                    <div class="col-sm-10">
                        <input type="text" name="setor" class="form-control" readonly="true"  value="<?php echo $setor_usuario; ?>" id="setor"  placeholder="Setor">
                    </div>
                  </div>
                  <!-- CARGO/FUNÇÃO DO COLABORADOR -->
                  <div class="form-group">
                    <label for="inputName" readonly="true" class="col-sm-2 control-label">Cargo</label>
                    <div class="col-sm-10">
                        <input type="text" name="cargo" class="form-control" readonly="true" value="<?php echo $dados_user->cargo; ?>" id="inputName" placeholder="Cargo">
                    </div>
                  </div>  
                  <!-- DATA_ADMINISSAO DO COLABORADOR -->
                  <div class="form-group">
                    <label for="data_admissao"  class="col-sm-2 control-label">Dt Admissão</label>
                    <div class="col-sm-10">
                        <input type="date" name="data_admissao" class="form-control" value="<?php echo $dados_user->data_admissao; ?>" id="data_admissao" placeholder="Data de Admissão">
                    </div>
                  </div>
                  <!-- DATA NASCIMENTO DO COLABORADOR -->
                  <div class="form-group">
                    <label for="data_nascimento"  class="col-sm-2 control-label">Dt Nascim.</label>
                    <div class="col-sm-10">
                        <input type="date" name="data_nascimento" class="form-control" value="<?php echo $dados_user->data_aniversario; ?>" id="data_nascimento" placeholder="Data de Nascimento">
                    </div>
                  </div>
                  <!-- DATA NASCIMENTO DO COLABORADOR -->
                  <div class="form-group">
                    <label for="ramal" class="col-sm-2 control-label">Ramal</label>
                    <div class="col-sm-10">
                        <input type="number" name="ramal" class="form-control" maxlength="9" value="<?php echo $dados_user->ramal; ?>" id="ramal" placeholder="Ramal">
                    </div>
                  </div>
                  <!-- CELULAR 1 -->
                  <div class="form-group">
                    <label for="celular1" class="col-sm-2 control-label">Celular</label>
                    <div class="col-sm-10">
                        <input type="text" name="celular1" onkeyup="mascara( this, mtel );" maxlength="15" class="form-control"  value="<?php echo $dados_user->phone; ?>" id="celular1" placeholder="(99) 99999-9999">
                    </div>
                  </div>
                  <!-- CELULAR 2 -->
                  <div class="form-group">
                    <label for="celular_corportativo" class="col-sm-2 control-label">Cel. Corp.</label>
                    <div class="col-sm-10">
                        <input type="text" title="Celular Corporativo" onkeyup="mascara( this, mtel );" maxlength="15" name="celular_corportativo" class="form-control"  value="<?php echo $dados_user->corporativo; ?>" id="celular_corportativo" placeholder="(99) 99999-9999">
                    </div>
                  </div>
                  <!-- LINKEDIN -->
                  <div class="form-group">
                    <label for="inputLinkedin" class="col-sm-2 control-label">Linkedin (link)</label>
                    <div class="col-sm-10">
                        <input type="text" name="linkedin" title="Informe se desejar o link do seu linkedin, para que outras possoas possam visitar seu perfil." class="form-control" maxlength="150"  value="<?php echo $dados_user->linkedin; ?>" id="linkedin"  placeholder="Perfil do Linkedin">
                    </div>
                  </div>  
                  <!-- SKILLS -->
                  <div class="form-group">
                       <label for="inputSkills" readonly="true" class="col-sm-2 control-label">Habilidades</label>
                    <div class="col-sm-10">
                        <input type="text" name="inputSkills" title="Separe as suas habilidades por ';'.  Ex: Hab 1; Hab 2; Hab 3;" maxlength="250" class="form-control"  value="<?php echo $dados_user->habilidades; ?>" id="inputSkills" placeholder="Separe por (;). Ex: Gestão de Projetos; Contabilidade; Contas a Pagar;">
                    </div>
                  </div>
                  <!-- FORMAÇÃO ACADÊMICA -->
                  <div class="form-group">
                    <label for="formacaoAcademica" class="col-sm-2 control-label">Formação Acadêmica</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="formacaoAcademica" id="formacaoAcademica" placeholder="Fale um pouco sobre sua formação acadêmica"><?php echo $dados_user->formacao_academica; ?></textarea>
                    </div>
                  </div>
                  <!-- MENSAGEM PERFIL-->
                  <div class="form-group">
                    <label for="mensagemPerfil" class="col-sm-2 control-label">Mensagem do Perfil</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="mensagemPerfil" id="mensagemPerfil" placeholder="Você pode deixar uma mensagem de saudação (frase, provérbio, etc) para quem visitar seu perfil."><?php echo $dados_user->mensagem_perfil; ?></textarea>
                    </div>
                  </div>  
                  
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <?php echo form_submit('add_item', lang("Salvar"), 'id="add_item" class="btn btn-success "  '); ?>
                    </div>
                  </div>
                    <?php echo form_close(); ?>
                </div>
              </div>
              
              <div id="avatar" class="tab-pane fade">
                 
                  <div class="box">
                      <br>
                    <div class="box-content">
                         <?php echo form_open_multipart("auth/update_foto_capa"); ?>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-md-12">
                                    <h3>Foto de Capa</h3>
                                    <div style="position: relative;">
                                        
                                        <img alt="" width="100%" src="<?= $this->session->userdata('foto_capa') ? $assets . '../../../assets/uploads/'.$empresa.'/avatars/' . $this->session->userdata('foto_capa') : $assets . 'bi/dist/img/photo1.png'; ?>"
                                                 class="profile-image img-thumbnail">
                                              <?php if ($user->foto_capa) { ?>
                                            <a href="#" class="btn btn-danger btn-xs po" style="position: absolute; top: 0;" title="<?= lang('Deletar Foto de Capa?') ?>"
                                               data-content="<p><?= lang('r_u_sure') ?></p><a class='btn btn-block btn-danger po-delete' href='<?= site_url('auth/delete_capa/' . $id . '/' . $user->foto_capa) ?>'> <?= lang('i_m_sure') ?></a> <button class='btn btn-block po-close'> <?= lang('no') ?></button>"
                                               data-html="true" rel="popover"><i class="fa fa-trash-o"></i></a><br>
                                            <?php } ?>   
                                            <br>
                                    </div>
                                    
                                    <div class="form-group">
                                        <?= lang("Alterar Foto de Capa", "change_avatar"); ?> <small>Tamanho ideal: 1250 x 835px</small>
                                        <input type="file" data-browse-label="<?= lang('browse'); ?>" name="capa"  
                                               data-show-upload="false" data-show-preview="false" accept="image/*"
                                               class="form-control file"/>
                                    </div>
                                    

                                </div>
                               
                            </div>
                        </div>
                        <div class="form-group">
                                        <?php echo form_hidden('id', $id); ?>
                                        <?php echo form_hidden($csrf); ?>
                                        <?php echo form_submit('update_avatar', lang('Alterar Foto de Capa'), 'class="btn btn-primary"'); ?>
                                        <?php echo form_close(); ?>
                                    </div>
                    </div>  
                      <hr>
                    <div class="box-content">
                        
                         <?php echo form_open_multipart("auth/update_avatar"); ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-md-6">
                                    <div style="position: relative;">
                                       
                                            <img alt=""
                                                 src="<?= $this->session->userdata('avatar') ? $assets . '../../../assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>"
                                                 class="profile-image img-thumbnail">
                                             <?php if ($user->avatar) { ?>
                                            <a href="#" class="btn btn-danger btn-xs po"
                                               style="position: absolute; top: 0;" title="<?= lang('delete_avatar') ?>"
                                               data-content="<p><?= lang('r_u_sure') ?></p><a class='btn btn-block btn-danger po-delete' href='<?= site_url('auth/delete_avatar/' . $id . '/' . $user->avatar) ?>'> <?= lang('i_m_sure') ?></a> <button class='btn btn-block po-close'> <?= lang('no') ?></button>"
                                               data-html="true" rel="popover"><i class="fa fa-trash-o"></i></a><br>
                                            <br><?php } ?>
                                    </div>
                                    
                                    <div class="form-group">
                                        <?= lang("change_avatar", "change_avatar"); ?> <small>Tamanho ideal: 88px x 88px</small>
                                        <input type="file" data-browse-label="<?= lang('browse'); ?>" name="avatar" id="product_image" required="required"
                                               data-show-upload="false" data-show-preview="false" accept="image/*"
                                               class="form-control file"/>
                                    </div>
                                    

                                </div>
                               
                            </div>
                        </div>
                        
                        <div class="form-group">
                                        <?php echo form_hidden('id', $id); ?>
                                        <?php echo form_hidden($csrf); ?>
                                        <?php echo form_submit('update_avatar', lang('update_avatar'), 'class="btn btn-primary"'); ?>
                                        <?php echo form_close(); ?>
                                    </div>
                    </div>
                </div>
                
                  
                  
            </div>
              
              <div id="cpassword" class="tab-pane fade">
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-key nb"></i><?= lang('change_password'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo form_open("auth/change_password", 'id="change-password-form"'); 
                                      echo form_hidden('usuario', $usuario);
                                      echo form_hidden('empresa', $dados_user->empresa_id);
                                ?>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('old_password', 'curr_password'); ?> <br/>
                                                <?php echo form_password('old_password', '', 'class="form-control" id="curr_password" required="required"'); ?>
                                            </div>

                                            <div class="form-group">
                                                <label
                                                    for="new_password"><?php echo sprintf(lang('new_password'), $min_password_length); ?></label>
                                                <br/>
                                                <?php echo form_password('new_password', '', 'class="form-control" id="new_password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-regexp-message="'.lang('pasword_hint').'"'); ?>
                                                <span class="help-block"><small><?= lang('pasword_hint') ?></small></span>
                                            </div>

                                            <div class="form-group">
                                                <?php echo lang('confirm_password', 'new_password_confirm'); ?> <br/>
                                                <?php echo form_password('new_password_confirm', '', 'class="form-control" id="new_password_confirm" required="required" data-bv-identical="true" data-bv-identical-field="new_password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>

                                            </div>
                                            
                                            <p><?php echo form_submit('change_password', lang('change_password'), 'class="btn btn-primary"'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->

  <!-- /.content-wrapper -->