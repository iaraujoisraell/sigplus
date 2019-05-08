<?php
/**
 * File for class TWWStructEnviaSMSOTA8Bit
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSOTA8Bit originally named EnviaSMSOTA8Bit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSOTA8Bit extends TWWWsdlClass
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
	 * The SeuNum
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $SeuNum;
	/**
	 * The Celular
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $Celular;
	/**
	 * The Header
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $Header;
	/**
	 * The Data
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $Data;
	/**
	 * Constructor method for EnviaSMSOTA8Bit
	 * @see parent::__construct()
	 * @param string $_numUsu
	 * @param string $_senha
	 * @param string $_seuNum
	 * @param string $_celular
	 * @param string $_header
	 * @param string $_data
	 * @return TWWStructEnviaSMSOTA8Bit
	 */
	public function __construct($_numUsu = NULL,$_senha = NULL,$_seuNum = NULL,$_celular = NULL,$_header = NULL,$_data = NULL)
	{
		parent::__construct(array('NumUsu'=>$_numUsu,'Senha'=>$_senha,'SeuNum'=>$_seuNum,'Celular'=>$_celular,'Header'=>$_header,'Data'=>$_data));
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
	 * Get Celular value
	 * @return string|null
	 */
	public function getCelular()
	{
		return $this->Celular;
	}
	/**
	 * Set Celular value
	 * @param string $_celular the Celular
	 * @return string
	 */
	public function setCelular($_celular)
	{
		return ($this->Celular = $_celular);
	}
	/**
	 * Get Header value
	 * @return string|null
	 */
	public function getHeader()
	{
		return $this->Header;
	}
	/**
	 * Set Header value
	 * @param string $_header the Header
	 * @return string
	 */
	public function setHeader($_header)
	{
		return ($this->Header = $_header);
	}
	/**
	 * Get Data value
	 * @return string|null
	 */
	public function getData()
	{
		return $this->Data;
	}
	/**
	 * Set Data value
	 * @param string $_data the Data
	 * @return string
	 */
	public function setData($_data)
	{
		return ($this->Data = $_data);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSOTA8Bit
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