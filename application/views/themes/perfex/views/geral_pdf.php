<?php defined('BASEPATH') or exit('No direct script access allowed');
 /*
     * Larissa Oliveira
     * 29/07/2022
     * Descrição: view com as informações referentes às frequencias (pdf)
     */

$dimensions = $pdf->getPageDimensions();


// Get Y position for the separation
$y = $pdf->getY();


foreach ($relatorio_geral as $escala_setor) {

$this->ci->load->model('Unidades_hospitalares_model');
$this->ci->load->model('Relatorio_escala_model');

 
$dados_competencia = $this->ci->Unidades_hospitalares_model->get_competencia_escala($escala_setor['competencia_id']);
$ano_comp = $dados_competencia->ano;
$mes_comp = $dados_competencia->mes;


$tam_mes = strlen($mes_comp);
        if($tam_mes == 1){
            $mes_comp = '0'.$mes_comp;
        }
    $funcao = new DateTime("$ano_comp-$mes_comp");
    $numDias_mes = $funcao->format('t');
    
    $diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado');

        
        $dia=1;  

$pdf->SetFont($font_name, 'B', 10);
$pdf->SetTextColor(0);
$toolcopy = '<img src="assets/images/logo.jpg"  width="100" height="100">';
$pdf->writeHTML($toolcopy, false, 0, false, false, '');

//informações do cabeçalho
$pdf->Ln(2);
$pdf->Cell(0, 0,('Instituto de Traumato Ortopedia do Amazonas S/S Ltda'), 0, 1, 'L', 0, '', 0);
$pdf->SetFontSize(10);
$pdf->Ln(3);
$pdf->SetTextColor(0);
$pdf->SetFont($font_name, 'B', 10);
$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(238,248,246)));
$pdf->SetFillColor(238,248,246);
$pdf->Cell(0, 0,('Período: '.'0'.$dia.'/'.$mes_comp.'/'.$ano_comp.' a '.$numDias_mes.'/'.$mes_comp.'/'.$ano_comp),0, 1, 'R', 0, 0);
$pdf->Ln(3);
$pdf->Cell(0, 0, _l('Escala:  '.$escala_setor['unidade_id'].' - '.$escala_setor['fantasia'].' ('.$escala_setor['nome'].')'),1, 1, 'L', 1, 0);
$pdf->SetFont($font_name, '', $font_size);
$pdf->Ln(3);

//topo da tabela (head)
$pdf->SetFontSize(9);

    $tblhtml = '<table bgcolor="#fff" cellspacing="0" cellpadding="2" align="center" > 
    
    <tr height = "30%" class="w-100" style="color:#fff;" bgcolor="#6E8894" >
    <th width="6%"><b>Dia</b></th>
    <th width="13%"><b>Dia da semana</b></th>
    <th width="10%"><b>Horário</b></th>
    <th width="11%"><b>Qtde plantão</b></th>
    <th width="12%"><b>Titular</b></th>
    <th width="12%"><b>Escalado</b></th>
    <th width="12%"><b>Substituto</b></th>
    <th width="12%"><b>Plantonista</b></th>
    <th width="12%"><b>Creditado</b></th>';
    
    $tblhtml .= '</tr>';
    
//corpo da tabela     
$tblhtml .= '<tbody >'; 

              $color=0;
              $medicos = $this->ci->Relatorio_escala_model->get_medicos_geral($escala_setor['competencia_id'],$escala_setor['setor_id']); 
              foreach($medicos as $med){
                        if($color%2==0){
                          $tblhtml .= '
                            <tr style="color:#000;" class="w-100" bgcolor="#F0F0F0" align="center">';  
                        }else{
                            $tblhtml .= '
                            <tr style="color:#000;" class="w-100" bgcolor="#FFFFFF" align="center">';
                        }
                             $tblhtml .= '
                            <td width="6%" >&nbsp;'.$med['dia'].'</td>
                            <td width="13%" >&nbsp;'.$med['dia_semana'].'</td>
                            <td width="10%" >&nbsp;'.$med['hora_inicio'].'</td>
                            <td width="11%" >&nbsp;'.$med['quantidade'].'</td>
                            <td width="12%" >'.$med['titular'].'</td>
                            <td width="12%" >'.$med['escalado'].'</td>
                            <td width="12%" >'.$med['substituto'].'</td>
                            <td width="12%" >'.$med['plantonista'].'</td>
                            <td width="12%" >'.$med['creditado'].'</td>
                            ';
                            $tblhtml .= '</tr>';
                            $color++;
        }   


 $tblhtml .= '</tbody>';
$tblhtml .= '</table>';

$pdf->writeHTML($tblhtml, true, false, false, false, ''); 
 
$pdf->SetTextColor(0);
$pdf->SetFont($font_name, 'B', 10);
$data_hoje = date('d/m/Y');
$hora_hoje = date('H:i:s');
$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(238,248,246)));
$pdf->SetFillColor(238,248,246);
$pdf->Cell(0, 0, _l('Emitido em: '.$data_hoje.', às '.$hora_hoje),1, 1, 'L', 1, 0);
$pdf->writeHTML($content, true, 0, true, 0);//quebra de pagina
$pdf->AddPage();
$pdf->setPage($pdf->getPage()); 


}

