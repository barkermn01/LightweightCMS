<?php
class Model{
	public function mysql_connection(){
		$this->mysql = new config_MySQL();
		$this->connection = $this->mysql->getConnection();
	}
	
	public function debug($mixed, $varDump = false, $die = false){
		if($varDump){
			echo "<pre>";
			var_dump($mixed);
			echo "</pre>";
			if($die) die();
		}else{
			echo "<pre>".print_r($mixed, true)."</pre>";
			if($die) die();
		}
	}
}
?>