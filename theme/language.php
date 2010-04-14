<?

class Language {
	private $_values;

	public function __get($name) {
		return $this->_values[$name];
	}

	public function __set($name,$value) {
		if(isset($this->_values[$name])) {
			$this->_values[$name] = $value;
		}
	}

	public function readLanguageFile($file) {
		if(file_exists($file)) {
			$this->readXML($file);
		} else {
			throw new exception("Language file does not exist",601);
		}
	}

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
