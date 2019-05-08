<?php
/**
 * File for class TWWStructBuscaSMSAgendaResult
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
/**
 * This class stands for TWWStructBuscaSMSAgendaResult originally named BuscaSMSAgendaResult
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://webservices.twwwireless.com.br/reluzcap/wsreluzcap.asmx?WSDL}
 * @package TWW
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @version 20131125-01
 * @date 2013-12-04
 */
class TWWStructBuscaSMSAgendaResult extends TWWWsdlClass
{
	/**
	 * The schema
	 * @var DOMDocument
	 */
	public $schema;
	/**
	 * The any
	 * @var DOMDocument
	 */
	public $any;
	/**
	 * Constructor method for BuscaSMSAgendaResult
	 * @see parent::__construct()
	 * @param DOMDocument $_schema
	 * @param DOMDocument $_any
	 * @return TWWStructBuscaSMSAgendaResult
	 */
	public function __construct($_schema = NULL,$_any = NULL)
	{
		parent::__construct(array('schema'=>$_schema,'any'=>$_any));
	}
	/**
	 * Get schema value
	 * @uses DOMDocument::loadXML()
	 * @uses DOMDocument::hasChildNodes()
	 * @uses DOMDocument::saveXML()
	 * @uses DOMNode::item()
	 * @uses TWWStructBuscaSMSAgendaResult::setSchema()
	 * @param bool true or false whether to return XML value as string or as DOMDocument
	 * @return DOMDocument|null
	 */
	public function getSchema($_asString = true)
	{
		if(!empty($this->schema) && !($this->schema instanceof DOMDocument))
		{
			$dom = new DOMDocument('1.0','UTF-8');
			$dom->formatOutput = true;
			if($dom->loadXML($this->schema))
			{
				$this->setSchema($dom);
			}
			unset($dom);
		}
		return ($_asString && ($this->schema instanceof DOMDocument) && $this->schema->hasChildNodes())?$this->schema->saveXML($this->schema->childNodes->item(0)):$this->schema;
	}
	/**
	 * Set schema value
	 * @param DOMDocument $_schema the schema
	 * @return DOMDocument
	 */
	public function setSchema($_schema)
	{
		return ($this->schema = $_schema);
	}
	/**
	 * Get any value
	 * @uses DOMDocument::loadXML()
	 * @uses DOMDocument::hasChildNodes()
	 * @uses DOMDocument::saveXML()
	 * @uses DOMNode::item()
	 * @uses TWWStructBuscaSMSAgendaResult::setAny()
	 * @param bool true or false whether to return XML value as string or as DOMDocument
	 * @return DOMDocument|null
	 */
	public function getAny($_asString = true)
	{
		if(!empty($this->any) && !($this->any instanceof DOMDocument))
		{
			$dom = new DOMDocument('1.0','UTF-8');
			$dom->formatOutput = true;
			if($dom->loadXML($this->any))
			{
				$this->setAny($dom);
			}
			unset($dom);
		}
		return ($_asString && ($this->any instanceof DOMDocument) && $this->any->hasChildNodes())?$this->any->saveXML($this->any->childNodes->item(0)):$this->any;
	}
	/**
	 * Set any value
	 * @param DOMDocument $_any the any
	 * @return DOMDocument
	 */
	public function setAny($_any)
	{
		return ($this->any = $_any);
	}
	/**
	 * Method called when an object has been exported with var_export() functions
	 * It allows to return an object instantiated with the values
	 * @see TWWWsdlClass::__set_state()
	 * @uses TWWWsdlClass::__set_state()
	 * @param array $_array the exported values
	 * @return TWWStructBuscaSMSAgendaResult
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