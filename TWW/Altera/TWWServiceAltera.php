<?php
/**
 * File for class TWWServiceAltera
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWServiceAltera originally named Altera
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWServiceAltera extends TWWWsdlClass
{
	/**
	 * Method to call the operation originally named AlteraSenha
	 * Documentation : Altera a senha de usuário. A senha pode ter no máximo 18 caracteres. Retorna um boolean indicando o sucesso da operação.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructAlteraSenha::getNumUsu()
	 * @uses TWWStructAlteraSenha::getSenhaAntiga()
	 * @uses TWWStructAlteraSenha::getSenhaNova()
	 * @param TWWStructAlteraSenha $_tWWStructAlteraSenha
	 * @return TWWStructAlteraSenhaResponse
	 */
	public function AlteraSenha(TWWStructAlteraSenha $_tWWStructAlteraSenha)
	{
		try
		{
			$this->setResult(new TWWStructAlteraSenhaResponse(self::getSoapClient()->AlteraSenha(array('NumUsu'=>$_tWWStructAlteraSenha->getNumUsu(),'SenhaAntiga'=>$_tWWStructAlteraSenha->getSenhaAntiga(),'SenhaNova'=>$_tWWStructAlteraSenha->getSenhaNova()))));
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
	 * @return TWWStructAlteraSenhaResponse
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