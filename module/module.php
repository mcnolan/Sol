<?php
//base class for all modules
abstract class AModule {

	private $_sol;
	protected $_locked = false;

	//do something to install
	abstract function install();
	//this is where you want to make things happen
	abstract function main();

	protected function data() {
		if(isset($this->_sol)) {
			return $this->_sol->data;
		}
	}
	protected function setting() {
		if(isset($this->_sol)) {
			return $this->_sol->setting;
		}
	}
	protected function theme() {
		if(isset($this->_sol)) {
			return $this->_sol->theme;
		}
	}
	protected function args($ref) {
		if(isset($this->_sol)) {
			return $this->_sol->args[$ref];
		}
	}
	protected function runHooks($function,$pass = "") {
		$this->_sol->module->executeHooks($this->getName().ucfirst($function),$pass);
	}
	public function getName() {
		return get_class($this);
	}
	public function getLocked() {
		return $this->_locked;
	}
	//Default constructor
	public function __construct(Sol $baseref) {
		$this->_sol = $baseref;
	}

}

class ModuleHandler {

	protected $_modules = array();
	protected $_sol;

	public function __get($name) {
		if(array_key_exists($name,$this->_modules)) {
			//Module registered, check if object
			if(!is_object($this->_modules[$name])) {
				$this->_modules[$name] = new $name($this->_sol);
			}
			return $this->_modules[$name];
		} else {
			throw new exception("Module could not be found, or is not registered",301);
		}
	
	}

	public function executeHooks($function,$pass = "") {
		foreach($this->_modules as $mname => $module) {
			//check if hook function exists
			$flist = get_class_methods($mname);
			if(in_array($function,$flist)) {
				//run hook function
				$this->$mname->$function($pass);
			}
		}
	}

	private function register(AModule $module) {
		$newmodule[] = array("ModuleName" => $module->getName(), "ModuleLock" => $module->getLocked());
		$this->_sol->data->tableInsert('module',$newmodule);
		$this->_modules[$module->getName()] = $module;
	}
	
	private function getRegisteredModules() {
		$modules = $this->_sol->data->tableSelect('module');
		foreach($modules as $module) {
			$this->_modules[$module->ModuleName] = null;
		}
	}
	
	public function exists($name) {
		return array_key_exists($name,$this->_modules);
	} 

	public function __construct(Sol $sol) {
		$this->_sol = $sol;
		$this->getRegisteredModules();
	}
	
	
	public function install($module) {
		if(is_subclass_of($module,'AModule')) {
			if(!$this->exists($module->getName())) {
				$module->install();
				$this->register($module);
			} else {
				throw new exception("Module already installed",303);
			}
		} else {
			throw new exception("Illegal Module, cannot install",302);
		}
	}

}

?>
