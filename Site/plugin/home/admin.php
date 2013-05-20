<?php
class plugin_home_admin extends Plugin{
	
	public function indexAction(){
		$this->tpl->load("admin/index");
	}
}
?>