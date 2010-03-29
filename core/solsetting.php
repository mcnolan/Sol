<?

class SolSetting extends BaseSetting {
	private $_extra = array();
	
	public function __get($name) {
		if(empty($this->_extra[$name])) {
			throw new exception("Setting with that name does not exist",401);
		} else {
			return $this->_extra[$name];
		}
	}

	public function __set($name, $value) {
		$this->_extra[$name] = $value;
	}

	private function loadDatabaseValues() {
		//TODO Load custom settings from the database
	}

	public function __construct() {
		$this->loadDatabaseValues();	
	}
}

?>
