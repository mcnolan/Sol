<?php
/* 
| Project Sol
| A ~Nolan (http://github.com/mcnolan) production.
|
| Attached Version : 2010/M1
| 
| File : data.php
| 
| Purpose : Provides the Data Interface standard and the default Mysql data adaptor.
| 
| 
| Sol is copyright Nolan 2010, see README for more details
|
*/

//Data Access Interface containing functions other Modules will be expecting if the database type changes
interface IData {

	public function query($query);
	public function queryReturnLastId($query);
	public function connect($hostname,$username,$password,$database);
	public function resultArray($result);
	public function resultObject($result);
	public function resultCount($result);
	public function tableSelect($table,$arguments="",$order=""); //should return an array of objects
	public function tableInsert($table,$data); //should return array of inserted ids
	public function tableUpdate($table,$data,$arguments);
	public function insertRow($table,$dataRow); //should return an int containing new id
	public function rowCount($table,$arguments=""); //should return an int containing count
}

class Mysql extends BaseSetting implements IData {
	//TODO : add error handling
	private $_link;
	//Fetch data settings from the parent settings class and establish connection to database
	function __construct() {
		$config = $this->getDataSettings();
		$this->connect($config["Hostname"],$config["Username"],$config["Password"],$config["Database"]);
	}
	//Connect to the database
	public function connect($hostname, $username,$password,$database) {
		$this->_link = mysql_connect($hostname,$username,$password);
		mysql_select_db($database,$this->_link);
	}
	//Run a query against current database link
	public function query($query) {
		$r = mysql_query($query,$this->_link) or die(mysql_error());
		return $r;
	}
	//Run a query against the current database and fetch the uniquely generated identification number
	public function queryReturnLastId($query) {
		$this->query($query);
		return mysql_insert_id($this->_link);
	}
	//Wrapper for returning results as an array
	public function resultArray($result) {
		return mysql_fetch_array($result);
	}
	//Wrapper for returning results as an object
	public function resultObject($result) {
		return mysql_fetch_object($result);
	}
	//Returns the number of rows in a result set
	public function resultCount($result) {
		return mysql_num_rows($result);
	}
	//Returns the contents of a table
	public function tableSelect($table,$arguments = "", $order = "") { 
		$output = array();
		$query = "SELECT * FROM " . BaseSetting::$pre . $table;
		if($arguments != "") {
			$query .= " WHERE " . $arguments;
		}
		if($order != "") {
			$query .= " ORDER BY " . $order;
		}
		$result = $this->query($query);
		while($data = $this->resultObject($result)) {
			$output[] = $data;
		}
		return $output;
	}
	//Inserts data structure(s) into a table and returns an array of new unique identifiers
	public function tableInsert($table,$data) { 
		$newids = array();
		foreach($data as $row) {
			foreach($row as $column => $content) {
				$cols[] = "`".$column."`";
				$cdata[] = "'".$content."'";
			}
			$query = "INSERT INTO " . BaseSetting::$pre . $table." (".implode(",",$cols).") VALUES (".implode(",",$cdata).")";
			$newids[] = $this->queryReturnLastId($query);
		}
		return $newids;
 	}
	//Updates a table with the provided data structure(s)
	public function tableUpdate($table,$data,$arguments) { 
		foreach($data as $row) {
			foreach($row as $column => $content) {
				$update[] = "`".$column ."` = '". $content."'";
			}
			$query = "UPDATE " . BaseSetting::$pre . $table." SET " . implode(", ",$update) . " WHERE " . $arguments;
			$this->query($query);
		}
	}
	//Wrapper function to Insert a single row into a table
	public function insertRow($table,$dataRow) { 
		$idarray = $this->tableInsert($table,array($dataRow));
		return $idarray[0];
	}
	//Returns the number of rows in a table based on the arguments provided
	public function rowCount($table,$arguments = "") { 
		$query = "SELECT count(*) FROM " .BaseSetting::$pre . $table;
		if($arguments != "") { $query .= " WHERE " . $arguments; }
		$result = $this->resultArray($this->query($query));
		return $result[0];
	}
}

?>
