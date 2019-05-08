<?php
/**
 * File for class TWWServiceBusca
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWServiceBusca originally named Busca
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWServiceBusca extends TWWWsdlClass
{
	/**
	 * Method to call the operation originally named BuscaSMS
	 * Documentation : Retorna um DataSet chamado OutDataSet contendo uma Tabela chamada BuscaSMS com as mensagens transmitidas dentro de um período MÁXIMO DE 4 DIAS, e um MÁXIMO DE 4000 SMSs. Retorna Nothing em caso de erro.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructBuscaSMS::getDataIni()
	 * @uses TWWStructBuscaSMS::getDataFim()
	 * @uses TWWStructBuscaSMS::getNumUsu()
	 * @uses TWWStructBuscaSMS::getSenha()
	 * @param TWWStructBuscaSMS $_tWWStructBuscaSMS
	 * @return TWWStructBuscaSMSResponse
	 */
	public function BuscaSMS(TWWStructBuscaSMS $_tWWStructBuscaSMS)
	{
		try
		{
			$this->setResult(new TWWStructBuscaSMSResponse(self::getSoapClient()->BuscaSMS(array('DataIni'=>$_tWWStructBuscaSMS->getDataIni(),'DataFim'=>$_tWWStructBuscaSMS->getDataFim(),'NumUsu'=>$_tWWStructBuscaSMS->getNumUsu(),'Senha'=>$_tWWStructBuscaSMS->getSenha()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named BuscaSMSMO
	 * Documentation : Retorna um DataSet chamado OutDataSet contendo uma Tabela chamada BuscaSMSMO com todas as mensagens SMS MO recebidas DENTRO DE UM PERIODO como resposta a SMS enviados anteriormente. Retorna Nothing em caso de erro.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructBuscaSMSMO::getDataIni()
	 * @uses TWWStructBuscaSMSMO::getDataFim()
	 * @uses TWWStructBuscaSMSMO::getNumUsu()
	 * @uses TWWStructBuscaSMSMO::getSenha()
	 * @param TWWStructBuscaSMSMO $_tWWStructBuscaSMSMO
	 * @return TWWStructBuscaSMSMOResponse
	 */
	public function BuscaSMSMO(TWWStructBuscaSMSMO $_tWWStructBuscaSMSMO)
	{
		try
		{
			$this->setResult(new TWWStructBuscaSMSMOResponse(self::getSoapClient()->BuscaSMSMO(array('DataIni'=>$_tWWStructBuscaSMSMO->getDataIni(),'DataFim'=>$_tWWStructBuscaSMSMO->getDataFim(),'NumUsu'=>$_tWWStructBuscaSMSMO->getNumUsu(),'Senha'=>$_tWWStructBuscaSMSMO->getSenha()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named BuscaSMSAgenda
	 * Documentation : Retorna um DataSet chamado OutDataSet contendo uma Tabela chamada BuscaSMSAgenda com UM SMS AGENDADO ESPECIFICADO PELO CAMPO SEUNUM. Retorna Nothing em caso de erro.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructBuscaSMSAgenda::getNumUsu()
	 * @uses TWWStructBuscaSMSAgenda::getSenha()
	 * @uses TWWStructBuscaSMSAgenda::getSeuNum()
	 * @param TWWStructBuscaSMSAgenda $_tWWStructBuscaSMSAgenda
	 * @return TWWStructBuscaSMSAgendaResponse
	 */
	public function BuscaSMSAgenda(TWWStructBuscaSMSAgenda $_tWWStructBuscaSMSAgenda)
	{
		try
		{
			$this->setResult(new TWWStructBuscaSMSAgendaResponse(self::getSoapClient()->BuscaSMSAgenda(array('NumUsu'=>$_tWWStructBuscaSMSAgenda->getNumUsu(),'Senha'=>$_tWWStructBuscaSMSAgenda->getSenha(),'SeuNum'=>$_tWWStructBuscaSMSAgenda->getSeuNum()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named BuscaSMSAgendaDataSet
	 * Documentation : Recebe um DataSet com os campos: SeuNum, e retorna um DataSet chamado OutDataSet contendo a tabela BuscaSMSAgendaDS com mensagens AGENDADAS. Retorna Nothing em caso de erro.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructBuscaSMSAgendaDataSet::getNumUsu()
	 * @uses TWWStructBuscaSMSAgendaDataSet::getSenha()
	 * @uses TWWStructBuscaSMSAgendaDataSet::getDS()
	 * @param TWWStructBuscaSMSAgendaDataSet $_tWWStructBuscaSMSAgendaDataSet
	 * @return TWWStructBuscaSMSAgendaDataSetResponse
	 */
	public function BuscaSMSAgendaDataSet(TWWStructBuscaSMSAgendaDataSet $_tWWStructBuscaSMSAgendaDataSet)
	{
		try
		{
			$this->setResult(new TWWStructBuscaSMSAgendaDataSetResponse(self::getSoapClient()->BuscaSMSAgendaDataSet(array('NumUsu'=>$_tWWStructBuscaSMSAgendaDataSet->getNumUsu(),'Senha'=>$_tWWStructBuscaSMSAgendaDataSet->getSenha(),'DS'=>$_tWWStructBuscaSMSAgendaDataSet->getDS()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named BuscaSMSMONaoLido
	 * Documentation : Retorna um DataSet chamado OutDataSet contendo uma Tabela chamada SMSMO com no máximo 400 linhas, com as mensagens SMS MO não lidas, recebidas nos últimos 4 dias como resposta a SMS enviados anteriormente, e marca esses MOs COMO LIDOS. Se houverem 400 linhas na tabela, podem haver mais MOs não lidos, e estes devem ser lidos usando chamadas subsequentes à função. Retorna Nothing em caso de erro.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructBuscaSMSMONaoLido::getNumUsu()
	 * @uses TWWStructBuscaSMSMONaoLido::getSenha()
	 * @param TWWStructBuscaSMSMONaoLido $_tWWStructBuscaSMSMONaoLido
	 * @return TWWStructBuscaSMSMONaoLidoResponse
	 */
	public function BuscaSMSMONaoLido(TWWStructBuscaSMSMONaoLido $_tWWStructBuscaSMSMONaoLido)
	{
		try
		{
			$this->setResult(new TWWStructBuscaSMSMONaoLidoResponse(self::getSoapClient()->BuscaSMSMONaoLido(array('NumUsu'=>$_tWWStructBuscaSMSMONaoLido->getNumUsu(),'Senha'=>$_tWWStructBuscaSMSMONaoLido->getSenha()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Returns the result
	 * @see TWWWsdlClass::getResult()
	 * @return TWWStructBuscaSMSAgendaDataSetResponse|TWWStructBuscaSMSAgendaResponse|TWWStructBuscaSMSMONaoLidoResponse|TWWStructBuscaSMSMOResponse|TWWStructBuscaSMSResponse
	 */
	public function getResult()
	{
		return parent::getResult();
	}
	/**
	 * Method returning the class name
	 * @return string __CLASS__
	 */
	public function __toString()
	{
		return __CLASS__;
	}
}
?>