<?php defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

// Get Y position for the separation
$y = $pdf->getY();

$company_info = '<div style="color:#424242;">';
$company_info .= format_organization_info();
$company_info .= '</div>';

// Bill to

$client_details = format_customer_info($invoice_data, 'payment', 'billing');

$left_info  = $swap == '1' ? $client_details : $company_info;
$right_info = $swap == '1' ? $company_info : $client_details;

pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->SetFontSize(15);

$receit_heading = '<div style="text-align:center">' . mb_strtoupper('Recibo de pagamento', 'UTF-8') . '</div>';
$receit_heading .= '<div style="text-align:center">' . format_invoice_number($invoice_data->id) . '</div>';
$pdf->Ln(20);
$pdf->writeHTMLCell(0, '', '', '', $receit_heading, 0, 1, false, true, 'L', true);
$pdf->SetFontSize($font_size);
$pdf->Ln(20);
$pdf->Cell(0, 0, 'Data da Fatura' . ' ' . _d($invoice_data->date), 0, 1, 'L', 0, '', 0);
$pdf->Ln(2);
$pdf->writeHTMLCell(80, '', '', '', '<hr/>', 0, 1, false, true, 'L', true);
$payment_name = $payment->forma_pagamento;
if (!empty($payment->forma_pagamento)) {
    $payment_name .= ' - ' . $payment->forma_pagamento;
}
$pdf->Cell(0, 0, 'Valor da Fatura' . ' ' . app_format_money($invoice_data->total, $invoice_data->currency_name), 0, 1, 'L', 0, '', 0);

$pdf->Ln(2);
$pdf->writeHTMLCell(80, '', '', '', '<hr />', 0, 1, false, true, 'L', true);
$pdf->SetFillColor(132, 197, 41);
$pdf->SetTextColor(255);
$pdf->SetFontSize(12);
$pdf->Ln(3);
$pdf->Cell(80, 10, 'Valor Pago', 0, 1, 'C', '1');
$pdf->SetFontSize(11);
$pdf->Cell(80, 10, app_format_money($invoice_data->total, $invoice_data->currency_name), 0, 1, 'C', '1');

$pdf->Ln(10);
$pdf->SetTextColor(0);
$pdf->SetFont($font_name, 'B', 14);
$pdf->Cell(0, 0, 'Detalhes Pagamento(s)', 0, 1, 'L', 0, '', 0);
$pdf->SetFont($font_name, '', $font_size);
$pdf->Ln(5);

// Header
$tblhtml = '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="0">
<tr height="30" style="color:#fff;" bgcolor="#3A4656">
    <th width="' . ( 25) . '%;">' . 'Fatura# ' . '</th>
    <th width="' . ( 25) . '%;">' . 'Data Pgto' . '</th>    
    <th width="' . ( 25) . '%;">' . 'Valor Pago' . '</th>
    <th width="' . ( 25) . '%;">' . 'Forma Pagamento' . '</th>';


$tblhtml .= '</tr>';

$tblhtml .= '<tbody>';
foreach ($payment as $payments) {
$tblhtml .= '<tr>';
$tblhtml .= '<td>' . $payments['invoiceid'] . '</td>';
$tblhtml .= '<td>' . _d($payments['date'])  . '</td>';
$tblhtml .= '<td>' . app_format_money($payments['amount'], ' R$') . '</td>';
$tblhtml .= '<td>' . $payments['forma_pagamento']  . '</td>';
$tblhtml .= '</tr>';
}
$tblhtml .= '</tbody>';
$tblhtml .= '</table>';
$pdf->writeHTML($tblhtml, true, false, false, false, '');
