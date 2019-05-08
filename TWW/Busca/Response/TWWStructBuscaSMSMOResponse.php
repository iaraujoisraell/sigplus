<?php
/**
 * File for class TWWStructBuscaSMSMOResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructBuscaSMSMOResponse originally named BuscaSMSMOResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructBuscaSMSMOResponse extends TWWWsdlClass
{
	/**
	 * The BuscaSMSMOResult
	 * @var TWWStructBuscaSMSMOResult
	 */
	public $BuscaSMSMOResult;
	/**
	 * Constructor method for BuscaSMSMOResponse
	 * @see parent::__construct()
	 * @param TWWStructBuscaSMSMOResult $_buscaSMSMOResult
	 * @return TWWStructBuscaSMSMOResponse
	 */
	public function __construct($_buscaSMSMOResult = NULL)
	{
		parent::__construct(array('BuscaSMSMOResult'=>$_buscaSMSMOResult));
	}
	/**
	 * Get BuscaSMSMOResult value
	 * @return TWWStructBuscaSMSMOResult|null
	 */
	public function getBuscaSMSMOResult()
	{
		return $this->BuscaSMSMOResult;
	}
	/**
	 * Set BuscaSMSMOResult value
	 * @param TWWStructBuscaSMSMOResult $_buscaSMSMOResult the BuscaSMSMOResult
	 * @return TWWStructBuscaSMSMOResult
	 */
	public function setBuscaSMSMOResult($_buscaSMSMOResult)
	{
		return ($this->BuscaSMSMOResult = $_buscaSMSMOResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructBuscaSMSMOResponse
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