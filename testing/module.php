<pre>
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
		var_dump($this->data()->tableSelect('setting'));
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
	function oneView($data) { echo "this is module two\n$data\n"; }
}

class three extends AModule {
	function install() {
		echo "module three install\n";
	}
	function main() {}
	function oneView() { echo "this is module three\n"; }
}

class four {
	function install() {
		echo "module four install\n";
	}
}

echo "<p>Testing Sol Modules</p>";

echo "<p>Installing sample modules</p>";
try {
	$sol->module->install(new one($sol));
	$sol->module->install(new two($sol));
	$sol->module->install(new three($sol));
} catch (Exception $e) {

}
try {
	$sol->module->install(new four());
} catch(Exception $e) {
	echo "Did not install four\n";
}
echo "<p>Checking one output/checking backwards referencing</p>";
$sol->module->one->main();

echo "<p>Testing hooks</p>";
$sol->module->one->boo();


?>
</pre>
