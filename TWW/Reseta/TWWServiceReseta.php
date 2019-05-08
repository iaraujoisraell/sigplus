<?php
/**
 * File for class TWWServiceReseta
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWServiceReseta originally named Reseta
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWServiceReseta extends TWWWsdlClass
{
	/**
	 * Method to call the operation originally named ResetaStatusLido
	 * Documentation : Reseta o status de LIDO dos Status de SMS desde 1 dia atrás até a data atual. Retorna OK ou NOK em caso de erro.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructResetaStatusLido::getNumUsu()
	 * @uses TWWStructResetaStatusLido::getSenha()
	 * @param TWWStructResetaStatusLido $_tWWStructResetaStatusLido
	 * @return TWWStructResetaStatusLidoResponse
	 */
	public function ResetaStatusLido(TWWStructResetaStatusLido $_tWWStructResetaStatusLido)
	{
		try
		{
			$this->setResult(new TWWStructResetaStatusLidoResponse(self::getSoapClient()->ResetaStatusLido(array('NumUsu'=>$_tWWStructResetaStatusLido->getNumUsu(),'Senha'=>$_tWWStructResetaStatusLido->getSenha()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named ResetaMOLido
	 * Documentation : Reseta o status de LIDO dos SMS MO desde 1 dia atrás até o momento atual. Retorna OK ou NOK em caso de erro.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructResetaMOLido::getNumUsu()
	 * @uses TWWStructResetaMOLido::getSenha()
	 * @param TWWStructResetaMOLido $_tWWStructResetaMOLido
	 * @return TWWStructResetaMOLidoResponse
	 */
	public function ResetaMOLido(TWWStructResetaMOLido $_tWWStructResetaMOLido)
	{
		try
		{
			$this->setResult(new TWWStructResetaMOLidoResponse(self::getSoapClient()->ResetaMOLido(array('NumUsu'=>$_tWWStructResetaMOLido->getNumUsu(),'Senha'=>$_tWWStructResetaMOLido->getSenha()))));
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
	 * @return TWWStructResetaMOLidoResponse|TWWStructResetaStatusLidoResponse
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