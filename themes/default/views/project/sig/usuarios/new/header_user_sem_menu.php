<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escritório de Projetos - Unimed Manaus</title>
    
    <link href="<?= $assets ?>styles/theme_novo.css" rel="stylesheet"/>
        
   

    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>

    
    
    <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?= $assets ?>bi/dist/css/skins/_all-skins.min.css">
  
    
   
    
    
 <?php
 
        function calculaTempo($hora_inicial, $hora_final) {
                     $i = 1;
                     $tempo_total;

                     $tempos = array($hora_final, $hora_inicial);

                     foreach($tempos as $tempo) {
                      $segundos = 0;

                      list($h, $m, $s) = explode(':', $tempo);

                      $segundos += $h * 3600;
                      $segundos += $m * 60;
                      $segundos += $s;

                      $tempo_total[$i] = $segundos;

                      $i++;
                     }
                     $segundos = $tempo_total[1] - $tempo_total[2];

                     $horas = floor($segundos / 3600);
                     $segundos -= $horas * 3600;
                     $minutos = str_pad((floor($segundos / 60)), 2, '0', STR_PAD_LEFT);
                     $segundos -= $minutos * 60;
                     $segundos = str_pad($segundos, 2, '0', STR_PAD_LEFT);

                     return "$horas:$minutos";
                    }


 function encrypt($str, $key)
        {
           
            for ($return = $str, $x = 0, $y = 0; $x < strlen($return); $x++)
            {
                $return{$x} = chr(ord($return{$x}) ^ ord($key{$y}));
                $y = ($y >= (strlen($key) - 1)) ? 0 : ++$y;
            }

            return $return;
        }
 ?>

</head>



<div id="loading"></div>

<body class="hold-transition skin-green sidebar-collapse  sidebar-mini">
<div class="wrapper">
<?php
/*
 * TOPO
 */

//$this->load->view($this->theme . 'usuarios/new/topo'); 
/*
 * MENU ESQUERDO
 */
//$this->load->view($this->theme . 'usuarios/new/menu_esquerdo'); 
?>

    