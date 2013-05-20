<?php
class library_plugin_url{
	
	public function getFullAdddress(){
		return $_SERVER['REQUEST_URI'];
	}
	
	public function getFormAddr(){
		$urlParts = explode("/", $_SERVER['REQUEST_URI']);
		return "/".$urlParts[1]."/".$urlParts[2]."/".$urlParts[3]."/".$urlParts[4];
	}
	
	public function getMethodAddr($method){
		$urlParts = explode("/", $_SERVER['REQUEST_URI']);
		return "/".$urlParts[1]."/".$urlParts[2]."/".$urlParts[3]."/".$method;
	}
	
	public function getScriptUrl($file){
		$urlParts = explode("/", $_SERVER['REQUEST_URI']);
		$plugin = (isset($urlParts[3]))? $urlParts[3]:"content";
		return "/js/plugins/".$plugin."/".$file;
	}
	
	public function getStyleUrl($file){
		$urlParts = explode("/", $_SERVER['REQUEST_URI']);
		$plugin = (isset($urlParts[3]))? $urlParts[3]:"content";
		return "/css/plugins/".$plugin."/".$file;
	}
	
	public function getImageUrl($file){
		$urlParts = explode("/", $_SERVER['REQUEST_URI']);
		$plugin = (isset($urlParts[3]))? $urlParts[3]:"content";
		return "/image/plugins/".$plugin."/".$file;
	}
	
	public function getCMSImage($imgName){
		return "/image/admin/$imgName.png";
	}
}
?>