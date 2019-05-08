<?php
/**
 * File for class TWWStructEnviaSMSTIMResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSTIMResponse originally named EnviaSMSTIMResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSTIMResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMSTIMResult
	 * @var TWWStructEnviaSMSTIMResult
	 */
	public $EnviaSMSTIMResult;
	/**
	 * Constructor method for EnviaSMSTIMResponse
	 * @see parent::__construct()
	 * @param TWWStructEnviaSMSTIMResult $_enviaSMSTIMResult
	 * @return TWWStructEnviaSMSTIMResponse
	 */
	public function __construct($_enviaSMSTIMResult = NULL)
	{
		parent::__construct(array('EnviaSMSTIMResult'=>$_enviaSMSTIMResult));
	}
	/**
	 * Get EnviaSMSTIMResult value
	 * @return TWWStructEnviaSMSTIMResult|null
	 */
	public function getEnviaSMSTIMResult()
	{
		return $this->EnviaSMSTIMResult;
	}
	/**
	 * Set EnviaSMSTIMResult value
	 * @param TWWStructEnviaSMSTIMResult $_enviaSMSTIMResult the EnviaSMSTIMResult
	 * @return TWWStructEnviaSMSTIMResult
	 */
	public function setEnviaSMSTIMResult($_enviaSMSTIMResult)
	{
		return ($this->EnviaSMSTIMResult = $_enviaSMSTIMResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSTIMResponse
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