<?php
/**
 * File for class TWWStructStatusSMS2SN
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructStatusSMS2SN originally named StatusSMS2SN
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructStatusSMS2SN extends TWWWsdlClass
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
	 * The SeuNum1
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $SeuNum1;
	/**
	 * The SeuNum2
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $SeuNum2;
	/**
	 * Constructor method for StatusSMS2SN
	 * @see parent::__construct()
	 * @param string $_numUsu
	 * @param string $_senha
	 * @param string $_seuNum1
	 * @param string $_seuNum2
	 * @return TWWStructStatusSMS2SN
	 */
	public function __construct($_numUsu = NULL,$_senha = NULL,$_seuNum1 = NULL,$_seuNum2 = NULL)
	{
		parent::__construct(array('NumUsu'=>$_numUsu,'Senha'=>$_senha,'SeuNum1'=>$_seuNum1,'SeuNum2'=>$_seuNum2));
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
	 * Get SeuNum1 value
	 * @return string|null
	 */
	public function getSeuNum1()
	{
		return $this->SeuNum1;
	}
	/**
	 * Set SeuNum1 value
	 * @param string $_seuNum1 the SeuNum1
	 * @return string
	 */
	public function setSeuNum1($_seuNum1)
	{
		return ($this->SeuNum1 = $_seuNum1);
	}
	/**
	 * Get SeuNum2 value
	 * @return string|null
	 */
	public function getSeuNum2()
	{
		return $this->SeuNum2;
	}
	/**
	 * Set SeuNum2 value
	 * @param string $_seuNum2 the SeuNum2
	 * @return string
	 */
	public function setSeuNum2($_seuNum2)
	{
		return ($this->SeuNum2 = $_seuNum2);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructStatusSMS2SN
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