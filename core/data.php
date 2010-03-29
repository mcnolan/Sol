<?php

interface IData {

	public function query($query);
	public function queryReturnLastId($query);
	protected function connect($hostname,$username,$password,$database);
	public function returnArray($result);
	public function returnObject($result);
	public function rowCount($result);

}

class Mysql extends BaseSetting implements IData {
	//TODO : add error handling
	private $_link;

	function __construct() {
		$config = $this->getDataSettings();
		$this->connect($config["Hostname"],$config["Username"],$config["Password"],$config["Database"]);
	}
	protected function connect($hostname, $username,$password,$database) {
		$this->link = mysql_connect($hostname,$username,$password);
		mysql_select_db($database,$this->_link);
	}
	public function query($query) {
		$r = mysql_query($query,$this->_link);
		return $r;
	}
	public function queryReturnLastId($query) {
		return mysql_insert_id($this->query($query,$this->_link));
	}
	public function returnArray($result) {
		return mysql_fetch_array($result);
	}
	public function returnObject($result) {
		return mysql_fetch_object($result);
	}
	public function rowCount($result) {
		return mysql_num_rows($result);
	}
}

?>
