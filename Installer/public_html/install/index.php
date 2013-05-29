<?php
define("LWCMS_INSTALLER", true);
include("class/install.php");
$install = new Install();
$_GET['step'] = (isset($_GET['step']))? $_GET['step']:"1";

include("includes/details.php");
switch($_GET['step']){
	case "1":
	case "2":
		$method = "step".$_GET['step'];
		$install->$method();
	case "3":
		include("includes/step".$_GET['step'].".php");
	break;
	case "install":
		$install->install();
	break;
	case "finished":
		unlink("class/install.php");
		include("includes/complete.php");
	break;
}
?>