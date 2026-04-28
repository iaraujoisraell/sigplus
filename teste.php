<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://www.sincreplica.com.br/ords/saude/api/patients',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array(),
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic VFVPVEVNUE9fVklTSU9OOnR1b0BWSVNJT04jdGVtcG8yMQ=='
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

/*
    $additionalHeaders = '';
    $username = 'TUOTEMPO_VISION';
    $password = 'tuo@VISION#tempo21';
    
    
    
    // set post fields
    $post = [
        'start_date' => '25/01/23',
        'end_date' => '25/01/23',
    ];

    $ch = curl_init('http://www.sincreplica.com.br/ords/saude/api/patients');
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
    curl_setopt($ch,CURLOPT_TIMEOUT, 20);
    // execute!
    $response = curl_exec($ch);
    echo $response;
    $lista = json_decode($response, true);
    // close the connection, release resources used
    curl_close($ch);

    // do anything you want with your response
    print_r($lista); exit;
 * 
 */


    
   

        

?>