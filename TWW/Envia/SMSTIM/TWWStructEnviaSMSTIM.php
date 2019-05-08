<?php
/**
 * File for class TWWStructEnviaSMSTIM
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSTIM originally named EnviaSMSTIM
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSTIM extends TWWWsdlClass
{
	/**
	 * The XMLString
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $XMLString;
	/**
	 * Constructor method for EnviaSMSTIM
	 * @see parent::__construct()
	 * @param string $_xMLString
	 * @return TWWStructEnviaSMSTIM
	 */
	public function __construct($_xMLString = NULL)
	{
		parent::__construct(array('XMLString'=>$_xMLString));
	}
	/**
	 * Get XMLString value
	 * @return string|null
	 */
	public function getXMLString()
	{
		return $this->XMLString;
	}
	/**
	 * Set XMLString value
	 * @param string $_xMLString the XMLString
	 * @return string
	 */
	public function setXMLString($_xMLString)
	{
		return ($this->XMLString = $_xMLString);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSTIM
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