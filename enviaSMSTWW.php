<?php

require_once dirname(__FILE__) . '/TWW/TWWAutoload.php';

define('TWW_WSDL_URL','http://webservices2.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL');
define('TWW_USER_LOGIN','UNIMEDAM');
define('TWW_USER_PASSWORD','unimed@123');

function EnviaSMSTWW($numero, $mensagem, $referencia){
	
	
	$wsdl = array();
	$wsdl[TWWWsdlClass::WSDL_URL] = TWW_WSDL_URL;
	$wsdl[TWWWsdlClass::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
	$wsdl[TWWWsdlClass::WSDL_TRACE] = true;
	if(TWW_USER_LOGIN !== '')
		$wsdl[TWWWsdlClass::WSDL_LOGIN] = TWW_USER_LOGIN;
	if(TWW_USER_PASSWORD !== '')
		$wsdl[TWWWsdlClass::WSDL_PASSWD] = TWW_USER_PASSWORD;
	
	$tWWServiceEnvia = new TWWServiceEnvia($wsdl);
	
	$tWWServiceEnvia->EnviaSMS(new TWWStructEnviaSMS('UNIMEDAM','unimed@123',$referencia,'55'.$numero,$mensagem));
		
	return $tWWServiceEnvia->getResult()->EnviaSMSResult->EnviaSMSResult;
	
}

?>