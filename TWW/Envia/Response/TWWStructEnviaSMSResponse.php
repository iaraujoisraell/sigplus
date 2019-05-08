<?php
/**
 * File for class TWWStructEnviaSMSResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSResponse originally named EnviaSMSResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMSResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $EnviaSMSResult;
	/**
	 * Constructor method for EnviaSMSResponse
	 * @see parent::__construct()
	 * @param string $_enviaSMSResult
	 * @return TWWStructEnviaSMSResponse
	 */
	public function __construct($_enviaSMSResult = NULL)
	{
		parent::__construct(array('EnviaSMSResult'=>$_enviaSMSResult));
	}
	/**
	 * Get EnviaSMSResult value
	 * @return string|null
	 */
	public function getEnviaSMSResult()
	{
		return $this->EnviaSMSResult;
	}
	/**
	 * Set EnviaSMSResult value
	 * @param string $_enviaSMSResult the EnviaSMSResult
	 * @return string
	 */
	public function setEnviaSMSResult($_enviaSMSResult)
	{
		return ($this->EnviaSMSResult = $_enviaSMSResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSResponse
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