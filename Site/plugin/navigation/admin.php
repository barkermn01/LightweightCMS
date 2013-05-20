<?php
class plugin_navigation_admin extends Plugin{
	
	public function enable(){
		$this->db->query()
		->raw("CREATE TABLE IF NOT EXISTS `navigation` (
			  `nav_id` int(11) NOT NULL AUTO_INCREMENT,
			  `url` text NOT NULL,
			  `text` text NOT NULL,
			  PRIMARY KEY (`nav_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1")->exec(NULL);
	}
	
	public function disable(){
		$this->db->query()->raw("DROP TABLE `navigation`")->exec(NULL);
	}
	
	public function indexAction(){
		
	}
}
?>