<?php
/**
 * File for class TWWStructEnviaSMSXMLResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSXMLResponse originally named EnviaSMSXMLResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSXMLResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMSXMLResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $EnviaSMSXMLResult;
	/**
	 * Constructor method for EnviaSMSXMLResponse
	 * @see parent::__construct()
	 * @param string $_enviaSMSXMLResult
	 * @return TWWStructEnviaSMSXMLResponse
	 */
	public function __construct($_enviaSMSXMLResult = NULL)
	{
		parent::__construct(array('EnviaSMSXMLResult'=>$_enviaSMSXMLResult));
	}
	/**
	 * Get EnviaSMSXMLResult value
	 * @return string|null
	 */
	public function getEnviaSMSXMLResult()
	{
		return $this->EnviaSMSXMLResult;
	}
	/**
	 * Set EnviaSMSXMLResult value
	 * @param string $_enviaSMSXMLResult the EnviaSMSXMLResult
	 * @return string
	 */
	public function setEnviaSMSXMLResult($_enviaSMSXMLResult)
	{
		return ($this->EnviaSMSXMLResult = $_enviaSMSXMLResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSXMLResponse
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