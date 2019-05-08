<?php
/**
 * File for class TWWStructBuscaSMSMO
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructBuscaSMSMO originally named BuscaSMSMO
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructBuscaSMSMO extends TWWWsdlClass
{
	/**
	 * The DataIni
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 1
	 * @var dateTime
	 */
	public $DataIni;
	/**
	 * The DataFim
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 1
	 * @var dateTime
	 */
	public $DataFim;
	/**
	 * The NumUsu
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $NumUsu;
	/**
	 * The Senha
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $Senha;
	/**
	 * Constructor method for BuscaSMSMO
	 * @see parent::__construct()
	 * @param dateTime $_dataIni
	 * @param dateTime $_dataFim
	 * @param string $_numUsu
	 * @param string $_senha
	 * @return TWWStructBuscaSMSMO
	 */
	public function __construct($_dataIni,$_dataFim,$_numUsu = NULL,$_senha = NULL)
	{
		parent::__construct(array('DataIni'=>$_dataIni,'DataFim'=>$_dataFim,'NumUsu'=>$_numUsu,'Senha'=>$_senha));
	}
	/**
	 * Get DataIni value
	 * @return dateTime
	 */
	public function getDataIni()
	{
		return $this->DataIni;
	}
	/**
	 * Set DataIni value
	 * @param dateTime $_dataIni the DataIni
	 * @return dateTime
	 */
	public function setDataIni($_dataIni)
	{
		return ($this->DataIni = $_dataIni);
	}
	/**
	 * Get DataFim value
	 * @return dateTime
	 */
	public function getDataFim()
	{
		return $this->DataFim;
	}
	/**
	 * Set DataFim value
	 * @param dateTime $_dataFim the DataFim
	 * @return dateTime
	 */
	public function setDataFim($_dataFim)
	{
		return ($this->DataFim = $_dataFim);
	}
	/**
	 * Get NumUsu value
	 * @return string|null
	 */
	public function getNumUsu()
	{
		return $this->NumUsu;
	}
	/**
	 * Set NumUsu value
	 * @param string $_numUsu the NumUsu
	 * @return string
	 */
	public function setNumUsu($_numUsu)
	{
		return ($this->NumUsu = $_numUsu);
	}
	/**
	 * Get Senha value
	 * @return string|null
	 */
	public function getSenha()
	{
		return $this->Senha;
	}
	/**
	 * Set Senha value
	 * @param string $_senha the Senha
	 * @return string
	 */
	public function setSenha($_senha)
	{
		return ($this->Senha = $_senha);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructBuscaSMSMO
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