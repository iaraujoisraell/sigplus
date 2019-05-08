<?php
/**
 * File for class TWWStructStatusSMSNaoLidoResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructStatusSMSNaoLidoResponse originally named StatusSMSNaoLidoResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructStatusSMSNaoLidoResponse extends TWWWsdlClass
{
	/**
	 * The StatusSMSNaoLidoResult
	 * @var TWWStructStatusSMSNaoLidoResult
	 */
	public $StatusSMSNaoLidoResult;
	/**
	 * Constructor method for StatusSMSNaoLidoResponse
	 * @see parent::__construct()
	 * @param TWWStructStatusSMSNaoLidoResult $_statusSMSNaoLidoResult
	 * @return TWWStructStatusSMSNaoLidoResponse
	 */
	public function __construct($_statusSMSNaoLidoResult = NULL)
	{
		parent::__construct(array('StatusSMSNaoLidoResult'=>$_statusSMSNaoLidoResult));
	}
	/**
	 * Get StatusSMSNaoLidoResult value
	 * @return TWWStructStatusSMSNaoLidoResult|null
	 */
	public function getStatusSMSNaoLidoResult()
	{
		return $this->StatusSMSNaoLidoResult;
	}
	/**
	 * Set StatusSMSNaoLidoResult value
	 * @param TWWStructStatusSMSNaoLidoResult $_statusSMSNaoLidoResult the StatusSMSNaoLidoResult
	 * @return TWWStructStatusSMSNaoLidoResult
	 */
	public function setStatusSMSNaoLidoResult($_statusSMSNaoLidoResult)
	{
		return ($this->StatusSMSNaoLidoResult = $_statusSMSNaoLidoResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructStatusSMSNaoLidoResponse
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