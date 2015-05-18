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
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Delete"), "$('#delPageFrm').trigger('submit');");
		
		$this->tpl->pages = $this->db->query()->select("*","pages")->exec("getRows");
		
		$this->tpl->removeErr = isset($_URL['removeErr']);
		$this->tpl->removed = isset($_URL['removed']);
		
		// load the tpl
		$this->tpl->load("admin/page/list");
	}
	
	public function addPageAction(){
		// setup the sub menu
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), "$('#pageAddFrm').trigger('submit');");
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Modify"), $this->tpl->menu->navigate("listPage"));
		
		$this->tpl->blocks = $this->db->query()->select("*", "block_types")->exec("getRows"); 
		
		if(isset($_POST['posted']) && $_POST['posted'] === "true"){
			if((isset($_POST['homepage']))){
				$this->db->query()->update("pages", array("homepage" => 0), array("homepage" => 1))->exec();
			}
			if((isset($_POST['is404']))){
				$this->db->query()->update("pages", array("e404" => 0), array("e404" => 1))->exec();
			}
			$page_id = $this->db->query()->insert(array(
				"page_title" => $_POST['title'],
				"homepage" => (isset($_POST['homepage']))? 1:0,
				"e404" => (isset($_POST['is404']))? 1:0,
			), "pages")->exec("insert_id");
			$pos = 0;
			foreach($_POST['blocks'] as $block){
				foreach($block['vars'] as $var){
					$var['value'] = trim($var['value']);
				}
				$data = array(
					"block_type" => $block['type'],
					"block_name" => $block['name'],
					"block_pos" => $pos,
					"page_id" => $page_id,
					"block_data" => json_encode($block['vars'])
				);
				$test = $this->db->query()->insert($data, "content_blocks")->exec();
				$pos++;
			}
			$this->redirectToUrl($this->url->getMethodAddr("editPage")."/id/".$page_id."/added/true");
		}
		
		// load the tpl
		$this->tpl->load("admin/page/add");
	}
	
	public function removePageAction(){
		// setup the sub menu
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Back"), $this->tpl->menu->navigate("listPage"));
		
		if(isset($_POST["confirm"]) && $_POST["confirm"] === "true"){
			$rem = implode(",", $_POST['remove']);
			$data = $this->db->query()->select('`page_title`, `page_id`', 'pages', "page_id IN ($rem)")->exec("getRows");
			foreach($data as $value){
				$this->db->query()->delete("content_blocks", array("page_id" => $value['page_id']))->exec();
				$this->db->query()->delete("pages", array("page_id" => $value['page_id']))->exec();
			}
			$this->redirectToUrl($this->url->getMethodAddr("listPage")."/removed/true");
		}else{	
			if(empty($_POST["remove"])){
				$this->redirectToUrl($this->tpl->url->getMethodAddr("listBlockType")."/removeErr/true");
			}
			
			$pages = "";
			$rem = implode(",", $_POST['remove']);
			$data = $this->db->query()->select('`page_title`, `page_id`', 'pages', "page_id IN ($rem)")->exec("getRows");
			foreach($data as $value){
				$pages .= "'".$value['page_title']."'<input type='hidden' name='remove[]' value='".$value['page_id']."' />, ";
			}
			$this->tpl->pages = substr($pages, 0, -2);
		}
		
		// load the tpl
		$this->tpl->load("admin/page/remove");
	}
	
	public function editPageAction(){
	global $_URL;
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Back"), 'history.go(-1);');
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), '$("#pageEditFrm").trigger(\'submit\');');
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Modify"), $this->tpl->menu->navigate("listPage"));
		$this->tpl->updated = isset($_URL['updated']);
		$this->tpl->added = isset($_URL['added']);
		$this->tpl->page = $this->db->query()->select("*", "pages", "`page_id`='".$_URL['id']."'")->exec("getRow"); 
		
		if(isset($_POST['posted']) && $_POST['posted'] === "true"){
			if((isset($_POST['homepage']))){
				$this->db->query()->update("pages", array("homepage" => 0), array("homepage" => 1))->exec();
			}
			if((isset($_POST['is404']))){
				$this->db->query()->update("pages", array("e404" => 0), array("e404" => 1))->exec();
			}
			$page_id = $_URL['id'];
			$this->db->query()->update("pages", array(
				"page_title" => $_POST['title'],
				"homepage" => (isset($_POST['homepage']))? 1:0,
				"e404" => (isset($_POST['is404']))? 1:0,
			), array("page_id" => $page_id))->exec("insert_id");
			$pos = 0;
			$this->db->query()->delete("content_blocks", array('page_id' => $_URL['id']))->exec();
			foreach($_POST['blocks'] as $block){
				foreach($block['vars'] as $var){
					$var['value'] = str_replace(array("\r", "\n"), " ", $var['value']);
				}
				$json = json_encode($block['vars']);
				$data = array(
					"block_type" => $block['type'],
					"block_name" => $block['name'],
					"block_pos" => $pos,
					"page_id" => $page_id,
					"block_data" => addslashes($json)
				);
				$test = $this->db->query()->insert($data, "content_blocks")->exec();
				$pos++;
			}
		}
		
		$this->tpl->blocks = $this->db->query()->select("*", "block_types")->exec("getRows"); 
		$this->tpl->pageBlocks = $this->db->query()->raw(
			"SELECT 
				* 
			FROM 
				`content_blocks` AS CB 
			LEFT JOIN 
				`block_types` AS BT 
			ON  
				CB.`block_type` = BT.`type_id`
			WHERE 
				CB.`page_id`='".$_URL['id']."'
			"
			)->exec("getRows");
		
		// load the tpl
		$this->tpl->page_id = $_URL['id'];
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
			$ids_str = implode(',', $_POST["remove"]);
			$this->db->query()->delete("block_types", "`type_id` IN (".$ids_str.")")->exec();
			$this->redirectToUrl($this->tpl->url->getMethodAddr("listBlockType")."/removed/true");
		}
		
		$ids_str = implode(',', $_POST["remove"]);
		
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