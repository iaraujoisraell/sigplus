<?php
/**
 * File for class TWWStructDelSMSAgendaResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructDelSMSAgendaResponse originally named DelSMSAgendaResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructDelSMSAgendaResponse extends TWWWsdlClass
{
	/**
	 * The DelSMSAgendaResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $DelSMSAgendaResult;
	/**
	 * Constructor method for DelSMSAgendaResponse
	 * @see parent::__construct()
	 * @param string $_delSMSAgendaResult
	 * @return TWWStructDelSMSAgendaResponse
	 */
	public function __construct($_delSMSAgendaResult = NULL)
	{
		parent::__construct(array('DelSMSAgendaResult'=>$_delSMSAgendaResult));
	}
	/**
	 * Get DelSMSAgendaResult value
	 * @return string|null
	 */
	public function getDelSMSAgendaResult()
	{
		return $this->DelSMSAgendaResult;
	}
	/**
	 * Set DelSMSAgendaResult value
	 * @param string $_delSMSAgendaResult the DelSMSAgendaResult
	 * @return string
	 */
	public function setDelSMSAgendaResult($_delSMSAgendaResult)
	{
		return ($this->DelSMSAgendaResult = $_delSMSAgendaResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructDelSMSAgendaResponse
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