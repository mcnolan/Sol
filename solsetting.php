<?

class SolSetting {

	private $_database = "mysql";
	private $_dbHost = "localhost";
	private $_dbUser = "root";
	private $_dbPass = "ergitz";
	private $_extra = array();

	public __get($name) {
		return $this->_extra[$name];
	}
	public __set($name, $value) {
		$this->_extra[$name] = $value;
	}

	public getDatabaseType() {
		return $this->_database;
	}

	public getDataSettings() {
		$dbSetting = array("Hostname" => $this->_dbHost, "Username" => $this->_dbUser, "Password" => $this->_dbPass);
		return $dbSetting;
	}

}
?>
