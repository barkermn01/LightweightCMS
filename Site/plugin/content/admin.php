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
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Add"), $this->tpl->menu->navigate("addBlockType"));
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Modify"), $this->tpl->menu->navigate("listPage"));
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Delete"), $this->tpl->menu->navigate("removeBlockType"));
		
		// load the tpl
		$this->tpl->load("admin/blockType/list");
	}
	
	public function addBlockTypeAction(){	
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), "return false");
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Pie Chart"), $this->tpl->menu->navigate("listBlockType"));
		// load the tpl
		$this->tpl->load("admin/blockType/add");
	}
	
	public function removeBlockTypeAction(){
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), "return false");
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Pie Chart"), $this->tpl->menu->navigate("listBlockType"));
		
		// load the tpl
		$this->tpl->load("admin/blockType/remove");
	}
	
	public function editBlockTypeAction(){
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), "return false");
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Pie Chart"), $this->tpl->menu->navigate("listBlockType"));
		
		// load the tpl
		$this->tpl->load("admin/blockType/edit");
	}
}
?>