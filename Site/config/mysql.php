<?php
class config_MySQL{
	private $host = "";
	private $user = "";
	private $pass = "";
	private $db = "";
	private $connection;
	
	public function __construct(){
		$this->connection = new MySQLi($this->host, $this->user, $this->pass, $this->db);
		if(mysqli_connect_errno()){
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	}
	
	public function query(){
		return new library_mysql_queryBuilder($this->connection);	
	}
	
	public function getConnection(){
		return $this->connection;
	}
	
	public function __destruct(){
		if(isset($this->connection)){
			$this->connection->close();
		}
	}
}
?>