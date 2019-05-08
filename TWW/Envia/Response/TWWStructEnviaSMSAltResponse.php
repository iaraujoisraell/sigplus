<?php
/**
 * File for class TWWStructEnviaSMSAltResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSAltResponse originally named EnviaSMSAltResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSAltResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMSAltResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $EnviaSMSAltResult;
	/**
	 * Constructor method for EnviaSMSAltResponse
	 * @see parent::__construct()
	 * @param string $_enviaSMSAltResult
	 * @return TWWStructEnviaSMSAltResponse
	 */
	public function __construct($_enviaSMSAltResult = NULL)
	{
		parent::__construct(array('EnviaSMSAltResult'=>$_enviaSMSAltResult));
	}
	/**
	 * Get EnviaSMSAltResult value
	 * @return string|null
	 */
	public function getEnviaSMSAltResult()
	{
		return $this->EnviaSMSAltResult;
	}
	/**
	 * Set EnviaSMSAltResult value
	 * @param string $_enviaSMSAltResult the EnviaSMSAltResult
	 * @return string
	 */
	public function setEnviaSMSAltResult($_enviaSMSAltResult)
	{
		return ($this->EnviaSMSAltResult = $_enviaSMSAltResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSAltResponse
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