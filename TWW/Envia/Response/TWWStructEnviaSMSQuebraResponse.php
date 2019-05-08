<?php
/**
 * File for class TWWStructEnviaSMSQuebraResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSQuebraResponse originally named EnviaSMSQuebraResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSQuebraResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMSQuebraResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $EnviaSMSQuebraResult;
	/**
	 * Constructor method for EnviaSMSQuebraResponse
	 * @see parent::__construct()
	 * @param string $_enviaSMSQuebraResult
	 * @return TWWStructEnviaSMSQuebraResponse
	 */
	public function __construct($_enviaSMSQuebraResult = NULL)
	{
		parent::__construct(array('EnviaSMSQuebraResult'=>$_enviaSMSQuebraResult));
	}
	/**
	 * Get EnviaSMSQuebraResult value
	 * @return string|null
	 */
	public function getEnviaSMSQuebraResult()
	{
		return $this->EnviaSMSQuebraResult;
	}
	/**
	 * Set EnviaSMSQuebraResult value
	 * @param string $_enviaSMSQuebraResult the EnviaSMSQuebraResult
	 * @return string
	 */
	public function setEnviaSMSQuebraResult($_enviaSMSQuebraResult)
	{
		return ($this->EnviaSMSQuebraResult = $_enviaSMSQuebraResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSQuebraResponse
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