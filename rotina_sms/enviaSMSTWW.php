<?php

require_once dirname(__FILE__) . '/TWW/TWWAutoload.php';

define('TWW_WSDL_URL','http://webservices2.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL');
define('TWW_USER_LOGIN','UNIMEDAM');
define('TWW_USER_PASSWORD','#adm123@');

function EnviaSMSTWW($numero, $canal ,$mensagem){
	
	$mensagem = removeAcentos($mensagem);
	$canal    = removeAcentos($canal);
	
	//echo "$numero, $canal ,$mensagem"; exit;
	
	$wsdl = array();
	$wsdl[TWWWsdlClass::WSDL_URL] = TWW_WSDL_URL;
	$wsdl[TWWWsdlClass::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
	$wsdl[TWWWsdlClass::WSDL_TRACE] = true;
	if(TWW_USER_LOGIN !== '')
		$wsdl[TWWWsdlClass::WSDL_LOGIN] = TWW_USER_LOGIN;
	if(TWW_USER_PASSWORD !== '')
		$wsdl[TWWWsdlClass::WSDL_PASSWD] = TWW_USER_PASSWORD;
	
	$tWWServiceEnvia = new TWWServiceEnvia($wsdl);
	$numero = '92991553632';
	$tWWServiceEnvia->EnviaSMS(new TWWStructEnviaSMS('UNIMEDAM','#adm123@', $canal, '55'.$numero, $mensagem));
	
	//echo "<pre>"; print_r($tWWServiceEnvia->getResult()); exit;
	
	return $tWWServiceEnvia->getResult()->EnviaSMSResult->EnviaSMSResult;
	
}

function removeAcentos($string){
	//return preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $string ) );
	//return preg_replace( '/[`^~\'"]/', null, $string );
	return $string;
}

?>