<?php
/**
 * File for class TWWStructEnviaSMSTextoEnhancedResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSTextoEnhancedResponse originally named EnviaSMSTextoEnhancedResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSTextoEnhancedResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMSTextoEnhancedResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $EnviaSMSTextoEnhancedResult;
	/**
	 * Constructor method for EnviaSMSTextoEnhancedResponse
	 * @see parent::__construct()
	 * @param string $_enviaSMSTextoEnhancedResult
	 * @return TWWStructEnviaSMSTextoEnhancedResponse
	 */
	public function __construct($_enviaSMSTextoEnhancedResult = NULL)
	{
		parent::__construct(array('EnviaSMSTextoEnhancedResult'=>$_enviaSMSTextoEnhancedResult));
	}
	/**
	 * Get EnviaSMSTextoEnhancedResult value
	 * @return string|null
	 */
	public function getEnviaSMSTextoEnhancedResult()
	{
		return $this->EnviaSMSTextoEnhancedResult;
	}
	/**
	 * Set EnviaSMSTextoEnhancedResult value
	 * @param string $_enviaSMSTextoEnhancedResult the EnviaSMSTextoEnhancedResult
	 * @return string
	 */
	public function setEnviaSMSTextoEnhancedResult($_enviaSMSTextoEnhancedResult)
	{
		return ($this->EnviaSMSTextoEnhancedResult = $_enviaSMSTextoEnhancedResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSTextoEnhancedResponse
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