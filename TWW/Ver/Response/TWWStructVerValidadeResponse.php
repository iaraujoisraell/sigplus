<?php
/**
 * File for class TWWStructVerValidadeResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructVerValidadeResponse originally named VerValidadeResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructVerValidadeResponse extends TWWWsdlClass
{
	/**
	 * The VerValidadeResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 1
	 * @var dateTime
	 */
	public $VerValidadeResult;
	/**
	 * Constructor method for VerValidadeResponse
	 * @see parent::__construct()
	 * @param dateTime $_verValidadeResult
	 * @return TWWStructVerValidadeResponse
	 */
	public function __construct($_verValidadeResult)
	{
		parent::__construct(array('VerValidadeResult'=>$_verValidadeResult));
	}
	/**
	 * Get VerValidadeResult value
	 * @return dateTime
	 */
	public function getVerValidadeResult()
	{
		return $this->VerValidadeResult;
	}
	/**
	 * Set VerValidadeResult value
	 * @param dateTime $_verValidadeResult the VerValidadeResult
	 * @return dateTime
	 */
	public function setVerValidadeResult($_verValidadeResult)
	{
		return ($this->VerValidadeResult = $_verValidadeResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructVerValidadeResponse
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