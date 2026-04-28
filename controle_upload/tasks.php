<?php

$task_id = $_GET['task_id'];
//echo $task_id; exit;
// Receber o arquivo do formulário
$arquivo = $_FILES['arquivo'];


$baseName = $arquivo['name'];
$extension = substr($baseName, strpos($baseName, '.') + 1);
//echo $extension;
//exit;
//var_dump($arquivo);
//exit;
// Validar extensão do arquivo


    // Criar novo nome para o arquivo PDF
    $renomear_arquivo = md5(time()) . '.'.$extension;

    // Caminho para o upload
    $caminho_upload = "../uploads/";

    // Realizar upload do arquivo
    if(move_uploaded_file($arquivo['tmp_name'], $caminho_upload . $renomear_arquivo)){
        
        // Criar o array com a mensagem de sucesso
        $retorno = ['status' => true, 'msg' => "<p style='color: green;'>Upload realizado com sucesso!</p>"];

    }else{

        // Criar o array com a mensagem de erro
        $retorno = ['status' => false, 'msg' => "<p style='color: #f00;'>Erro: Upload não realizado com sucesso!</p>"];
        
    }



// Retornar objeto para o JavaScript
echo json_encode($retorno);