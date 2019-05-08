<?php
/**
 * File for class TWWStructEnviaSMSAgeResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSAgeResponse originally named EnviaSMSAgeResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSAgeResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMSAgeResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $EnviaSMSAgeResult;
	/**
	 * Constructor method for EnviaSMSAgeResponse
	 * @see parent::__construct()
	 * @param string $_enviaSMSAgeResult
	 * @return TWWStructEnviaSMSAgeResponse
	 */
	public function __construct($_enviaSMSAgeResult = NULL)
	{
		parent::__construct(array('EnviaSMSAgeResult'=>$_enviaSMSAgeResult));
	}
	/**
	 * Get EnviaSMSAgeResult value
	 * @return string|null
	 */
	public function getEnviaSMSAgeResult()
	{
		return $this->EnviaSMSAgeResult;
	}
	/**
	 * Set EnviaSMSAgeResult value
	 * @param string $_enviaSMSAgeResult the EnviaSMSAgeResult
	 * @return string
	 */
	public function setEnviaSMSAgeResult($_enviaSMSAgeResult)
	{
		return ($this->EnviaSMSAgeResult = $_enviaSMSAgeResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSAgeResponse
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