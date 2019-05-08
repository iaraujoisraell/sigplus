<?php
/**
 * File for class TWWStructAlteraSenhaResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructAlteraSenhaResponse originally named AlteraSenhaResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructAlteraSenhaResponse extends TWWWsdlClass
{
	/**
	 * The AlteraSenhaResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 1
	 * @var boolean
	 */
	public $AlteraSenhaResult;
	/**
	 * Constructor method for AlteraSenhaResponse
	 * @see parent::__construct()
	 * @param boolean $_alteraSenhaResult
	 * @return TWWStructAlteraSenhaResponse
	 */
	public function __construct($_alteraSenhaResult)
	{
		parent::__construct(array('AlteraSenhaResult'=>$_alteraSenhaResult));
	}
	/**
	 * Get AlteraSenhaResult value
	 * @return boolean
	 */
	public function getAlteraSenhaResult()
	{
		return $this->AlteraSenhaResult;
	}
	/**
	 * Set AlteraSenhaResult value
	 * @param boolean $_alteraSenhaResult the AlteraSenhaResult
	 * @return boolean
	 */
	public function setAlteraSenhaResult($_alteraSenhaResult)
	{
		return ($this->AlteraSenhaResult = $_alteraSenhaResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructAlteraSenhaResponse
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