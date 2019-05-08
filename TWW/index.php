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
define('TWW_WSDL_URL','http://webservices2.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL');
define('TWW_USER_LOGIN','UNIMEDAM');
define('TWW_USER_PASSWORD','unimed@123');
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
if($tWWServiceEnvia->EnviaSMS(new TWWStructEnviaSMS('UNIMEDAM','unimed@123','webservices','5592991400231','teste'))){
	echo '<pre>'; print_r($tWWServiceEnvia->getResult());
}else{
	echo '<pre>'; print_r($tWWServiceEnvia->getLastError());
}
?>