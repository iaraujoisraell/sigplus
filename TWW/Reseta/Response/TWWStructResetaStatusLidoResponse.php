<?php
/**
 * File for class TWWStructResetaStatusLidoResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructResetaStatusLidoResponse originally named ResetaStatusLidoResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructResetaStatusLidoResponse extends TWWWsdlClass
{
	/**
	 * The ResetaStatusLidoResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $ResetaStatusLidoResult;
	/**
	 * Constructor method for ResetaStatusLidoResponse
	 * @see parent::__construct()
	 * @param string $_resetaStatusLidoResult
	 * @return TWWStructResetaStatusLidoResponse
	 */
	public function __construct($_resetaStatusLidoResult = NULL)
	{
		parent::__construct(array('ResetaStatusLidoResult'=>$_resetaStatusLidoResult));
	}
	/**
	 * Get ResetaStatusLidoResult value
	 * @return string|null
	 */
	public function getResetaStatusLidoResult()
	{
		return $this->ResetaStatusLidoResult;
	}
	/**
	 * Set ResetaStatusLidoResult value
	 * @param string $_resetaStatusLidoResult the ResetaStatusLidoResult
	 * @return string
	 */
	public function setResetaStatusLidoResult($_resetaStatusLidoResult)
	{
		return ($this->ResetaStatusLidoResult = $_resetaStatusLidoResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructResetaStatusLidoResponse
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