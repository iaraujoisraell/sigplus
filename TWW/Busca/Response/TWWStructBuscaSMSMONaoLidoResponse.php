<?php
/**
 * File for class TWWStructBuscaSMSMONaoLidoResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructBuscaSMSMONaoLidoResponse originally named BuscaSMSMONaoLidoResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructBuscaSMSMONaoLidoResponse extends TWWWsdlClass
{
	/**
	 * The BuscaSMSMONaoLidoResult
	 * @var TWWStructBuscaSMSMONaoLidoResult
	 */
	public $BuscaSMSMONaoLidoResult;
	/**
	 * Constructor method for BuscaSMSMONaoLidoResponse
	 * @see parent::__construct()
	 * @param TWWStructBuscaSMSMONaoLidoResult $_buscaSMSMONaoLidoResult
	 * @return TWWStructBuscaSMSMONaoLidoResponse
	 */
	public function __construct($_buscaSMSMONaoLidoResult = NULL)
	{
		parent::__construct(array('BuscaSMSMONaoLidoResult'=>$_buscaSMSMONaoLidoResult));
	}
	/**
	 * Get BuscaSMSMONaoLidoResult value
	 * @return TWWStructBuscaSMSMONaoLidoResult|null
	 */
	public function getBuscaSMSMONaoLidoResult()
	{
		return $this->BuscaSMSMONaoLidoResult;
	}
	/**
	 * Set BuscaSMSMONaoLidoResult value
	 * @param TWWStructBuscaSMSMONaoLidoResult $_buscaSMSMONaoLidoResult the BuscaSMSMONaoLidoResult
	 * @return TWWStructBuscaSMSMONaoLidoResult
	 */
	public function setBuscaSMSMONaoLidoResult($_buscaSMSMONaoLidoResult)
	{
		return ($this->BuscaSMSMONaoLidoResult = $_buscaSMSMONaoLidoResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructBuscaSMSMONaoLidoResponse
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