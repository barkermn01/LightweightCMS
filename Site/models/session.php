<?php
class models_session extends Model{
	
	public static function check(){
		$mysql = new config_mysql();
		$query = $mysql->query()->select("`ip`", "sessions", array("session_id"=>session_id()));
		
		if($query->exec("num_rows") > 0){
			$session = $query->exec("getRow");
			if($session['ip'] != $_SERVER['REMOTE_ADDR']){
				session_unset();
				session_regenerate_id();
			}
		}else{
			echo $mysql->query()->insert(array(
				"ip" => $_SERVER['REMOTE_ADDR'],
				"session_id" => session_id()
			), "sessions")->exec();
		}
	}
}
?>