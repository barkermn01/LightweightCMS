<?php
/**
 * Description of admin
 *
 * @author Martin
 */
class controllers_admin extends Controller{
	public function __constuct(){
	}
	
	private function checkSession($loging = false){
		if(!$loging && !isset($_SESSION['user'])){
			die("Hacking Is Illegal");
		}
		models_session::check();
	}

    public function indexAction($failed = false){
		$this->checkSession(true);
		if(isset($_COOKIE['Cookie_ID'])){
			$mysql = new config_mysql();
			$row = $mysql->query()->select("`varname`, `value`", "cookie_data", 
			array(
				"cookie_id" => addslashes($_COOKIE['Cookie_ID']),
				"varname" => "user_id"
			))->exec("getRow");
			$_SESSION['user'] = $row['value'];
			setcookie("Cookie_ID", $_COOKIE['Cookie_ID'], time()+60*60*24*30, "/");
			$this->adminAction();
			return;
		}
		
		header("location: /admin/login");
	}
	
	public function loginAction(){
		$this->checkSession(true);
		if(cleanData::issetPOST("username")){
			// do login
			$username = cleanData::POST("username");
			$password = cleanData::POST("password");
			$remeber = cleanData::issetPOST("remeber");
			
			$mysql = new config_mysql();
			$query = $mysql->query()->select("`user_id`", "users", array(
				"username" => $username,
				"password" => sha1($password),
				"is_admin" => 1,
				"is_banned" => 0
			));
			
			if($query->exec("num_rows")){
				$row = $query->exec("getRow");
				$_SESSION['user'] = $row['user_id'];
				if($remeber){
					$cookie_id = sha1(microtime());
					setcookie("Cookie_ID", $cookie_id, time()+60*60*24*30, "/");
					$mysql->query()->insert(array(
						"cookie_id" => $cookie_id,
						"varname" => "user_id",
						"value" => $row['user_id']
					), "cookie_data")->exec();
				}
				header("location: /admin/admin");
			}else{
				header("location: /admin/login/failed");
			}
		}else{
			$this->tpl = new library_template();
			$this->tpl->failed = cleanData::issetURL("failed");
			$this->tpl->CSS = array("admin.css");
			$this->tpl->load("admin/login");
		}
	}
	
	public function logoutAction(){
		$this->checkSession(true);
		session_unset();
		if(isset($_COOKIE['Cookie_ID'])){
			setcookie("Cookie_ID", $cookie_id, time(), "/");
		}
		header("location: /admin/index");
	}
	
	public function adminAction(){
		$this->checkSession(true);
		if(!isset($_SESSION['user'])){
			header("location: /admin/login");
			return;
		}
		$tpl = new library_template();
		$tpl->JS = array(
			"ckeditor/ckeditor.js", 
			"jQuery.js", 
			"jQuery-ui.js", 
			"ckeditor/adapters/jquery.js",  
			"jQuery.Form.js",
			"admin.js"
		);
		$mysql = new config_mysql();
		$dbUser = $mysql->query()->select("`username`", "users", array("user_id" => $_SESSION['user']))->exec("getRow");
		$tpl->user = $dbUser['username'];
		
		$tabedPlugins = $mysql->query()->select("`name`,`image`", "plugins", array(
			"tab" => "1"
		))->exec("getRows");
		
		$tpl->tabs = $tabedPlugins;
		
		$tpl->CSS = array("admin.css");
		$tpl->load("admin/index");
	}
	
	public function pluginAction(){
		$this->checkSession();
		$URL = explode("/", $_SERVER['REQUEST_URI']);
		$method = (!empty($URL[4]))? $URL[4]."Action": "indexAction";
		if(empty($URL[3])){
			$URL[3] = "home";
		}
		$obj = Lightweight::AdminPlugin($URL[3]);
		$obj->$method();
	}
}
?>
