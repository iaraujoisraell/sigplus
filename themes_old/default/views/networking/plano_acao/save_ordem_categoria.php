<?php
if (isset($_POST)) {
    include('../../db_connection.php');

 
 
    $arrayItems = $_POST['ordem_categoria'];
    print_r($arrayItems);
    $order = 0;
exit;

        foreach ($arrayItems as $item) {
            $sql = "UPDATE sma_planos SET position='$order', status_tipo = 1 WHERE idplanos ='$item'";
            mysqli_query($link, $sql);
            $order++;
        }

   // echo 'Salvo!';
    mysqli_close($link);
}


