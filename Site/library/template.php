<?php
class library_template{
	private $plugin;
	private $vars = array();
	private $container;
	private $methods = array();
	
	public final function setContainer(&$con){
		$this->container = &$con;
	}
	
	public final function allowContainerFunction($func_name){
		$this->methods[$func_name] = true;
	}
	
	public final function denyContainerFunction($func_name){
		$this->methods[$func_name] = false;
	}
	
	public final function __call($method, $args){
		if(isset($this->methods[$method]) && $this->methods[$method]){
			return call_user_func(array($this->container, $method), $args);
		}
	}
	
	public final function __set($name, $value){
		$this->vars[$name] = $value;
	}
	
	public final function __get($name){
		return $this->vars[$name];
	}
	
	public final function __isset($name)
    {
        return isset($this->vars[$name]);
    }
	
	public final function __unset($name)
    {
        unset($this->vars[$name]);
    }
	
	public function setPluginPath($pluginName){
		$this->plugin = $pluginName;
	}
	
	public function load($pathName, $return = false){
        if($this->check($pathName)){
			if($return){
				if(empty($this->plugin)){
					return eval(file_get_contents("../views/$pathName.phtml"));
				}else{
					return eval(file_get_contents("../views/plugins/$this->plugin/$pathName.phtml"));
				}
			}else{
				if(empty($this->plugin)){
            		include("../views/$pathName.phtml");
				}else{
            		include("../views/plugins/$this->plugin/$pathName.phtml");
				}
			}
        }else{
			if($return){
				return false;
			}else{
            	Controller::f404static();
			}
        }
    }

    public function check($pathName){
        return (
			empty($this->plugin) && file_exists("../views/$pathName.phtml") ||
			!empty($this->plugin) && file_exists("../views/plugins/$this->plugin/$pathName.phtml")
		);
    }
}
?>