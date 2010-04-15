<?php
/* 
| Project Sol
| A ~Nolan (http://github.com/mcnolan) production.
|
| Attached Version : 2010/M1
| 
| File : sol.php
| 
| Purpose : Root class for Sol project, provides an anchor for all common features
| 
| 
| Sol is copyright Nolan 2010, see README for more details
|
*/
class Sol {

protected $_setting;
protected $_module;
protected $_data;
protected $_theme;
protected $_arg;
//Retrieve the module handler class
public function getModule() {
	if($this->_module === null) {
		$this->_module = new ModuleHandler($this);
	}
	return $this->_module;
}
//Retrieve the Data Adaptor currently defined in the settings
public function getData() {
	if($this->_data === null) {
		$dtype = BaseSetting::getDatabaseType();
		$this->_data = new $dtype();
	}
	return $this->_data;
}
//Retrieve the Theme handler class
public function getTheme() {
	if($this->_theme === null) {
		$this->_theme = new ThemeHandler($this);
	}
	return $this->_theme;
}
//Retrieve the Setting Handler class
public function getSetting() {
	if($this->_setting === null) {
		$this->_setting = new SolSetting($this);
	}
	return $this->_setting;
}
//Get the arguments provided in the query string
public function getArgs() {
	return $this->_arg;
}
//Maps variable requests to the correct function
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
	$this->module->executeHooks("solInit");
	if(isset($this->_arg[0]) && $this->module->exists($this->_arg[0])) {
		$module = $this->_arg[0];
	} else {
		$module = $this->setting->defaultModule;
	}
	$this->module->$module->main();
}

//Load the query string up, then start the main execution
function __construct() {
	$this->processQueryString();
	$this->main();
}

}
?>
