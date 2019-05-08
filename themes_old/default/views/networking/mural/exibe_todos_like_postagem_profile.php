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
    $post_id = $_POST['post'];

    
   
}

?>
<script type="text/javascript">
     function registra_like_post(post, usuario) {
                 $.ajax({
                type: "POST",
                url: "themes/default/views/networking/profile/registra_like_post.php",
                data: {
                      usuario: usuario,
                      post: post
                    },
                success: function(data) {
                  $('#div_like_unico'+post).html(data);
                }
              });
            }
            
            
     function registra_deslike_post(post, usuario) {
                 $.ajax({
                type: "POST",
                url: "themes/default/views/networking/profile/registra_deslike_post.php",
                data: {
                      usuario: usuario,
                      post: post
                    },
                success: function(data) {
                  $('#div_like_unico'+post).html(data);
                }
              });
            }       
            
          function registra_comentario_post_unico(post, usuario) {
                 $.ajax({
                type: "POST",
                url: "themes/default/views/networking/profile/registra_comentario_post.php",
                data: {
                      usuario: usuario,
                      post: post, 
                       tipo: 2, 
                      comentario: $('#comentario'+post).val()
                     // comentario:  document.getElementById("comentario").value 
                   
                    },
                success: function(data) {
                  $('#div_like_unico'+post).html(data);
                }
              });
            }     
    
    </script>
    
    <script type="text/javascript">
        function exibe_postagem_unico(post, usuario) {
              $.ajax({
                type: "POST",
                url: "themes/default/views/networking/profile/exibe_postagem_unico.php",
                data: {
                     usuario: usuario,
                      post: post
                    },
                success: function(data) {
                  $('#mural').html(data);
                 
                }

              });

            }
    
    </script>
    
<meta charset="utf-8">

<?php 
    $sql_dados_user = "SELECT * FROM sig_users where id = $usuario ";
    $result_dados_user = mysqli_query($link, $sql_dados_user); //$link->query($sql_historico);
    $row_dados_user = $result_dados_user->fetch_assoc();
    $avatar_novo_comentario = $row_dados_user['avatar'];
    $genero_novo_comentario = $row_dados_user['gender'];
    
    $avatar_profile_novo_comentario = "assets/uploads/avatars/thumbs/$avatar_novo_comentario";
    $avatar_genero_novo_comentario = "assets/images/$genero_novo_comentario".'1'.".png";

    /*********************************************************************************************/

    $date_hoje = date('Y-m-d');
    $sql_like_post = "SELECT * FROM sig_postagem_likes
                    where postagem_id = $post_id and usuario_id = $usuario ";
    $result_like = mysqli_query($link, $sql_like_post); //$link->query($sql_historico);
    $row_like = $result_like->fetch_assoc();
    $like_id = $row_like['id'];
    
    // qtde likes
    $sql_like_post_qtde = "SELECT count(*) as quantidade FROM sig_postagem_likes
                    where postagem_id = $post_id ";
    $result_like_qtde = mysqli_query($link, $sql_like_post_qtde); //$link->query($sql_historico);
    $row_like_qtde = $result_like_qtde->fetch_assoc();
    $like_qtde = $row_like_qtde['quantidade'];
    
    
    // qtde comentarios
    $sql_comentarios_post_qtde = "SELECT count(*) as quantidade_comentario FROM sig_post_comentarios
                    where id_post = $post_id ";
    $result_comentario_qtde = mysqli_query($link, $sql_comentarios_post_qtde); //$link->query($sql_historico);
    $row_coment_qtde = $result_comentario_qtde->fetch_assoc();
    $comentario_qtde = $row_coment_qtde['quantidade_comentario'];
    
    
    // ultimos 3 comentários
    $sql_comentarios_post = "SELECT * FROM sig_post_comentarios c
                        inner join sig_users u on u.id = c.id_user
                        where id_post = $post_id order by date_comentario desc  ";
    $result_comentario = mysqli_query($link, $sql_comentarios_post); //$link->query($sql_historico);
    
?> 
             
    <ul class="list-inline">
          <li>
              <?php 
              if($like_id){
              ?>
              <a style="cursor:pointer;" onclick="registra_deslike_post(<?php echo $post_id; ?>, <?php echo $usuario; ?>)" class="link-black text-sm"><i class="fa fa-thumbs-up  margin-r-5"></i> Like <?php if($like_qtde > 0) { echo '('. $like_qtde.')'; } ?></a>
              
             <?php    
             }else{
             ?>
              <a style="cursor:pointer;" onclick="registra_like_post(<?php echo $post_id; ?>, <?php echo $usuario; ?>)" class="link-black text-sm"><i class="fa fa-thumbs-o-up  margin-r-5"></i> Like <?php if($like_qtde > 0) { echo '('. $like_qtde.')'; } ?></a>
              <?php    
             }
             ?>
        </li>
        <li class="pull-right">
            <a style="cursor:pointer;" onclick="exibe_postagem_unico(<?php echo $post_id; ?>, <?php echo $usuario; ?>)" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comentários
            (<?php echo $comentario_qtde; ?>)</a></li>
      </ul>
      <div class="box-footer box-comments">
          <?php
            while($row_comentario = $result_comentario->fetch_assoc()) {     
             $user_id = $row_comentario["id_user"]; 
             $comentario = $row_comentario["comentario"]; 
             $date_comentario = $row_comentario["date_comentario"]; 
             $nome_comentario = $row_comentario["first_name"]; 
             $avatar_comentario = $row_comentario["avatar"]; 
             $gender_comentario = $row_comentario["gender"]; 
             
             
             $avatar_profile_comentario = "assets/uploads/avatars/thumbs/$avatar_comentario";
             $avatar_genero_comentario = "assets/images/$gender_comentario".'1'.".png";
          ?>
                  <div class="box-comment">
                    <!-- User image -->
                    <img class="img-circle img-sm" src="<?php if($avatar_comentario){ echo $avatar_profile_comentario; }else{ echo $avatar_genero_novo_comentario; }?>" alt="User Image">

                    <div class="comment-text">
                          <span class="username">
                            <?php echo $nome_comentario; ?>
                            <span class="text-muted pull-right"><?php echo date('d/m/Y H:i:s', strtotime($date_comentario)) ?></span>
                          </span><!-- /.username -->
                      <?php echo $comentario; ?>
                    </div>
                    <!-- /.comment-text -->
                  </div>
          <?php
            }
            ?>
                  
                </div>

        <div class="box-footer">
          <div class="form-horizontal">
           <div class="col-sm-10">   
            <img class="img-responsive img-circle img-sm" src="<?php if($avatar_novo_comentario){ echo $avatar_profile_novo_comentario; }else{ echo $avatar_genero_comentario; }?>" alt="Alt Text">
            <!-- .img-push is used to add margin to elements next to floating images -->
            <div class="img-push">
                <input type="text" required="true" maxlength="250" class="form-control input-sm" name="comentario<?php echo $post_id; ?>" id="comentario<?php echo $post_id; ?>" placeholder="Responder">
            </div>
            </div>
            <div class="col-sm-2">
                <button type="button" onclick="registra_comentario_post_unico(<?php echo $post_id; ?>, <?php echo $usuario; ?>)"  class="btn btn-primary pull-right btn-block btn-sm">Enivar</button>
            </div>
          </div>
        </div>
             
      