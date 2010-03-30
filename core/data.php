<?php

interface IData {

	public function query($query);
	public function queryReturnLastId($query);
	public function connect($hostname,$username,$password,$database);
	public function resultArray($result);
	public function resultObject($result);
	public function resultCount($result);
	public function tableSelect($table,$arguments); //should return an array of objects
	public function tableInsert($table,$data); //should return array of inserted ids
	public function tableUpdate($table,$data);
	public function insertRow($table,$dataRow); //should return an int containing new id
	public function rowCount($table,$arguments); //should return an int containing count
}

class Mysql extends BaseSetting implements IData {
	//TODO : add error handling
	//TODO : Add wrapper functions to complete interface
	private $_link;

	function __construct() {
		$config = $this->getDataSettings();
		$this->connect($config["Hostname"],$config["Username"],$config["Password"],$config["Database"]);
	}
	public function connect($hostname, $username,$password,$database) {
		$this->_link = mysql_connect($hostname,$username,$password);
		mysql_select_db($database,$this->_link);
	}
	public function query($query) {
		$r = mysql_query($query,$this->_link) or die(mysql_error());
		return $r;
	}
	public function queryReturnLastId($query) {
		$this->query($query);
		return mysql_insert_id($this->_link);
	}
	public function resultArray($result) {
		return mysql_fetch_array($result);
	}
	public function resultObject($result) {
		return mysql_fetch_object($result);
	}
	public function resultCount($result) {
		return mysql_num_rows($result);
	}
}

?>
