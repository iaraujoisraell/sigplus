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
  
  if($usuario){
      if($post){
          
          $sql = "DELETE FROM sig_postagem_likes WHERE postagem_id = $post and usuario_id = $usuario";
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
    
    
    exibe_like_post(<?php echo $post; ?>, <?php echo $usuario; ?>);
    </script>

<?php 

    
  

   ?> 


