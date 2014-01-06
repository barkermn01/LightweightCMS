<?php
class plugin_content_admin extends Plugin{
	
	public function indexAction(){
		$this->listPageAction();
	}
		
	// page actions
	public function listPageAction(){
		// setup the sub menu
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Add"), $this->tpl->menu->navigate("addPage"));
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Pie Chart"), $this->tpl->menu->navigate("listBlockType"));
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Delete"), $this->tpl->menu->navigate("removePage"));
		
		$this->tpl->pages = $this->db->query()->select("*","pages")->exec("getRows");
		
		// load the tpl
		$this->tpl->load("admin/page/list");
	}
	
	public function addPageAction(){
		// setup the sub menu
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), "return false");
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Modify"), $this->tpl->menu->navigate("listPage"));
		
		// load the tpl
		$this->tpl->load("admin/page/add");
	}
	
	public function removePageAction(){
		// setup the sub menu
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Tick"), "return false");
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Modify"), $this->tpl->menu->navigate("listPage"));
		
		// load the tpl
		$this->tpl->load("admin/page/remove");
	}
	
	public function editPageAction(){
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), "return false");
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Modify"), $this->tpl->menu->navigate("listPage"));
		
		// load the tpl
		$this->tpl->load("admin/page/edit");
	}
	
	// block type functions
	public function listBlockTypeAction(){
	global $_URL;
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Add"), $this->tpl->menu->navigate("addBlockType"));
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Modify"), $this->tpl->menu->navigate("listPage"));
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Delete"), "$('#delTypesFrm').trigger('submit');");
		
		$this->tpl->removeErr = isset($_URL['removeErr']);
		$this->tpl->removed = isset($_URL['removed']);
		
		$this->tpl->blocks = $this->db->query()->select("*","block_types")->exec("getRows");
		
		// load the tpl
		$this->tpl->load("admin/blockType/list");
	}
	
	public function addBlockTypeAction(){	
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), "$('#addBlockTypeForm').trigger('submit');");
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Back"), $this->tpl->menu->navigate("listBlockType"));
		
		if(isset($_POST['posted']) && $_POST['posted'] === "true"){
			$id = $this->db->query()->
			insert(array(
				"type_name" => addslashes($_POST['name']),
				"type_render" => $_POST['html'],
				"type_vars" => json_encode($_POST['type_vars']),
			), "block_types")->exec("insert_id");
			$this->redirectToUrl($this->url->getMethodAddr("editBlockType")."/id/".$id."/added/true");
		}
		
		$this->tpl->load("admin/blockType/add");
	}
	
	public function removeBlockTypeAction(){
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), "return false");
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Back"), $this->tpl->menu->navigate("listBlockType"));
		
		if(empty($_POST["remove"])){
			$this->redirectToUrl($this->tpl->url->getMethodAddr("listBlockType")."/removeErr/true");
		}
		
		if(isset($_POST['confirm']) && $_POST['confirm'] === "true"){
			$ids_str = "";
			foreach($_POST["remove"] as $v){
				$ids_str .= $v.",";
			}
			$ids_str = substr($ids_str, 0, -1);
			$this->db->query()->delete("block_types", "`type_id` IN (".$ids_str.")")->exec();
			$this->redirectToUrl($this->tpl->url->getMethodAddr("listBlockType")."/removed/true");
		}
		
		$ids_str = "";
		foreach($_POST["remove"] as $v){
			$ids_str .= $v.",";
		}
		$ids_str = substr($ids_str, 0, -1);
		
		$blocks = $this->db->query()->select("`type_name`, `type_id`","block_types", "`type_id` IN (".$ids_str.")")->exec("getRows");
		$block_names = "";
		foreach($blocks as $v){
			$block_names .= $v['type_name']."<input type='hidden' name='remove[]' value='".$v['type_id']."' />, ";
		}
		$this->tpl->blocks = substr($block_names, 0, -2);
		
		// load the tpl
		$this->tpl->load("admin/blockType/remove");
	}
	
	public function editBlockTypeAction(){
	global $_URL;
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), "$('#blockTypeFrm').trigger('submit');");
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Back"), $this->tpl->menu->navigate("listBlockType"));
		
		$block = $this->db->query()->select("*","block_types", array("type_id" => $_URL['id']))->exec("getRow");
		$block['type_vars'] = json_decode($block['type_vars']);
		$this->tpl->block = $block;
		$this->tpl->added = isset($_URL['added']);
		$this->tpl->updated = isset($_URL['updated']);
		
		if(isset($_POST['posted']) && $_POST['posted'] === "true"){
			$id = $this->db->query()->
			update("block_types", array(
				"type_name" => addslashes($_POST['name']),
				"type_render" => $_POST['html'],
				"type_vars" => json_encode($_POST['type_vars']),
			), array("type_id"=>$_URL['id']))->exec();
			
			$this->redirectToUrl($this->url->getMethodAddr("editBlockType")."/id/".$_URL['id']."/updated/true");
		}
		
		// load the tpl
		$this->tpl->load("admin/blockType/edit");
	}
}
?>