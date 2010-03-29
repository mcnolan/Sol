<?php
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
	//General setting error
	const E_SETTING = 400;
	//Trying to get a setting that doesn't exist
	const E_SETTING_NOSUCH = 401;
	
	public static function printError($code,$text) {
		echo "Error code $code\n$text";
	}

}
?>
