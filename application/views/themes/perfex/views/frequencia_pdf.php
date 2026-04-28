<?php defined('BASEPATH') or exit('No direct script access allowed');
 
$dimensions = $pdf->getPageDimensions();

// Get Y position for the separation
$y = $pdf->getY();

foreach ($frequencia as $escala_setor) {

$this->ci->load->model('Unidades_hospitalares_model');
$this->ci->load->model('Relatorio_frequencia_model');


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
        
if($escala_setor['id']!= 32){

$pdf->SetFont($font_name, 'B', 10);
$pdf->SetTextColor(0);
$base_url = base_url();
//$toolcopy = '<img src="'.$base_url.'assets/ITOAM/logo.png"  width="100" height="100">';
//$pdf->writeHTML($toolcopy, false, 0, false, false, '');

//informações do cabeçalho
$pdf->Ln(2);
$pdf->Cell(0, 0,('Instituto de Traumato Ortopedia do Amazonas S/S Ltda'), 0, 1, 'L', 0, '', 0);
$pdf->SetFontSize(10);
$pdf->Ln(3);
$pdf->SetTextColor(0);
$pdf->SetFont($font_name, '', 10);
$pdf->Cell(0, 0,('Frequência:  '.$escala_setor['id'].' - '.$escala_setor['fantasia'].' - '.'Período: '.'0'.$dia.'/'.$mes_comp.'/'.$ano_comp.' a '.$numDias_mes.'/'.$mes_comp.'/'.$ano_comp),0, 1, 'L', 0, '', 0);
$pdf->Ln(3);
$pdf->Cell(0, 0, _l('Setor: '.$escala_setor['setor_id'].' - '.'('.$escala_setor['nome'].')'), 0, 1, 'L', 0, '', 0);
$pdf->SetFont($font_name, '', $font_size);
$pdf->Ln(3);

//topo da tabela (head)
$pdf->SetFontSize(9);

    $tblhtml = '<table bgcolor="#fff" cellspacing="0" cellpadding="2" align="center" border="1"> 
    
    <tr style="color:#000;" class="w-100" bgcolor="#fff" >
    <th width="20%">HORÁRIO</th>
    <th width="40%">Nome do escalado</th>
    <th width="40%">Executor</th>';
    
    $tblhtml .= '</tr>';
    
//corpo da tabela     
$tblhtml .= '<tbody >'; 

    
        while($dia <= $numDias_mes){
           
            
            $tam_dia = strlen($dia);
            if($tam_dia == 1){
                $dia = '0'.$dia;
            }
            
            $data_cal = "$ano_comp-$mes_comp-$dia";
            $diasemana_numero = date('w', strtotime($data_cal));
            $dia_semana = $diasemana[$diasemana_numero];
           
                           
          
                    
            $i=0;//variavel contadora que imprime só as datas em que tem médicos escalados
            foreach ($horarios as $hora){
               
                  
                $setor_id = $escala_setor['setor_id'];
                
                $hora_inicio = $hora['hora_inicio'];
                $hora_fim = $hora['hora_fim'];
                $hora_format_st = date('H', strtotime($hora_inicio));
                $hora_format_end = date('H', strtotime($hora_fim));
                       
                $start_data = $data_cal.' '.$hora_inicio;
                
                   
                $medicos = $this->ci->Relatorio_frequencia_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$hora_fim);
    
                foreach ($medicos as $medico){
                        
                        if($i==0){
                        $tblhtml .= '
                            <tr style="color:#000;" class="w-100" bgcolor="#F0F0F0" align="left">
                            <td width="100%" ><b>'.$dia.'/'.$mes_comp.' - '.$dia_semana.'</b></td>';
                            $tblhtml .= '</tr>';
                            $i++;
                        }
                    $tblhtml .= '<tr>'; 
                     if($setor_id==$medico['setor_id'] && $medico['start']==$start_data && $hora_fim == $medico['hora_fim'] ){
                          
                            $tblhtml .= '

                                        <td width="20%">&nbsp;<br>'.$hora_format_st.'-'.$hora_format_end.'</td>
                                        <td width="40%" height="70">&nbsp;<br>'.$medico['medico'].'</td>
                                        <td width="40%" height="70">  </td>';  
                            $tblhtml .= '</tr>';
                     
                    }
                    
                }

            }
             
                
                $dia++; 
  
        }   


 $tblhtml .= '</tbody>';
$tblhtml .= '</table>';

$pdf->writeHTML($tblhtml, true, false, false, false, ''); 
 
$pdf->SetTextColor(255);
$pdf->SetFont($font_name, 'B', 10);
$data_hoje = date('d/m/Y');
$hora_hoje = date('H:i:s');
$pdf->Cell(0, 0, _l('Emitido em: '.$data_hoje.', às '.$hora_hoje), 1, 0, 'L',1, '', 0);

$pdf->writeHTML($content, true, 0, true, 0);//quebra de pagina
$pdf->AddPage();
$pdf->setPage($pdf->getPage()); 
}// modelo 1 
else{ //MODELO 2 (DANILO CÔRREA)

foreach($setores as $setor) {
                $i=0;
                $cont = 0;
                $medico_id = $setor['medicoid'];
                $setor_id = $escala_setor['setor_id'];
                $medicos_spa = $this->ci->Relatorio_frequencia_model->get_medicos_DanielCorrea($escala_setor['competencia_id'], $setor_id, $medico_id); 
               
                 $tblhtml = '<table bgcolor="#fff" cellspacing="0" cellpadding="2" align="center" border="1"> ';
                foreach ($medicos_spa as $medico_spa){
                    
                 if($cont==0){
                //informções do cabeçalho
                $pdf->SetFont($font_name, 'B', 10);
                $pdf->SetTextColor(0);
                $toolcopy = '<img src="'.$base_url.'assets/ITOAM/logo.png"  width="100" height="100">';
                $pdf->writeHTML($toolcopy, false, 0, false, false, '');
                $pdf->Ln(1);
                $pdf->SetTextColor(0);
                $pdf->SetFont($font_name, 'B', 10);
                $pdf->Cell(0, 0,('SPA E POLICLINICA DR. DANILO CORRÊA - LISTA DE FREQUÊNCIA DA ORTOPEDIA'),0, 1, 'C', 0, '', 0);
                $pdf->Cell(0, 0, _l(''), 0, 1, 'C', 0, '', 0);
                $pdf->Cell(0, 0, _l('SETOR: '.$escala_setor['setor_id'].' ('.$escala_setor['nome'].') - '.'Período: '.'0'.$dia.'/'.$mes_comp.'/'.$ano_comp.' a '.$numDias_mes.'/'.$mes_comp.'/'.$ano_comp), 0, 1, 'C', 0, '', 0);
                $pdf->SetFont($font_name, '', $font_size);
                $pdf->Ln(5);
                
                //topo da tabela (head)
                $pdf->SetFontSize(9);
                 
                    $tblhtml .=' 

                    <tr style="color:#000;" bgcolor="#F0F0F0" >
                    <th width="15%"><b>Data</b></th>
                    <th width="10%"><b>Horário</b></th>
                    <th width="35%"><b>Nome na escala</b></th>
                    <th width="40%"><b>Paga a:</b></th>';

                    $tblhtml .= '</tr>';
                    }
                //corpo da tabela     
                $tblhtml .= '<tbody >'; 

                    $start_data = date('d/m/Y',strtotime($medico_spa['start']));
                    $hora_format_st = date('H', strtotime($medico_spa['start']));
                    $hora_format_end = date('H', strtotime($medico_spa['end']));
                       
                   
                     if($medico_id==$medico_spa['medicoid']){
                         
                            $tblhtml .= '<tr>
                                        <td width="15%" >&nbsp;<br><b>'.$start_data.'</b></td>
                                        <td width="10%">&nbsp;<br>'.$hora_format_st.'-'.$hora_format_end.'</td>
                                        <td width="35%" height="70">&nbsp;<b><br>'.$medico_spa['medico'].'</b><br>'.$medico_spa['nome'].'</td>
                                        <td width="40%" height="70">  </td>';  
                            $tblhtml .= '</tr>';
                            
                            
                            
                            $i++;
                            $cont++;
                     
                        }
                     
                     if($i==13){
                        $pdf->Ln(1);
                     }
         
$tblhtml .= '</tbody>';

                

                }
               

$tblhtml .= '</table>';
$pdf->writeHTML($tblhtml, true, false, false, false, '');  
               
if($cont!=0){
$pdf->SetTextColor(255);
$pdf->SetFont($font_name, 'B', 10);
$data_hoje = date('d/m/Y');
$hora_hoje = date('H:i:s');
$pdf->Cell(0, 0, ('Emitido em: '.$data_hoje.', às '.$hora_hoje), 1, 0, 'L',1, '', 0);
$pdf->writeHTML($content, true, 0, true, 0);//quebra de pagina
$pdf->AddPage();
$pdf->setPage($pdf->getPage());        
}    
          
 
         }
         

}

}