<?php
$link = mysqli_connect(
    'localhost',
    'root',
    '',
    'sigplus');

if (!$link) {
    printf("Erro na conexão com o BD: %s\n", mysqli_connect_error());
    exit;
}