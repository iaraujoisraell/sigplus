<?php
/**
 * File for class TWWStructBuscaSMSAgendaResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructBuscaSMSAgendaResponse originally named BuscaSMSAgendaResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructBuscaSMSAgendaResponse extends TWWWsdlClass
{
	/**
	 * The BuscaSMSAgendaResult
	 * @var TWWStructBuscaSMSAgendaResult
	 */
	public $BuscaSMSAgendaResult;
	/**
	 * Constructor method for BuscaSMSAgendaResponse
	 * @see parent::__construct()
	 * @param TWWStructBuscaSMSAgendaResult $_buscaSMSAgendaResult
	 * @return TWWStructBuscaSMSAgendaResponse
	 */
	public function __construct($_buscaSMSAgendaResult = NULL)
	{
		parent::__construct(array('BuscaSMSAgendaResult'=>$_buscaSMSAgendaResult));
	}
	/**
	 * Get BuscaSMSAgendaResult value
	 * @return TWWStructBuscaSMSAgendaResult|null
	 */
	public function getBuscaSMSAgendaResult()
	{
		return $this->BuscaSMSAgendaResult;
	}
	/**
	 * Set BuscaSMSAgendaResult value
	 * @param TWWStructBuscaSMSAgendaResult $_buscaSMSAgendaResult the BuscaSMSAgendaResult
	 * @return TWWStructBuscaSMSAgendaResult
	 */
	public function setBuscaSMSAgendaResult($_buscaSMSAgendaResult)
	{
		return ($this->BuscaSMSAgendaResult = $_buscaSMSAgendaResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructBuscaSMSAgendaResponse
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