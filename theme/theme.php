<?php
/* 
| Project Sol
| A ~Nolan (http://github.com/mcnolan) production.
|
| Attached Version : 2010/M1
| 
| File : theme.php
| 
| Purpose : Load and render template files
| 
| 
| Sol is copyright Nolan 2010, see README for more details
|
*/

//TODO: look at making these seperate objects when called
class ThemeHandler {
	//Storage for values that will be used in a loaded template file
	private $_data;
	private $_language;
	private $_sol;

	//Get and Set methods for template data
	public function __get($name) {
		return $this->_data[$name];
	}
	public function __set($name, $value) {
		$this->_data[$name] = $value;
	}

	//provides access to the language files
	//TODO: if seperate, change to a reference.
	public function language() {
		if($this->_language === null) {
			$this->_language = new Language($this->_sol);
		}
		return $this->_language;
	}

	//Load the site master layout with loaded variables
	public function render() {
		//print the layout template to screen, hopefully you've loaded the $content variable at this point.
		echo $this->bufferTemplate(SolSetting::THEME_PATH.$this->_sol->setting->currentTheme."/".SolSetting::THEME_LAYOUT);
	}
	
	//Load a template, checking current theme, module path and default theme for substitute templates
	public function loadTemplate($name, $mdirectory = "") {
		//Build directory paths
		$override = SolSetting::THEME_PATH.$this->_sol->setting->currentTheme."/".$name.".phtml";
		$module = SolSetting::MODULE_PATH.ucfirst($mdirectory)."/".$name.".phtml";
		$default = SolSetting::THEME_PATH.$this->_sol->setting->defaultTheme."/".$name.".phtml";
		//echo $override . " " . $module . " " . $default . "<br>";
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
	
	//Return a template file that has been buffered into a string
	public function bufferTemplate($file) {
		if(file_exists($file)) {
			ob_start();
			//Setup shortcut variables
			$language = $this->language();
			include_once($file);
			$result = ob_get_contents();
			ob_end_clean();
			return $result;
		} else {
			throw new exception("Template file does not exist",501);
		}
	}

	//Load up and store root class reference
	function __construct(Sol $sol) {
		$this->_sol = $sol;
	}

}
?>
