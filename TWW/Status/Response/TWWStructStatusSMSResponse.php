<?php
/**
 * File for class TWWStructStatusSMSResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructStatusSMSResponse originally named StatusSMSResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructStatusSMSResponse extends TWWWsdlClass
{
	/**
	 * The StatusSMSResult
	 * @var TWWStructStatusSMSResult
	 */
	public $StatusSMSResult;
	/**
	 * Constructor method for StatusSMSResponse
	 * @see parent::__construct()
	 * @param TWWStructStatusSMSResult $_statusSMSResult
	 * @return TWWStructStatusSMSResponse
	 */
	public function __construct($_statusSMSResult = NULL)
	{
		parent::__construct(array('StatusSMSResult'=>$_statusSMSResult));
	}
	/**
	 * Get StatusSMSResult value
	 * @return TWWStructStatusSMSResult|null
	 */
	public function getStatusSMSResult()
	{
		return $this->StatusSMSResult;
	}
	/**
	 * Set StatusSMSResult value
	 * @param TWWStructStatusSMSResult $_statusSMSResult the StatusSMSResult
	 * @return TWWStructStatusSMSResult
	 */
	public function setStatusSMSResult($_statusSMSResult)
	{
		return ($this->StatusSMSResult = $_statusSMSResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructStatusSMSResponse
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