
<?php 
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
    <title>BOLETO UNIMED MANAUS</title>


<style type="text/css">
	
	.cp {  
		font-family: Arial, Helvetica, sans-serif;
		font-size: 11px;
		color: #000;
		font-weight:normal;
	};
	
	.ti {  
		font-family: Arial, Helvetica, sans-serif;
		font-size: 9px;
		color: #000;
		font-weight:normal;
	};
	
	.ld {  
		font-family: Arial, Helvetica, sans-serif;
		font-size: 15px;
		color: #000;
		font-weight:normal;
	};
	
	.ct {  
		font-family: Arial, Helvetica, sans-serif;
		font-size: 9px;
		color: #000033;
		font-weight:normal;
	};
	
	.cn {  
		font-family: Arial, Helvetica, sans-serif;
		font-size: 20px;
		color: #000000;
		font-weight:bold;
	};
	
	table, td
	{
		padding:0;
	}
	
	.td_dm_1, .td_dm_2, .td_dm_3{
		padding: 3px;
		font-size: 8px;
	}
	.td_dm_1{
		width:30%;
	}
	.td_dm_2{
		width:55%;
	}
	.td_dm_3{
		width:15%;
		padding: 3px;
		text-align: right;
	}
	
</style>

</head>';
?>

<?php

	 function fbarcode($valor){

		$fino = 1 ;
		$largo = 3 ;
		$altura = 50 ;
		
		  $barcodes[0] = "00110" ;
		  $barcodes[1] = "10001" ;
		  $barcodes[2] = "01001" ;
		  $barcodes[3] = "11000" ;
		  $barcodes[4] = "00101" ;
		  $barcodes[5] = "10100" ;
		  $barcodes[6] = "01100" ;
		  $barcodes[7] = "00011" ;
		  $barcodes[8] = "10010" ;
		  $barcodes[9] = "01010" ;
		  for($f1=9;$f1>=0;$f1--){ 
			for($f2=9;$f2>=0;$f2--){  
			  $f = ($f1 * 10) + $f2 ;
			  $texto = "" ;
			  for($i=1;$i<6;$i++){ 
				$texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
			  }
			  $barcodes[$f] = $texto;
			}
		  }
		
		
		//Desenho da barra
		
		//Guarda inicial
		
		$html = '<img src='.base_url().'imagens/p.png  width="'.$fino.'" height="'.$altura.'" border=0><img 
		src="'.base_url().'imagens/b.png" width="'.$fino.'" height="'.$altura.'" border=0><img 
		src="'.base_url().'imagens/p.png" width="'.$fino.'" height="'.$altura.'" border=0><img 
		src="'.base_url().'imagens/b.png" width="'.$fino.'" height="'.$altura.'" border=0><img ';
		
		$texto = $valor ;
		if((strlen($texto) % 2) <> 0){
			$texto = "0" . $texto;
		}
		
		// Draw dos dados
		while (strlen($texto) > 0) {
		  $i = round(esquerda($texto,2));
		  $texto = direita($texto,strlen($texto)-2);
		  $f = $barcodes[$i];
		  for($i=1;$i<11;$i+=2){
			if (substr($f,($i-1),1) == "0") {
			  $f1 = $fino ;
			}else{
			  $f1 = $largo ;
			}
		
		$html .= '	src="'.base_url().'imagens/p.png" width="'.$f1.'" height="'.$altura.'" border=0><img ';
		
			if (substr($f,$i,1) == "0") {
			  $f2 = $fino ;
			}else{
			  $f2 = $largo ;
			}
		
		$html .= '	src="'.base_url().'imagens/b.png" width="'.$f2.'" height="'.$altura.'" border=0><img ';
		
		  }
		}
		
		// Draw guarda final
		$html .= '
		src="'.base_url().'imagens/p.png" width="'.$largo.'" height="'.$altura.'" border=0><img 
		src="'.base_url().'imagens/b.png" width="'.$fino.'" height="'.$altura.'" border=0><img 
		src="'.base_url().'imagens/p.png" width="1" height="'.$altura.'" border=0> ';
		return $html;
		} //Fim da função
?>

<?php 

$html .= '<body  backtop="1mm" backbottom="1mm" backleft="4mm" backright="4mm" style="font-size: 8px;  font-weight: normal; color:#000033;">
	<table width="100%"  cellspacing="0" cellpadding="0" border="0.3">
		<tr>
			<td valign="top" class="cp" style="height:60px;">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td valign="top" class="cp" style="width:20%;">
							<div style="margin:2px; float:left;">
								<img src="'.  $logo_unimed .'" width=115 />
							</div>
						</td>
						<td valign="top" class="cp" style="width:65%;">
							<div style="font-size: 13px; font-weight: normal; color:#000033; text-align:center;">
								<br/>UNIMED DE MANAUS COOPERATIVA DE TRABALHO MÉDICO LTDA
							</div>
						</td>
						<td valign="top" class="cp" style="width:15%;">
							<div style="">
								<img style="float:right;" src="'. $logo_ans .'" width=70 />
							</div>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		<tr>
			<td valign="top" class="cp">
				<div align="center" style="height:40px;">
					<span style="font-size: 13px; font-weight: normal; color:#000033; margin:15px;">
						Demonstrativo dos Itens da Mensalidade
					</span>
				</div>
			</td>
		</tr>
		
		<tr>
			<td valign="top" class="cp">
				<div align="left" class="cabecalho" style="height:120px;">
					<table width="100%" cellspacing=0 cellpadding=0 border=0>';
							if( !empty($dados_demonstrativo) ){
								$html .= '<tr><td class="td_dm_1">'.$dados_demonstrativo['IE_TIPO']." - ".$dados_demonstrativo['DS_ITEM'].'</td><td class="td_dm_2">'.$dados_demonstrativo['DS_MENSAGEM_REAJUSTE'].'</td><td class="td_dm_3">R$'.$dados_demonstrativo['VL_ITEM'].'</td></tr>';
							}
						
					$html .= '</table>
				</div>
			</td>
		</tr>
	</table>
	
	<table width="100%" cellspacing=0 cellpadding=0  border="0.3">
		<tr>
			
		</tr>
		<tr>
			
			<td class="cp" style="width:100%;text-align:justify;font-size:9px;">'. $demonstrativo1 . '<br>' . $demonstrativo2 . '<br>' . $demonstrativo3.' </td>
			
			
		</tr>
		<tr>
			
			<td class="cp" style="width:100%;text-align:justify;">'. $demonstrativo4 .'<br> <br> </td>
			
			
		</tr>
		<tr>
			
			<td width="100%" class="ct">Sacador/Avalista</td>
		
			
		</tr>
		<tr>
			
			<td class="cp"><b>'. $sacadoravalista .'</b> <br> <br> </td>
			
			
		</tr>
		<tr><td  class="ct" align=right>Corte na linha pontilhada</td></tr>
		<tr><td ><img src="'. $imagem_banco_6 .'" width=690 height=1></td></tr>
	</table>
	
	<br>
	<table width="100%" cellspacing=0 cellpadding=0  border="0.3">
		<tr>
			<td  class="cp"><img src="'. $logo.'" alt="Sicredi" width="150" height="40"></td>
			<td width=3 valign=bottom><img height=22 src="'. $imagem_banco_3.'" width=2></td>
			<td  class=bc valign=bottom align=center>
				<span align="center" style="font-size: 20px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; color:#000;">
					'. $codigo_banco_com_dv.'
				</span>
			</td>
			<td width=3 valign=bottom><img height=22 src="'.$imagem_banco_3.'" width=2></td>
			<td  class=ld align=right valign=bottom>
				<span style="font-size: 15px; font-weight: normal; color:#000; font-weight: normal; font-family: Arial, Helvetica, sans-serif;">
					'.$linha_digitavel.'
				</span>
			</td>
		</tr>
		<tr><td colspan=5><img height=1 src="'.$imagem_banco_2.'" ></td></tr>
	</table>
	
	<table width="100%" cellspacing=0 cellpadding=0 border=0 height="2">
		<tr>
			<td width=7 height=1><img src="'. $imagem_banco_2.'" width=7 height=1></td>
			<td width=80><img src="'. $imagem_banco_2.'" width=80 height=1></td>
			<td width=7><img src="'. $imagem_banco_2.'" width=7 height=1></td>
			<td width=74><img src="'. $imagem_banco_2.'" width=74 height=1></td>
			<td width=7><img src="'. $imagem_banco_2.'" width=7 height=1></td>
			<td width=73><img src="'. $imagem_banco_2.'" width=73 height=1></td>
			<td width=7><img src="'. $imagem_banco_2.'" width=7 height=1></td>
			<td width=55><img src="'. $imagem_banco_2.'" width=55 height=1></td>
			<td width=7><img src="'. $imagem_banco_2.'" width=7 height=1></td>
			<td width=35><img src="'. $imagem_banco_2.'" width=35 height=1></td>
			<td width=7><img src="'. $imagem_banco_2.'" width=7 height=1></td>
			<td width="1"00><img src="'. $imagem_banco_2.'" width=100 height=1></td>
			<td width=7><img src="'. $imagem_banco_2.'" width=7 height=1></td>
			<td width=120><img src="'. $imagem_banco_2.'" width=120 height=1></td>
		</tr>
		<tr>
			<td height="13"><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td colspan="11" class="ct">Local de pagamento</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Vencimento</td>
		</tr>
		<tr>
			<td height="12"><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td colspan="11" class="cp">'.$preferencia.'</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp" >'.$data_vencimento.'</td>
		</tr>
		<tr><td colspan="14" height=1><img src="'. $imagem_banco_2.'" width=690 height=1></td></tr>
		<tr>
			<td height="13"><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td colspan="11" class="ct">Cedente</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Agência/Código cedente</td>
		</tr>
		<tr>
			<td height="12"><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td colspan="11"  class="cp">'.$cedente.'</td>
			<td><img src="'.$imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp" >'.$agencia_codigo.'</td>
		</tr>
		<tr><td colspan="14" height=1><img src="'.$imagem_banco_2.'" width=690 height=1></td></tr>
		<tr>
			<td height="13"><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Data do documento</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td colspan=3 class="ct">Número do documento</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Espécie doc.</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Aceite</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Data processamento</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Nosso número</td>
		</tr>
		<tr>
			<td height="12"><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp">'.$data_documento.'</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td colspan=3 class="cp">'.$numero_documento.'</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp">'.$especie_doc.'</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp">'.$aceite.'</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp">'.$data_processamento.'</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp" >'.$nosso_numero.'</td>
		</tr>
		<tr><td colspan="14" height=1><img src="'. $imagem_banco_2.'" width=690 height=1></td></tr>
		<tr>
			<td height="13"><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Uso do banco</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Carteira</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Espécie</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td colspan=3 class="ct">Quantidade</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Valor</td>
			<td><img src="'. $imagem_banco_1.'" width=1 height=13></td>
			<td class="ct">(=) Valor documento</td>
		</tr>
		<tr>
			<td height="12"><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp" height="12"> </td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp">'.$carteira.'</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp">'.$especie.'</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td colspan=3 class="cp">'.$quantidade.'</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp">'.$valor_unitario.'</td>
			<td><img src="'. $imagem_banco_1.'" width="1" height="12"></td>
			<td class="cp" >'.$valor_boleto.'</td>
		</tr>
		<tr><td colspan="14" height=1><img src="'. $imagem_banco_2.'" width=690 height=1></td></tr>
	</table>
	
	<table width="100%" cellspacing=0 cellpadding=0 border=0>
		<tr>
			<td width=7 height=26><img src="'.$imagem_banco_1.'" width="1" height=26></td>
			<td width=426 rowspan=9 valign=top>
				<span class="ct">Instruções (Texto de responsabilidade do cedente)</span><br>
				 <br>
				<span class="cp">'.$instrucoes1.'<br>' . $instrucoes2 . '<br>' . $instrucoes3 . '<br>' . $instrucoes4 . '<br>' . $instrucoes5 .'</span>
			</td>
			<td width=7><img src="'.$imagem_banco_2.'" width="1" height=26></td>
			<td width=120 class=ct>(-) Desconto / Abatimentos</td>
		</tr>
		<tr><td height=1><img src="'.$imagem_banco_2.'" width="1" height=1></td>
		<td><img src="'.$imagem_banco_2.'" width=7 height=1></td>
		<td><img src="'.$imagem_banco_2.'" width=100 height=1></td></tr>
		<tr>
			<td height=26><img src="'.$imagem_banco_1.'" width="1" height=26></td>
			<td><img src="'.$imagem_banco_2.'" width="1" height=26></td>
			<td class="ct">(-) Outras deduções</td>
		</tr>
		<tr><td height=1><img src="'.$imagem_banco_1.'" width="1" height=1></td>
		<td><img src="'.$imagem_banco_2.'" width=7 height=1></td>
		<td><img src="'.$imagem_banco_2.'" width=100 height=1></td></tr>
		<tr>
			<td height=26><img src="'.$imagem_banco_1.'" width="1" height=26></td>
			<td><img src="'.$imagem_banco_2.'" width="1" height=26></td>
			<td class="ct">(+) Mora / Multa</td>
		</tr>
		<tr><td height=1><img src="'.$imagem_banco_1.'" width="1" height=1></td>
		<td><img src="'.$imagem_banco_2.'" width=7 height=1></td>
		<td><img src="'.$imagem_banco_2.'" width=100 height=1></td></tr>
		<tr>
			<td height=26><img src="'.$imagem_banco_1.'" width="1" height=26></td>
			<td><img src="'.$imagem_banco_2.'" width="1" height=26></td>
			<td class="ct">(+) Outros acréscimos</td>
		</tr>
		<tr><td height=1><img src="'.$imagem_banco_1.'" width="1" height=1></td>
		<td><img src="'.$imagem_banco_2.'" width=7 height=1></td>
		<td><img src="'.$imagem_banco_2.'" width=100 height=1></td></tr>
		<tr>
			<td height=26><img src="'.$imagem_banco_1.'" width="1" height=26></td>
			<td><img src="'.$imagem_banco_2.'" width="1" height=26></td>
			<td class="ct">(=) Valor cobrado</td>
		</tr>
		<tr><td colspan=4 height=1><img src="'.$imagem_banco_2.'" width=690 height=1></td></tr>
		<tr>
			<td height="13"><img src="'.$imagem_banco_1.'" width="1" height="13"></td>
			<td class="ct">Sacado</td>
			<td><img src="'.$imagem_banco_b.'" width="1" height=1></td>
			<td><img src="'.$imagem_banco_b.'" width="1" height=1></td>
		</tr>
		<tr>
			<td height=39><img src="'.$imagem_banco_1.'" width="1" height=39></td>
			<td class="cp">'.$sacado. '<br>' . $endereco1 . '<br>' . $endereco2.'</td>
			<td valign=bottom><img src="'.$imagem_banco_1.'" width="1" height="13"></td>
			<td valign=bottom><span class="ct">Cód. baixa</span></td>
		</tr>
		<tr><td colspan=4 height=1><img src="'.$imagem_banco_2.'" width=690 height=1></td></tr>
	</table>
	<br />
	<table  width="100%" cellspacing=0 cellpadding=0 border=0>
		<tr>
			<td width=50% class="ct">Sacador/Avalista</td>
			<td width=50% class="ct" align="right">Autenticação mecânica - <span class="cp">Ficha de Compensação</span></td>
		</tr>
		<tr><td height=20 colspan=2><b>'.$sacadoravalista.'</b></td></tr>
		<tr><td height=50 colspan=2>'.fbarcode($codigo_barras).'</td></tr>
		<tr><td colspan=2 class="ct" align="right">Corte na linha pontilhada</td></tr>
		<tr><td colspan=2 height=1><img src="'.$imagem_banco_6.'" width=690 height=1></td></tr>
	</table>	
</body>  
</html>  ';



try{

	
	
	$dompdf->loadHtml($html);

	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'portrait');

	// Render the HTML as PDF
	$dompdf->render();

	$dompdf->stream('boleto_unimed_manaus.pdf', ['Attachment' => false]);

	// Output the generated PDF to Browser
	$dompdf->stream("Ficha_de_Atendimento.pdf", ['Attachment' => false]);


	/*$html2pdf = new HTML2PDF('P', 'A4', 'pt', true, 'UTF-8');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));	
	$html2pdf->Output('Boleto_Unimed_Manaus_'.$cpf.'.pdf'); */
	
	/* Salva o PDF no servidor para enviar por email */
	//$html2pdf->Output('gerados/'.$cpf.'.pdf', 'F');
	
	/* Força o download no browser */
	//$html2pdf->Output('boleto.pdf', 'D');
}catch(HTML2PDF_exception $e) {
	echo $e;
	exit;
}
	//echo ; exit;
	?>      