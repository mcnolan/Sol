<?php
/* 
| Project Sol
| A ~Nolan (http://github.com/mcnolan) production.
|
| Attached Version : 2010/M1
| 
| File : error.php
| 
| Purpose : Provides Error handling
| 
| 
| Sol is copyright Nolan 2010, see README for more details
|
*/
class Error {

	//lets declare some custom error types
	//Generic catchalls
	const E_DATABASE = 100;
	const E_CORE = 200;
	const E_MODULE = 300;
	//Module does not exist in any way
	const E_MODULE_NOT_EXIST = 301;
	//Module does not extend base module interface
	const E_MODULE_ILLEGAL = 302;
	//Module already installed
	const E_MODULE_IEXISTS = 303;
	//Autoload failure
	const E_MODULE_NOLOAD = 304;
	//General setting error
	const E_SETTING = 400;
	//Trying to get a setting that doesn't exist
	const E_SETTING_NOSUCH = 401;
	//Trying to write to a setting that is locked
	const E_SETTING_LOCKED = 402;
	//Trying to register a setting that already exists
	const E_SETTING_REGEXIST = 403;
	//Trying to remove a setting that doesn't exist
	const E_SETTING_REMEXIST = 404;
	//General Theme/template error
	const E_THEME = 500;
	//Template file does not exist
	const E_TEMPLATE_NOSUCH = 501;
	//General Language definition error
	const E_LANGUAGE = 600;
	//Language file does not exist
	const E_LANGUAGE_NOSUCH = 601;
	
	public static function printError($code,$text) {
		//TODO: Flesh this out
		echo "Error code $code\n$text";
	}

}
?>
