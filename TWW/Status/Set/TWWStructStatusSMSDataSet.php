<?php
/**
 * File for class TWWStructStatusSMSDataSet
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructStatusSMSDataSet originally named StatusSMSDataSet
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructStatusSMSDataSet extends TWWWsdlClass
{
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
	 * The DS
	 * @var TWWStructDS
	 */
	public $DS;
	/**
	 * Constructor method for StatusSMSDataSet
	 * @see parent::__construct()
	 * @param string $_numUsu
	 * @param string $_senha
	 * @param TWWStructDS $_dS
	 * @return TWWStructStatusSMSDataSet
	 */
	public function __construct($_numUsu = NULL,$_senha = NULL,$_dS = NULL)
	{
		parent::__construct(array('NumUsu'=>$_numUsu,'Senha'=>$_senha,'DS'=>$_dS));
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
	 * Get DS value
	 * @return TWWStructDS|null
	 */
	public function getDS()
	{
		return $this->DS;
	}
	/**
	 * Set DS value
	 * @param TWWStructDS $_dS the DS
	 * @return TWWStructDS
	 */
	public function setDS($_dS)
	{
		return ($this->DS = $_dS);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructStatusSMSDataSet
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