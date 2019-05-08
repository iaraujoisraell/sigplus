<?php
/**
 * File for class TWWStructStatusSMSDataSetResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructStatusSMSDataSetResponse originally named StatusSMSDataSetResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructStatusSMSDataSetResponse extends TWWWsdlClass
{
	/**
	 * The StatusSMSDataSetResult
	 * @var TWWStructStatusSMSDataSetResult
	 */
	public $StatusSMSDataSetResult;
	/**
	 * Constructor method for StatusSMSDataSetResponse
	 * @see parent::__construct()
	 * @param TWWStructStatusSMSDataSetResult $_statusSMSDataSetResult
	 * @return TWWStructStatusSMSDataSetResponse
	 */
	public function __construct($_statusSMSDataSetResult = NULL)
	{
		parent::__construct(array('StatusSMSDataSetResult'=>$_statusSMSDataSetResult));
	}
	/**
	 * Get StatusSMSDataSetResult value
	 * @return TWWStructStatusSMSDataSetResult|null
	 */
	public function getStatusSMSDataSetResult()
	{
		return $this->StatusSMSDataSetResult;
	}
	/**
	 * Set StatusSMSDataSetResult value
	 * @param TWWStructStatusSMSDataSetResult $_statusSMSDataSetResult the StatusSMSDataSetResult
	 * @return TWWStructStatusSMSDataSetResult
	 */
	public function setStatusSMSDataSetResult($_statusSMSDataSetResult)
	{
		return ($this->StatusSMSDataSetResult = $_statusSMSDataSetResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructStatusSMSDataSetResponse
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