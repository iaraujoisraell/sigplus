<?php
/**
 * File for class TWWServiceEnvia
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWServiceEnvia originally named Envia
 * @package TWW
 * @subpackage Services
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWServiceEnvia extends TWWWsdlClass
{
	/**
	 * Method to call the operation originally named EnviaSMS
	 * Documentation : Envia uma mensagem para um celular. Retorna OK, NOK, Erro ou NA (não disponível).
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMS::getNumUsu()
	 * @uses TWWStructEnviaSMS::getSenha()
	 * @uses TWWStructEnviaSMS::getSeuNum()
	 * @uses TWWStructEnviaSMS::getCelular()
	 * @uses TWWStructEnviaSMS::getMensagem()
	 * @param TWWStructEnviaSMS $_tWWStructEnviaSMS
	 * @return TWWStructEnviaSMSResponse
	 */
	public function EnviaSMS(TWWStructEnviaSMS $_tWWStructEnviaSMS)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMSResponse(self::getSoapClient()->EnviaSMS(array('NumUsu'=>$_tWWStructEnviaSMS->getNumUsu(),'Senha'=>$_tWWStructEnviaSMS->getSenha(),'SeuNum'=>$_tWWStructEnviaSMS->getSeuNum(),'Celular'=>$_tWWStructEnviaSMS->getCelular(),'Mensagem'=>$_tWWStructEnviaSMS->getMensagem()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named EnviaSMS2SN
	 * Documentation : Envia uma mensagem para um celular, usando 2 campos de referência NUMÉRICOS (SeuNum1 e SeuNum2) de no máximo 24 dígitos cada. Retorna OK, NOK, Erro ou NA (não disponível).
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMS2SN::getNumUsu()
	 * @uses TWWStructEnviaSMS2SN::getSenha()
	 * @uses TWWStructEnviaSMS2SN::getSeuNum1()
	 * @uses TWWStructEnviaSMS2SN::getSeuNum2()
	 * @uses TWWStructEnviaSMS2SN::getCelular()
	 * @uses TWWStructEnviaSMS2SN::getMensagem()
	 * @param TWWStructEnviaSMS2SN $_tWWStructEnviaSMS2SN
	 * @return TWWStructEnviaSMS2SNResponse
	 */
	public function EnviaSMS2SN(TWWStructEnviaSMS2SN $_tWWStructEnviaSMS2SN)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMS2SNResponse(self::getSoapClient()->EnviaSMS2SN(array('NumUsu'=>$_tWWStructEnviaSMS2SN->getNumUsu(),'Senha'=>$_tWWStructEnviaSMS2SN->getSenha(),'SeuNum1'=>$_tWWStructEnviaSMS2SN->getSeuNum1(),'SeuNum2'=>$_tWWStructEnviaSMS2SN->getSeuNum2(),'Celular'=>$_tWWStructEnviaSMS2SN->getCelular(),'Mensagem'=>$_tWWStructEnviaSMS2SN->getMensagem()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named EnviaSMSQuebra
	 * Documentation : Envia uma mensagem de texto para um celular. Se essa mensagem for mais longa que 140 caracteres, ela será dividida em várias mensagens de até 140 caracteres, com ... separando as mensagens. Tamanho máximo da mensagem = 4096 caracteres. Retorna OK n (n é o número de SMS enviados pela operação), NOK (usuário ou senha inválidos, ou mensagem maior que 4096 caracteres), Erro ou NA (não disponível).
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMSQuebra::getNumUsu()
	 * @uses TWWStructEnviaSMSQuebra::getSenha()
	 * @uses TWWStructEnviaSMSQuebra::getSeuNum()
	 * @uses TWWStructEnviaSMSQuebra::getCelular()
	 * @uses TWWStructEnviaSMSQuebra::getMensagem()
	 * @param TWWStructEnviaSMSQuebra $_tWWStructEnviaSMSQuebra
	 * @return TWWStructEnviaSMSQuebraResponse
	 */
	public function EnviaSMSQuebra(TWWStructEnviaSMSQuebra $_tWWStructEnviaSMSQuebra)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMSQuebraResponse(self::getSoapClient()->EnviaSMSQuebra(array('NumUsu'=>$_tWWStructEnviaSMSQuebra->getNumUsu(),'Senha'=>$_tWWStructEnviaSMSQuebra->getSenha(),'SeuNum'=>$_tWWStructEnviaSMSQuebra->getSeuNum(),'Celular'=>$_tWWStructEnviaSMSQuebra->getCelular(),'Mensagem'=>$_tWWStructEnviaSMSQuebra->getMensagem()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named EnviaSMSAlt
	 * Documentation : Envia uma mensagem para um celular utilizando url alternativa. Retorna OK, NOK, Erro ou NA (não disponível).
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMSAlt::getUser()
	 * @uses TWWStructEnviaSMSAlt::getPwd()
	 * @uses TWWStructEnviaSMSAlt::getMsgid()
	 * @uses TWWStructEnviaSMSAlt::getPhone()
	 * @uses TWWStructEnviaSMSAlt::getMsgtext()
	 * @param TWWStructEnviaSMSAlt $_tWWStructEnviaSMSAlt
	 * @return TWWStructEnviaSMSAltResponse
	 */
	public function EnviaSMSAlt(TWWStructEnviaSMSAlt $_tWWStructEnviaSMSAlt)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMSAltResponse(self::getSoapClient()->EnviaSMSAlt(array('user'=>$_tWWStructEnviaSMSAlt->getUser(),'pwd'=>$_tWWStructEnviaSMSAlt->getPwd(),'msgid'=>$_tWWStructEnviaSMSAlt->getMsgid(),'phone'=>$_tWWStructEnviaSMSAlt->getPhone(),'msgtext'=>$_tWWStructEnviaSMSAlt->getMsgtext()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named EnviaSMSAge
	 * Documentation : Envia uma mensagem para um celular com agendamento. Retorna OK, NOK, Erro ou NA (não disponível).
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMSAge::getAgendamento()
	 * @uses TWWStructEnviaSMSAge::getNumUsu()
	 * @uses TWWStructEnviaSMSAge::getSenha()
	 * @uses TWWStructEnviaSMSAge::getSeuNum()
	 * @uses TWWStructEnviaSMSAge::getCelular()
	 * @uses TWWStructEnviaSMSAge::getMensagem()
	 * @param TWWStructEnviaSMSAge $_tWWStructEnviaSMSAge
	 * @return TWWStructEnviaSMSAgeResponse
	 */
	public function EnviaSMSAge(TWWStructEnviaSMSAge $_tWWStructEnviaSMSAge)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMSAgeResponse(self::getSoapClient()->EnviaSMSAge(array('Agendamento'=>$_tWWStructEnviaSMSAge->getAgendamento(),'NumUsu'=>$_tWWStructEnviaSMSAge->getNumUsu(),'Senha'=>$_tWWStructEnviaSMSAge->getSenha(),'SeuNum'=>$_tWWStructEnviaSMSAge->getSeuNum(),'Celular'=>$_tWWStructEnviaSMSAge->getCelular(),'Mensagem'=>$_tWWStructEnviaSMSAge->getMensagem()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named EnviaSMSAgeQuebra
	 * Documentation : Envia uma mensagem para um celular com agendamento. Se essa mensagem for mais longa que 140 caracteres, ela será dividida em várias mensagens de até 140 caracteres, com ... separando as mensagens. Retorna OK n (n é o número de SMS enviados pela operação), NOK, Erro ou NA (não disponível).
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMSAgeQuebra::getAgendamento()
	 * @uses TWWStructEnviaSMSAgeQuebra::getNumUsu()
	 * @uses TWWStructEnviaSMSAgeQuebra::getSenha()
	 * @uses TWWStructEnviaSMSAgeQuebra::getSeuNum()
	 * @uses TWWStructEnviaSMSAgeQuebra::getCelular()
	 * @uses TWWStructEnviaSMSAgeQuebra::getMensagem()
	 * @param TWWStructEnviaSMSAgeQuebra $_tWWStructEnviaSMSAgeQuebra
	 * @return TWWStructEnviaSMSAgeQuebraResponse
	 */
	public function EnviaSMSAgeQuebra(TWWStructEnviaSMSAgeQuebra $_tWWStructEnviaSMSAgeQuebra)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMSAgeQuebraResponse(self::getSoapClient()->EnviaSMSAgeQuebra(array('Agendamento'=>$_tWWStructEnviaSMSAgeQuebra->getAgendamento(),'NumUsu'=>$_tWWStructEnviaSMSAgeQuebra->getNumUsu(),'Senha'=>$_tWWStructEnviaSMSAgeQuebra->getSenha(),'SeuNum'=>$_tWWStructEnviaSMSAgeQuebra->getSeuNum(),'Celular'=>$_tWWStructEnviaSMSAgeQuebra->getCelular(),'Mensagem'=>$_tWWStructEnviaSMSAgeQuebra->getMensagem()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named EnviaSMSDataSet
	 * Documentation : Recebe um DataSet com mensagens SMS a serem enviadas, com os seguintes campos: seunum (Seu número com até 20 caracteres), celular (55DDNNNNNNNN), mensagem, agendamento (dd/mm/aaaa hh:mm:ss). Retorna OK n (n é o número de SMSs recebidos), NOK, Erro ou NA (não disponível)
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMSDataSet::getNumUsu()
	 * @uses TWWStructEnviaSMSDataSet::getSenha()
	 * @uses TWWStructEnviaSMSDataSet::getDS()
	 * @param TWWStructEnviaSMSDataSet $_tWWStructEnviaSMSDataSet
	 * @return TWWStructEnviaSMSDataSetResponse
	 */
	public function EnviaSMSDataSet(TWWStructEnviaSMSDataSet $_tWWStructEnviaSMSDataSet)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMSDataSetResponse(self::getSoapClient()->EnviaSMSDataSet(array('NumUsu'=>$_tWWStructEnviaSMSDataSet->getNumUsu(),'Senha'=>$_tWWStructEnviaSMSDataSet->getSenha(),'DS'=>$_tWWStructEnviaSMSDataSet->getDS()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named EnviaSMSXML
	 * Documentation : Recebe um XML com mensagens SMS a serem enviadas, com os seguintes campos: seunum (Seu número com até 20 caracteres), celular (55DDNNNNNNNN), mensagem, agendamento (dd/mm/aaaa hh:mm:ss). Retorna OK n (n é o número de SMSs recebidos), NOK, Erro ou NA (não disponível)
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMSXML::getNumUsu()
	 * @uses TWWStructEnviaSMSXML::getSenha()
	 * @uses TWWStructEnviaSMSXML::getStrXML()
	 * @param TWWStructEnviaSMSXML $_tWWStructEnviaSMSXML
	 * @return TWWStructEnviaSMSXMLResponse
	 */
	public function EnviaSMSXML(TWWStructEnviaSMSXML $_tWWStructEnviaSMSXML)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMSXMLResponse(self::getSoapClient()->EnviaSMSXML(array('NumUsu'=>$_tWWStructEnviaSMSXML->getNumUsu(),'Senha'=>$_tWWStructEnviaSMSXML->getSenha(),'StrXML'=>$_tWWStructEnviaSMSXML->getStrXML()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named EnviaSMSTIM
	 * Documentation : Recebe uma String com um XML no mesmo formato usado para enviar SMS a operadora TIMSUL, para facilitar a integração com sistemas já desenvolvidos.
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMSTIM::getXMLString()
	 * @param TWWStructEnviaSMSTIM $_tWWStructEnviaSMSTIM
	 * @return TWWStructEnviaSMSTIMResponse
	 */
	public function EnviaSMSTIM(TWWStructEnviaSMSTIM $_tWWStructEnviaSMSTIM)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMSTIMResponse(self::getSoapClient()->EnviaSMSTIM(array('XMLString'=>$_tWWStructEnviaSMSTIM->getXMLString()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named EnviaSMSOTA8Bit
	 * Documentation : Envia uma mensagem binária para um celular. Tanto o campo Header como o Data devem estar no formato OTA 8 bit, com um número par de caracteres hexadecimais. Retorna OK, NOK, Erro ou NA (não disponível).
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMSOTA8Bit::getNumUsu()
	 * @uses TWWStructEnviaSMSOTA8Bit::getSenha()
	 * @uses TWWStructEnviaSMSOTA8Bit::getSeuNum()
	 * @uses TWWStructEnviaSMSOTA8Bit::getCelular()
	 * @uses TWWStructEnviaSMSOTA8Bit::getHeader()
	 * @uses TWWStructEnviaSMSOTA8Bit::getData()
	 * @param TWWStructEnviaSMSOTA8Bit $_tWWStructEnviaSMSOTA8Bit
	 * @return TWWStructEnviaSMSOTA8BitResponse
	 */
	public function EnviaSMSOTA8Bit(TWWStructEnviaSMSOTA8Bit $_tWWStructEnviaSMSOTA8Bit)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMSOTA8BitResponse(self::getSoapClient()->EnviaSMSOTA8Bit(array('NumUsu'=>$_tWWStructEnviaSMSOTA8Bit->getNumUsu(),'Senha'=>$_tWWStructEnviaSMSOTA8Bit->getSenha(),'SeuNum'=>$_tWWStructEnviaSMSOTA8Bit->getSeuNum(),'Celular'=>$_tWWStructEnviaSMSOTA8Bit->getCelular(),'Header'=>$_tWWStructEnviaSMSOTA8Bit->getHeader(),'Data'=>$_tWWStructEnviaSMSOTA8Bit->getData()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named EnviaSMSEnhanced
	 * Documentation : Envia uma mensagem para um celular contendo um gráfico seguido de um texto. O formato do gráfico é BITMAP monocromático, tamanho máximo 255x255, normalmente é usado 72x28 ou 72x14. O BITMAP deve ser transmitido com cada byte representado por 2 caracteres hexadecimais. Retorna OK n (n é o número de SMS enviados), NOK, Erro ou NA (não disponível).
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMSEnhanced::getNumUsu()
	 * @uses TWWStructEnviaSMSEnhanced::getSenha()
	 * @uses TWWStructEnviaSMSEnhanced::getSeuNum()
	 * @uses TWWStructEnviaSMSEnhanced::getCelular()
	 * @uses TWWStructEnviaSMSEnhanced::getBmp()
	 * @uses TWWStructEnviaSMSEnhanced::getTexto()
	 * @param TWWStructEnviaSMSEnhanced $_tWWStructEnviaSMSEnhanced
	 * @return TWWStructEnviaSMSEnhancedResponse
	 */
	public function EnviaSMSEnhanced(TWWStructEnviaSMSEnhanced $_tWWStructEnviaSMSEnhanced)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMSEnhancedResponse(self::getSoapClient()->EnviaSMSEnhanced(array('NumUsu'=>$_tWWStructEnviaSMSEnhanced->getNumUsu(),'Senha'=>$_tWWStructEnviaSMSEnhanced->getSenha(),'SeuNum'=>$_tWWStructEnviaSMSEnhanced->getSeuNum(),'Celular'=>$_tWWStructEnviaSMSEnhanced->getCelular(),'bmp'=>$_tWWStructEnviaSMSEnhanced->getBmp(),'texto'=>$_tWWStructEnviaSMSEnhanced->getTexto()))));
		}
		catch(SoapFault $soapFault)
		{
			return !$this->saveLastError(__METHOD__,$soapFault);
		}
		return $this->getResult();
	}
	/**
	 * Method to call the operation originally named EnviaSMSTextoEnhanced
	 * Documentation : Envia uma mensagem de texto para um celular. Essa mensagem pode conter caracteres acentuados e pode ser mais longa que 160 caracteres. A mensagem utilizará 1 SMS transmitido para cada 128 caracteres. Retorna OK n (n é o número de SMS enviados), NOK, Erro ou NA (não disponível).
	 * @uses TWWWsdlClass::getSoapClient()
	 * @uses TWWWsdlClass::setResult()
	 * @uses TWWWsdlClass::getResult()
	 * @uses TWWWsdlClass::saveLastError()
	 * @uses TWWStructEnviaSMSTextoEnhanced::getNumUsu()
	 * @uses TWWStructEnviaSMSTextoEnhanced::getSenha()
	 * @uses TWWStructEnviaSMSTextoEnhanced::getSeuNum()
	 * @uses TWWStructEnviaSMSTextoEnhanced::getCelular()
	 * @uses TWWStructEnviaSMSTextoEnhanced::getTexto()
	 * @param TWWStructEnviaSMSTextoEnhanced $_tWWStructEnviaSMSTextoEnhanced
	 * @return TWWStructEnviaSMSTextoEnhancedResponse
	 */
	public function EnviaSMSTextoEnhanced(TWWStructEnviaSMSTextoEnhanced $_tWWStructEnviaSMSTextoEnhanced)
	{
		try
		{
			$this->setResult(new TWWStructEnviaSMSTextoEnhancedResponse(self::getSoapClient()->EnviaSMSTextoEnhanced(array('NumUsu'=>$_tWWStructEnviaSMSTextoEnhanced->getNumUsu(),'Senha'=>$_tWWStructEnviaSMSTextoEnhanced->getSenha(),'SeuNum'=>$_tWWStructEnviaSMSTextoEnhanced->getSeuNum(),'Celular'=>$_tWWStructEnviaSMSTextoEnhanced->getCelular(),'texto'=>$_tWWStructEnviaSMSTextoEnhanced->getTexto()))));
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
	 * @return TWWStructEnviaSMS2SNResponse|TWWStructEnviaSMSAgeQuebraResponse|TWWStructEnviaSMSAgeResponse|TWWStructEnviaSMSAltResponse|TWWStructEnviaSMSDataSetResponse|TWWStructEnviaSMSEnhancedResponse|TWWStructEnviaSMSOTA8BitResponse|TWWStructEnviaSMSQuebraResponse|TWWStructEnviaSMSResponse|TWWStructEnviaSMSTextoEnhancedResponse|TWWStructEnviaSMSTIMResponse|TWWStructEnviaSMSXMLResponse
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