<?php
     /*  $ch = curl_init();
    $headers = array(
    'Accept: application/json',
    'Content-Type: application/json',

    );
    curl_setopt($ch, CURLOPT_URL, 'http://189.2.65.2/sigplus/api/Excel');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $body = '{}';

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Timeout in seconds
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $authToken = curl_exec($ch); 
    */

    $lista = file_get_contents("http://189.2.65.2/sigplus/api/Excel/index");

    //$infor = json_decode($lista);
    print_r(count($lista[0])); exit;
    $cont= 0;
    for($i = 0; $i < count($lista); $i++ ){
        echo $i++;
    }
        
    /*foreach ($items as $key => $item) {
        
        //$empresa = $lista['EMPRESA'];
        
        print_r($item). '<br>';
        exit;
    }/*
    
    
    exit;
    

    echo json_decode($authToken);
    return $authToken;
            ?>