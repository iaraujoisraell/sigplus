  <?php
    $usuario = $this->session->userdata('user_id');
    $dados_user = $this->site->getUser($usuario);
    $publicao_institucional = $dados_user->publicacoes_institucionais;
    /*
    $dados_user_setor = $this->atas_model->getUserSetorByUserID($usuario);
    $setor_user_id = $dados_user_setor->setores_id;
    
    $dados_setor = $this->atas_model->getSetorByID($setor_user_id);
    $setor_usuario = $dados_setor->nome;
   
    
   * 
   */
     
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
                    Mural de Publicações 
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#">Networking</a></li>
                    <li class="active">Publicações</li>
                </ol>
            </section>
            <br>
            <?php if($publicao_institucional == 1){ ?>
            <div class="box-header">
                    <span class="pull-right-container">
                        <div class=" clearfix no-border">
                           <a  title="Fazer nova postagem" class="btn bg-olive-active pull-right" href="<?= site_url('welcome/novaPostagem_mural'); ?>" data-toggle="modal" data-target="#myModal">  
                            <i class="fa fa-plus"></i> Nova Publicação
                           </a> 
                        </div>
                    </span>
                </div>
            <?php } ?>
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
       
        <!-- /.col -->

        <div class="col-md-12">
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
                            url: "themes/default/views/networking/mural/exibe_like_postagem_profile.php",
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
                            url: "themes/default/views/networking/mural/oculta_post.php",
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
                $usuario =  $this->session->userdata('user_id');
                
                    // $posts_profile = $this->networking_model->getAllPostProfileVisitanteByUser($usuario);
                     foreach ($posts_profile as $posts) {    
                         
                         $dados_user = $this->site->getUserSetorByUser($posts->user_de);
                         $user_id = $dados_user->user_id; 
                         $nome = $dados_user->first_name; 
                         $avatar = $dados_user->avatar;
                         $gender = $dados_user->gender;
                         $setor_id = $dados_user->setor_id;
                       
                         // dados post
                         $data_postagem = $posts->data_postagem;
                         $texto = $posts->descricao;
                         $post_id = $posts->id;

                         if($user_id != $usuario){
                             $descricao = "Publicou";
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
                                      <?php  if($user_id == $usuario){ ?>
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