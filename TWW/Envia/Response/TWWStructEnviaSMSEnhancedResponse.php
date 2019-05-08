<?php
/**
 * File for class TWWStructEnviaSMSEnhancedResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSEnhancedResponse originally named EnviaSMSEnhancedResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSEnhancedResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMSEnhancedResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $EnviaSMSEnhancedResult;
	/**
	 * Constructor method for EnviaSMSEnhancedResponse
	 * @see parent::__construct()
	 * @param string $_enviaSMSEnhancedResult
	 * @return TWWStructEnviaSMSEnhancedResponse
	 */
	public function __construct($_enviaSMSEnhancedResult = NULL)
	{
		parent::__construct(array('EnviaSMSEnhancedResult'=>$_enviaSMSEnhancedResult));
	}
	/**
	 * Get EnviaSMSEnhancedResult value
	 * @return string|null
	 */
	public function getEnviaSMSEnhancedResult()
	{
		return $this->EnviaSMSEnhancedResult;
	}
	/**
	 * Set EnviaSMSEnhancedResult value
	 * @param string $_enviaSMSEnhancedResult the EnviaSMSEnhancedResult
	 * @return string
	 */
	public function setEnviaSMSEnhancedResult($_enviaSMSEnhancedResult)
	{
		return ($this->EnviaSMSEnhancedResult = $_enviaSMSEnhancedResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSEnhancedResponse
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