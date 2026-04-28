<?php defined('BASEPATH') or exit('No direct script access allowed');
  /*
     * Larissa Oliveira
     * 18/08/2022
     * Descrição: view com as informações referentes às escalas (pdf)
     */
$dimensions = $pdf->getPageDimensions();

// Get Y position for the separation
$y = $pdf->getY();

//CAPA PDF

//borda
$pdf->SetLineStyle(array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(41, 61, 110)));
$pdf->Line(0,0,$pdf->getPageWidth(),0); 
$pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,0,0,$pdf->getPageHeight());

$pdf->SetFont($font_name, 'B', 10);
$pdf->SetTextColor(0);
$pdf->Cell(0, 0,('ITO-AM'), 0, 1, 'C', 0, '', 0);
$pdf->SetFont($font_name, '', 8);
$pdf->Cell(0, 0,('INSTITUTO DE TRAUMATO ORTOPEDIA DO AMAZONAS S/S LTDA'), 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 0,('CNPJ 11.439.746/001-12'), 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 0,('AV. Mário Ypiranga n° 315 - B. Sala 917. Edifício The Office - Adrianópolis '), 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 0,('CEP: 69.057-000 - Tel. (92)3236-8223 - Manaus - Amazonas'), 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 0,('Email: ito-am@hotmail.com'), 0, 1, 'C', 0, '', 0);
$base_url = base_url();
$toolcopy = '<img src="'.$base_url.'assets/ITOAM/logo.png"  width="200" height="150">';
$pdf->writeHTML($toolcopy, false, 0, false, false, '');
$pdf->Ln(10);

foreach($escala as $capa){
$this->ci->load->model('Unidades_hospitalares_model');
$this->ci->load->model('Relatorio_escala_model');

$competencia = $this->ci->Unidades_hospitalares_model->get_competencia_escala($capa['competencia_id']);
$anocomp = $competencia->ano;
$mescomp =  $competencia->mes;

$meses_ano = array('zero', 'JANEIRO', 'FEVEREIRO', 'MARÇO', 'ABRIL', 'MAIO', 'JUNHO', 'JULHO', 'AGOSTO', 'SETEMBRO', 'OUTUBRO', 'NOVEMBRO', 'DEZEMBRO');

$mes = $meses_ano[$mescomp];

}

$pdf->SetTextColor(110,136,148);
$pdf->SetFont($font_name, '', 90);
$pdf->Cell(0, 0,('ESCALA'), 0, 1, 'C', 0, '', 0);
$pdf->SetFont($font_name, '', 30);
$pdf->Cell(0, 0,('DE PLANTÕES'), 0, 1, 'C', 0, '', 0);
$pdf->SetFont($font_name, 'B', 10);
$pdf->Cell(0, 0,('URGÊNCIA/EMERGÊNCIA/CENTRO-CIRÚRGICO/ SPA´S E AMBULATÓRIOS'), 0, 1, 'C', 0, '', 0);
$pdf->Ln(10);
$pdf->SetFont($font_name, '', 60);
$pdf->Cell(0, 0,(''.$mes), 0, 1, 'C', 0, '', 0);
$pdf->SetFont($font_name, '', 50);
$pdf->Cell(0, 0,(''.$anocomp), 0, 1, 'C', 0, '', 0);
$pdf->SetFont($font_name, 'B', 10);
$pdf->Cell(0, 0,('SÓCIOS NAS UNIDADES.'), 0, 1, 'C', 0, '', 0);
$pdf->Ln(70);
$pdf->SetTextColor(0);
$pdf->SetFont($font_name, 'B', 8);
$pdf->Cell(0, 0,('"Feliz é a nação cujo Deus é o Senhor.'.'" (Salmos 33:12)'), 0, 1, 'C', 0, '', 0);
$pdf->writeHTML($content, true, 0, true, 0);//quebra de pagina
$pdf->AddPage();
$pdf->setPage($pdf->getPage()); 
$pdf->Ln(8);
//INFORMAÇÕES PDF

foreach ($escala as $escala_setor) {
 
            //MODELO HPS CRIANÇA Z.OESTE  
            if($escala_setor['unidade_id']==15){


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

              $diasemana = array('domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado');

              $dia=1;

              $pdf->Ln(5);
              $pdf->SetFont($font_name, '', 10);
              $pdf->SetTextColor(0);
             // $toolcopy = '<img src="'.$base_url.'assets/ITOAM/logo.png"  width="150" height="70" >';
              //$pdf->writeHTML($toolcopy, false, 0, false, false, '');
              $pdf->Ln(1);
              $pdf->Cell(0, 0,('Instituto de Traumato Ortopedia do Amazonas S/S Ltda'), 0, 1, 'C', 0, '', 0);
              $pdf->SetFont($font_name, 'B', 10);
              $pdf->Cell(0, 0,('ITOAM'), 0, 1, 'C', 0, '', 0);
              $pdf->Cell(0, 0,('Plantonistas da Escala ref. '.'0'.$dia.'/'.$mes_comp.'/'.$ano_comp.' a '.$numDias_mes.'/'.$mes_comp.'/'.$ano_comp.', Escalado'), 0, 1, 'C', 0, '', 0);
              $pdf->Ln(2);   
              $data_hoje = date('d/m/Y');
              $hora_hoje = date('H:i:s');
              $pdf->SetTextColor(0);
              $pdf->SetFont($font_name, '',8);
              $pdf->Cell(0, 0, _l($data_hoje.' - '.$hora_hoje), 0, 0, 'R', '', 0);
              $pdf->Ln(5);   
              $pdf->SetFont($font_name, 'B', 8);
              $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255,224,84)));
              $pdf->SetFillColor(255,224,84);
              $pdf->Cell(0, 0,(''.$escala_setor['unidade_id'].' - '.$escala_setor['fantasia'].' ('.$escala_setor['nome'].')'),1, 1, 'L', 1, 0);    
              $pdf->Ln(5);   

              //topo da tabela (head)
              $pdf->SetFont($font_name, '', 8);

              $tblhtml = '<table bgcolor="#fff" cellspacing="0" cellpadding="2" >

              <tr style="color:#B94649;" class="w-100" bgcolor="#fff">
              <th width="33%" align="left"><b>Nome</b></th>
              <th width="20%" align="left"><b>Função</b></th>
              <th width="15%" align="center"><b>Conselho</b></th>
              <th width="15%" align="left"><b>Setor</b></th>
              <th width="10%" align="center"><b>Data</b></th>
              <th width="7%"  align="center"><b>Horário</b></th>
              </tr>';

              for($dia=1; $dia<=$numDias_mes; $dia++){
               $tam_dia = strlen($dia);
                  if($tam_dia == 1){
                          $dia = '0'.$dia;
                  }

                  $data_cal = "$ano_comp-$mes_comp-$dia";
                  $diasemana_numero = date('w', strtotime($data_cal));
                  $dia_semana = $diasemana[$diasemana_numero];

                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  

                          $i=0;//variavel contadora que imprime só as datas em que tem médicos escalados     
                          foreach ($horarios as $hora) {

                              $setor_id = $hora['setor_id'];
                              $hora_inicio = $hora['hora_inicio'];
                              $hora_fim = $hora['hora_fim'];
                              $hora_format_st = date('H', strtotime($hora_inicio));
                              $hora_format_end = date('H', strtotime($hora_fim));
                              $start_data = $data_cal.' '.$hora_inicio;                            
                              $end_data = $data_cal.' '.$hora_fim;
                              $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,$dia_semana); 
                                  foreach ($medicos as $medico){
                                    if($i==0){
                                      $tblhtml .= '
                                                              <tr style="color:#000;" class="w-100" bgcolor="#F0F0F0" align="left">
                                                              <td width="100%"><b>Data: </b>'.$dia.'/'.$mes_comp.'/'.$ano_comp.'</td>';
                                      $tblhtml .= '</tr>';
                                      $i++;
                                    }
                                      $tblhtml .= '<tr>
                                                      <td width="33%" align="left">'.$medico['nome_profissional'].'</td>
                                                      <td width="20%" align="left">TRAUMATO-ORTOPEDIA</td>
                                                      <td width="15%" align="center">CRM/'.$medico['CRM'].'</td>
                                                      <td width="15%" align="left">'.$medico['nome_setor'].'</td>
                                                      <td width="10%" align="center">'.$dia.'/'.$mes_comp.'/'.$ano_comp.'</td>
                                                      <td width="7%" align="center">'.$hora_format_st.'-'.$hora_format_end.'</td>
                                                   </tr>';  
                                  }

                          }
              }
              $tblhtml .= '</table>';


                  $pdf->SetAutoPageBreak(true, 50);
                  $pdf->writeHTML($tblhtml, true, false, false, false, ''); 
                  $pdf->writeHTML($content, true, 0, true, 0);//quebra de pagina
                  $pdf->AddPage();
                  $pdf->setPage($pdf->getPage()); 

            }

            // MODELO PADRÃO
            else{
                
              $pdf->Ln(5);

              $quant_plantoes = 0;
              $quantidade = $this->ci->Relatorio_escala_model->get_quantidade($escala_setor['competencia_id'], $escala_setor['setor_id']); 
                  foreach ($quantidade as $quant){
                      $quant_plantoes= $quant_plantoes+$quant['quantidade'];
                  }

                  $pdf->SetFont($font_name, 'B', 10);
                  $pdf->SetTextColor(0);
                  //$toolcopy = '<img src="'.$base_url.'assets/ITOAM/logo.png"  width="150" height="70" >';
                  //$pdf->writeHTML($toolcopy, false, 0, false, false, '');
                  $pdf->Ln(2);
                  $pdf->Cell(0, 0,('Instituto de Traumato Ortopedia do Amazonas S/S Ltda'), 0, 1, 'L', 0, '', 0);

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


                  $dia=1; 
                  $pdf->SetFontSize(10);
                  $pdf->Ln(1);
                  $pdf->SetTextColor(0);
                  $pdf->Cell(0, 0, _l('Qtde de plantões: '.$quant_plantoes), 0, 1, 'R', 0, '', 0);
                  $pdf->Cell(0, 0, _l('Período: '.'0'.$dia.'/'.$mes_comp.'/'.$ano_comp.' a '.$numDias_mes.'/'.$mes_comp.'/'.$ano_comp), 0, 1, 'R', 0, '', 0);
                  $pdf->SetTextColor(92,70,114);
                  $pdf->SetFont($font_name, 'B', 10);
                  $pdf->Cell(0, 0,(''.$escala_setor['unidade_id'].' - '.$escala_setor['fantasia'].' ('.$escala_setor['nome'].')'),0, 1, 'L', 0, '', 0);
                  $pdf->SetFont($font_name, '', $font_size);
                  $pdf->Ln(3);

                  $pdf->SetFontSize(9);


                  $tblhtml = '<table bgcolor="#fff" cellspacing="0" cellpadding="2" align="center" border="1" bordercolor="#6E8894"> ';

                  $diasemana = array('Domingo', 'Segunda', 'Terca', 'Quarta', 'Quinta', 'Sexta', 'Sabado');

                  $temsemana6 = 0;
                          for($dia=1; $dia <= $numDias_mes; $dia++){

                              $tam_dia = strlen($dia);
                              if($tam_dia == 1){
                                  $dia = '0'.$dia;
                              }
                              $data_cal = "$ano_comp-$mes_comp-$dia";
                              $diasemana_numero = date('w', strtotime($data_cal));
                              $dia_semana = $diasemana[$diasemana_numero];

                              if($dia == 1){
                                  $dia_semana = $diasemana[$diasemana_numero];
                                  $semana1 = $diasemana[$diasemana_numero];

                              }

                              if($dia == 8){
                                  $dia_semana = $diasemana[$diasemana_numero];
                                  $semana2 = $diasemana[$diasemana_numero];
                              }

                              if($dia == 15){
                                  $dia_semana = $diasemana[$diasemana_numero];
                                  $semana3 = $diasemana[$diasemana_numero];
                                  $exibesemana3 = $dia;
                              }

                              if($dia == 22){
                                  $dia_semana = $diasemana[$diasemana_numero];
                                  $semana4 = $diasemana[$diasemana_numero];
                              }


                              if($mes_comp == 2){
                                 if($dia == 28){ 
                                  $dia_semana = $diasemana[$diasemana_numero];
                                  $semana5 = $diasemana[$diasemana_numero];
                                 } 
                              }else
                              if($dia == 29){
                                  $dia_semana = $diasemana[$diasemana_numero];
                                  $semana5 = $diasemana[$diasemana_numero];

                                  if($semana5 == 'Sexta'){
                                      $temsemana6_dom = 1;
                                  }else if($semana5 == 'Sabado'){
                                      $temsemana6_seg = 1;
                                  }
                              }

                              if($temsemana6_dom){
                                  $dia_semana = $diasemana[$diasemana_numero];
                                  $semana6_dom = $diasemana[$diasemana_numero];
                              }

                              if($temsemana6_seg){
                                  $dia_semana = $diasemana[$diasemana_numero];
                                  $semana6_seg = $diasemana[$diasemana_numero];
              }

          }     // fim for dias do mes

                              /*
                               *  PRIMEIRA SEMANA
                               */

                              if($semana1 == 'Domingo'){

                                   $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 01/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 02/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 03/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 04/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 05/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 06/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 07/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';

                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));

                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';

                                             $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-01";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-02";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-03";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-04";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quinta

                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-05";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>'; //sexta   
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-06";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-07";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';


                                          $tblhtml .= '</tr>';

                                      } 
                              }

                              if($semana1 == 'Segunda'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 01/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 02/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 03/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 04/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 05/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 06/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';

                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {
                                         
                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));



                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//domingo

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-01";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                   
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          
                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-02";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-03";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quinta

                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-04";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>'; //sexta   
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-05";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-06";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';


                                          $tblhtml .= '</tr>';

                                      }     
                              }

                              if($semana1 == 'Terca'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 01/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 02/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 03/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 04/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 05/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';

                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));



                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//domingo
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//segunda

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-01";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-02";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quinta

                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-03";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>'; //sexta   
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-04";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-05";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';


                                          $tblhtml .= '</tr>';

                                      }     
                              }

                              if($semana1 == 'Quarta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 01/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 02/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 03/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 04/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';

                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));



                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//domingo
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//segunda
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//terca

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-01";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quinta

                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-02";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>'; //sexta   
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-03";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-04";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';


                                          $tblhtml .= '</tr>';

                                      }     
                              }

                              if($semana1 == 'Quinta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 01/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 02/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 03/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';

                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));



                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//domingo
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//segunda
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//terca
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//quarta

                                          $tblhtml .= '<th width="13%" ><b>';//quinta

                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-01";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>'; //sexta   
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-02";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-03";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';


                                          $tblhtml .= '</tr>';

                                      }     
                              }

                              if($semana1 == 'Sexta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 01/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 02/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {
                                          //echo 'kmfrlk'; exit;
                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));            

                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//domingo
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//segunda
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//terca                                       
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//quarta
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//quinta                

                                          $tblhtml .= '<th width="13%" ><b>'; //sexta   
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-01";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 

                                                      foreach ($medicos as $medico){

                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';    
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-02";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 

                                                      foreach ($medicos as $medico){

                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';


                                          $tblhtml .= '</tr>';

                                      }     


                              }

                              if($semana1 == 'Sabado'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 01/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {
                                          //echo 'kmfrlk'; exit;
                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));            

                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//domingo
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//segunda
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//terca                                       
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//quarta
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//quinta                
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//sexta 

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-01";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 

                                                      foreach ($medicos as $medico){

                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';


                                          $tblhtml .= '</tr>';

                                      }     


                              }

                              /*
                               * FIM 1A SEMANA
                               */

                              /*
                               *  SEGUNDA SEMANA
                               * 
                               */


                              if($semana2 == 'Domingo'){

                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 08/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 09/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 10/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 11/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 12/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 13/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 14/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                     $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';

                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-08";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-09";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-10";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-11";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quinta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-12";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//sexta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-13";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//sabado
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-14";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '</tr>';

                                      }    
                              }

                              if($semana2 == 'Segunda'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 07/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 08/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 09/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 10/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 11/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 12/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 13/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                     $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-07";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-08";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-09";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-10";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-11";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-12";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-13";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }    
                              }

                              if($semana2 == 'Terca'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 06/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 07/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 08/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 09/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 10/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 11/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 12/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                     $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-06";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-07";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-08";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-09";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-10";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-11";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-12";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }    
                              }

                              if($semana2 == 'Quarta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 05/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 06/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 07/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 08/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 09/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 10/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 11/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                     $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-05";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-06";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-07";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-08";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-09";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-10";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-11";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }    
                              }

                              if($semana2 == 'Quinta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 04/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 05/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 06/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 07/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 08/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 09/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 10/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                     $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-04";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-05";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-06";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-07";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-08";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-09";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-10";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }    
                              }

                              if($semana2 == 'Sexta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 03/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 04/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 05/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 06/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 07/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 08/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 09/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';



                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-03";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-04";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-05";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-06";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-07";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-08";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-09";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }      
                              }

                              if($semana2 == 'Sabado'){

                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 02/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 03/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 04/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 05/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 06/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 07/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 08/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';

                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-02";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-03";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-04";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-05";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-06";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-07";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-08";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }      
                              }

                              /*
                               * FIM 2A SEMANA
                               */

                              /*
                               *  3a SEMANA
                               * 
                               */


                              if($semana3 == 'Domingo'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 15/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 16/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 17/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 18/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 19/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 20/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 21/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);    
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));

                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';

                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-15";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-16";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-17";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-18";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quinta

                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-19";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>'; //sexta   
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-20";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-21";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '</tr>';

                                      }         
                              }

                              if($semana3 == 'Segunda'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 14/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 15/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 16/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 17/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 18/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 19/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 20/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);    
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));

                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';

                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-14";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-15";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-16";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-17";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quinta

                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-18";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>'; //sexta   
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-19";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-20";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '</tr>';

                                      }         
                              }

                              if($semana3 == 'Terca'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 13/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 14/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 15/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 16/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 17/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 18/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 19/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);    
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));

                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';

                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-13";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-14";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-15";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-16";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quinta

                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-17";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>'; //sexta   
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-18";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-19";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '</tr>';

                                      }         
                              }

                              if($semana3 == 'Quarta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 12/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 13/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 14/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 15/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 16/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 17/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 18/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);    
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));

                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';

                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-12";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-13";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-14";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-15";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quinta

                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-16";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>'; //sexta   
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-17";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-18";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '</tr>';

                                      }         
                              }

                              if($semana3 == 'Quinta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 11/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 12/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 13/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 14/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 15/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 16/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 17/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);    
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));

                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';

                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-11";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-12";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-13";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-14";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quinta

                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-15";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>'; //sexta   
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-16";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>'; //sabado  
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-17";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '</tr>';

                                      }         
                              }

                              if($semana3 == 'Sexta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 10/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 11/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 12/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 13/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 14/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 15/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 16/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);   
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));

                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-10";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-11";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-12";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-13";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-14";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-15";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-16";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }                  
                              }

                              if($semana3 == 'Sabado'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 09/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 10/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 11/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 12/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 13/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 14/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 15/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);   
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));

                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-09";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-10";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-11";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-12";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-13";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-14";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-15";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }                  
                              }

                              /*
                               * FIM 3a SEMANA
                               */


                              /*
                               *  4a SEMANA
                               * 
                               */


                              if($semana4 == 'Domingo'){

                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 22/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 23/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 24/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 25/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-22";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-23";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//terça
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-24";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-25";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quinta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-26";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sexta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-27";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sabado
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-28";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }  
                              }

                              if($semana4 == 'Segunda'){

                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 21/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 22/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 23/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 24/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 25/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-21";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-22";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//terça
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-23";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-24";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quinta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-25";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sexta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-26";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sabado
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-27";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }  
                              }

                              if($semana4 == 'Terca'){

                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 20/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 21/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 22/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 23/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 24/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 25/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-20";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-21";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//terça
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-22";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-23";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quinta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-24";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sexta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-25";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sabado
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-26";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }  
                              }

                              if($semana4 == 'Quarta'){

                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 19/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 20/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 21/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 22/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 23/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 24/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 25/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-19";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-20";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//terça
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-21";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-22";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quinta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-23";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sexta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-24";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sabado
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-25";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }  
                              }

                              if($semana4 == 'Quinta'){

                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 18/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 19/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 20/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 21/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 22/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 23/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 24/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-18";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-19";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//terça
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-20";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-21";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quinta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-22";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sexta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-23";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sabado
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-24";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }  
                              }

                              if($semana4 == 'Sexta'){



                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 17/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 18/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 19/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 20/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 21/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 22/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 23/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';



                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-17";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-18";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//terça
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-19";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-20";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quinta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-21";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sexta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-22";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sabado
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-23";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }      

                              }

                              if($semana4 == 'Sabado'){



                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 16/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 17/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 18/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 19/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 20/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 21/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 22/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '</tr>';



                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-16";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-17";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//terça
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-18";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-19";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//quinta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-20";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sexta
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-21";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '<th width="13%" ><b>';//sabado
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-22";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>'; 
                                          $tblhtml .= '</b></th>';
                                          $tblhtml .= '</tr>';

                                      }      

                              }

                              /*
                               * FIM 4 SEMANA
                               */


                              /*
                               *  5a SEMANA
                               * 
                               */


                              if($semana5 == 'Domingo'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';

                                      if($numDias_mes==29){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça</b></th>';
                                      }
                                      else if($numDias_mes==30){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 30/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça</b></th>';
                                      }
                                      else{
                                      $tblhtml .= '<th width="13%" ><b>Domingo 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 30/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 31/'.$mes_comp.'</b></th>';            
                                      }
                                      $tblhtml .= '<th width="13%" ><b>Quarta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado</b></th>';
                                      $tblhtml .= '</tr>';



                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-29";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-30";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-31";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b></b></th>';//quarta            
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//quinta
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//sexta
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//sabado

                                          $tblhtml .= '</tr>';

                                      } 

                              }

                              if($semana5 == 'Segunda'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';

                                      if($numDias_mes==28){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta</b></th>';
                                      }
                                      else if($numDias_mes==29){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta</b></th>';
                                      }
                                      else if($numDias_mes==30){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 30/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta</b></th>';
                                      }
                                      else{
                                      $tblhtml .= '<th width="13%" ><b>Domingo 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 30/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 31/'.$mes_comp.'</b></th>';
                                      }
                                      $tblhtml .= '<th width="13%" ><b>Quinta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado</b></th>';
                                      $tblhtml .= '</tr>';



                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-28";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-29";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-30";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-31";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b></b></th>';//quinta
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//sexta
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//sabado

                                          $tblhtml .= '</tr>';

                                      } 

                                      }

                              if($semana5 == 'Terca'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';

                                      if($numDias_mes==28){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta</b></th>';
                                      }
                                      else if($numDias_mes==29){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta</b></th>';
                                      }
                                      else if($numDias_mes==30){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 30/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta</b></th>';
                                      }
                                      else{
                                      $tblhtml .= '<th width="13%" ><b>Domingo 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 30/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 31/'.$mes_comp.'</b></th>';
                                      }

                                      $tblhtml .= '<th width="13%" ><b>Sexta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado</b></th>';
                                      $tblhtml .= '</tr>';



                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-27";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-28";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-29";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-30";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>';//quinta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-31";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b></b></th>';//sexta
                                          $tblhtml .= '<th width="13%" ><b></b></th>';//sabado

                                          $tblhtml .= '</tr>';

                                      } 

                                      }

                              if($semana5 == 'Quarta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';

                                      if($numDias_mes==28){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta</b></th>';
                                      }
                                      else if($numDias_mes==29){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta</b></th>';
                                      }
                                      else if($numDias_mes==30){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 30/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta</b></th>';
                                      }
                                      else{
                                      $tblhtml .= '<th width="13%" ><b>Domingo 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 30/'.$mes_comp.'</b></th>';            
                                      $tblhtml .= '<th width="13%" ><b>Sexta 31/'.$mes_comp.'</b></th>';
                                      }

                                      $tblhtml .= '<th width="13%" ><b>Sábado</b></th>';
                                      $tblhtml .= '</tr>';



                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-26";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-27";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-28";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-29";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>';//quinta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-30";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//sexta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-31";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b></b></th>';//sabado

                                          $tblhtml .= '</tr>';

                                      } 

                             }

                              if($semana5 == 'Quinta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';

                                      if($numDias_mes==28){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 25/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado</b></th>';
                                      }
                                      else if($numDias_mes==29){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 25/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado</b></th>';
                                      }
                                      else if($numDias_mes==30){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 25/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 30/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado</b></th>';
                                      }
                                      else{
                                      $tblhtml .= '<th width="13%" ><b>Domingo 25/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 30/'.$mes_comp.'</b></th>';           
                                      $tblhtml .= '<th width="13%" ><b>Sábado 31/'.$mes_comp.'</b></th>';
                                      }

                                      $tblhtml .= '</tr>';



                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-25";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-26";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-27";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-28";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>';//quinta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-29";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//sexta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-30";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//sabado
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-31";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data,'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '</tr>';

                                      } 

                             }

                              if($semana5 == 'Sexta'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 24/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 25/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 26/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 27/'.$mes_comp.'</b></th>';
                                      if($numDias_mes==28){
                                      $tblhtml .= '<th width="13%" ><b>Quinta 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado</b></th>';
                                      }
                                      else if($numDias_mes==29){
                                      $tblhtml .= '<th width="13%" ><b>Quinta 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado</b></th>';
                                      }else{
                                      $tblhtml .= '<th width="13%" ><b>Quinta 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 29/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 30/'.$mes_comp.'</b></th>';
                                      }
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));



                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-24";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 

                                                      foreach ($medicos as $medico){

                                                         $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';   
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-25";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-26";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-27";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>';//quinta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-28";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                            $tblhtml .= '<th width="13%" ><b>';//sexta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-29";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>';//sabado
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-30";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';


                                          $tblhtml .= '</tr>';

                                      } 

                              }

                              if($semana5 == 'Sabado'){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 23/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 24/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça 25/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta 26/'.$mes_comp.'</b></th>';
                                      if($numDias_mes==28){
                                      $tblhtml .= '<th width="13%" ><b>Quinta 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado</b></th>';
                                      }
                                      else if($numDias_mes==29){
                                      $tblhtml .= '<th width="13%" ><b>Quinta 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 29/'.$mes_comp.'</b></th>';
                                      }else{
                                      $tblhtml .= '<th width="13%" ><b>Quinta 27/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta 28/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado 29/'.$mes_comp.'</b></th>';
                                      }
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']); 
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';//domingo
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-23";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 

                                                      foreach ($medicos as $medico){

                                                         $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';   
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//segunda
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-24";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//terca
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-25";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'terca'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                          $tblhtml .= '<th width="13%" ><b>';//quarta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-26";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quarta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>';//quinta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-27";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'quinta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                            $tblhtml .= '<th width="13%" ><b>';//sexta
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-28";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sexta'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';

                                           $tblhtml .= '<th width="13%" ><b>';//sabado
                                               $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-29";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'sabado'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                          $tblhtml .= '</b></th>';


                                          $tblhtml .= '</tr>';

                                      } 

                              }


                              /*
                               * FIM 5 SEMANA
                               */


                              /*
                               * quando a semana 6 termina no domingo
                               */

                              if($semana6_dom){

                                      if($numDias_mes == 31){

                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Domingo 31/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda </b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Terça </b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta </b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta </b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta </b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado </b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-31";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '<th width="13%" ><b>';

                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '<th width="13%" ><b>';

                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '<th width="13%" ><b>';

                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '<th width="13%" ><b>';

                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '<th width="13%" ><b>';

                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '<th width="13%" ><b>';

                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '</tr>';

                                      }      

                                  }
                              }

                              if($semana6_seg){


                                      $tblhtml .= '<tr style="color:#fff;" bgcolor="#6E8894" >';
                                      $tblhtml .= '<th width="9%"><b>Horário</b></th>';
                                      if($numDias_mes==30){
                                      $tblhtml .= '<th width="13%" ><b>Domingo 30/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda</b></th>';
                                      }else{
                                      $tblhtml .= '<th width="13%" ><b>Domingo 30/'.$mes_comp.'</b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Segunda 31/'.$mes_comp.'</b></th>';    
                                      }
                                      $tblhtml .= '<th width="13%" ><b>Terça </b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quarta </b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Quinta </b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sexta </b></th>';
                                      $tblhtml .= '<th width="13%" ><b>Sábado </b></th>';
                                      $tblhtml .= '</tr>';


                                      $horarios = $this->ci->Relatorio_escala_model->get_horarios_escala($escala_setor['setor_id']);  
                                      foreach ($horarios as $hora) {

                                          $setor_id = $hora['setor_id'];
                                          $hora_inicio = $hora['hora_inicio'];
                                          $hora_fim = $hora['hora_fim'];
                                          $hora_format_st = date('H', strtotime($hora_inicio));
                                          $hora_format_end = date('H', strtotime($hora_fim));


                                          $tblhtml .= '<tr class="center" >';
                                          $tblhtml .= '<td width="9%" align="center">&nbsp;<br>'
                                                      .$hora_format_st.'-'.$hora_format_end.
                                                      '</td>';
                                          $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-30";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'domingo'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                              $tblhtml .= '</b></th>';

                                              $tblhtml .= '<th width="13%" ><b>';
                                              $tblhtml .= '<table>';
                                                      $data_cal = "$ano_comp-$mes_comp-31";
                                                      $start_data = $data_cal.' '.$hora_inicio;                              $end_data = $data_cal.' '.$hora_fim;
                                                      $medicos = $this->ci->Relatorio_escala_model->get_medicos($escala_setor['competencia_id'], $setor_id, $start_data ,$end_data, 'segunda'); 
                                                      foreach ($medicos as $medico){
                                                          $tblhtml .= '<tr><td>&nbsp;<br>'.$medico['medico'].'</td></tr>';  
                                                      }
                                              $tblhtml .= '</table>';  
                                              $tblhtml .= '</b></th>';

                                              $tblhtml .= '<th width="13%" ><b>';

                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '<th width="13%" ><b>';

                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '<th width="13%" ><b>';

                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '<th width="13%" ><b>';

                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '<th width="13%" ><b>';

                                              $tblhtml .= '</b></th>';
                                              $tblhtml .= '</tr>';

                                      }      


                              }


                  $tblhtml .= '</table>';


                  $pdf->SetAutoPageBreak(true, 50);
                  //echo $tblhtml;
                  $pdf->writeHTML($tblhtml, true, false, false, false, ''); 
                  //echo 'testando'; exit;
                  //RODAPÉ PÁGINA
                  $pdf->SetLineStyle( array( 'width' => 0, 'color' => array(0,0,0)));
                  $data_hoje = date('d/m/Y');
                  $hora_hoje = date('H:i:s');
                  $pdf->SetTextColor(0);
                  $pdf->SetFont($font_name, 'B', 10);
                  $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(240, 240, 240)));
                  $pdf->SetFillColor(240, 240, 240);
                  $pdf->Cell(0, 0, _l('Emitido em: '.$data_hoje.', às '.$hora_hoje),1, 1, 'L', 1, 0);
                  $pdf->writeHTML($content, true, 0, true, 0);//quebra de pagina
                  $pdf->AddPage();
                  $pdf->setPage($pdf->getPage()); 
            }
}