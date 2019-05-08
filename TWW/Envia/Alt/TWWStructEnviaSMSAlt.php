<?php
/**
 * File for class TWWStructEnviaSMSAlt
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructEnviaSMSAlt originally named EnviaSMSAlt
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructEnviaSMSAlt extends TWWWsdlClass
{
	/**
	 * The user
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $user;
	/**
	 * The pwd
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $pwd;
	/**
	 * The msgid
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $msgid;
	/**
	 * The phone
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $phone;
	/**
	 * The msgtext
	 * Meta informations extracted from the WSDL
	 * - maxOccurs : 1
	 * - minOccurs : 0
	 * @var string
	 */
	public $msgtext;
	/**
	 * Constructor method for EnviaSMSAlt
	 * @see parent::__construct()
	 * @param string $_user
	 * @param string $_pwd
	 * @param string $_msgid
	 * @param string $_phone
	 * @param string $_msgtext
	 * @return TWWStructEnviaSMSAlt
	 */
	public function __construct($_user = NULL,$_pwd = NULL,$_msgid = NULL,$_phone = NULL,$_msgtext = NULL)
	{
		parent::__construct(array('user'=>$_user,'pwd'=>$_pwd,'msgid'=>$_msgid,'phone'=>$_phone,'msgtext'=>$_msgtext));
	}
	/**
	 * Get user value
	 * @return string|null
	 */
	public function getUser()
	{
		return $this->user;
	}
	/**
	 * Set user value
	 * @param string $_user the user
	 * @return string
	 */
	public function setUser($_user)
	{
		return ($this->user = $_user);
	}
	/**
	 * Get pwd value
	 * @return string|null
	 */
	public function getPwd()
	{
		return $this->pwd;
	}
	/**
	 * Set pwd value
	 * @param string $_pwd the pwd
	 * @return string
	 */
	public function setPwd($_pwd)
	{
		return ($this->pwd = $_pwd);
	}
	/**
	 * Get msgid value
	 * @return string|null
	 */
	public function getMsgid()
	{
		return $this->msgid;
	}
	/**
	 * Set msgid value
	 * @param string $_msgid the msgid
	 * @return string
	 */
	public function setMsgid($_msgid)
	{
		return ($this->msgid = $_msgid);
	}
	/**
	 * Get phone value
	 * @return string|null
	 */
	public function getPhone()
	{
		return $this->phone;
	}
	/**
	 * Set phone value
	 * @param string $_phone the phone
	 * @return string
	 */
	public function setPhone($_phone)
	{
		return ($this->phone = $_phone);
	}
	/**
	 * Get msgtext value
	 * @return string|null
	 */
	public function getMsgtext()
	{
		return $this->msgtext;
	}
	/**
	 * Set msgtext value
	 * @param string $_msgtext the msgtext
	 * @return string
	 */
	public function setMsgtext($_msgtext)
	{
		return ($this->msgtext = $_msgtext);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructEnviaSMSAlt
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