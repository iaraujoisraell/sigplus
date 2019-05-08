<?php
/**
 * File for class TWWStructEnviaSMSEnhanced
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSEnhanced originally named EnviaSMSEnhanced
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSEnhanced extends TWWWsdlClass
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
	 * The bmp
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $bmp;
	/**
	 * The texto
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $texto;
	/**
	 * Constructor method for EnviaSMSEnhanced
	 * @see parent::__construct()
	 * @param string $_numUsu
	 * @param string $_senha
	 * @param string $_seuNum
	 * @param string $_celular
	 * @param string $_bmp
	 * @param string $_texto
	 * @return TWWStructEnviaSMSEnhanced
	 */
	public function __construct($_numUsu = NULL,$_senha = NULL,$_seuNum = NULL,$_celular = NULL,$_bmp = NULL,$_texto = NULL)
	{
		parent::__construct(array('NumUsu'=>$_numUsu,'Senha'=>$_senha,'SeuNum'=>$_seuNum,'Celular'=>$_celular,'bmp'=>$_bmp,'texto'=>$_texto));
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
	 * Get bmp value
	 * @return string|null
	 */
	public function getBmp()
	{
		return $this->bmp;
	}
	/**
	 * Set bmp value
	 * @param string $_bmp the bmp
	 * @return string
	 */
	public function setBmp($_bmp)
	{
		return ($this->bmp = $_bmp);
	}
	/**
	 * Get texto value
	 * @return string|null
	 */
	public function getTexto()
	{
		return $this->texto;
	}
	/**
	 * Set texto value
	 * @param string $_texto the texto
	 * @return string
	 */
	public function setTexto($_texto)
	{
		return ($this->texto = $_texto);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSEnhanced
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