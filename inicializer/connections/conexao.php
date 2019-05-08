<?php

$local = true;

if ($local) {

    $conexao = new mysqli("localhost", "root", "","gestorfacil_atual");
	$conexao->set_charset("utf8");
} else {

	// Conecta-se ao banco de dados MySQL
	$conexao = new mysqli("localhost", "maelymal_pdv2", "maely.2016","maelymal_pdv2");
	$conexao->set_charset("utf8");
	// Caso algo tenha dado errado, exibe uma mensagem de erro
	if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());

    
  
}
/*
if ($local) {

    $conexao = mysql_connect("localhost", "root", "") or die("Erro na conexao!");
    $db = mysql_select_db("gestorfacil_atual", $conexao) or die("Erro ao selecionar banco de dados!");
    mysql_query("SET NAMES 'utf8'");
    mysql_query('SET character_set_connection=utf8');
    mysql_query('SET character_set_client=utf8');
    mysql_query('SET character_set_results=utf8');
} else {

    $conexao = mysql_connect("localhost", "laparril_user", "gestor.123456") or die("Erro na conexao!");
    $db = mysql_select_db("gestorfa_gestor", $conexao) or die("Erro ao selecionar banco de dados!");
    mysql_query("SET NAMES 'utf8'");
    mysql_query('SET character_set_connection=utf8');
    mysql_query('SET character_set_client=utf8');
    mysql_query('SET character_set_results=utf8');
}
 * 
 */
?>