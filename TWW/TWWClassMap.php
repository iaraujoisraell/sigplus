<?php
/**
 * File for the class which returns the class map definition
 * @package TWW
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * Class which returns the class map definition by the static method TWWClassMap::classMap()
 * @package TWW
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWClassMap
{
	/**
	 * This method returns the array containing the mapping between WSDL structs and generated classes
	 * This array is sent to the SoapClient when calling the WS
	 * @return array
	 */
	final public static function classMap()
	{
		return array (
  'AlteraSenha' => 'TWWStructAlteraSenha',
  'AlteraSenhaResponse' => 'TWWStructAlteraSenhaResponse',
  'BuscaSMS' => 'TWWStructBuscaSMS',
  'BuscaSMSAgenda' => 'TWWStructBuscaSMSAgenda',
  'BuscaSMSAgendaDataSet' => 'TWWStructBuscaSMSAgendaDataSet',
  'BuscaSMSAgendaDataSetResponse' => 'TWWStructBuscaSMSAgendaDataSetResponse',
  'BuscaSMSAgendaDataSetResult' => 'TWWStructBuscaSMSAgendaDataSetResult',
  'BuscaSMSAgendaResponse' => 'TWWStructBuscaSMSAgendaResponse',
  'BuscaSMSAgendaResult' => 'TWWStructBuscaSMSAgendaResult',
  'BuscaSMSMO' => 'TWWStructBuscaSMSMO',
  'BuscaSMSMONaoLido' => 'TWWStructBuscaSMSMONaoLido',
  'BuscaSMSMONaoLidoResponse' => 'TWWStructBuscaSMSMONaoLidoResponse',
  'BuscaSMSMONaoLidoResult' => 'TWWStructBuscaSMSMONaoLidoResult',
  'BuscaSMSMOResponse' => 'TWWStructBuscaSMSMOResponse',
  'BuscaSMSMOResult' => 'TWWStructBuscaSMSMOResult',
  'BuscaSMSResponse' => 'TWWStructBuscaSMSResponse',
  'BuscaSMSResult' => 'TWWStructBuscaSMSResult',
  'DS' => 'TWWStructDS',
  'DataSet' => 'TWWStructDataSet',
  'DelSMSAgenda' => 'TWWStructDelSMSAgenda',
  'DelSMSAgendaResponse' => 'TWWStructDelSMSAgendaResponse',
  'EnviaSMS' => 'TWWStructEnviaSMS',
  'EnviaSMS2SN' => 'TWWStructEnviaSMS2SN',
  'EnviaSMS2SNResponse' => 'TWWStructEnviaSMS2SNResponse',
  'EnviaSMSAge' => 'TWWStructEnviaSMSAge',
  'EnviaSMSAgeQuebra' => 'TWWStructEnviaSMSAgeQuebra',
  'EnviaSMSAgeQuebraResponse' => 'TWWStructEnviaSMSAgeQuebraResponse',
  'EnviaSMSAgeResponse' => 'TWWStructEnviaSMSAgeResponse',
  'EnviaSMSAlt' => 'TWWStructEnviaSMSAlt',
  'EnviaSMSAltResponse' => 'TWWStructEnviaSMSAltResponse',
  'EnviaSMSDataSet' => 'TWWStructEnviaSMSDataSet',
  'EnviaSMSDataSetResponse' => 'TWWStructEnviaSMSDataSetResponse',
  'EnviaSMSEnhanced' => 'TWWStructEnviaSMSEnhanced',
  'EnviaSMSEnhancedResponse' => 'TWWStructEnviaSMSEnhancedResponse',
  'EnviaSMSOTA8Bit' => 'TWWStructEnviaSMSOTA8Bit',
  'EnviaSMSOTA8BitResponse' => 'TWWStructEnviaSMSOTA8BitResponse',
  'EnviaSMSQuebra' => 'TWWStructEnviaSMSQuebra',
  'EnviaSMSQuebraResponse' => 'TWWStructEnviaSMSQuebraResponse',
  'EnviaSMSResponse' => 'TWWStructEnviaSMSResponse',
  'EnviaSMSTIM' => 'TWWStructEnviaSMSTIM',
  'EnviaSMSTIMResponse' => 'TWWStructEnviaSMSTIMResponse',
  'EnviaSMSTIMResult' => 'TWWStructEnviaSMSTIMResult',
  'EnviaSMSTextoEnhanced' => 'TWWStructEnviaSMSTextoEnhanced',
  'EnviaSMSTextoEnhancedResponse' => 'TWWStructEnviaSMSTextoEnhancedResponse',
  'EnviaSMSXML' => 'TWWStructEnviaSMSXML',
  'EnviaSMSXMLResponse' => 'TWWStructEnviaSMSXMLResponse',
  'ResetaMOLido' => 'TWWStructResetaMOLido',
  'ResetaMOLidoResponse' => 'TWWStructResetaMOLidoResponse',
  'ResetaStatusLido' => 'TWWStructResetaStatusLido',
  'ResetaStatusLidoResponse' => 'TWWStructResetaStatusLidoResponse',
  'StatusSMS' => 'TWWStructStatusSMS',
  'StatusSMS2SN' => 'TWWStructStatusSMS2SN',
  'StatusSMS2SNResponse' => 'TWWStructStatusSMS2SNResponse',
  'StatusSMS2SNResult' => 'TWWStructStatusSMS2SNResult',
  'StatusSMSDataSet' => 'TWWStructStatusSMSDataSet',
  'StatusSMSDataSetResponse' => 'TWWStructStatusSMSDataSetResponse',
  'StatusSMSDataSetResult' => 'TWWStructStatusSMSDataSetResult',
  'StatusSMSNaoLido' => 'TWWStructStatusSMSNaoLido',
  'StatusSMSNaoLidoResponse' => 'TWWStructStatusSMSNaoLidoResponse',
  'StatusSMSNaoLidoResult' => 'TWWStructStatusSMSNaoLidoResult',
  'StatusSMSResponse' => 'TWWStructStatusSMSResponse',
  'StatusSMSResult' => 'TWWStructStatusSMSResult',
  'VerCredito' => 'TWWStructVerCredito',
  'VerCreditoResponse' => 'TWWStructVerCreditoResponse',
  'VerValidade' => 'TWWStructVerValidade',
  'VerValidadeResponse' => 'TWWStructVerValidadeResponse',
);
	}
}
?>