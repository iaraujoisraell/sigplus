  <?php
    $usuario_visitante = $this->session->userdata('user_id');
 
    $dados_user = $this->site->getUser($usuario);
    
    $dados_user_setor = $this->atas_model->getUserSetorByUserID($usuario);
    $setor_user_id = $dados_user_setor->setores_id;
    
    $dados_setor = $this->atas_model->getSetorByID($setor_user_id);
    $setor_usuario = $dados_setor->nome;
   
    $empresa_perfil = $dados_user->empresa_id;
     
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
                    Perfil de <?php echo $dados_user->first_name; ?>
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
                           <a  title="Fazer nova postagem" class="btn bg-olive-active pull-right" href="<?= site_url('welcome/novaPostagem_visitante/'.$usuario); ?>" data-toggle="modal" data-target="#myModal">  
                           <i class="fa fa-plus"></i> Fazer Publicação
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
                 
            <div class="widget-user-header bg-black" style="background: url('<?= $dados_user->foto_capa ? $assets . '../../../assets/uploads/'.$empresa_perfil.'/avatars/' . $dados_user->foto_capa : $assets . 'bi/dist/img/photo1.png' ?>') center center;">
              
                <h3 class="widget-user-username"><?php echo $dados_user->first_name; ?></h3>
              <h5 class="widget-user-desc"><?php echo $dados_user->cargo; ?></h5>
             
            </div>
            <div class="widget-user-image">
                <img style="width: 88px; height: 88px;" class="img-circle" src="<?= $dados_user->avatar ? $assets . '../../../assets/uploads/'.$empresa_perfil.'/avatars/thumbs/' . $dados_user->avatar : $assets . 'images/' . $dados_user->gender . '.png'; ?>" alt="User Avatar">
            </div>
            <?php 
              $qtde_projetos_users = $this->networking_model->getQuantidadeProjetosEquipeProfileVisitante($usuario);
              $qtde_projeto = $qtde_projetos_users->quantidade_projetos;
              
             // AÇÕES PENDENTES
             $qtde_acoes_pendentes_users = $this->networking_model->getQuantidadeAcoesPendenteUserEmpresaProfileVisitante($usuario);
             $qtde_acao_pendente = $qtde_acoes_pendentes_users->qtde_acoes_pendentes;

             // AÇÕES AGUARDANDO VALIDAÇÃO
             $qtde_acoes_aguardando_users = $this->networking_model->getQuantidadeAcoesAguardandoValidacaoUserEmpresaProfileVisitante($usuario);
             $qtde_acao_aguardando = $qtde_acoes_aguardando_users->qtde_acoes_aguardando;
                
             // AÇÕES ATRASADAS
             $qtde_acoes_atrasadas_users = $this->networking_model->getQuantidadeAcoesAtrasadasUserEmpresaProfileVisitante($usuario);
             $qtde_acao_atrasada = $qtde_acoes_atrasadas_users->qtde_acoes_atrasadas;
            
             $total_acao_pendente = $qtde_acao_pendente + $qtde_acao_aguardando + $qtde_acao_atrasada;
             
             // AÇÕES CONCLUÍDAS
            $qtde_acoes_concluidas_users = $this->networking_model->getQuantidadeAcoesConcluidasUserEmpresaProfileVisitante($usuario);
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

        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#mural" data-toggle="tab">Mural</a></li>
              </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="mural">
                
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
                $usuario_visitante =  $this->session->userdata('user_id'); 
                $empresa = $dados_user->empresa_id;
                     $posts_profile = $this->networking_model->getAllPostProfileVisitanteByUser($usuario);
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
                         
                         /***SUPORTE***
                        $empresa = $this->session->userdata('empresa');
                        $dados_empresa = $this->owner_model->getEmpresaById($empresa);
                        $empresa_id = $dados_empresa->id;
                        $suporte = $dados_empresa->suporte;
                        */
        
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
                         $avatar_profile = "assets/uploads/avatars/thumbs/$avatar";
                         $avatar_genero = "assets/images/$gender".'1'.".png";


                        ?> 

                            <div class="post clearfix">
                              <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="<?php if($avatar){ echo $avatar_profile; }else{ echo $avatar_genero; }?> " alt="User Image">
                                    <span class="username">
                                      <a href="<?= site_url('welcome/profile_visitor/'.$user_id); ?>"><?php echo $nome; ?></a>
                                      <?php  if($user_id == $usuario_visitante){ ?>
                                      <a style="cursor:pointer;" onclick="oculta_post(<?php echo $post_id; ?>, <?php echo $usuario; ?>)" class="pull-right btn-box-tool"><i title="Remover" class="fa fa-trash"></i></a>
                                      <?php  } ?>
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
                                <?php echo $texto; ?>
                              </p>
                              <div id="div_like<?php echo $post_id; ?>">
                                  <script> exibe_like_post(<?php echo $post_id; ?>, <?php echo $usuario_visitante; ?>) </script>
                              </div>

                            </div>

                    <?php     
                    $cont_total++;
                    }   
           
                    //    }
                ?>
                
              </div>
            
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