<?
/* 
| Project Sol
| A ~Nolan (http://github.com/mcnolan) production.
|
| Attached Version : 2010/M1
| 
| File : langauge.php
| 
| Purpose : Handles variable language strings for the theme/template system.
| 
| 
| Sol is copyright Nolan 2010, see README for more details
|
*/

//TODO: Possibly revise this into a Module.Option format to simplify calling language options
class Language {
	//Contains all the language strings
	private $_values;
	
	//Read a langauge string, if such a string does not exist, return empty string
	public function __get($name) {
		return $this->_values[$name];
	}
	//TODO: Make this not useless.
	public function __set($name,$value) {
		if(isset($this->_values[$name])) {
			$this->_values[$name] = $value;
		}
	}
	//Reads and processes an XML language file
	public function readLanguageFile($file) {
		if(file_exists($file)) {
			$this->readXML($file);
		} else {
			throw new exception("Language file does not exist",601);
		}
	}
	
	//Process an XML file into the _values array
	private function readXML($xmlfile) {
		$parser = xmL_parser_create();
		$themelang = file_get_contents($xmlfile);
		$lang = array();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($parser, trim($themelang), $lang);
		foreach($lang as $tag) {
			switch($tag["type"]) {
				case "open" :
					//Opening tag for module definition
					$currentmod = $tag["tag"];
				break;
				case "complete":
					$this->_values[$currentmod][$tag["tag"]] = $tag["value"];
				break;
				default:
				break;
			}
		
		}
	}
	//Constuct language object, load current theme and default language files.
	function __construct(Sol $sol) {
		//Language files
		$themefile = SolSetting::THEME_PATH.$sol->setting->currentTheme."/".SolSetting::LANGUAGE_FILE;
		$defaultfile = SolSetting::THEME_PATH.$sol->setting->defaultTheme."/".SolSetting::LANGUAGE_FILE;
		$this->readXML($defaultfile);
		if(file_exists($themefile)) {
			$this->readXML($themefile);
		}
	}

}

?>
