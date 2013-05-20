<?php
require_once("controllers/controller.php");
require_once("models/model.php");

set_time_limit(300);
ini_set("memory_limit","128M");

error_reporting(2047);
ini_set("display_errors",1);

function __autoload($class_name){
	$className = explode('_', $class_name);
	$path = "";
	foreach($className as $key => $val){
		$path .= $val."/";
	}
	$path = substr($path, 0, strlen($path)-1);
        require_once($path.".php");
}


$files = array();

$handle = opendir('../controllers/');
while (false !== ($file = readdir($handle))) {
	$ext = end(explode('.', $file));
	if($ext == "php") $files[] = $file;
}

foreach($files as $key => $name){
	$controlName = current(explode('.',$name));
	if($name != "controller.php"){
		$controlName = "controllers_$controlName";
		
		$controller = new $controlName(true);
		echo "Started parsing $controlName\r\n";
		if(method_exists($controller, "cronAction")){
			$controller->cronAction();
			echo "Finished Parsing, $controlName and run cronAction\r\n";
		}else{
			echo "Finished Parsing, $controlName and cronAction was not found\r\n";
		}
	}
}
echo "Finished Parsing Controlers";
closedir($handle);
?>