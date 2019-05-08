<?php
  /*
   * 1 - INSERT
   * 2 - STATUS = CONCLUIDO
     

 $id_plano = $_GET['id_plano'];
 $atualizou = $_GET['atualizou'];
 */


 
if (isset($_POST)) {
    include('../../db_connection.php');

    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Manaus');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    //   $dataLocal = date('d/m/Y H:i:s', time());
    
    $date_hoje = date('Y-m-d H:i:s', time()); 
    $ip = $_SERVER["REMOTE_ADDR"];
    $usuario = $_POST['usuario'];
    $post = $_POST['post'];
    $comentario = $_POST['comentario'];
    $tipo = $_POST['tipo'];
   // echo 'aqui : '.$comentario ;  exit;
    
  if($usuario){
      if($post){
          
          $sql = "INSERT INTO sig_post_comentarios (id_post, id_user, comentario, date_comentario, status) values ('$post', '$usuario', '$comentario', '$date_hoje', '1')";
        if ($link->query($sql) === TRUE) {
            // echo "Registro Apagado com Sucesso!";
        }
          
      }
  }
    
    
   
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
</script>

<?php 

    if($tipo == 1){
   ?>     
        <script> exibe_like_post(<?php echo $post; ?>, <?php echo $usuario; ?>); </script>
  <?php          
    }else if($tipo == 2){
  ?>     
        <script> exibe_like_post_unico(<?php echo $post; ?>, <?php echo $usuario; ?>); </script>
  <?php 
    }

?> 


