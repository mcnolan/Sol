<?

class SolSetting extends BaseSetting {
	private $_extra = array();
	private $_sol;	

	public function __get($name) {
		if(!empty($this->_extra[$name]["value"])) {
			return $this->_extra[$name]["value"];
		} else {
			throw new exception("Setting with that name does not exist",401);
		}
	}

	public function __set($name, $value) {
		if(!$this->extra[$name]["locked"]) {
			$this->_extra[$name]["value"] = $value;
		} else {
			throw new exception("This setting is locked, and cannot be changed",402);
		}
	}

	public function exists($name) {
		return isset($this->_extra[$name]);
	}

	private function loadDatabaseValues() {
		//TODO Load custom settings from the database
		$rowset = $this->_sol->data->tableSelect('setting');
		foreach($rowset as $setting) {
			$this->_extra[$setting->SettingName]["value"] = $setting->SettingValue;
			$this->_extra[$setting->SettingName]["locked"] = $setting->SettingLocked;
		}
	}
	
	public function registerGlobalSetting($name,$value,$locked = false) {
		if(!$this->exists($name)) {
			//Setting does not already exist
			$new[] = array("SettingName" => $name, "SettingValue" => $value, "SettingLocked" => (($locked)?1:0));
			$this->_sol->data->tableInsert('setting',$new);
		} else {
			throw new exception("Setting already exists",403);
		}
	}

	public function __construct(Sol $sol) {
		$this->_sol = $sol;
		$this->loadDatabaseValues();
	}
}

?>
