<?php

class Sol {

protected $_setting;
protected $_module;
protected $_data;
protected $_theme;

public function module() {
	if($this->_module === null) {
		$this->_module = new ModuleHandler();
	}
	return $this->_module;
}

public function data() {
	if($this->_data === null) {
		$dtype = $this->setting()->getDatabaseType();
		$this->_data = new $dtype();
	}
	return $this->_data;
}

public function theme() {
	if($this->_theme === null) {
		$this->_theme = new ThemeHandler();
	}
	return $this->_theme;
}

public function setting() {
	if($this->_setting === null) {
		$this->_setting = new SolSetting();
	}
	return $this->_setting;
}

//This function will break the query string into usable chunks
protected function processQueryString() {
	//if($this->setting()->
}

//Entry point into the program
public function main() {
}

function __construct() {
	

}

}
?>
