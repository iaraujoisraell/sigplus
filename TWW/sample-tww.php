<?php
/**
 * Test with TWW
 * @package TWW
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
ini_set('memory_limit','512M');
ini_set('display_errors', true);
error_reporting(-1);
/**
 * Load autoload
 */
require_once dirname(__FILE__) . '/TWWAutoload.php';
/**
 * TWW Informations
 */
define('TWW_WSDL_URL','http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL');
define('TWW_USER_LOGIN','');
define('TWW_USER_PASSWORD','');
/**
 * Wsdl instanciation infos
 */
$wsdl = array();
$wsdl[TWWWsdlClass::WSDL_URL] = TWW_WSDL_URL;
$wsdl[TWWWsdlClass::WSDL_CACHE_WSDL] = WSDL_CACHE_NONE;
$wsdl[TWWWsdlClass::WSDL_TRACE] = true;
if(TWW_USER_LOGIN !== '')
	$wsdl[TWWWsdlClass::WSDL_LOGIN] = TWW_USER_LOGIN;
if(TWW_USER_PASSWORD !== '')
	$wsdl[TWWWsdlClass::WSDL_PASSWD] = TWW_USER_PASSWORD;
// etc....
/**
 * Examples
 */


/*****************************
 * Example for TWWServiceEnvia
 */
$tWWServiceEnvia = new TWWServiceEnvia($wsdl);

// sample call for TWWServiceEnvia::EnviaSMS()
if($tWWServiceEnvia->EnviaSMS(new TWWStructEnviaSMS(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

// sample call for TWWServiceEnvia::EnviaSMS2SN()
if($tWWServiceEnvia->EnviaSMS2SN(new TWWStructEnviaSMS2SN(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

// sample call for TWWServiceEnvia::EnviaSMSQuebra()
if($tWWServiceEnvia->EnviaSMSQuebra(new TWWStructEnviaSMSQuebra(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

// sample call for TWWServiceEnvia::EnviaSMSAlt()
if($tWWServiceEnvia->EnviaSMSAlt(new TWWStructEnviaSMSAlt(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

// sample call for TWWServiceEnvia::EnviaSMSAge()
if($tWWServiceEnvia->EnviaSMSAge(new TWWStructEnviaSMSAge(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

// sample call for TWWServiceEnvia::EnviaSMSAgeQuebra()
if($tWWServiceEnvia->EnviaSMSAgeQuebra(new TWWStructEnviaSMSAgeQuebra(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

// sample call for TWWServiceEnvia::EnviaSMSDataSet()
if($tWWServiceEnvia->EnviaSMSDataSet(new TWWStructEnviaSMSDataSet(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

// sample call for TWWServiceEnvia::EnviaSMSXML()
if($tWWServiceEnvia->EnviaSMSXML(new TWWStructEnviaSMSXML(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

// sample call for TWWServiceEnvia::EnviaSMSTIM()
if($tWWServiceEnvia->EnviaSMSTIM(new TWWStructEnviaSMSTIM(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

// sample call for TWWServiceEnvia::EnviaSMSOTA8Bit()
if($tWWServiceEnvia->EnviaSMSOTA8Bit(new TWWStructEnviaSMSOTA8Bit(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

// sample call for TWWServiceEnvia::EnviaSMSEnhanced()
if($tWWServiceEnvia->EnviaSMSEnhanced(new TWWStructEnviaSMSEnhanced(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

// sample call for TWWServiceEnvia::EnviaSMSTextoEnhanced()
if($tWWServiceEnvia->EnviaSMSTextoEnhanced(new TWWStructEnviaSMSTextoEnhanced(/*** update parameters list ***/)))
	print_r($tWWServiceEnvia->getResult());
else
	print_r($tWWServiceEnvia->getLastError());

/******************************
 * Example for TWWServiceStatus
 */
$tWWServiceStatus = new TWWServiceStatus($wsdl);

// sample call for TWWServiceStatus::StatusSMS()
if($tWWServiceStatus->StatusSMS(new TWWStructStatusSMS(/*** update parameters list ***/)))
	print_r($tWWServiceStatus->getResult());
else
	print_r($tWWServiceStatus->getLastError());

// sample call for TWWServiceStatus::StatusSMS2SN()
if($tWWServiceStatus->StatusSMS2SN(new TWWStructStatusSMS2SN(/*** update parameters list ***/)))
	print_r($tWWServiceStatus->getResult());
else
	print_r($tWWServiceStatus->getLastError());

// sample call for TWWServiceStatus::StatusSMSDataSet()
if($tWWServiceStatus->StatusSMSDataSet(new TWWStructStatusSMSDataSet(/*** update parameters list ***/)))
	print_r($tWWServiceStatus->getResult());
else
	print_r($tWWServiceStatus->getLastError());

// sample call for TWWServiceStatus::StatusSMSNaoLido()
if($tWWServiceStatus->StatusSMSNaoLido(new TWWStructStatusSMSNaoLido(/*** update parameters list ***/)))
	print_r($tWWServiceStatus->getResult());
else
	print_r($tWWServiceStatus->getLastError());

/*****************************
 * Example for TWWServiceBusca
 */
$tWWServiceBusca = new TWWServiceBusca($wsdl);

// sample call for TWWServiceBusca::BuscaSMS()
if($tWWServiceBusca->BuscaSMS(new TWWStructBuscaSMS(/*** update parameters list ***/)))
	print_r($tWWServiceBusca->getResult());
else
	print_r($tWWServiceBusca->getLastError());

// sample call for TWWServiceBusca::BuscaSMSMO()
if($tWWServiceBusca->BuscaSMSMO(new TWWStructBuscaSMSMO(/*** update parameters list ***/)))
	print_r($tWWServiceBusca->getResult());
else
	print_r($tWWServiceBusca->getLastError());

// sample call for TWWServiceBusca::BuscaSMSAgenda()
if($tWWServiceBusca->BuscaSMSAgenda(new TWWStructBuscaSMSAgenda(/*** update parameters list ***/)))
	print_r($tWWServiceBusca->getResult());
else
	print_r($tWWServiceBusca->getLastError());

// sample call for TWWServiceBusca::BuscaSMSAgendaDataSet()
if($tWWServiceBusca->BuscaSMSAgendaDataSet(new TWWStructBuscaSMSAgendaDataSet(/*** update parameters list ***/)))
	print_r($tWWServiceBusca->getResult());
else
	print_r($tWWServiceBusca->getLastError());

// sample call for TWWServiceBusca::BuscaSMSMONaoLido()
if($tWWServiceBusca->BuscaSMSMONaoLido(new TWWStructBuscaSMSMONaoLido(/*** update parameters list ***/)))
	print_r($tWWServiceBusca->getResult());
else
	print_r($tWWServiceBusca->getLastError());

/***************************
 * Example for TWWServiceDel
 */
$tWWServiceDel = new TWWServiceDel($wsdl);

// sample call for TWWServiceDel::DelSMSAgenda()
if($tWWServiceDel->DelSMSAgenda(new TWWStructDelSMSAgenda(/*** update parameters list ***/)))
	print_r($tWWServiceDel->getResult());
else
	print_r($tWWServiceDel->getLastError());

/******************************
 * Example for TWWServiceAltera
 */
$tWWServiceAltera = new TWWServiceAltera($wsdl);

// sample call for TWWServiceAltera::AlteraSenha()
if($tWWServiceAltera->AlteraSenha(new TWWStructAlteraSenha(/*** update parameters list ***/)))
	print_r($tWWServiceAltera->getResult());
else
	print_r($tWWServiceAltera->getLastError());

/***************************
 * Example for TWWServiceVer
 */
$tWWServiceVer = new TWWServiceVer($wsdl);

// sample call for TWWServiceVer::VerCredito()
if($tWWServiceVer->VerCredito(new TWWStructVerCredito(/*** update parameters list ***/)))
	print_r($tWWServiceVer->getResult());
else
	print_r($tWWServiceVer->getLastError());

// sample call for TWWServiceVer::VerValidade()
if($tWWServiceVer->VerValidade(new TWWStructVerValidade(/*** update parameters list ***/)))
	print_r($tWWServiceVer->getResult());
else
	print_r($tWWServiceVer->getLastError());

/******************************
 * Example for TWWServiceReseta
 */
$tWWServiceReseta = new TWWServiceReseta($wsdl);

// sample call for TWWServiceReseta::ResetaStatusLido()
if($tWWServiceReseta->ResetaStatusLido(new TWWStructResetaStatusLido(/*** update parameters list ***/)))
	print_r($tWWServiceReseta->getResult());
else
	print_r($tWWServiceReseta->getLastError());

// sample call for TWWServiceReseta::ResetaMOLido()
if($tWWServiceReseta->ResetaMOLido(new TWWStructResetaMOLido(/*** update parameters list ***/)))
	print_r($tWWServiceReseta->getResult());
else
	print_r($tWWServiceReseta->getLastError());
?>