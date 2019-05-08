<?php
/**
 * File for class TWWServiceDel
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWServiceDel originally named Del
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWServiceDel extends TWWWsdlClass
{
	/**
	 * Method to call the operation originally named DelSMSAgenda
	 * Documentation : Deleta uma mensagem agendada. Retorna OK ou NOK.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructDelSMSAgenda::getAgendamento()
	 * @uses TWWStructDelSMSAgenda::getNumUsu()
	 * @uses TWWStructDelSMSAgenda::getSenha()
	 * @uses TWWStructDelSMSAgenda::getSeuNum()
	 * @param TWWStructDelSMSAgenda $_tWWStructDelSMSAgenda
	 * @return TWWStructDelSMSAgendaResponse
	 */
	public function DelSMSAgenda(TWWStructDelSMSAgenda $_tWWStructDelSMSAgenda)
	{
		try
		{
			$this->setResult(new TWWStructDelSMSAgendaResponse(self::getSoapClient()->DelSMSAgenda(array('Agendamento'=>$_tWWStructDelSMSAgenda->getAgendamento(),'NumUsu'=>$_tWWStructDelSMSAgenda->getNumUsu(),'Senha'=>$_tWWStructDelSMSAgenda->getSenha(),'SeuNum'=>$_tWWStructDelSMSAgenda->getSeuNum()))));
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
	 * @return TWWStructDelSMSAgendaResponse
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