<?php
class Lightweight{
	public static $plugins = array();
	
	public static function HackAttempt(){
		die("Hacking Attempt Logged");
	}
	
	public static function Plugin($pluginName, $new = false){
		if(!$new){
			if(!isset(self::$plugins[$pluginName])){
				$pluginCls = "plugin_".$pluginName."_index";
				self::$plugins[$pluginName] = new $pluginCls();
			}
			$ref = & self::$plugins[$pluginName];
			return $ref;
		}
		$pluginCls = "plugin_".$pluginName."_index";
		return new $pluginCls();
	}
	
	public static function AdminPlugin($pluginName, $new = false){
		if(!$new){
			if(!isset(self::$plugins[$pluginName])){
				$pluginCls = "plugin_".$pluginName."_admin";
				self::$plugins[$pluginName] = new $pluginCls();
			}
			$ref = & self::$plugins[$pluginName];
			return $ref;
		}
		return new $pluginCls();
	}
}

class cleanData{
	static function GET($varName){
		return addslashes($_GET[$varName]);
	}
	
	static function POST($varName){
		return addslashes($_POST[$varName]);
	}
	
	static function URL($varName){
		global $_URL;
		return addslashes($_URL[$varName]);
	}
	
	static function issetGET($varName){
		return isset($_GET[$varName]);
	}
	
	static function issetPOST($varName){
		return isset($_POST[$varName]);
	}
	
	static function issetURL($varName){
		global $_URL;
		return isset($_URL[$varName]);
	}
}

?>