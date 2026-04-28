<?php

require_once 'autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Cria uma instância do Dompdf
$options = new Options();
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

// Conteúdo HTML que será convertido em PDF
$html = '<html>
<head>
  <meta charset="UTF-8">
  <title>Exemplo de Paginação</title>
</head>
<body>
  <h1>Exemplo de Paginação com DomPDF</h1>
  <p>Este é um exemplo de como adicionar paginação em um PDF com DomPDF no PHP.</p>';

// Adiciona 50 parágrafos para gerar várias páginas
for ($i = 1; $i <= 100; $i++) {
    $html .= "<p>Parágrafo $i</p>";
}

$html .= '</body></html>';

// Carrega o HTML no Dompdf
$dompdf->loadHtml($html);

// Renderiza o PDF
$dompdf->render();

// Obtém o número total de páginas
$totalPages = $dompdf->get_canvas()->get_page_count();



for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++) {
$dompdf->getCanvas()->page_text(550, 15, "Página " . $pageNumber . " de " . $totalPages, null, 12, array(0, 0, 0));
}

// Salva ou exibe o PDF
$dompdf->stream("exemplo_de_paginacao_com_dompdf.pdf", array("Attachment" => false));
?>
