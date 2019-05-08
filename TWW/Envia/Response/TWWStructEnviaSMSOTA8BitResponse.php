<?php
/**
 * File for class TWWStructEnviaSMSOTA8BitResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSOTA8BitResponse originally named EnviaSMSOTA8BitResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSOTA8BitResponse extends TWWWsdlClass
{
	/**
	 * The EnviaSMSOTA8BitResult
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $EnviaSMSOTA8BitResult;
	/**
	 * Constructor method for EnviaSMSOTA8BitResponse
	 * @see parent::__construct()
	 * @param string $_enviaSMSOTA8BitResult
	 * @return TWWStructEnviaSMSOTA8BitResponse
	 */
	public function __construct($_enviaSMSOTA8BitResult = NULL)
	{
		parent::__construct(array('EnviaSMSOTA8BitResult'=>$_enviaSMSOTA8BitResult));
	}
	/**
	 * Get EnviaSMSOTA8BitResult value
	 * @return string|null
	 */
	public function getEnviaSMSOTA8BitResult()
	{
		return $this->EnviaSMSOTA8BitResult;
	}
	/**
	 * Set EnviaSMSOTA8BitResult value
	 * @param string $_enviaSMSOTA8BitResult the EnviaSMSOTA8BitResult
	 * @return string
	 */
	public function setEnviaSMSOTA8BitResult($_enviaSMSOTA8BitResult)
	{
		return ($this->EnviaSMSOTA8BitResult = $_enviaSMSOTA8BitResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSOTA8BitResponse
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