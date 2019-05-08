<?php
/**
 * File for class TWWServiceVer
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWServiceVer originally named Ver
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWServiceVer extends TWWWsdlClass
{
	/**
	 * Method to call the operation originally named VerCredito
	 * Documentation : Verifica os créditos de um Usuário Pré-Pago. Retorna o número de créditos ou -1 se o Usuário não for do tipo Pré-Pago ou -2 em caso de erro nos parâmetros.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructVerCredito::getNumUsu()
	 * @uses TWWStructVerCredito::getSenha()
	 * @param TWWStructVerCredito $_tWWStructVerCredito
	 * @return TWWStructVerCreditoResponse
	 */
	public function VerCredito(TWWStructVerCredito $_tWWStructVerCredito)
	{
		try
		{
			$this->setResult(new TWWStructVerCreditoResponse(self::getSoapClient()->VerCredito(array('NumUsu'=>$_tWWStructVerCredito->getNumUsu(),'Senha'=>$_tWWStructVerCredito->getSenha()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named VerValidade
	 * Documentation : Retorna a data de validade dos créditos de um Usuário Pré-Pago. Retorna Nothing se o Usuário não for do tipo Pré-Pago ou caso haja erro nos parâmetros.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructVerValidade::getNumUsu()
	 * @uses TWWStructVerValidade::getSenha()
	 * @param TWWStructVerValidade $_tWWStructVerValidade
	 * @return TWWStructVerValidadeResponse
	 */
	public function VerValidade(TWWStructVerValidade $_tWWStructVerValidade)
	{
		try
		{
			$this->setResult(new TWWStructVerValidadeResponse(self::getSoapClient()->VerValidade(array('NumUsu'=>$_tWWStructVerValidade->getNumUsu(),'Senha'=>$_tWWStructVerValidade->getSenha()))));
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
	 * @return TWWStructVerCreditoResponse|TWWStructVerValidadeResponse
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