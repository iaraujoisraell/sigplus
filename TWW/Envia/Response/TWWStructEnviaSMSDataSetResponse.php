<?php
/**
 * File for class TWWStructEnviaSMSDataSetResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSDataSetResponse originally named EnviaSMSDataSetResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSDataSetResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMSDataSetResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $EnviaSMSDataSetResult;
	/**
	 * Constructor method for EnviaSMSDataSetResponse
	 * @see parent::__construct()
	 * @param string $_enviaSMSDataSetResult
	 * @return TWWStructEnviaSMSDataSetResponse
	 */
	public function __construct($_enviaSMSDataSetResult = NULL)
	{
		parent::__construct(array('EnviaSMSDataSetResult'=>$_enviaSMSDataSetResult));
	}
	/**
	 * Get EnviaSMSDataSetResult value
	 * @return string|null
	 */
	public function getEnviaSMSDataSetResult()
	{
		return $this->EnviaSMSDataSetResult;
	}
	/**
	 * Set EnviaSMSDataSetResult value
	 * @param string $_enviaSMSDataSetResult the EnviaSMSDataSetResult
	 * @return string
	 */
	public function setEnviaSMSDataSetResult($_enviaSMSDataSetResult)
	{
		return ($this->EnviaSMSDataSetResult = $_enviaSMSDataSetResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSDataSetResponse
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