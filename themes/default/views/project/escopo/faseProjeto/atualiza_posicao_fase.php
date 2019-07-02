<?php
 /*
     * 1 - FASE
     * 2 - DELETE
     */
 $tipo_operacao = $_GET['tipo'];
 
    $date_hoje = date('Y-m-d H:i:s', time()); 
    $ip = $_SERVER["REMOTE_ADDR"];
    $id_fase = $_POST['id_fase'];
    $usuario =  $_POST['usuario'];
    $empresa =  $_POST['empresa'];
    //echo 'Fase : '.$id_fase.'<br>';
    //echo 'Usuário : '.$usuario.'<br>';
    //echo 'Empresa : '.$empresa.'<br>';
 
 
// INSERT 

    include('../../../db_connection.php');

   // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Manaus');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    //   $dataLocal = date('d/m/Y H:i:s', time());
    
    $cont_validacao = 0;
   
    
     if($tipo_operacao == 1){
     
        $sql_fase = "SELECT * FROM sig_user_escopo where usuario = $usuario and empresa = $empresa and fase = $id_fase";
        $result_fase = mysqli_query($link, $sql_fase); //$link->query($sql_historico);

        if ($result_fase->num_rows > 0) {
         
            while($row = $result_fase->fetch_assoc()) {
             $status_fase = $row["status_fase"];    
             if($status_fase == 0){
                 $novo_status = 1;
             }else if($status_fase == 1){
                 $novo_status = 0;
             }
             
            // echo 'novo status <br> :'.$novo_status;
             $sql = "UPDATE sig_user_escopo SET STATUS_FASE = $novo_status where usuario = $usuario and empresa = $empresa and fase = $id_fase";   
             mysqli_query($link, $sql);
             
             }

        }else{
          //  echo 'não tem';
            
            $sql = "INSERT INTO sig_user_escopo (fase, status_fase, usuario,  empresa) VALUE('$id_fase','1', '$usuario','$empresa')";
            mysqli_query($link, $sql);
        }
         
     
     }else if($tipo_operacao == 2){
         
         $sql_fase = "SELECT * FROM sig_user_escopo where usuario = $usuario and empresa = $empresa and evento = $id_fase";
        $result_fase = mysqli_query($link, $sql_fase); //$link->query($sql_historico);

        if ($result_fase->num_rows > 0) {
         
            while($row = $result_fase->fetch_assoc()) {
             $status_fase = $row["status_evento"];    
             if($status_fase == 0){
                 $novo_status = 1;
             }else if($status_fase == 1){
                 $novo_status = 0;
             }
             
            // echo 'novo status <br> :'.$novo_status;
             $sql = "UPDATE sig_user_escopo SET status_evento = $novo_status where usuario = $usuario and empresa = $empresa and evento = $id_fase";   
             mysqli_query($link, $sql);
             
             }

        }else{
          //  echo 'não tem';
            
            $sql = "INSERT INTO sig_user_escopo (evento, status_evento, usuario,  empresa) VALUE('$id_fase','1', '$usuario','$empresa')";
            mysqli_query($link, $sql);
        }
         
         
     }

   
   

   


?>

       