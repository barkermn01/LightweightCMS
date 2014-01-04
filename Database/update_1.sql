DROP TABLE IF EXISTS `lightweight`.`pages`;
CREATE TABLE `lightweight`.`pages`(
	`page_id` INT(11) NOT NULL AUTO_INCREMENT,
	`page_title` VARCHAR(255) NOT NULL,
	`homepage` INT(1) NOT NULL DEFAULT 0,
	`e404` INT(1) NOT NULL DEFAULT 0,
	PRIMARY KEY(`page_id`)
)ENGINE=MyISAM;

INSERT INTO `lightweight`.`pages` VALUES(
	NULL,
	'Welcome to Lightweight CMS',
	1,
	0
);

SELECT * FROM `pages` WHERE 1=1;

DROP TABLE IF EXISTS `lightweight`.`block_types`;
CREATE TABLE `lightweight`.`block_types`(
	`type_id` INT(11) NOT NULL AUTO_INCREMENT,
	`type_name` VARCHAR(255) NOT NULL,
	`type_render` TEXT NOT NULL,
	`type_count` TEXT NOT NULL,
	PRIMARY KEY(`type_id`)
)ENGINE=MyISAM;

INSERT INTO `lightweight`.`block_types` VALUES
(1,'Meta Description Block','<meta type="description" content="{description}" />','[{"name":"description","type":"text"}]'),
(2,'Meta Keywords Block','<meta type="keywords" content="{keywords}" />','[{"name":"keywords","type":"text"}]'),
(3,'Content Block', '<div class="content_block">{content}</div>','[{"name":"content","type":"WYSIWYG"}]'),
(4,'HTML Block','<div class="html_block">{html}</div>','[{"name":"html", "type":"textarea"}]'),
(5,'Javascript Block','<div class="js_block"><script type="text/javascript">{js}</script></div>','[{"name":"js", "type":"textarea"}]');

DROP TABLE IF EXISTS `lightweight`.`content_blocks`;
CREATE TABLE `lightweight`.`content_blocks`(
	`block_id` INT(11) NOT NULL,
	`block_type` INT(11) NOT NULL,
	`block_data` TEXT NOT NULL,
	`block_title` VARCHAR(200) NOT NULL,
	`block_pos` INT(11) NOT NULL,
	PRIMARY KEY(`block_id`)
)ENGINE=MyISAM;