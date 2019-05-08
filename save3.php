<?php
if (isset($_POST)) {
    include('db_connection.php');

 
    $arrayItems = $_POST['item'];
    print_r($arrayItems);
    $order = 0;


        foreach ($arrayItems as $item) {
            $sql = "UPDATE sma_planos SET position='$order', status_tipo = 3 WHERE idplanos ='$item'";
            mysqli_query($link, $sql);
            $order++;
        }

   // echo 'Salvo!';
    mysqli_close($link);
}


