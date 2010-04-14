<?php
require_once("basesetting.php");
require_once("core/solsetting.php");

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

//Start the ball rolling
$sol = new Sol();
?>
