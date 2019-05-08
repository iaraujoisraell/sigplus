<?php
/**
 * File for class TWWStructBuscaSMSResponse
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructBuscaSMSResponse originally named BuscaSMSResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructBuscaSMSResponse extends TWWWsdlClass
{
	/**
	 * The BuscaSMSResult
	 * @var TWWStructBuscaSMSResult
	 */
	public $BuscaSMSResult;
	/**
	 * Constructor method for BuscaSMSResponse
	 * @see parent::__construct()
	 * @param TWWStructBuscaSMSResult $_buscaSMSResult
	 * @return TWWStructBuscaSMSResponse
	 */
	public function __construct($_buscaSMSResult = NULL)
	{
		parent::__construct(array('BuscaSMSResult'=>$_buscaSMSResult));
	}
	/**
	 * Get BuscaSMSResult value
	 * @return TWWStructBuscaSMSResult|null
	 */
	public function getBuscaSMSResult()
	{
		return $this->BuscaSMSResult;
	}
	/**
	 * Set BuscaSMSResult value
	 * @param TWWStructBuscaSMSResult $_buscaSMSResult the BuscaSMSResult
	 * @return TWWStructBuscaSMSResult
	 */
	public function setBuscaSMSResult($_buscaSMSResult)
	{
		return ($this->BuscaSMSResult = $_buscaSMSResult);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructBuscaSMSResponse
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