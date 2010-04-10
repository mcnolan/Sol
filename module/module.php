<?php
//base class for all modules
abstract class AModule {

	private $_sol;

	//do something to install
	abstract function install();
	//this is where you want to make things happen
	abstract function main();

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
	protected function args($ref) {
		if(isset($this->_sol)) {
			return $this->_sol->args[$ref];
		}
	}
	public function getClassName() {
		return get_class($this);
	}
	//Default constructor
	public function __construct(Sol $baseref) {
		$this->_sol = $baseref;
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
			throw new exception("Module could not be found, or is not registered",301);
		}
	
	}
	/*
	public function __set($name, $value) {
		if(is_subclass_of($value,'IModule')) {
			$this->_modules[$name] = $value;
		}
	} */

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

	private function registerModule(AModule $module) {
		//TODO Add module-database interaction /Add to database
		$this->_modules[get_class($module)] = $module;
	}
	
	private function getRegisteredModules() {
		$modules = $this->_sol->data->tableSelect('module');
		foreach($modules as $module) {
			$this->_modules[$module->ModuleName] = null;
		}
	}
	
	public function __construct(Sol $sol) {
		$this->_sol = $sol;
		$this->getRegisteredModules();
	}
	
	
	public function install($module) {
		if(is_subclass_of($module,'AModule')) {
			//error check?			
			$module->_install();
			$this->registerModule($module);
		} else {
			throw new exception("Illegal Module, cannot install",302);
		}
	}

}

?>
