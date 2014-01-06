<?php
abstract class Plugin{
	public $db, $tpl, $url;
	
	public function __construct(){
		$this->db = new config_MySQL();
		$this->tpl = new library_template();
		$clsName = get_called_class();
		$clsNameParts = explode("_", $clsName);
		$clsName = $clsNameParts[1];
		$url = new library_plugin_url();
		$menu = new library_plugin_menu($clsName);
		$this->tpl->url = $url;
		$this->url =& $this->tpl->url;
		$this->tpl->menu = $menu;
		$this->tpl->setPluginPath($clsName);
	}
	
	public function redirectToUrl($url){
		die('<script type="text/javascript">downloadData("'.$url.'")</script>');
	}
	
	public abstract function indexAction();
}
?>