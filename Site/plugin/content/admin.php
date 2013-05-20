<?php
class plugin_content_admin extends Plugin{
	
	public function indexAction(){
		$data = $this->db->query()->select("`page_id`, `title`, `creator`, `created`, `updator`, `updated`, `default`", "content")->exec("getRows");
		foreach($data as $key => $page){
			$data[$key]['changed_by'] = (!empty($page['updator']))? $page['updator']: $page['creator'];
			$data[$key]['changed'] = (!empty($page['updated']))? $page['updated']: $page['created'];
			$user = $this->db->query()->select("`username`", "users", array("user_id" => $data[$key]['changed_by']))->exec("getRow");
			$data[$key]['changed_by'] = $user['username'];
		}
		$this->tpl->data = $data;
		$js = $this->tpl->menu->navigate("create");
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Add"), $js);
		$this->tpl->load("admin/index");
	}
	
	public function editAction(){
		if(cleanData::issetURL("post")){
			try{
				$title = cleanData::POST('title');
				if(empty($title)) throw new Exception("Title can't be empty!");
				$this->db->query()->update("content", 
					array(
						"title" => $title,
						"content" => cleanData::POST('content'),
						"meta_description" => cleanData::POST('meta_description'),
						"meta_keywords" => cleanData::POST('meta_keywords')
					),
					array(
						"page_id" => cleanData::URL('id')
					)
				)->exec();
				$this->tpl->saved = true;
			}catch(Exception $e){
				$this->tpl->error = true;
				$this->tpl->errorText = $e->getMessage()."<br />".$e->getTraceAsString();
			}
		}
		
		$data = $this->db->query()->select("*", "content", array("page_id" => cleanData::URL("id")))->exec("getRow");
		$this->tpl->data = $data;
		$js = "$('#content_frm').submit();";
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), $js);
		$js = $this->tpl->menu->navigate();
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Back"), $js);
		if(cleanData::issetURL("saved")){
			$this->saved = true;
		}
		$this->tpl->load("admin/edit");
	}
	
	public function createAction(){
		if(cleanData::issetURL("post")){
			try{
				$title = cleanData::POST('title');
				if(empty($title)) throw new Exception("Title can't be empty!");
				$id = $this->db->query()->insert( 
					array(
						"title" => $title,
						"content" => cleanData::POST('content'),
						"meta_description" => cleanData::POST('meta_description'),
						"meta_keywords" => cleanData::POST('meta_keywords'),
						"creator" => $_SESSION['user']
					),
					"content"
				)->exec("insert_id");
				
				header("location: ".$this->tpl->url->getMethodAddr("edit")."/id/".$id."/saved");
				return;
			}catch(Exception $e){
				$this->tpl->error = true;
				$this->tpl->errorText = $e->getMessage()."<br />".$e->getTraceAsString();
			}
		}

		$js = "$('#content_frm').submit();";
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Save"), $js);
		
		$js = $this->tpl->menu->navigate();
		$this->tpl->menu->addButton($this->tpl->url->getCMSImage("Back"), $js);
		
		$this->tpl->load("admin/create");
	}
	
	public function deleteAction(){
		if(cleanData::issetURL("id")){
			$this->db->query()->delete("content", array(
				"page_id" => cleanData::URL("id")
			))->exec();
			header("location: ".$this->tpl->url->getMethodAddr("index"));
			return;
		}
		trigger_error("Can't delete all pages");
	}
	
	public function getLinksAction(){
		$pages = $this->db->query()->select('`page_id`, `title`', 'content')->exec("getRows");
		foreach($pages as $key => $arr){
			$pages[$key]['url'] = str_replace(' ', '_', $arr['title']);
			$pages[$key]['page_name'] = $arr['title'];
			unset($pages[$key]['title']);
		}
		echo json_encode($pages);
		return;
	}
}
?>