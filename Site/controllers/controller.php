<?php
class Controller{
    public $user, $title, $action;
    public function mysql_connection(){
        $this->mysql = new config_MySQL();
	$this->connection = $this->mysql->getConnection();
    }

    public function loadView($pathName, $return = false){
        if($this->checkView($pathName)){
			if($return){
				return file_get_contents("../views/$pathName.phtml");
			}else{
            	include("../views/$pathName.phtml");
			}
        }else{
			if($return){
				return false;
			}else{
            	$this->f404Action();
			}
        }
    }

    public function checkView($pathName){
        if(file_exists("../views/$pathName.phtml")){
            return true;
        }else{
            return false;
        }
    }

    public function indexAction(){
        echo "Welcome to the Base install of LightWeight";
    }

    public function f404Action(){
        if(!headers_sent()){
            header("HTTP/1.0 404 Not Found");
        }
        echo "<title>File, Action Or Controll Not Found</title><h1>Error 404</h1> <p>Sorry the page your looking for dose not exsits or has been moved<br />
            if you think you seeing this page in error please contact the site administrator
            </p><hr />
            <em>".$_SERVER['SERVER_SOFTWARE']." <strong>LightWeight MVC</strong></em>";
    }
    
    public static function f404Static(){
        if(!headers_sent()){
            header("HTTP/1.0 404 Not Found");
        }
        echo "<title>File, Action Or Controll Not Found</title><h1>Error 404</h1> <p>Sorry the page your looking for dose not exsits or has been moved<br />
            if you think you seeing this page in error please contact the site administrator
            </p><hr />
            <em>".$_SERVER['SERVER_SOFTWARE']." <strong>LightWeight MVC</strong></em>";
    }

    ## parses the url to get the controlla data and save the $_URL data
    /**
     *
     * @global system $_URL
     * @param string &$controlerName
     * @param string &$viewName
     */
    public static function getLoadDetails(&$controllerName, &$actionName){
    global $_URL;
            $filePath = explode('?',$_SERVER['REQUEST_URI']);
			$filePath = $filePath[0];
            $filePath = explode("/", $filePath);
            array_shift($filePath);

            $controllerName = array_shift($filePath);
            $actionName = array_shift($filePath);

            for($i = 0; $i < count($filePath); $i++){
                    $key = $filePath[$i];
                    $i++;
                    $val = @$filePath[$i];
                    $_URL[$key] = urldecode($val);
            }
    }
    ## end of the url parser
}
?>