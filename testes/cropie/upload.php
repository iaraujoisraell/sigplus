<?php 
echo $_POST['name-img'] . '<br><br>';
if(isset($_FILES['img-h'])){
    $dir = 'uploads/';
    $file_path = $dir . uniqid() . '.png';
    if(move_uploaded_file($_FILES['img-h']['tmp_name'], $file_path)){
        echo "Sucesso: imagem cortada e salva com sucesso";
    }
    else{
        echo "Erro: imagem cortada e não salva";
    }
}
?>