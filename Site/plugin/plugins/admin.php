<?php
class plugin_plugins_admin extends Plugin{
	
	public function indexAction(){
		$plugins = $this->db->query()->select('*', 'plugins')->exec("getRows");
		$this->tpl->data = $plugins;
		
		// setup button tool
		$this->tpl->menu->addButton(
			$this->tpl->url->getCMSImage("Add"), 
			$this->tpl->menu->navigate("create")
		);
		$this->tpl->menu->addButton(
			$this->tpl->url->getCMSImage("Load"), 
			$this->tpl->menu->navigate("install")
		);
		
		// load the view	
		$this->tpl->load("admin/index");
	}
	
	public function createAction(){
		$this->tpl->load("admin/create");
	}
	
	public function enableAction(){
		if(cleanData::issetURL("id")){
			$this->db->query()
			->raw("UPDATE `plugins` SET `active` = 1 WHERE `plugin_id` = ".cleanData::URL("id"))
			->exec(NULL);
			$plugin = $this->getPluginById(cleanData::URL("id"));
			$obj = Lightweight::AdminPlugin($plugin['name']);
			$ro = new ReflectionClass($obj);
			if( $ro->hasMethod("enable") ){
				$obj->enable();
			}
		}
		header("location:".$this->url->getMethodAddr("index"));
	}
	
	public function disableAction(){
		if(cleanData::issetURL("id")){
			$this->db->query()
			->raw("UPDATE `plugins` SET `active` = 0 WHERE `plugin_id` = ".cleanData::URL("id"))
			->exec(NULL);
			$plugin = $this->getPluginById(cleanData::URL("id"));
			$obj = Lightweight::AdminPlugin($plugin['name']);
			$ro = new ReflectionClass($obj);
			if( $ro->hasMethod("disable") ){
				$obj->disable();
			}
		}
		header("location:".$this->url->getMethodAddr("index"));
	}
	
	private function getPluginById($id){
		return $this->db->query()
		->raw("SELECT `name` FROM `plugins` WHERE `plugin_id` = ".$id)
		->exec("getRow");
	}
	
	public function installAction(){
		$this->tpl->load("admin/upload");
	}
	
	public function infoAction(){
		if(cleanData::issetURL("id")){
			$plugin_id = cleanData::URL("id");
			$this->tpl->data = $this->db->query()->select("*", "plugins", array("plugin_id" => $plugin_id))->exec("getRow");
			
			$js = "$('#plugins_frm').submit();";
			$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), $js);
			$js = $this->tpl->menu->navigate();
			$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Back"), $js);
			$this->tpl->load("admin/edit");
		}else{
			trigger_error("Missing Plugin Id to Edit", E_USER_ERROR);
		}
	}
}
?>