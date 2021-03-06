<?php
/**
 * File for class TWWStructResetaMOLidoResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructResetaMOLidoResponse originally named ResetaMOLidoResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructResetaMOLidoResponse extends TWWWsdlClass
{
	/**
	 * The ResetaMOLidoResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $ResetaMOLidoResult;
	/**
	 * Constructor method for ResetaMOLidoResponse
	 * @see parent::__construct()
	 * @param string $_resetaMOLidoResult
	 * @return TWWStructResetaMOLidoResponse
	 */
	public function __construct($_resetaMOLidoResult = NULL)
	{
		parent::__construct(array('ResetaMOLidoResult'=>$_resetaMOLidoResult));
	}
	/**
	 * Get ResetaMOLidoResult value
	 * @return string|null
	 */
	public function getResetaMOLidoResult()
	{
		return $this->ResetaMOLidoResult;
	}
	/**
	 * Set ResetaMOLidoResult value
	 * @param string $_resetaMOLidoResult the ResetaMOLidoResult
	 * @return string
	 */
	public function setResetaMOLidoResult($_resetaMOLidoResult)
	{
		return ($this->ResetaMOLidoResult = $_resetaMOLidoResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructResetaMOLidoResponse
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