<?
include "../core/sol.php";
include "../basesetting.php";
include "../core/solsetting.php";
include "../core/data.php";
include "../module/module.php";

$sol = new Sol();

class one extends AModule {

	function install() {
		echo "module one install\n";
	}
	function main() {
		//sample output data
		echo "Hello World, this is " . $this->getName();
	}
	function boo() {
		$this->runHooks("view","Error");
	}

}

class two extends AModule {

	function install() {
		echo "module two install\n";
	}
	function main() {}
	function oneView($data) { echo "this is module two custom data = '$data'<br>"; }
	function checkOtherModule() {
		return $this->getModule("three")->printOut();
	}	
}

class three extends AModule {
	function install() {
		echo "module three install\n";
	}
	function main() {}
	function oneView() { echo "this is module three<br>"; }
	function printOut() { return "Test result : Success"; }
}

class four {
	function install() {
		echo "module four install\n";
	}
}

echo "<p>Testing Sol Modules</p>";

echo "+ Installing sample modules<br>";
try {
	$sol->module->install(new one($sol));
	$sol->module->install(new two($sol));
	$sol->module->install(new three($sol));
} catch (Exception $e) {
	echo "<Modules already exist>";
}
echo "+ Attempting to install illegal module<br> Test result : ";
try {
	$sol->module->install(new four());
	echo "Fail";
} catch(Exception $e) {
	echo "Success";
}
echo "<p>= Checking module output</p>";
$sol->module->one->main();

echo "<p>= Testing hooks</p>";
$sol->module->one->boo();

echo "<p>= Testing backwards referencing</p>";

echo $sol->module->two->checkOtherModule();


?>
