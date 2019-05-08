<?php
/**
 * File for class TWWStructDelSMSAgenda
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructDelSMSAgenda originally named DelSMSAgenda
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructDelSMSAgenda extends TWWWsdlClass
{
	/**
	 * The Agendamento
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 1
	 * @var dateTime
	 */
	public $Agendamento;
	/**
	 * The NumUsu
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $NumUsu;
	/**
	 * The Senha
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $Senha;
	/**
	 * The SeuNum
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $SeuNum;
	/**
	 * Constructor method for DelSMSAgenda
	 * @see parent::__construct()
	 * @param dateTime $_agendamento
	 * @param string $_numUsu
	 * @param string $_senha
	 * @param string $_seuNum
	 * @return TWWStructDelSMSAgenda
	 */
	public function __construct($_agendamento,$_numUsu = NULL,$_senha = NULL,$_seuNum = NULL)
	{
		parent::__construct(array('Agendamento'=>$_agendamento,'NumUsu'=>$_numUsu,'Senha'=>$_senha,'SeuNum'=>$_seuNum));
	}
	/**
	 * Get Agendamento value
	 * @return dateTime
	 */
	public function getAgendamento()
	{
		return $this->Agendamento;
	}
	/**
	 * Set Agendamento value
	 * @param dateTime $_agendamento the Agendamento
	 * @return dateTime
	 */
	public function setAgendamento($_agendamento)
	{
		return ($this->Agendamento = $_agendamento);
	}
	/**
	 * Get NumUsu value
	 * @return string|null
	 */
	public function getNumUsu()
	{
		return $this->NumUsu;
	}
	/**
	 * Set NumUsu value
	 * @param string $_numUsu the NumUsu
	 * @return string
	 */
	public function setNumUsu($_numUsu)
	{
		return ($this->NumUsu = $_numUsu);
	}
	/**
	 * Get Senha value
	 * @return string|null
	 */
	public function getSenha()
	{
		return $this->Senha;
	}
	/**
	 * Set Senha value
	 * @param string $_senha the Senha
	 * @return string
	 */
	public function setSenha($_senha)
	{
		return ($this->Senha = $_senha);
	}
	/**
	 * Get SeuNum value
	 * @return string|null
	 */
	public function getSeuNum()
	{
		return $this->SeuNum;
	}
	/**
	 * Set SeuNum value
	 * @param string $_seuNum the SeuNum
	 * @return string
	 */
	public function setSeuNum($_seuNum)
	{
		return ($this->SeuNum = $_seuNum);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructDelSMSAgenda
	 */
	public static function __set_state(array $_array,$_className = __CLASS__)
	{
		return parent::__set_state($_array,$_className);
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