<?php
$nome = $_POST["nome"]; 
$email = $_POST["email"];  
// imprime na tela em formato json 
echo json_encode( array( "email" => $email, "nome" => $nome ) );  
?>