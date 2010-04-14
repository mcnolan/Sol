<?php

class Sol {

protected $_setting;
protected $_module;
protected $_data;
protected $_theme;
protected $_arg;

public function getModule() {
	if($this->_module === null) {
		$this->_module = new ModuleHandler($this);
	}
	return $this->_module;
}

public function getData() {
	if($this->_data === null) {
		$dtype = BaseSetting::getDatabaseType();
		$this->_data = new $dtype();
	}
	return $this->_data;
}

public function getTheme() {
	if($this->_theme === null) {
		$this->_theme = new ThemeHandler($this);
	}
	return $this->_theme;
}

public function getSetting() {
	if($this->_setting === null) {
		$this->_setting = new SolSetting($this);
	}
	return $this->_setting;
}

public function getArgs() {
	return $this->_arg;
}

public function __get($name) {
	$func = "get".ucfirst($name);
	if(method_exists($this,$func)) {
		return $this->$func();
	}
}

//This function will break the query string into usable chunks
protected function processQueryString() {
	//if($this->setting->exists("sol.useCleanURL")
	//TODO: Sanitise arguments
	foreach(explode('/',$_SERVER['PATH_INFO']) as $query) {
		if(!empty($query)) {
			$this->_arg[] = $query;
		}
	}
}

//Entry point into the program
public function main() {
	//TODO: Main execution
}

function __construct() {
	$this->processQueryString();
}

}
?>
