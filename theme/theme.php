<?php

class ThemeHandler {
	private $_data;
	private $_language;
	private $_sol;

	public function __get($name) {
		return $this->_data[$name];
	}
	public function __set($name, $value) {
		$this->_data[$name] = $value;
	}
	public function language() {
		if($this->_language === null) {
			$this->_language = new Language($this->_sol);
		}
		return $this->_language;
	}

	public function render() {
		//print the layout template to screen, hopefully you've loaded the $content variable at this point.
		echo bufferTemplate(SolSetting::THEME_PATH.$this->_sol->setting->currentTheme."/".SolSetting::THEME_LAYOUT);
	}
	
	public function loadTemplate($name, $mdirectory = "") {
		//Build directory paths
		$override = SolSetting::THEME_PATH.$this->_sol->setting->currentTheme."/".$name.".phtml";
		$module = SolSetting::MODULE_PATH.$mdirectory."/".$name.".phtml";
		$default = SolSetting::THEME_PATH.$this->_sol->setting->defaultTheme."/".$name.".phtml";
		if(file_exists($override)) {
			return $this->bufferTemplate($override);
		} elseif(file_exists($module)) {
			//File within module
			return $this->bufferTemplate($module);
		} elseif(file_exists($default)) {
			return $this-> bufferTemplate($default);
		} else {
			throw new exception("Template file does not exist",501);
		}
	}
	
	public function bufferTemplate($file) {
		if(file_exists($file)) {
			ob_start();
			include_once($file);
			$result = ob_get_contents();
			ob_end_clean();
			return $result;
		} else {
			throw new exception("Template file does not exist",501);
		}
	}

	function __construct(Sol $sol) {
		$this->_sol = $sol;
	}

}
?>
