<?php
## debuging disable when not needed
error_reporting(2047);
ini_set("display_errors",1);
## end of debuging code

date_default_timezone_set("Europe/London");
##start the sessions var
session_start();

## these are the base classes so that any class can extend them
include_once('controllers/controller.php');
include_once('models/model.php');
include_once('plugin/plugin.php');
include_once('library/statics.php');
## end of critical includes

## enter custom code here it is not recomended to edit below this block ##
## end of custom code block ##

## DataStore for url params
$_URL = array();

## autoloader works like Zend_Framework's
/**
 *
 * @param string $class_name
 */
function __autoload($class_name){
	$className = explode('_', $class_name);
	$path = "";
	foreach($className as $key => $val){
		$path .= $val."/";
	}
	$path = substr($path, 0, strlen($path)-1);
	require_once(strtolower($path).".php");
}
## end of autoloader
Controller::getLoadDetails($controller, $view);
$action = $view;
if(empty($controller)){
    $controller = "controllers_index";
    $view = "indexAction";
}else{
    $controller = "controllers_".$controller;
    if(!empty($view)){
        $view .= "Action";
    }else{
        $view = "indexAction";
    }
}

try{
	$control = new $controller;
	$control->action = $action;
	$control->controller = $controller;
	if(method_exists($control, $view)){
	    $control->$view();
	}else{
	    $view = "f404Action";
	    $control->$view();
	}
}catch(Exception $e){
	Controller::f404Static();
}
?>