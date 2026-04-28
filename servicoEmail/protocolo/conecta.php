<?php

header('Content-Type:text/html; charset=UTF-8');
date_default_timezone_set('America/Manaus');

class MysqlConnectionProtocolo{

	public static $instance; 
	
	private function __construct() { 
		// 
	} 
	
	public static function getInstance() {
			self::$instance = new PDO('mysql:host=40.40.0.92;dbname=protocolo', 'financeiro', 'financeiro', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")); 
		if (!isset(self::$instance)) {
			self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		} 
		
		return self::$instance; 
	}
	
}

?>