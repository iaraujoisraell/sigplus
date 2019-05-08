<?php
if (isset($_POST)) {
    include('../db_connection.php');

 
    $arrayItems = $_POST['item'];

    $order = 0;


        foreach ($arrayItems as $item) {
            $sql = "UPDATE sma_fases_projeto SET ordem='$order' WHERE id ='$item'";
            mysqli_query($link, $sql);
            $order++;
        }

   // echo 'Salvo!';
    mysqli_close($link);
}


