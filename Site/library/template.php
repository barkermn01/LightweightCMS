<?php
class library_template{
	private $plugin;
	private $vars = array();
	
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
        if(
			empty($this->plugin) && file_exists("../views/$pathName.phtml") ||
			!empty($this->plugin) && file_exists("../views/plugins/$this->plugin/$pathName.phtml")
		){
            return true;
        }else{
            return false;
        }
    }
}
?>