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

    
    
    $date_hoje = date('Y-m-d');
    $sql_postagem_unica = "SELECT u.id as user_id, p.id as post_id, u.*, p.* 
                    FROM sig_postagens p
                    inner join sig_users u on u.id = p.user_de
                    where p.id = $post_id ";
    $result_id = mysqli_query($link, $sql_postagem_unica); //$link->query($sql_historico);
    $row_post_unico = $result_id->fetch_assoc();
    
    $user_id = $row_post_unico["user_id"]; 
     $nome = $row_post_unico["first_name"]; 
     $avatar = $row_post_unico["avatar"];
     $gender = $row_post_unico["gender"];
     $data_postagem = $row_post_unico["data_postagem"];
     $texto = $row_post_unico["descricao"];
     $posagem_id = $row_post_unico["post_id"];

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

     $assets = "themes/default/assets/";
     $avatar_profile = "assets/uploads/avatars/thumbs/$avatar";
     $avatar_genero = "assets/images/$gender".'1'.".png";
   
}

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
    
    
    $sql_comentarios_post_todos = "SELECT * FROM sig_post_comentarios c
                        inner join sig_users u on u.id = c.id_user
                        where id_post = $post_id order by date_comentario desc  ";
    $result_comentario_todos = mysqli_query($link, $sql_comentarios_post_todos); //$link->query($sql_historico);

?>

<script>
        function exibe_like_post_unico(post, usuario) {
                 $.ajax({
                type: "POST",
                url: "themes/default/views/networking/mural/exibe_todos_like_postagem_profile.php",
                data: {
                      usuario: usuario,
                      post: post
                    },
                success: function(data) {
                  $('#div_like_unico'+post).html(data);
                }
              });
            }
    
    
    
</script> 

<div class="post clearfix">
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="<?php if($avatar){ echo $avatar_profile; }else{ echo $avatar_genero; }?> " alt="User Image">
                        <span class="username">
                          <a ><?php echo  $nome; ?></a>
                          <a style="cursor:pointer;" onclick="history.go(0);" class="pull-right btn-box-tool"><i title="Retornar" class="fa fa-reply"></i></a>
                        </span>
                    <span class="description"><?php echo $descricao. ' em '. date('d/m/Y H:i:s', strtotime($data_postagem)); ?> </span>
                  </div>
                  <!-- /.user-block -->
                  <p>
                    <?php echo $texto; ?>;
                  </p>
                  
                  <div id="div_like_unico<?php echo $post_id; ?>">
                      <script> exibe_like_post_unico(<?php echo $post_id; ?>, <?php echo $usuario; ?>) </script>
                  </div>
                  
                  
                </div>