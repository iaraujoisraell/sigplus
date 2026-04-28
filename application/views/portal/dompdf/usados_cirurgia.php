<?php

//echo $procedimentos['0']['description'];
//exit;

require_once 'autoload.inc.php';
// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->setChroot(__DIR__);
$options->setIsRemoteEnabled(true);

// instantiate and use the dompdf class
$dompdf = new Dompdf($options);

 
$html = '
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RASTREABILIDADE DE PROCEDIMENTOS E MATERIAIS USADOS EM CIRURGIA</title>
</head>
  
<style>

 .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
           
            padding: 10px;            
            
        }

body {
           
   font-family: Arial, sans-serif;
   width: 100%; 
   text-align: center; 
   margin:0;

}


header {

color: #0000;
text-align: center;

margin-top:-40px;



}

 .center-image {
        text-align: left;
        float: left;
        margin-right:25px;
        margin-top: 15px;
        margin-left:12 ;
        margin-bottom: 25;
     
    }

h4 {
    
       color: #000000;
       text-align: center;
 
}


p {
    position: absolute;
    color: #000000;
    font-size:13px;
    text-align: left;
    width: 120%; 
}

table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #000000;
      text-align: left;
      padding: 8px;
    font-size:12px;
    }


table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #000000;
      text-align: left;
   
    font-size:12px;
    width:30%;
     white-space: nowrap;
    }

    .borda{

border: 1px solid black;
padding: 5px;
width:105%;
margin: 0 auto;
text-align:left;
 white-space: nowrap;
}


.quadrado{

border: 1px solid black;
padding: 5px;
width:50%;
 height:10%;
text-align:center;
 white-space: nowrap;
}


.quadrado1{

border: 1px solid black;
padding: 5px;
width:45%;
 height:-10%;
text-align:center;
 white-space: nowrap;
}







</style>
<header>
<body>
 
<div><div class="center-image">
<img src="'.$logo_empresa.'" 
 style="width: 100px; height:65px; "></div>
<h4>RASTREABILIDADE DE PROCEDIMENTOS E<br> MATERIAIS USADOS EM CIRURGIA</h4>
</header>
<br>
<br>
<div><p>Nome do Paciente:<b> '. $client['company'].'</b>   ( Data/Hora:<b> '. date('d/m/Y H:i:s') .')</b></p></div><br>
<div><p>Idade: '. $idade.' </p></div><br>
<div><p>CPF: '. $client['vat'].'</p></div><br>
<div><p>Convênio/Plano: '.$convenio.'    INI: _________ TER: __________</p></div><br>
<div><p>CIRURGIÃO: '.($medico['nome_profissional']).' CRM: '.($medico['codigo_registro']).'</div></p><br>


<table>
<tbody>
      <tr>
        <td class="borda";>SALA: ______</td>
        <td class="borda";><b>OLHO:</b> OD ( ) OE ( ) AO ( )</td>
        <td class="borda";><b>PUNÇÃO VENOSA:</b> MSD ( ) MSE ( ) CURATIVO ( )</td>
      </tr>
</tbody>
</table>    



<table>
<tbody>
      <tr>
        <td class="borda";  style="font-size:11px";><b>TIPO DE ANESTESIA:</b> TÓPICA ( ) INFILTRATIVA ( ) BLOQUEIO PERI ( ) SEDAÇÃO ( ) MONITORAMENTO ( ) GERAL INALATÓRIA ( )</td></tr>

        <tr><td class="borda"; style="font-size:11px";><b>BLEFARO TOTAL ( ) BLEFARO SUPERIOR ( ) BLEFARO INFERIOR ( ) PTERÍGIO ( ) PTOSE ( ) TUMOR PALP. ( )
TUMOR CONJ. ( )<br> CALÁZIO ( ) EVISCERAÇÃO ( ) SONDAGEM ( ) ECTRÓPIO ( ) ENTROPIO ( )</b></td></tr>
      </tr>


<tr><td class="borda"; style="font-size:11px";>NYLON 6.0 ( ) SEDA 6.0 ( ) SEDA 7.0 ( ) SEDA 4.0 ( ) POLIESTER 5.0 ( ) FORMOL _______ ML ( ) COLA TISSEEL ( ) LUVAS:
6.5 ( ) 7.0 ( )<br> 7.5 ( ) 8.0 ( ) 8.5 ( ) LÂMINA 11: ( ) 15: ( ) SERINGA: 1ml ( ) 5ml ( ) 10 ml ( ) XYLEST. C VAS ( )
 XYLEST. SEM VAS ( )</td></tr>
   
</tbody>
</table> 



<table>
<tbody>
      <tr>
        <td class="borda";  style="font-size:11px";><b>CATARATA: ( ) ESTRABISMO: ( ) CROSSLINK ( ) TRABE ( ) REFRATIVA ( )</b></td></tr>



        <tr><td class="borda"; style="font-size:11px";>SUTURA: POLIPROPILENO 9.0( ) NYLON 10.0 MONO AGULHADO ( ) NYLON 10.0 BI-AGULHADO ( ) SEDA 6.0 ( )
 VICRYL 6.0 ( )<br> CARTUCHO B ( ) C ( ) D ( ) BISTURI 2.4 ( ) 2.75 ( ) 15° ( ) CRESCENTE ( ) ( )
LENTE TERAPÊUTICA: ( ) CARBACOL ( ) ETACOAT ( )<br> COTONETE ESPECIAL ( ) AZUL DE TRIPAN ( ) VISCOAT ( ) VISCO ( )
LUVAS: 6.5 ( ) 7.0 ( ) 7.5 ( ) 8.0 ( ) 8.5 ( ) SERINGA: 1ml ( ) 5ml ( )<br> 10 ml ( )
VITRECTOMIA _____________ EPINEFRINA ( ) XYLESTESIN ISOBÁRICO ( ) TEMPO DE FACO: _______________</td></tr>
    

</tbody>
</table> 



<table>
<tbody>
      <tr>
        <td class="borda";  style="font-size:11px";><b>ANEL DE FERRARA: ( ) TRANSPLANTE DE CÓRNEA: ( )</b></td></tr>



        <tr><td class="borda"; style="font-size:11px";>SLENTE TERAPÊUTICA: ( ) NYLON 10.0 MONO ( ) NYLON 10.0 BI-AGULHADO ( ) TREPANO: 7 ( ) 7.5 ( ) 8 ( ) 8.5 ( ) 9 ( )
ANEL 140°<br> 0,25 MM ( ) 140° 0,30 MM( ) 160° 0,15 MM ( ) 160° 0,20 MM ( ) 210° 0,15 MM ( ) 210° 0,25 MM ( )<br><br>
LUVAS: 6.5 ( ) 7.0 ( ) 7.5 ( ) 8.0 ( ) 8.5 ( ) SERINGA: 1ml ( ) 5ml ( ) 10 ml ( ) GENTAMICINA ( ) DEXAMETASONA ( )
BETAPROSPAN ( )</td></tr>
    

</tbody>
</table> 



<table>
<tbody>
      <tr>
        <td class="borda";  style="font-size:11px";><b>RETINA: ( ) APLICAÇÃO: ( )</b></td></tr>



        <tr><td class="borda"; style="font-size:11px";>VITRECTOMIA( ) ENDOLASER ( ) ÓLEO DE SILICONE _______ C3F8 ( ) FAIXA ____________ SONDA DIATERMIA ( ) SUTURA:
<br>ALGODÃO 4.0( ) NYLON 10.0 BI-AGULHADO ( ) SEDA 6.0 ( ) VICRYL 6.0 ( ) POLIÉSTER 5.0 ( ) ALGODÃO COM
POLIÉSTER 5.0( )<br> BISTURI 2.4 ( ) 2.75 ( ) 15° ( ) CRESCENTE ( ) POLIPROPILENO ( ) AZUL DE TRIPAN ( ) AZUL BRILHANTE
( ) CANULA SOFT ( )<br> GENTAMICINA ( ) DEXAMETASONA ( )VISCO ( ) LUVAS: 6.5 ( ) 7.0 ( ) 7.5 ( ) 8.0 ( ) 8.5 ( )
SERINGA: 1ml ( ) 5ml ( ) 10 ml ( ) 
</td></tr>
    

</tbody>
</table> 
<br>

<div>

<p class="quadrado";><b>ETIQUETA DA LENTE COLAR AQUI<br><br><br><br></p><br><br>


</div>
 
<div>

<p style="left:380px";>RESP.:______________________________________</p><br>
<p style="left:380px";>OBS: ________________________________________</p>

</div>

<br><br><br><br><br>
<div>

<p> SN2: 226434 ( ) SN1: 21082604 ( ) TESTE:_______</p><br>
<p>DATA DE ESTERILIZAÇÃO  _____/______/______ – VENCIMENTO _____/______/______</p><br>
<p>Nº CICLO: _______ PROG:_______ LOTE:_____________</p><br>
<p>MATERIAL: _________________________</p><br>
<p>QTD. DE PEÇAS:  __________________________________</p>
</div><br><br>

<div>

<p  class="quadrado1"; style="left:380px";>INTEGRADOR QUÍMICO COLAR AQUI<br><br><br><br></p>

</div>







<div class="footer">
    <p style = "  font-style: italic; font-size:9px">'. date('d/m/Y H:i:s') .' - '.$usuario_rodape .'</p>
</div>


</body>  

</html>
';
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

//$dompdf->stream('mic_1.pdf', ['Attachment' => false]);

// Output the generated PDF to Browser
$dompdf->stream("usados_cirurgia", ['Attachment' => false]);
