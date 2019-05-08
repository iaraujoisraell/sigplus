<?php
/**
 * File for class TWWStructStatusSMS2SNResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructStatusSMS2SNResponse originally named StatusSMS2SNResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructStatusSMS2SNResponse extends TWWWsdlClass
{
	/**
	 * The StatusSMS2SNResult
	 * @var TWWStructStatusSMS2SNResult
	 */
	public $StatusSMS2SNResult;
	/**
	 * Constructor method for StatusSMS2SNResponse
	 * @see parent::__construct()
	 * @param TWWStructStatusSMS2SNResult $_statusSMS2SNResult
	 * @return TWWStructStatusSMS2SNResponse
	 */
	public function __construct($_statusSMS2SNResult = NULL)
	{
		parent::__construct(array('StatusSMS2SNResult'=>$_statusSMS2SNResult));
	}
	/**
	 * Get StatusSMS2SNResult value
	 * @return TWWStructStatusSMS2SNResult|null
	 */
	public function getStatusSMS2SNResult()
	{
		return $this->StatusSMS2SNResult;
	}
	/**
	 * Set StatusSMS2SNResult value
	 * @param TWWStructStatusSMS2SNResult $_statusSMS2SNResult the StatusSMS2SNResult
	 * @return TWWStructStatusSMS2SNResult
	 */
	public function setStatusSMS2SNResult($_statusSMS2SNResult)
	{
		return ($this->StatusSMS2SNResult = $_statusSMS2SNResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructStatusSMS2SNResponse
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