<?php
/**
 * File for class TWWStructAlteraSenha
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructAlteraSenha originally named AlteraSenha
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructAlteraSenha extends TWWWsdlClass
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
	 * The SenhaAntiga
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $SenhaAntiga;
	/**
	 * The SenhaNova
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $SenhaNova;
	/**
	 * Constructor method for AlteraSenha
	 * @see parent::__construct()
	 * @param string $_numUsu
	 * @param string $_senhaAntiga
	 * @param string $_senhaNova
	 * @return TWWStructAlteraSenha
	 */
	public function __construct($_numUsu = NULL,$_senhaAntiga = NULL,$_senhaNova = NULL)
	{
		parent::__construct(array('NumUsu'=>$_numUsu,'SenhaAntiga'=>$_senhaAntiga,'SenhaNova'=>$_senhaNova));
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
	 * Get SenhaAntiga value
	 * @return string|null
	 */
	public function getSenhaAntiga()
	{
		return $this->SenhaAntiga;
	}
	/**
	 * Set SenhaAntiga value
	 * @param string $_senhaAntiga the SenhaAntiga
	 * @return string
	 */
	public function setSenhaAntiga($_senhaAntiga)
	{
		return ($this->SenhaAntiga = $_senhaAntiga);
	}
	/**
	 * Get SenhaNova value
	 * @return string|null
	 */
	public function getSenhaNova()
	{
		return $this->SenhaNova;
	}
	/**
	 * Set SenhaNova value
	 * @param string $_senhaNova the SenhaNova
	 * @return string
	 */
	public function setSenhaNova($_senhaNova)
	{
		return ($this->SenhaNova = $_senhaNova);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructAlteraSenha
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