/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `block_types`
--

DROP TABLE IF EXISTS `block_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `block_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL,
  `type_render` text NOT NULL,
  `type_vars` text NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `block_types`
--

INSERT INTO `block_types` VALUES (1,'Meta Description Block','<meta type=\"description\" content=\"{description}\" />\n','[{\"name\":\"description\",\"type\":\"text\"}]'),
(2,'Meta Keywords Block','<meta type=\"keywords\" content=\"{keywords}\" />','[{\"name\":\"keywords\",\"type\":\"text\"}]'),
(3,'Content Block','<div class=\"content_block\">{content}</div>','[{\"name\":\"content\",\"type\":\"WYSIWYG\"}]'),
(4,'HTML Block','<div class=\"html_block\">{html}</div>','[{\"name\":\"html\", \"type\":\"textarea\"}]'),
(5,'Javascript Block','<div class=\"js_block\"><script type=\"text/javascript\">{js}</script></div>','[{\"name\":\"js\", \"type\":\"textarea\"}]'),
(6,'Meta Robots','<meta name=\"robots\" content=\"{robots}\"/>','[{\"name\":\"robots\",\"type\":\"text\"}]');
/*!40000 ALTER TABLE `block_types` ENABLE KEYS */;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) NOT NULL,
  `homepage` int(1) NOT NULL DEFAULT '0',
  `e404` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` VALUES (1,'Welcome to Lightweight CMS',0,0);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;

--
-- Table structure for table `content_blocks`
--

DROP TABLE IF EXISTS `content_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_blocks` (
  `block_id` int(11) NOT NULL AUTO_INCREMENT,
  `block_type` int(11) NOT NULL,
  `block_data` text NOT NULL,
  `block_name` varchar(200) NOT NULL,
  `block_pos` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`block_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_blocks`
--

/*!40000 ALTER TABLE `content_blocks` DISABLE KEYS */;
INSERT INTO `content_blocks` VALUES (2,3,'[{\"name\":\"content\",\"value\":\"<p>this is a test</p>\"}]','test_content',0,22);
/*!40000 ALTER TABLE `content_blocks` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-10  2:02:46
