<?php
class DB {
	private $severname; // 连接服务器名称
	public $conn; // 数据库连接资源
	private $sql; // sql语句
	private $result; // sql语句执行结果
	public function __construct($severname = 'localhost') {
		$this->severname = $severname;
		$this->connect (); // 实例化时连接数据库
	}
	// 连接数据库
	protected function connect() {
		$this->conn = mysql_connect ( $this->severname, 'root', '123456zxc' ) or die ( print_r ( mysql_error (), true ) );
		mysql_query ( "set names 'utf8'" ); // 数据库输出编码
		$selectdb = mysql_select_db ( 'wx', $this->conn ); // 打开数据库
		if (! $selectdb) {
			echo mysql_errno ( $this->conn );
		}
	}
	
	// 执行sql语句
	protected function query($sql) {
		! empty ( $sql ) ? $this->sql = $sql : die ( "The sql cannot be empty!" );
		$result = mysql_query ( $this->sql, $this->conn );
		// print_r($result);
		if ($result) {
			$this->result = $result;
		} else {
			// echo $sql;
		}
	}
	// 执行结果
	public function execsql($sql) {
		$sqlType = strtoupper ( substr ( trim ( $sql ), 0, 6 ) );
		if ($sqlType == "SELECT") {
			$this->query ( $sql ); // 执行sql语句
			$rows = $this->getrows ();
			return $rows;
		} else {
			if ($sqlType == "UPDATE" || $sqlType == "INSERT" || $sqlType == "DELETE") {
				$this->query ( $sql );
				return $this->result;
			}
		}
	}
	// 查询多条结果
	protected function getrows() {
		$rows = array ();
		$row = array ();
		if ($this->result && mysql_num_rows($this->result)) {
			while ( $row = mysql_fetch_array ( $this->result, MYSQL_ASSOC ) ) {
				$rows [] = $row;
			}
		} else {
			// echo "kdjsfal";
		}
		return $rows;
	}
	// 查询一条结果
	public function getrow($sql) {
		$row = array ();
		$this->query ( $sql );
		$row = mysql_fetch_array ( $this->result, MYSQL_ASSOC );
		return $row;
	}
	
	protected function get_num_row($sql) {
		$row = array ();
		$this->query ( $sql );
		if($this->result != null)
		   $row = mysql_fetch_array ( $this->result, MYSQL_NUM );
		return $row;
	}
	public function insert($table, $array) {
		$keys = join ( ",", array_keys ( $array ) ); // 获取传过来的键名
		$vals = "'" . join ( "','", array_values ( $array ) ) . "'"; // 获取传过来的值
		$sql = "INSERT INTO {$table}({$keys}) VALUES ({$vals})";
		// echo $sql;die;
		$stmt=mysql_query ( $sql,$this->conn );
		$res=mysql_affected_rows();
		if ($res){
			return true;
			
		}else {

			return false;
			
		}
	}
	
	public function db_close(){
		if($result)
		  mysql_free_result($result);
		mysql_close();	
	}
}	
	
	
	
	
	
	
	
	
	
	
	
	
	
