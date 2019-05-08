<?php
/**
 * File for class TWWStructVerCreditoResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructVerCreditoResponse originally named VerCreditoResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructVerCreditoResponse extends TWWWsdlClass
{
	/**
	 * The VerCreditoResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 1
	 * @var int
	 */
	public $VerCreditoResult;
	/**
	 * Constructor method for VerCreditoResponse
	 * @see parent::__construct()
	 * @param int $_verCreditoResult
	 * @return TWWStructVerCreditoResponse
	 */
	public function __construct($_verCreditoResult)
	{
		parent::__construct(array('VerCreditoResult'=>$_verCreditoResult));
	}
	/**
	 * Get VerCreditoResult value
	 * @return int
	 */
	public function getVerCreditoResult()
	{
		return $this->VerCreditoResult;
	}
	/**
	 * Set VerCreditoResult value
	 * @param int $_verCreditoResult the VerCreditoResult
	 * @return int
	 */
	public function setVerCreditoResult($_verCreditoResult)
	{
		return ($this->VerCreditoResult = $_verCreditoResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructVerCreditoResponse
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