<?php
class library_mysql_queryBuilder{
	public $con;
	private $query;
	
	public function __construct(mysqli $con){
		$this->con = $con;
	}
	
	private function buildWhere(array $mixed){
		$str = "";
		foreach($mixed as $key => $val){
			if(!is_string($val)){
				$str .= "`$key` = $val AND";
			}else{
				$str .= "`$key` = '$val' AND";
			}
		}
		return @substr($str, 0, count($str)-5);
	}
	
	private function buildSet(array $mixed){
		$str = "";
		foreach($mixed as $key => $val){
			if(!is_string($val)){
				$str .= "`$key` = $val, ";
			}else{
				$str .= "`$key` = '$val', ";
			}
		}
		$str = substr($str, 0, count($str)-3);
		return $str;
	}
	
	public function select($what, $from, $where = null){
		if(!is_null($where)){
			$str_where = $this->buildWhere($where);
		}else{
			$str_where = "1=1";
		}
		$this->query = "SELECT $what FROM `$from` WHERE ".$str_where." ";
		
		return $this;
	}
	
	public function insert($what, $to){
		$this->query = "INSERT INTO `$to` SET ".$this->buildSet($what)." ";
		return $this;
	}
	
	public function update($table, $what, $where = null){
		if(!is_null($where)){
			$str_where = $this->buildWhere($where);
		}else{
			$str_where = "1=1";
		}
		$this->query = "UPDATE `$table` SET ".$this->buildSet($what)." WHERE ".$str_where." ";
		return $this;
	}
	
	public function delete($from, $where = null){
		if(!is_null($where)){
			$str_where = $this->buildWhere($where);
		}else{
			$str_where = "1=1";
		}
		$this->query = "DELETE FROM `$from` WHERE ".$str_where." ";
		return $this;
	}
	
	public function orderby($row, $mode){
		if($mode != "DESC" && $mode != "ASC"){
			trigger_error("Order by mode can only been 'DESC'(decending) or 'ASC'(ascending)", E_WARNING);
		}
		$this->query .= "ORDER BY `$row` $mode ";
		return $this;
	}
	
	public function limit($count, $start = NULL ){
		if($start == NULL){
			$this->query .= "LIMT $count ";
		}else{
			$this->query .= "LIMIT $start, $count";
		}
		return $this;
	}
	
	public function raw($sql){
		$this->query = $sql;
		return $this;
	}
	
	public function exec($mode = NULL, $method = false){
		switch($mode){
			case "getString":
				return $this->query;
			break;
			case "getRows":
				$result = $this->con->query($this->query) or trigger_error($this->con->error);
				$rows = array();
				if($result->num_rows > 0) {
					while($row = $result->fetch_assoc()){
						$rows[] = $row;
					}
				}else{
					return array();
				}
				return $rows;
			break;
			case "getRow":
				$result = $this->con->query($this->query) or trigger_error($this->con->error);
				if($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					return $row;
				}else{
					return array();
				}
			break;
			case NULL:
				$data = $this->con->query($this->query) or trigger_error($this->con->error);
				return $data;
			break;
			case "insert_id":
				$this->con->query($this->query) or trigger_error($this->con->error);
				return mysqli_insert_id($this->con);
			break;
			default:
				try{
					$result = $this->con->query($this->query) or trigger_error($this->con->error);
					if($method){
						return $result->$mode();
					}
					return $result->$mode;
				}catch(Exception $e){
					trigger_error("Unknown database execute command", E_USER_ERROR);
					return;
				}
			break;
		}
	}
}
?>