<?php
if (isset($_POST)) {
    include('db_connection.php');

    $date_hoje = date('Y-m-d H:i:s'); 
    $ip = $_SERVER["REMOTE_ADDR"];
    
    $mensagem = $_POST['mensagem'];
    $acao = $_POST['acao'];
    $user = $_POST['user'];
    
   

        
            $sql = "INSERT INTO sma_historico_acao_usuario (usuario,data_envio,plano, observacao,ip,status_msg) VALUE('$user','$date_hoje','$acao','$mensagem','$ip','0')";
              mysqli_query($link, $sql);
         
       echo $sql;

   // echo "foi";
    mysqli_close($link);
}


