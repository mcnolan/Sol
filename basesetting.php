<?
class BaseSetting {

	private $_adaptor = "Mysql";
	private $_dbHost = "localhost";
	private $_dbUser = "root";
	private $_dbPass = "ergitz";
	private $_dbData = "sol";
	public static $pre = "sol_";

	public function getDatabaseType() {
		return $this->_adaptor;
	}

	protected function getDataSettings() {
		$dbSetting = array("Hostname" => $this->_dbHost, "Username" => $this->_dbUser, "Password" => $this->_dbPass);
		return $dbSetting;
	}
}
?>
