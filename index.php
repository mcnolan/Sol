<?php
/* 
| Project Sol
| A ~Nolan (http://github.com/mcnolan) production.
|
| Attached Version : 2010/M1
| 
| File : index.php
| 
| Purpose : Provide entry point into the program. Loads all required library files and provides the autoload mechanism for extra modules
| 
| 
| Sol is copyright Nolan 2010, see README for more details
|
*/

require_once("basesetting.php");
require_once("core/solsetting.php");

//Makes the assumption that the class name is also the name of the containing folder
function __autoload($classname) {
	$classname = ucfirst($classname);
	$classfile = SolSetting::MODULE_PATH.$classname."/".$classname.".php";
	if(file_exists($classfile)) {
		require_once($classfile);
	} else {
		throw new exception("Could not load requested module", 304);
	}
}

//Load prereq classes
require_once("core/sol.php");
require_once("core/data.php");
require_once("core/error.php");
require_once("module/module.php");
require_once("theme/language.php");
require_once("theme/theme.php");
require_once("core/authentication.php");

//Start the ball rolling
$sol = new Sol();
?>
