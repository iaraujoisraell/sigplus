<?php

// VALIDA LOGIN

if ($_POST) {
    $curl = curl_init();
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $url = 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Login';
    $data = ['login' => $login, 'senha' => $senha];
    $ch = curl_init();

    curl_setopt_array($ch, array(
        CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/Login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{ "login": "' . $login . '", 
                                "senha": "' . $senha . '"
                                }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json; charset=utf-8'
        ),
    ));

    $response = curl_exec($ch);
    if ($response == '1') {
        header('Location: ' . "https://sigplus.app.br/admin/authentication/valida_login/$login");
    } else {

        ///deu erro
        //header('Location: ' . "https://unimedmanaus.sigplus.online/?ANS=ERROR_UNIMED");
    }
    curl_close($curl);

    $cofre_decode = json_decode($response, true);

    //$card_id = $cofre_decode['card_id'];
}
?>