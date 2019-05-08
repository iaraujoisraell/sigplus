<?php
/**
 * File for class TWWStructBuscaSMSAgendaDataSetResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructBuscaSMSAgendaDataSetResponse originally named BuscaSMSAgendaDataSetResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructBuscaSMSAgendaDataSetResponse extends TWWWsdlClass
{
	/**
	 * The BuscaSMSAgendaDataSetResult
	 * @var TWWStructBuscaSMSAgendaDataSetResult
	 */
	public $BuscaSMSAgendaDataSetResult;
	/**
	 * Constructor method for BuscaSMSAgendaDataSetResponse
	 * @see parent::__construct()
	 * @param TWWStructBuscaSMSAgendaDataSetResult $_buscaSMSAgendaDataSetResult
	 * @return TWWStructBuscaSMSAgendaDataSetResponse
	 */
	public function __construct($_buscaSMSAgendaDataSetResult = NULL)
	{
		parent::__construct(array('BuscaSMSAgendaDataSetResult'=>$_buscaSMSAgendaDataSetResult));
	}
	/**
	 * Get BuscaSMSAgendaDataSetResult value
	 * @return TWWStructBuscaSMSAgendaDataSetResult|null
	 */
	public function getBuscaSMSAgendaDataSetResult()
	{
		return $this->BuscaSMSAgendaDataSetResult;
	}
	/**
	 * Set BuscaSMSAgendaDataSetResult value
	 * @param TWWStructBuscaSMSAgendaDataSetResult $_buscaSMSAgendaDataSetResult the BuscaSMSAgendaDataSetResult
	 * @return TWWStructBuscaSMSAgendaDataSetResult
	 */
	public function setBuscaSMSAgendaDataSetResult($_buscaSMSAgendaDataSetResult)
	{
		return ($this->BuscaSMSAgendaDataSetResult = $_buscaSMSAgendaDataSetResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructBuscaSMSAgendaDataSetResponse
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