<?php
/**
 * File for class TWWServiceStatus
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWServiceStatus originally named Status
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWServiceStatus extends TWWWsdlClass
{
	/**
	 * Method to call the operation originally named StatusSMS
	 * Documentation : Retorna um DataSet chamado OutDataSet contendo a tabela StatusSMS com o status de UM SMS já transmitido. Retorna Nothing em caso de erro.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructStatusSMS::getNumUsu()
	 * @uses TWWStructStatusSMS::getSenha()
	 * @uses TWWStructStatusSMS::getSeuNum()
	 * @param TWWStructStatusSMS $_tWWStructStatusSMS
	 * @return TWWStructStatusSMSResponse
	 */
	public function StatusSMS(TWWStructStatusSMS $_tWWStructStatusSMS)
	{
		try
		{
			$this->setResult(new TWWStructStatusSMSResponse(self::getSoapClient()->StatusSMS(array('NumUsu'=>$_tWWStructStatusSMS->getNumUsu(),'Senha'=>$_tWWStructStatusSMS->getSenha(),'SeuNum'=>$_tWWStructStatusSMS->getSeuNum()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named StatusSMS2SN
	 * Documentation : Retorna um DataSet chamado OutDataSet contendo a tabela StatusSMS com o status de UM SMS já transmitido. Retorna Nothing em caso de erro.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructStatusSMS2SN::getNumUsu()
	 * @uses TWWStructStatusSMS2SN::getSenha()
	 * @uses TWWStructStatusSMS2SN::getSeuNum1()
	 * @uses TWWStructStatusSMS2SN::getSeuNum2()
	 * @param TWWStructStatusSMS2SN $_tWWStructStatusSMS2SN
	 * @return TWWStructStatusSMS2SNResponse
	 */
	public function StatusSMS2SN(TWWStructStatusSMS2SN $_tWWStructStatusSMS2SN)
	{
		try
		{
			$this->setResult(new TWWStructStatusSMS2SNResponse(self::getSoapClient()->StatusSMS2SN(array('NumUsu'=>$_tWWStructStatusSMS2SN->getNumUsu(),'Senha'=>$_tWWStructStatusSMS2SN->getSenha(),'SeuNum1'=>$_tWWStructStatusSMS2SN->getSeuNum1(),'SeuNum2'=>$_tWWStructStatusSMS2SN->getSeuNum2()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named StatusSMSDataSet
	 * Documentation : Recebe um DataSet com os campos: SeuNum, e retorna um DataSet chamado OutDataSet contendo a tabela StatusSMSDS com várias mensagens já transmitidas. Retorna Nothing em caso de erro.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructStatusSMSDataSet::getNumUsu()
	 * @uses TWWStructStatusSMSDataSet::getSenha()
	 * @uses TWWStructStatusSMSDataSet::getDS()
	 * @param TWWStructStatusSMSDataSet $_tWWStructStatusSMSDataSet
	 * @return TWWStructStatusSMSDataSetResponse
	 */
	public function StatusSMSDataSet(TWWStructStatusSMSDataSet $_tWWStructStatusSMSDataSet)
	{
		try
		{
			$this->setResult(new TWWStructStatusSMSDataSetResponse(self::getSoapClient()->StatusSMSDataSet(array('NumUsu'=>$_tWWStructStatusSMSDataSet->getNumUsu(),'Senha'=>$_tWWStructStatusSMSDataSet->getSenha(),'DS'=>$_tWWStructStatusSMSDataSet->getDS()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named StatusSMSNaoLido
	 * Documentation : Retorna um DataSet chamado OutDataSet contendo a tabela StatusSMS com no máximo 400 linhas, contendo somente os status de SMS dos últimos 4 dias que ainda não tenham sido lidos, e os MARCA COMO LIDOS. Se houverem 400 linhas na tabela, podem haver mais status não lidos, e estes devem ser lidos usando chamadas subsequentes à função. Retorna Nothing em caso de erro.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructStatusSMSNaoLido::getNumUsu()
	 * @uses TWWStructStatusSMSNaoLido::getSenha()
	 * @param TWWStructStatusSMSNaoLido $_tWWStructStatusSMSNaoLido
	 * @return TWWStructStatusSMSNaoLidoResponse
	 */
	public function StatusSMSNaoLido(TWWStructStatusSMSNaoLido $_tWWStructStatusSMSNaoLido)
	{
		try
		{
			$this->setResult(new TWWStructStatusSMSNaoLidoResponse(self::getSoapClient()->StatusSMSNaoLido(array('NumUsu'=>$_tWWStructStatusSMSNaoLido->getNumUsu(),'Senha'=>$_tWWStructStatusSMSNaoLido->getSenha()))));
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
	 * @return TWWStructStatusSMS2SNResponse|TWWStructStatusSMSDataSetResponse|TWWStructStatusSMSNaoLidoResponse|TWWStructStatusSMSResponse
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