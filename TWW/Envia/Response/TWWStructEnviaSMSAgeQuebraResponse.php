<?php
/**
 * File for class TWWStructEnviaSMSAgeQuebraResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSAgeQuebraResponse originally named EnviaSMSAgeQuebraResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSAgeQuebraResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMSAgeQuebraResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $EnviaSMSAgeQuebraResult;
	/**
	 * Constructor method for EnviaSMSAgeQuebraResponse
	 * @see parent::__construct()
	 * @param string $_enviaSMSAgeQuebraResult
	 * @return TWWStructEnviaSMSAgeQuebraResponse
	 */
	public function __construct($_enviaSMSAgeQuebraResult = NULL)
	{
		parent::__construct(array('EnviaSMSAgeQuebraResult'=>$_enviaSMSAgeQuebraResult));
	}
	/**
	 * Get EnviaSMSAgeQuebraResult value
	 * @return string|null
	 */
	public function getEnviaSMSAgeQuebraResult()
	{
		return $this->EnviaSMSAgeQuebraResult;
	}
	/**
	 * Set EnviaSMSAgeQuebraResult value
	 * @param string $_enviaSMSAgeQuebraResult the EnviaSMSAgeQuebraResult
	 * @return string
	 */
	public function setEnviaSMSAgeQuebraResult($_enviaSMSAgeQuebraResult)
	{
		return ($this->EnviaSMSAgeQuebraResult = $_enviaSMSAgeQuebraResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSAgeQuebraResponse
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