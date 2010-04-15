<?php
/* 
| Project Sol
| A ~Nolan (http://github.com/mcnolan) production.
|
| Attached Version : 2010/M1
| 
| File : module.php
| 
| Purpose : Contains Abstract class for all Sol supplimentary modules and Sol's module handling class
| 
| 
| Sol is copyright Nolan 2010, see README for more details
|
*/

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
	protected function language($variable, $module = "Default") {
		$langset = $this->theme()->language()->$module;
		return $langset[$variable];
	}
	protected function args($ref) {
		if(isset($this->_sol)) {
			return $this->_sol->args[$ref];
		}
	}
	protected function getModule($name) {
		try {
			return $this->_sol->module->$name;
		} catch (Exception $e) {
			return false;
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
		if(SolSetting::MODULE_AUTOLOAD_LANG) {
			$xml = SolSetting::MODULE_PATH.$this->getName()."/".SolSetting::LANGUAGE_FILE;
			if(file_exists($xml)) {
				$this->theme()->language()->readLanguageFile($xml);
			}
		}
	}

}

class ModuleHandler {
	//Array of all registered modules
	protected $_modules = array();
	protected $_sol;

	//Provides read access to registered modules
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
	
	//Execute a function across all module classes if it exists
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
	
	//Register module with the database
	private function register(AModule $module) {
		//TODO: Probably want to change this to a module name, not the actual module.
		$newmodule[] = array("ModuleName" => $module->getName(), "ModuleLock" => $module->getLocked());
		$this->_sol->data->tableInsert('module',$newmodule);
		$this->_modules[$module->getName()] = $module;
	}
	
	//Loads all modules registered with the database
	private function getRegisteredModules() {
		$modules = $this->_sol->data->tableSelect('module');
		foreach($modules as $module) {
			$this->_modules[$module->ModuleName] = null;
		}
	}

	//Check if a module is registered	
	public function exists($name) {
		return array_key_exists($name,$this->_modules);
	} 
	
	//Store the root class reference and load registered modules
	public function __construct(Sol $sol) {
		$this->_sol = $sol;
		$this->getRegisteredModules();
	}
	
	//Install module and register with the database
	public function install($module) {
		if(is_subclass_of($module,'AModule')) {
			//Lets try and avoid installing a module more than once
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
