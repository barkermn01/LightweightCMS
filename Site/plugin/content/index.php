<?php
class plugin_content_index extends Plugin{
	private $blocks;
	
	public function __construct(){
		parent::__construct();
		$this->tpl->setContainer($this);
		$this->tpl->allowContainerFunction("getPrepairedBlock");
		$this->tpl->allowContainerFunction("doesBlockExist");
	}
	
	public function getPrepairedBlock($arr){
		$block_name = $arr[0];
		foreach($this->blocks as $block){
			if($block['block_name'] == $block_name){
				$temp = json_decode($block['block_data']);
				$data = array();
				foreach($temp as $datas){
					$name = $datas->name;
					$val = $datas->value;
					$data[$name] = $val;
				}
				$render = $block['type_render'];
				$matches = array();
				preg_match_all("/{(.*)}/", $render, $matches);
				for($match_i = 0; $match_i < count($matches[0]); $match_i++){
					$key = $matches[1][$match_i];
					$render = str_replace($matches[0][$match_i], $data[$key], $render);
				}
				return $render;
			}
		}
	}
	
	public function doesBlockExist(){
		foreach($this->blocks as $block){
			if($block['block_name'] == $block_name){
				return true;
			}
		}
		
		return false;
	}
	
	public function indexAction(){
		$this->tpl->page = $this->db->query()->select("*", "pages", array("homepage" => '1'))->exec("getRow");
		$page_id = $this->tpl->page["page_id"];
		$this->blocks = $this->db->query()->raw(
			"SELECT * FROM `content_blocks` AS CB LEFT JOIN `block_types` AS BT ON  CB.`block_type` = BT.`type_id`
			WHERE CB.`page_id`='$page_id'" )->exec("getRows");
		$this->tpl->load("index");
	}
}
?>