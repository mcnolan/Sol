<?
class SolSetting extends BaseSetting {
	private $_extra = array();
	private $_sol;
	const MODULE_PATH = "module/";
	const MODULE_AUTOLOAD_LANG = true;
	const THEME_PATH = "theme/";
	const THEME_EXT = ".phtml";
	const THEME_LAYOUT = "layout.phtml";
	const LANGUAGE_FILE = "language.xml";

	public function __get($name) {
		if(!empty($this->_extra[$name]["value"])) {
			return $this->_extra[$name]["value"];
		} else {
			throw new exception("Setting with that name does not exist",401);
		}
	}

	public function __set($name, $value) {
		if(!$this->_extra[$name]["locked"]) {
			$this->_extra[$name]["value"] = $value;
		} else {
			throw new exception("This setting is locked, and cannot be changed",402);
		}
	}

	public function exists($name) {
		return isset($this->_extra[$name]);
	}

	private function loadDatabaseValues() {
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

	public function unregisterSetting($name) {
		if($this->exists($name)) {
			if(isset($this->_extra[$name]['locked'])) {
				//Database loaded variable, run DELETE command
				$this->_sol->data->query("DELETE FROM " . BaseSetting::$pre . "setting WHERE SettingName = '".$name."'");
			}
			unset($this->_extra[$name]);
		} else {
			throw new exception("Setting does not exist",404);
		}
	}

	public function __construct(Sol $sol) {
		$this->_sol = $sol;
		$this->loadDatabaseValues();
	}
}

?>
