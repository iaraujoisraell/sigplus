<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("America/Manaus");
setlocale(LC_ALL, 'pt_BR');

//echo "<pre>"; print_r($_POST); exit;

/**************************** FUNÇÕES ******************************************/
    

function digitoVerificador_nossonumero($numero) {
	$resto2 = modulo_11($numero, 7, 1);
	 $digito = 11 - $resto2;
	 if ($digito == 10) {
		$dv = "P";
	 } elseif($digito == 11) {
		 $dv = 0;
	} else {
		$dv = $digito;
		 }
	 return $dv;
}

function digitoVerificador_barra($numero) {
	$resto2 = modulo_11($numero, 9, 1);
	 if ($resto2 == 0 || $resto2 == 1 || $resto2 == 10) {
		$dv = 1;
	 } else {
		 $dv = 11 - $resto2;
	 }
	 return $dv;
}

function formata_numero($numero,$loop,$insert,$tipo = "geral") {
	if ($tipo == "geral") {
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "valor") {
		/*
		retira as virgulas
		formata o numero
		preenche com zeros
		*/
		$numero = str_replace(",","",$numero);
		while(strlen($numero)<$loop){
			$numero = $insert . $numero;
		}
	}
	if ($tipo == "convenio") {
		while(strlen($numero)<$loop){
			$numero = $numero . $insert;
		}
	}
	return $numero;
}
//echo 'aqui'; exit;


function esquerda($entra,$comp){
	return substr($entra,0,$comp);
}

function direita($entra,$comp){
	return substr($entra,strlen($entra)-$comp,$comp);
}

function fator_vencimento($data) {
	$data = explode("/",$data);
	$ano = $data[2];
	$mes = $data[1];
	$dia = $data[0];
	return(abs((_dateToDays("1997","10","07")) - (_dateToDays($ano, $mes, $dia))));
}

function _dateToDays($year,$month,$day) {
	$century = substr($year, 0, 2);
	$year = substr($year, 2, 2);
	if ($month > 2) {
		$month -= 3;
	} else {
		$month += 9;
		if ($year) {
			$year--;
		} else {
			$year = 99;
			$century --;
		}
	}
	return ( floor((  146097 * $century)    /  4 ) +
			floor(( 1461 * $year)        /  4 ) +
			floor(( 153 * $month +  2) /  5 ) +
				$day +  1721119);
}

function modulo_10($num) { 
		$numtotal10 = 0;
		$fator = 2;

		// Separacao dos numeros
		for ($i = strlen($num); $i > 0; $i--) {
			// pega cada numero isoladamente
			$numeros[$i] = substr($num,$i-1,1);
			// Efetua multiplicacao do numero pelo (falor 10)
			// 2002-07-07 01:33:34 Macete para adequar ao Mod10 do Itaú
			$temp = $numeros[$i] * $fator; 
			$temp0=0;
			foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ $temp0+=$v; }
			$parcial10[$i] = $temp0; //$numeros[$i] * $fator;
			// monta sequencia para soma dos digitos no (modulo 10)
			$numtotal10 += $parcial10[$i];
			if ($fator == 2) {
				$fator = 1;
			} else {
				$fator = 2; // intercala fator de multiplicacao (modulo 10)
			}
		}
		
		// várias linhas removidas, vide função original
		// Calculo do modulo 10
		$resto = $numtotal10 % 10;
		$digito = 10 - $resto;
		if ($resto == 0) {
			$digito = 0;
		}
		
		return $digito;
		
}

function modulo_11($num, $base=9, $r=0)  {
	/**
	 *   Autor:
	 *           Pablo Costa <pablo@users.sourceforge.net>
	 *
	 *   Função:
	 *    Calculo do Modulo 11 para geracao do digito verificador 
	 *    de boletos bancarios conforme documentos obtidos 
	 *    da Febraban - www.febraban.org.br 
	 *
	 *   Entrada:
	 *     $num: string numérica para a qual se deseja calcularo digito verificador;
	 *     $base: valor maximo de multiplicacao [2-$base]
	 *     $r: quando especificado um devolve somente o resto
	 *
	 *   Saída:
	 *     Retorna o Digito verificador.
	 *
	 *   Observações:
	 *     - Script desenvolvido sem nenhum reaproveitamento de código pré existente.
	 *     - Assume-se que a verificação do formato das variáveis de entrada é feita antes da execução deste script.
	 */                                        

	$soma = 0;
	$fator = 2;

	/* Separacao dos numeros */
	for ($i = strlen($num); $i > 0; $i--) {
		// pega cada numero isoladamente
		$numeros[$i] = substr($num,$i-1,1);
		// Efetua multiplicacao do numero pelo falor
		$parcial[$i] = $numeros[$i] * $fator;
		// Soma dos digitos
		$soma += $parcial[$i];
		if ($fator == $base) {
			// restaura fator de multiplicacao para 2 
			$fator = 1;
		}
		$fator++;
	}

	/* Calculo do modulo 11 */
	if ($r == 0) {
		$soma *= 10;
		$digito = $soma % 11;
		if ($digito == 10) {
			$digito = 0;
		}
		return $digito;
	} elseif ($r == 1){
		$resto = $soma % 11;
		return $resto;
	}
}

function monta_linha_digitavel($codigo) {

	// 01-03    -> Código do banco sem o digito
	// 04-04    -> Código da Moeda (9-Real)
	// 05-05    -> Dígito verificador do código de barras
	// 06-09    -> Fator de vencimento
	// 10-19    -> Valor Nominal do Título
	// 20-44    -> Campo Livre (Abaixo)
	
	// 20-23    -> Código da Agencia (sem dígito)
	// 24-05    -> Número da Carteira
	// 26-36    -> Nosso Número (sem dígito)
	// 37-43    -> Conta do Cedente (sem dígito)
	// 44-44    -> Zero (Fixo)
		
/* 
DE EXEMPLO 23799665500000005002002020002720000100690300
		   01234567890123456789012345678901234567890123
				0		1			2		 3		 4
COD BANCO 237
MOEDA 9
DIG VERIFICADOR 9
FAT VENCIMENTO 6655
VALOR 0000000500
CAMPO LIVRE 2002020002720000100690300
*/

		$_codbanco = substr($codigo, 0, 3);
		$_codmoeda = substr($codigo, 3, 1);
		$_digverificador = substr($codigo, 4, 1);
		$_fatvencimento = substr($codigo, 5, 4);
		$_valor = substr($codigo, 9, 10);
		$_camlivre = substr($codigo, 19);
		
		if (strpos($_camlivre, "2002") !== false AND strpos($_camlivre, "6903") !== false) {
			//echo $codigo;
			$_campo1 = $_codbanco . $_codmoeda . substr($_camlivre, 0, 5);
			$_campo1 = substr($_campo1, 0, 5) . "." . substr($_campo1, 5) . modulo_10($_campo1);
			
			$_campo2 = substr($_camlivre, 5, 10);
			$_campo2 = $_campo2 . modulo_10($_campo2);
			$_campo2 = substr($_campo2, 0, 5) . "." . substr($_campo2, 5);
			
			$_campo3 = substr($_camlivre, 15, 10);
			$_campo3 = $_campo3 . modulo_10($_campo3);
			$_campo3 = substr($_campo3, 0, 5) . "." . substr($_campo3, 5);
			
			$_campo4 = $_digverificador;
			
			$_campo5 = $_fatvencimento . $_valor;
			
			return "$_campo1 $_campo2 $_campo3 $_campo4 $_campo5";
		}
		
		// 1. Campo - composto pelo código do banco, código da moéda, as cinco primeiras posições
		// do campo livre e DV (modulo10) deste campo
		
		$p1 = substr($codigo, 0, 4);							// Numero do banco + Carteira
		$p2 = substr($codigo, 19, 5);						// 5 primeiras posições do campo livre
		$p3 = modulo_10("$p1$p2");						// Digito do campo 1
		$p4 = "$p1$p2$p3";								// União
		$campo1 = substr($p4, 0, 5).'.'.substr($p4, 5);

		// 2. Campo - composto pelas posiçoes 6 a 15 do campo livre
		// e livre e DV (modulo10) deste campo
		$p1 = substr($codigo, 24, 10);						//Posições de 6 a 15 do campo livre
		$p2 = modulo_10($p1);								//Digito do campo 2	
		$p3 = "$p1$p2";
		$campo2 = substr($p3, 0, 5).'.'.substr($p3, 5);

		// 3. Campo composto pelas posicoes 16 a 25 do campo livre
		// e livre e DV (modulo10) deste campo
		$p1 = substr($codigo, 34, 10);						//Posições de 16 a 25 do campo livre
		$p2 = modulo_10($p1);								//Digito do Campo 3
		$p3 = "$p1$p2";
		$campo3 = substr($p3, 0, 5).'.'.substr($p3, 5);

		// 4. Campo - digito verificador do codigo de barras
		$campo4 = substr($codigo, 4, 1);

		// 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
		// indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
		// tratar de valor zerado, a representacao deve ser 000 (tres zeros).
		$p1 = substr($codigo, 5, 4);
		$p2 = substr($codigo, 9, 10);
		$campo5 = "$p1$p2";

		return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
}

function geraCodigoBanco($numero) {
	$parte1 = substr($numero, 0, 3);
	$parte2 = modulo_11($parte1);
	return $parte1 . "-" . $parte2;
}




/*** FIM FUNÇÕES BOLETO**/



if( empty($cpf) OR empty($boleto) ){
	die('Sem dados para começar.');
}


$ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => 'http://sistemaweb.unimedmanaus.com.br/sigplus/api/boleto',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ "cpf_pagador": "' . $cpf . '", "boleto": "' . $boleto . '" }',
           // CURLOPT_POSTFIELDS => '{ "cpf": "02700034287" }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
        ));
        $resultado = curl_exec($ch);
        $dados_boleto_geral = json_decode($resultado, true);
        $dados_boleto = $dados_boleto_geral['resultado'];

        $DADOS_DEMONSTRATIVO = $dados_boleto_geral['resultado2'];
        $DADOS_SACADO = $dados_boleto_geral['resultado3'];




// $dados_boleto = retorna_dados_boleto($conexao_db,$cpf,$boleto);

//echo "<pre>"; print_r($dados_demons); exit;

if( !empty($dados_boleto) ){

	$VL_TITULO 			= $dados_boleto['VL_TITULO'];
	$DATA_PROCESSAMENTO = $dados_boleto['DATA_PROCESSAMENTO'];
	$DATA_DOCUMENTO 	= $dados_boleto['DATA_DOCUMENTO'];
	$DTA_VENCIMENTO 	= $dados_boleto['DATA_VENCIMENTO'];
	$NR_DOCUMENTO 		= $dados_boleto['NR_DOCUMENTO'];
	$CD_CONVENIO_BANCO 	= $dados_boleto['CD_CONVENIO_BANCO'];
	$NR_NOSSO_NUMERO 	= $dados_boleto['NR_NOSSO_NUMERO'];
	$NOME_PAGADOR 		= $dados_boleto['NOME_PAGADOR'];
	$CONTRATO 			= "";
	$ENDERECO_PAGADOR 	= $dados_boleto['ENDERECO_PAGADOR'];
	$CIDADE_PAGADOR 	= $dados_boleto['CIDADE_PAGADOR'];
	$CD_CARTEIRA 		= $dados_boleto['CD_CARTEIRA'];
	$CD_AGENCIA_BANCARIA= $dados_boleto['CD_AGENCIA_BANCARIA'];
	$NR_CONTA 			= $dados_boleto['NR_CONTA'];	
	$MENSAGEM1			= $dados_boleto['DS_MENSAGEM_1'];
	$MENSAGEM2			= $dados_boleto['DS_MENSAGEM_2'];
	$MENSAGEM3			= $dados_boleto['DS_MENSAGEM_3'];
	$MENSAGEM4			= $dados_boleto['DS_MENSAGEM_4'];
	$MENSAGEM_SACADO	= "";
	$CD_BANCO			= $dados_boleto['CD_BANCO'];
	$NR_BLOQUETO_EDITADO= $dados_boleto['NR_BLOQUETO_EDITADO'];
	$NR_BLOQUETO		= $dados_boleto['NR_BLOQUETO'];
	$CD_AGENCIA_CONTA	= $dados_boleto['CD_AGENCIA_CONTA'];
	
	 // retorna_dados_demonstrativo($conexao_db,$cpf,$boleto);
	
	//$DADOS_SACADO = $dados_boleto['resultado3']; // retorna_dados_sacado($conexao_db,$cpf,$boleto);
	
}else{
	die('Sem resultados.');
}
	
	//DADOS DEMONSTRATIVO
	//print_R($DADOS_DEMONSTRATIVO); exit;
	$dadosboleto["dados_demonstrativo"] = $DADOS_DEMONSTRATIVO; //é um array de informacoes
	
	//DADOS BOLETO
	$dadosboleto["nosso_numero"] 		= $NR_NOSSO_NUMERO;  	
	$dadosboleto["numero_documento"] 	= $NR_DOCUMENTO;  		//$dadosboleto["nosso_numero"];	// Num do pedido ou do documento = Nosso numero
	$dadosboleto["data_vencimento"] 	= $DTA_VENCIMENTO; 		// Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"] 		= $DATA_DOCUMENTO; 		// Data de emissão do Boleto
	$dadosboleto["data_processamento"] 	= $DATA_PROCESSAMENTO; 	// Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"] 		= $VL_TITULO;			// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
	
	// DADOS DO SEU CLIENTE
	$dadosboleto["sacado"] 		= $NOME_PAGADOR; //"Nome do seu Cliente";
	$dadosboleto["endereco1"] 	= $ENDERECO_PAGADOR;//"Endereço do seu Cliente";
	$dadosboleto["endereco2"] 	= $CIDADE_PAGADOR;//"Cidade - Estado -  CEP: 00000-000";
	
	// INFORMACOES PARA O CLIENTE
	$dadosboleto["demonstrativo1"] = @$DADOS_SACADO["MENS1"];
	$dadosboleto["demonstrativo2"] = @$DADOS_SACADO["MENS2"];
	$dadosboleto["demonstrativo3"] = @$DADOS_SACADO["MENS3"];
	$dadosboleto["demonstrativo4"] = nl2br($MENSAGEM_SACADO);
	
	$dadosboleto["instrucoes1"] = $MENSAGEM1;
	$dadosboleto["instrucoes2"] = $MENSAGEM2;
	$dadosboleto["instrucoes3"] = $MENSAGEM3;
	$dadosboleto["instrucoes4"] = $MENSAGEM4;
	$dadosboleto["instrucoes5"] = "";
	//$dadosboleto["instrucoes5"] = "Haverá desconto de 3% para pagamentos até o vencimento.";
	
	// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
	$dadosboleto["quantidade"] 		= "";
	$dadosboleto["valor_unitario"] 	= $VL_TITULO;
	$dadosboleto["aceite"] 			= "N";
	$dadosboleto["especie"] 		= "R$";
	$dadosboleto["especie_doc"] 	= "DS";
	
	// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
	
	$dadosboleto["conta_cedente"] 	= $NR_DOCUMENTO; // ContaCedente do Cliente, sem digito (Somente Números)
	$dadosboleto["carteira"] 		= $CD_CARTEIRA;  // Código da Carteira: pode ser 06 ou 03
	
	// SEUS DADOS
	$dadosboleto["identificacao"] 	= "";
	$dadosboleto["cpf_cnpj"] 		= "04.612.990/0001-70";
	$dadosboleto["endereco"] 		= "";
	$dadosboleto["cidade_uf"] 		= "Manaus/AM";
	$dadosboleto["cedente"] 		= "UNIMED MANAUS COOP DE TRAB MÉDICO LTDA";
	$dadosboleto["codigobanco"] 	= $CD_BANCO;
	$dadosboleto['sacadoravalista'] = 'Unimed Manaus Cooperativa de Trabalho Medico Ltda - CNPJ 004.612.990/0001-70 - gerado em : '.date("d/m/Y H:i:s");
	
	$dadosboleto["codigo_barras"] 	= $NR_BLOQUETO;
	$dadosboleto["linha_digitavel"] = $NR_BLOQUETO_EDITADO;
	$dadosboleto["agencia_codigo"] 	= $CD_AGENCIA_CONTA;
	
	//RETORNA A IMAGEM/MSG CORRETA NO BOLETO
	$dadosboleto["logo"] = "";
	$dadosboleto["preferencia"] = "";
	

    $base_url = base_url();
	//echo $base_url."uploads/imagens_banco/logobradesco.jpg";
    //exit;
    

	if( $CD_BANCO == 237 ){ //bradesco
		$dadosboleto["logo"] = $base_url."uploads/imagens_banco/logobradesco.jpg";
		$dadosboleto["preferencia"] = "Pagável preferencialmente na rede Bradesco ou Bradesco Expresso";
	}
	
	if( $CD_BANCO == 756 ){ //sicoob
		$dadosboleto["logo"] = $base_url."uploads/imagens_banco/logosicoob.jpg";
		$dadosboleto["preferencia"] = "Pagável preferencialmente na rede Sicoob";
	}
	
	if( $CD_BANCO == 341 ){ //itau
		$dadosboleto["logo"] = $base_url."uploads/imagens_banco/logoitau.jpg";
		$dadosboleto["preferencia"] = "Pagável preferencialmente na rede Itaú";
	}
        
        if( $CD_BANCO == 748 ){ //SICRED
		$dadosboleto["logo"] = $base_url."uploads/imagens_banco/logosicred.jpg";
		$dadosboleto["preferencia"] = "Pagável preferencialmente na rede Sicred";
	}

	if( $CD_BANCO == 33 ){ //SANTANDER
		$dadosboleto["logo"] = $base_url."uploads/imagens_banco/logosantander.jpg";
		$dadosboleto["preferencia"] = "Pagável preferencialmente na rede Sicred";
	}
	
	///////////////////////////////
	
ob_start();

//include("funcoes.php");

$dadosboleto["codigo_banco_com_dv"] = geraCodigoBanco($dadosboleto["codigobanco"]);

$this->load->view('portal/financeiro/layout.php', $dadosboleto);
//include("layout.php");

$content = ob_get_clean();
 
// converter para PDF


//require_once('https://unimedmanaus.sigplus.app.br/plugins/html2pdf/html2pdf.class.php');

//require_once('http://unimedweb.unimedmanaus.com.br/servicos/html2pdf/html2pdf.class.php');


try{
	echo $content; exit;
	$html2pdf = new HTML2PDF('P', 'A4', 'pt', true, 'UTF-8');
	//echo $html2pdf; exit;
	/* Abre a tela de impressão */
	$html2pdf->pdf->SetDisplayMode('real');
	
	/* Parametro vuehtml = true desabilita o pdf para desenvolvimento do layout */
	//$_GET['vuehtml'] = true;
	$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	
	/* Abrir no navegador */
	$html2pdf->Output('Boleto_Unimed_Manaus_'.$cpf.'.pdf'); 
	
	/* Salva o PDF no servidor para enviar por email */
	//$html2pdf->Output('gerados/'.$cpf.'.pdf', 'F');
	
	/* Força o download no browser */
	//$html2pdf->Output('boleto.pdf', 'D');
}catch(HTML2PDF_exception $e) {
	echo $e;
	exit;
}
?>