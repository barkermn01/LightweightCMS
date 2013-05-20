<?php
class plugin_content_index extends Plugin{

	public function indexAction(){
		$this->tpl->CSS = array(
			$this->tpl->url->getStyleUrl("style.css"),
			$this->tpl->url->getStyleUrl("fonts.css")
		);
		$page = cleanData::issetURL("id")? cleanData::URL("id") : 0;
		
		$content = array();
			
		if($page == 0){
			$content = $this->db->query()->select("*", "content", array("default" => 1))->exec("getRow");
		}else{
			$content = $this->db->query()->select("*", "content", array("page_id" => $page));
		}
		$this->tpl->content = $content;
		$this->tpl->pageTitle = $content['title'];
		$this->tpl->load("index");
	}
}
?>