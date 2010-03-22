<?php
//base class for all modules
abstract class IModule {

//install
abstract function __install();
//run
abstract function main();
//init
abstract function __init();

}

class ModuleHandler {

protected $_modules;

public function __get($name) {
	if(in_array($name,$this->_modules)) {
		return $this->_modules[$this];
	}
}

public function __set($name, $value) {
	if(is_subclass_of($value,'IModule') {
		$this->_modules[$name] = $value;
	}
}

public function executeHooks($function) {
	foreach($this->_modules as $module) {
		//check if hook function exists
		$flist = get_class_methods($module);
		if(in_array($function,$flist)) {
			//run hook function
			$this->$module->$function();
		}
	}
}

public function install($module) {
	if(is_subclass_of($module,'IModule')) {
		$module->__install();
		$this->_modules[] = $module;
	}
}

}

?>
