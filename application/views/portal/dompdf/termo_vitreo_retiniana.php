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
    <title>TERMO DA CIRURGIA VÍTREO-RETINIANA</title>
</head>
  
<style>


body {
           
    font-family: Arial, sans-serif;
    width: 100%; 
    box-sizing: border-box;
     
}


header {

color: #0000;
text-align: center;
border: 1px solid black;
margin-top:-15px;
margin: bottom -10px;
}

.footer {
           position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 10px;         

        }

 .center-image {
        text-align: left;
        float: left;
        margin-right:25px;
        margin-top: 8px;
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
    font-size:12.5px;
    text-align: justify ;
    width: 100%; 
    margin-top:-15px;
}


</style>
<header>
<body>
 
<div>
    <div class="center-image">
<img src="'.$logo_empresa.'" 
 style="width: 100px; height:70px; ">    </div>
    <h4> TERMO DE CIÊNCIA E CONSENTIMENTO<br> CIRURGIA VÍTREO-RETINIANA</h4>
    </header>
    <br>
    <br>

<div><p>Por intermédio do presente instrumento particular, eu, '. $client['company'].', declaro para todos os fins legais, em
especial no disposto no Art. 39, VI, da Lei 8.078/90, que autorizo o médico (a), '.($medico['nome_profissional']).', CRM '.($medico['codigo_registro']).', a diagnosticar o meu estado de saúde ocular atual, bem como a realizar o tratamento cirúrgico designado
<b>CIRURGIA VÍTREO-RETINIANA</b>, no olho ________, bem como todos os procedimentos oftalmológicos que integram e que se
fizerem necessários (retinopexia, vitrectomia, criopexia, diatermopexia, endolaser, translocação macular, membranectomia, injeção de
gás ou implante de óleo de silicone) e demais condutas médicas cirúrgicas que o referido tratamento possa exigir (extração do cristalino
com ou sem implante, transplante de córnea, extração ou reposicionamento do implante intra-ocular). Declaro, outrossim, que o
referido (a) médico (a), atendendo ao disposto no Art. 22º, 24º, 31º e 34º do Código de Ética Médica e no Art. 9º da Lei 8.078/90
(abaixo transcritos).</p></div><br><br><br><br><br><br><br><br>


<div><p style=" font-size:12.5px;"><b>Princípios e indicações:</b></p></div><br><br>


<div><p>A cirurgia vítreo-retiniana é realizada com o objetivo de tratar algumas doenças oculares que não só ameaçam ou levam à baixa de
visão, como têm alto potencial cegante (deslocamento de retina, hemorragia vítrea de causas diversas, tumores intra-oculares,deslocamento
de coroide, luxação do cristalino ou implante intra-ocular no vítreo, corpo estranho intra-ocular,
pré-atrofia ocular).</p></div><br><br><br><br>

<div><p style=" font-size:12.5px;"><b>Complicações:</b></p></div><br><br>

<div><p>A cirurgia de deslocamento de retina é um procedimento seguro, mas, como toda cirurgia apresenta riscos, dentre os quais, as
complicações decorrentes da reação do organismo à anestesia e medicamentos utilizados.
</p></div><br><br>

<div><p>As complicações pós-operatória podem ocorrer dias, semanas, meses ou anos após o ato cirúrgico e incluem:</p></div><br>

<div><p>- Decorrente da anestesia: perfuração do globo ocular, trauma do nervo óptico, oclusão da artéria central da retina e parada
respiratória.</p></div><br><br>


<div><p>- Decorrente da própria cirurgia: rotura e perfuração da esclera; edema de córnea; hemorragia intra-ocular; encarceramento da
retina; rotura retiniana e hipotonia ocular; estrabismo e visão dupla; infecção do implante; isquemia do segmento anterior e
posterior; glaucoma secundário; deslocamento de coroide; redescolamento de retina; catarata; edema de mácula; expulsão do
implante; membrana macular; reproliferação vítrea e vítreo-retinopatia proliferativa; endoftalmite; atrofia ocular e cegueira.
</p></div><br><br><br><br>

<div><p>O paciente deverá seguir as recomendações e prescrições pós-operatórias fornecidas pelo cirurgião para minimizar as possibilidades
de ocorrência de complicações no pré, trans e pós-operatório.</p></div><br><br><br>


<div><p style=" font-size:12.5px;"><b>Declaração de consentimento:</b></p></div><br><br>


<div><p>Afirmo estar plenamente ciente da minha situação ocular em face das alterações vítreo-retinianas presentes descritas em linguagem
simples e explicadas detalhadamente pelo cirurgião e sua equipe, poderão levar não só a uma acentuada baixa de visão, além de
representarem uma constante ameaça de atrofia e perda do olho afetado.
</p></div><br><br><br>

<div><p>Declaro ainda, livre de qualquer coação e constrangimento, para não restar nenhuma dúvida quanto à cirurgia proposta em questão,
que sou conhecedor (a) dos seus princípios, indicações, riscos, complicações e resultados.</p></div><br><br>


<div><p>Asseguro estar plenamente ciente de que a cirurgia a ser realizada, em virtude da possibilidade de ocorrência de riscos e complicações,
não permite ao cirurgião e à sua equipe assegurar-me a garantia expressa ou implícita da cura.</p></div><br><br>

<div><p>Tenho ciência de que o pagamento do procedimento independe do resultado, sendo indevida qualquer retenção de valores
condicionada ao sucesso do procedimento.</p></div><br><br>

<div><p>Estou ciente que qualquer outro procedimento que venha a ser realizado após a cirurgia, neste momento consentida, não
está incluso no valor já acertado, devendo ser contratado e pago separadamente.</p></div><br><br><br>


<div><p>Com base em todas as informações e ainda pelas informações adicionais que me foram prestadas pelo meu médico, autorizo a
realização da cirurgia de VÍTREO-RETINIANA.</p></div><br><br><br><br><br>
<p  style=" font-size:12.5px;"> <b>Manaus, '. date('d/m/Y H:i:s') .'</b>.</p><br><br><br><br><br><br><br><br>


<div><p  style=" font-size:12.5px;"><b>Nome do Paciente:</b> '. $client['company'].'</p></div><br>
<div><p  style=" font-size:12.5px;">
<b>RG:</b> '. $client['documento_rg'].'<br>
<b>CPF:</b> '. $client['vat'].'</p><br><br></div><br>


<div><p  style=" font-size:12.5px;"><b>Nome do Responsável:</b>  '.$responsavel['nome'].'</p></div><br>
<div><p  style=" font-size:12.5px;">         <b>CPF:</b> '.$responsavel['cpf'].' </p></div><br><br><br>

<div><p style=" font-size:12.5px;"><b>Nome do Médico:</b> '.($medico['nome_profissional']).'</p></div><br><br><br>


<div><p  style=" font-size:12.5px;"><b>Código de Ética Médica:</b></p></div><br><br>

<div><p>- Art. 22º. É vedado ao médico deixar de obter consentimento do paciente ou de seu representante legal após esclarecê-lo sobre o
procedimento a ser realizado, salvo em caso de risco iminente de morte.</p></div><br><br>

<div><p>- Art. 24º. Deixar de garantir ao paciente o exercício do direito de decidir livremente sobre sua pessoa ou seu bem-estar, bem como
exercer sua autoridade para limitá-lo.</p></div><br>

<br><div><p>- Art. 31º. Deixar de informar ao paciente o diagnóstico, o prognóstico, os riscos e os objetivos do tratamento, salvo quando a
comunicação direta possa lhe provocar danos, devendo, nesse caso, fazer a comunicação a seu representante legal.</p></div><br><br>


<div><p>- Art. 34º. Desrespeitar o direito do paciente ou de seu representante legal de decidir livremente sobre a execução de práticas diagnósticas
ou terapêuticas, salvo em caso de iminente risco de morte.
</p></div><br><br><br>

<div><p  style=" font-size:12.5px;"><b>Lei 8.078 de 11/09/1990 – Código Brasileiro de Defesa do Consumidor:</b></p></div><br><br>

<div><p>- Art. 9º. O fornecedor e produtos ou serviços potencialmente perigosos à saúde ou segurança deverá informar, de maneira ostensiva e
adequada, a respeito da sua nocividade ou periculosidade, sem prejuízo da adoção de outras medidas cabíveis em cada caso concreto.</p></div><br><br><br>

<div><p>- Art. 39º. É vedado ao fornecedor de produtos ou serviços dentre outras práticas abusivas: VI – executar serviços sem a prévia
elaboração de orçamento e autorização expressa do consumidor, ressalvadas as decorrentes de práticas anteriores entre as partes.</p></div><br><br>


<br><br><br><br><div><p style="margin-left:5px; text-align:center"> <b>__________________________________________________</b><br> Assinatura do Paciente </p></div>
<br>
<br>
<br>
<br>
<div><p style="margin-left: 5px; text-align:center"> <b>__________________________________________________</b><br> Assinatura do Responsável </p></div>
<br>


<div class="footer">
    <p style="  font-style: italic; font-size:9px; margin-left:480px;">'. date('d/m/Y H:i:s') .' - '.$usuario_rodape .'</p>
</div>
</body>  
</html>
';
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("termo_vitreo_retiniana.pdf", ['Attachment' => false]);
