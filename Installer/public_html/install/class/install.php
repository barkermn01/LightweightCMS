<?php
session_start();
if(!defined("LWCMS_INSTALLER")) 
	die("can't load outside of installer index.php");
	
class Install{
	private $zipServer = "https://github.com/barkermn01/LWCMS-Installer/archive/{branch}.zip";
	private $ds = DIRECTORY_SEPARATOR;
	
	private function buildMySQLConfig($h, $u, $p, $d){
		return '<?php
class config_MySQL{
	private $host = "'.$h.'";
	private $user = "'.$u.'";
	private $pass = "'.$p.'";
	private $db = "'.$d.'";
	private $connection;
	
	public function __construct(){
		$this->connection = new MySQLi($this->host, $this->user, $this->pass, $this->db);
		if(mysqli_connect_errno()){
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	}
	
	public function query(){
		return new library_mysql_queryBuilder($this->connection);	
	}
	
	public function getConnection(){
		return $this->connection;
	}
	
	public function __destruct(){
		if(isset($this->connection)){
			$this->connection->close();
		}
	}
}
?>';
	}
	
	private function rmove($src, $dest){
	 
		// If source is not a directory stop processing
		if(!is_dir($src)) return false;
	 
		// If the destination directory does not exist create it
		if(!is_dir($dest)) { 
			if(!mkdir($dest)) {
				// If the destination directory could not be created stop processing
				return false;
			}    
		}
	 
		// Open the source directory to read in files
		$i = new DirectoryIterator(realpath($src));
		foreach($i as $f) {
			if($f->isFile()) {
				echo "installed file: ".$dest.$this->ds.$f->getFilename()." \r\n";
				rename($f->getRealPath(), $dest.$this->ds.$f->getFilename());
			} else if(!$f->isDot() && $f->isDir()) {
				echo "installing dir: ".$dest.$this->ds.$f." \r\n";
				$this->rmove($f->getRealPath(), $dest.$this->ds.$f);
			}
		}
	}
	
	private function resetSteps(){
		unset($_SESSION['step1']);
		unset($_SESSION['step2']);
		unset($_SESSION['step3']);
	}
	
	private function download(){
		try{
			$url = str_replace("{branch}", $_SESSION['version'], $this->zipServer); 
			file_put_contents("LWCMS-Installer-".$_SESSION['version'].".zip", fopen($url, 'r'));
		}catch(Exception $e){
			echo "Failed! Error Code 1001<br />Please try a manual Download and Setup<br />";
			exit();
		}
	}
	
	private function writeConfig(){
		$fh = fopen("../../config/mysql.php", 'w');
		fwrite($fh, $this->buildMySQLConfig(
			$_SESSION['db']['host'], 
			$_SESSION['db']['username'], 
			$_SESSION['db']['password'], 
			$_SESSION['db']['database']
		));
		fclose($fh);
	}
	
	private function unpack(){
		$zip = new ZipArchive();
		$res = $zip->open("LWCMS-Installer-".$_SESSION['version'].".zip");
		if($res === TRUE){
			$zip->extractTo('./');
		}else{
			echo "Failed! Error Code 1002<br />Please try a manual Download and Setup<br />";
			exit();
		}
	}
	
	private function installBaseDB(){
		$fh = fopen("LWCMS-Installer-".$_SESSION['version'].$this->ds."database".$this->ds."base.sql", 'r');
		$content = "";
		while(!feof($fh)){
			$content .= fread($fh, 128);
		}
		fclose($fh);
		$db = new MySQLi(
			$_SESSION['db']['host'],
			$_SESSION['db']['username'],
			$_SESSION['db']['password'],
			$_SESSION['db']['database']
		);
		$data = explode(";", $content);
		foreach($data as $sql){
			$query = $db->query($sql);
		}
		$db->close();
	}
	
	public function cleanFiles(){
		unlink("LWCMS-Installer-".$_SESSION['version'].".zip");
		$dir = "LWCMS-Installer-".$_SESSION['version'];
		
		$it = new RecursiveDirectoryIterator($dir);
		$files = new RecursiveIteratorIterator($it,
					 RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file) {
			if ($file->getFilename() === '.' || $file->getFilename() === '..') {
				continue;
			}
			if ($file->isDir()){
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}
		rmdir($dir);
	}
	
	private function createUser(){
		$db = new MySQLi(
			$_SESSION['db']['host'],
			$_SESSION['db']['username'],
			$_SESSION['db']['password'],
			$_SESSION['db']['database']
		);
		$sql = "INSERT INTO `users` VALUES(
			NULL, 
			'".$_SESSION['user']['username']."', 
			'".$_SESSION['user']['password']."', 
			'".$_SESSION['user']['email']."', 1, 0)";
		$query = $db->query($sql);
		$db->close();
	}
	
	public function step1(){
		$this->resetSteps();
		if(isset($_POST['step']) && $_POST['step'] == '1'){
			$_SESSION['db'] = $_POST['db'];
			$mysql = @new MySQLi(
				$_POST['db']['host'], 
				$_POST['db']['username'],
				$_POST['db']['password'],
				$_POST['db']['database']
			);
			
			if (mysqli_connect_error($mysql)){
				$_SESSION['step1'] = false;
			}else{
				$_SESSION['step1'] = true;
				header("location: index.php?step=2");
			}
		}
	}
	
	public function step2(){
		$this->resetSteps();
		if(isset($_POST['step']) && $_POST['step'] == '2'){
			$_SESSION['user'] = $_POST['user'];
			if( 
				empty($_POST['user']['username']) || 
				empty($_POST['user']['password']) || 
				empty($_POST['user']['email'])
			){
			}else{					
				$_SESSION['step2'] = true;
				$_SESSION['version'] = ($_POST['version'] == "Live")? "master":$_POST['version'];
				header("location: index.php?step=3");
			}
		}
	}
	
	public function install(){
		$this->resetSteps();
		if(isset($_GET['step']) && $_GET['step'] == 'install'){
			ob_start();
			echo "Downloading LightweightCMS Files<br />";
			ob_flush();
			$this->download();
			
			echo "Unpacking LightweightCMS<br />";
			ob_flush();
			$this->unpack();
			
			echo "Installing Database<br />";
			ob_flush();
			$this->installBaseDB();
			
			echo "Installing Admin User<br />";
			ob_flush();
			$this->createUser();
			
			echo "Installing LightweightCMS Files<br />";
			ob_flush();
			echo "<pre>";
			$this->rmove("LWCMS-Installer-".$_SESSION['version']."/site", "../../");
			echo "</pre>";
			
			echo "Building LightweightCMS Config Files<br />";
			ob_flush();
			$this->writeConfig();
			die();
			
			echo "Cleaning Install Files<br />";
			ob_flush();
			$this->cleanFiles();
			
			ob_end_flush();
			exit();
		}
	}
}
?>