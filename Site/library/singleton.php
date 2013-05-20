<?php
abstract class library_singleton{
	private static $instance;
	
	public static function getInstance(){
		if(isset(self::$instance)){
			self::$instance = new get_called_class();
		}
		return self::$instance;
	}
	
	private function __construct(){
	}
}
?>