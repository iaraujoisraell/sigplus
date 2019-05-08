<?php
/*
 *  'vardnn0146.locaweb.com.br',
    'israel',
    '@r1skm3dRJ',
 */
$link = mysqli_connect(
    'localhost',
    'root',
    '',
    'sigplus');
$link->set_charset("utf8");
if (!$link) {
    printf("Erro na conexão com o BD: %s\n", mysqli_connect_error());
    exit;
}