<?php
class MysqlConnectionProtocolo extends PDO{
	private $Host;
	private $User;
	private $Password;
	private $Schema;
	public  $MySql;
	
	function __construct(){
//		$this->Host = "localhost";
//		$this->User = "root";
//		$this->Password = "";
//		$this->Schema = "protocolo";	
		$this->Host = "40.40.0.92";
		$this->User = "financeiro";
		$this->Password = "financeiro";
		$this->Schema = "protocolo";	
		$this->StartConnection();		
	}	
	
	protected function StartConnection(){
		header('Content-Type: text/html; charset=utf-8');
		try{
			@$this->MySql = new PDO("mysql:host={$this->Host};dbname={$this->Schema}", $this->User, $this->Password);
			$this->MySql->exec("SET CHARACTER SET utf8");
			$this->MySql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			die(utf8_encode($e->getMessage()));
		}
		
	}

}
?>