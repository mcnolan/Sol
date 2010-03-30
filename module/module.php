<?php
//base class for all modules
abstract class IModule {

	private $_sol;

	//do something to install
	abstract function _install();

	protected function data() {
		if(isset($this->_sol)) {
			return $this->_sol->data();
		}
	}
	protected function setting() {
		if(isset($this->_sol)) {
			return $this->_sol->setting();
		}
	}
	protected function theme() {
		if(isset($this->_sol)) {
			return $this->_sol->theme();
		}
	}
	
	//Default constructor will process its own part of the query string and map args[1] to Action[ARG]
	public function __construct(Sol $baseref) {
		$this->_sol = $baseref;
		//Process query string
	}

}

class ModuleHandler {

	protected $_modules;
	protected $_sol;

	public function __get($name) {
		if(isset($this->_modules[$name])) {	
			//Module registered, check if object
			if(!is_object($this->_modules[$name])) {
				$this->_modules[$name] = new $name($this->_sol);
			}
			return $this->_modules[$name];
		} else {
			throw new exception("Module could not be found, or is not registered");
		}
	
	}

	public function __set($name, $value) {
		if(is_subclass_of($value,'IModule') {
			$this->_modules[$name] = $value;
		}
	}

	public function executeHooks($function) {
		foreach($this->_modules as $mname => $module) {
			//check if hook function exists
			$flist = get_class_methods($mname);
			if(in_array($function,$flist)) {
				//run hook function
				$this->$mname->$function();
			}
		}
	}

	public function registerModule($module) {
		//TODO Add module-database interaction
	}
	
	private function getRegisteredModules() {
		//TODO Add registered module database interaction. Populate module array
	}
	
	public function __construct(Sol $sol) {
		$this->_sol = $sol;
		$this->getRegisteredModules();
	}
	
	
	public function install($module) {
		if(is_subclass_of($module,'IModule')) {
			$module->_install();
			$this->_modules[] = $module;
		}
	}

}

?>
