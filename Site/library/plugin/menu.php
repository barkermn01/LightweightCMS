<?php
class library_plugin_menu{
	private $plugin;
	private $menu = array();
	
	public function __construct($pluginName){
		$this->plugin = $pluginName;
	}
	
	public function addButton($img, $js){
		$this->menu[] = array($img, $js);
	}
	
	public function addButtons($mixed){
		foreach($mixed as $key => $val){
			$this->addButton($mixed['img'], $mixed['js']);
		}
	}
	
	public function buildOutput(){
		$html = '<script type="text/javascript">setupMenuButtons([';
		
		foreach($this->menu as $key => $val){
			$html .= '{"image":"'.$val[0].'", "click":function(){'.$val[1].'}},';
		}
		$html = rtrim($html, ",");
		$html .= ']);</script>';
		return $html;
	}
	
	public function navigate($to = null){
		return "document.location.hash = '/".$this->plugin."/".$to."';";
	}
}
?>