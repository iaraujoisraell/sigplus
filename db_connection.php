<?php
$link = mysqli_connect(
    'localhost',
    'root',
    '',
    'sig_plus');

if (!$link) {
    printf("Erro na conexão com o BD: %s\n", mysqli_connect_error());
    exit;
}