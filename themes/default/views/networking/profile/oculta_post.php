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
          
          $sql = "UPDATE sig_postagens SET STATUS = 0 where id = '$post' ";
        if ($link->query($sql) === TRUE) {
            // echo "Registro Apagado com Sucesso!";
        }
          
      }
  }
    
    
   
}

?>
<script type="text/javascript">
exibe_mural_profile(<?php echo $usuario; ?>)
</script>

