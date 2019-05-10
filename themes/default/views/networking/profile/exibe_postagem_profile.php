<?php
 
if (isset($_POST)) {
    include('../../db_connection.php');

    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Manaus');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    //   $dataLocal = date('d/m/Y H:i:s', time());
    
    $date_hoje = date('Y-m-d H:i:s', time()); 
    $ip = $_SERVER["REMOTE_ADDR"];
    
   // $descricao_checklist = $_POST['descricao_checklist'];
   // $id_plano = $_POST['id_plano'];
    $usuario = $_POST['usuario'];
    $empresa = $_POST['empresa'];

}

?>
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
   

    $date_hoje = date('Y-m-d');
    $sql_postagem = "SELECT u.id as user_id, p.id as post_id, u.*, p.* 
                    FROM sig_postagens p
                    inner join sig_users u on u.id = p.user_de
                    where user_para = $usuario and p.status = 1 order by data_postagem desc";
    $result = mysqli_query($link, $sql_postagem); //$link->query($sql_historico);
    if ($result->num_rows > 0) {
         $cont_total = 1;
        
         while($row = $result->fetch_assoc()) {     
             $user_id = $row["user_id"]; 
             $nome = $row["first_name"]; 
             $avatar = $row["avatar"];
             $gender = $row["gender"];
             $data_postagem = $row["data_postagem"];
             $texto = $row["descricao"];
             $post_id = $row["post_id"];
             
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
             $imagem1 = $row["imagem1"];
             if($imagem1){
                $qtde_imagem++;
                 $url_img_post1 = "assets/uploads/$empresa/posts/$imagem1";
             }
             $imagem2 = $row["imagem2"];
             if($imagem2){
                 $qtde_imagem++;
                 $url_img_post2 = "assets/uploads/$empresa/posts/$imagem2";
             }
             $imagem3 = $row["imagem3"];
             if($imagem3){
                 $qtde_imagem++;
                 $url_img_post3 = "assets/uploads/$empresa/posts/$imagem3";
             }
             $imagem4 = $row["imagem4"];
             if($imagem4){
                 $qtde_imagem++;
                 $url_img_post4 = "assets/uploads/$empresa/posts/$imagem4";
             }
             $imagem5 = $row["imagem5"];
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
                          <a href=""><?php echo $nome; ?></a>
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
                              <img style="height: 250px; min-height: 250px; max-height: 250px;" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                            </div>
                           
                          </div>
                    
                    <?php 
                    
                       }else  if($qtde_imagem == 2){
                           
                            ?>
                    
                                <div class="row margin-bottom">

                                <!-- /.col -->
                                <div  class="col-sm-6">
                                    <img style="height: 250px; min-height: 250px; max-height: 250px;" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                </div>
                                
                                <div class="col-sm-6">
                              <img style="height: 250px; min-height: 250px; max-height: 250px;" class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                            </div>
                                
                                
                            <!-- /.col -->
                            
                                <!-- /.col -->
                              </div>

                        <?php 
                           
                       }else  if($qtde_imagem == 3){
                           
                           ?>
                    
                                <div class="row margin-bottom">

                                <!-- /.col -->
                                <div class="col-sm-4">
                              <img style="width: auto; height: 163px; max-height: 163px;" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                            </div>
                                
                                <div class="col-sm-4">
                              <img style="width: auto; height: 163px; max-height: 163px;"  class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                            </div>
                                
                                <div class="col-sm-4">
                              <img style="width: auto; height: 163px; max-height: 163px;" class="img-responsive" src="<?= $url_img_post3 ?>" alt="Photo">
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
                                <div class="col-sm-6">
                                  <img style="width: auto; height: 250px; max-height: 250px;" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                                  <br>
                                  <img style="width: auto; height: 250px; max-height: 250px;" class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                                </div>
                                <!-- /.col -->
                                <div  class="col-sm-6">
                                  <img style="width: auto; height: 250px; max-height: 250px;" class="img-responsive" src="<?= $url_img_post3 ?>" alt="Photo">
                                  <br>
                                  <img style="width: auto; height: 250px; max-height: 250px;" class="img-responsive" src="<?= $url_img_post4 ?>" alt="Photo">
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
                              <img style="width: auto; height: 250px; max-height: 250px" class="img-responsive" src="<?= $url_img_post1 ?>" alt="Photo">
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6">
                              <div class="row">
                                <div class="col-sm-6">
                                  <img style="width: auto; height: 115px; max-height: 115px" class="img-responsive" src="<?= $url_img_post2 ?>" alt="Photo">
                                  <br>
                                  <img style="width: auto; height: 115px; max-height: 115px" class="img-responsive" src="<?= $url_img_post3 ?>" alt="Photo">
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6">
                                  <img style="width: auto; height: 115px; max-height: 115px" class="img-responsive" src="<?= $url_img_post4 ?>" alt="Photo">
                                  <br>
                                  <img style="width: auto; height: 115px; max-height: 115px" class="img-responsive" src="<?= $url_img_post5 ?>" alt="Photo">
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
    }
    
   ?> 

