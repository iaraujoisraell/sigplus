<?php
/**
 * File for class TWWStructEnviaSMS2SNResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMS2SNResponse originally named EnviaSMS2SNResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMS2SNResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMS2SNResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $EnviaSMS2SNResult;
	/**
	 * Constructor method for EnviaSMS2SNResponse
	 * @see parent::__construct()
	 * @param string $_enviaSMS2SNResult
	 * @return TWWStructEnviaSMS2SNResponse
	 */
	public function __construct($_enviaSMS2SNResult = NULL)
	{
		parent::__construct(array('EnviaSMS2SNResult'=>$_enviaSMS2SNResult));
	}
	/**
	 * Get EnviaSMS2SNResult value
	 * @return string|null
	 */
	public function getEnviaSMS2SNResult()
	{
		return $this->EnviaSMS2SNResult;
	}
	/**
	 * Set EnviaSMS2SNResult value
	 * @param string $_enviaSMS2SNResult the EnviaSMS2SNResult
	 * @return string
	 */
	public function setEnviaSMS2SNResult($_enviaSMS2SNResult)
	{
		return ($this->EnviaSMS2SNResult = $_enviaSMS2SNResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMS2SNResponse
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