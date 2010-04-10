<?php
include "../core/sol.php";
include "../basesetting.php";
include "../core/solsetting.php";
include "../core/data.php";

echo "Testing Sol settings class.";
$sol = new Sol();
echo "<p>+ Object constructed</p>";
echo "<p>= Setting 'kittens' to 'furry'<br>";
$sol->setting->kittens = "furry";
echo "+ Output \$sol->setting->kittens</p>";
echo $sol->setting->kittens;
echo "<p>Test result : ";
if($sol->setting->kittens == "furry") {
	echo "Success";
} else {
	echo "Fail";
}

echo "</p><p>= Getting data access type 'Mysql' </p>Test result : ";

if($sol->setting->getDatabaseType() == "Mysql") {
	echo "Success";
} else {
	echo "Fail";
}

echo "<p>= Checking kittens cannot be saved as global</p>Test result : ";
try {
	$sol->setting->registerGlobalSetting('kittens','fluffy');
	echo "Fail";
} catch (Exception $e) {
	echo "Success";
}

echo "<p>= Remove kittens variable</p>Test result : ";
$sol->setting->unregisterSetting('kittens');
try {
	$k = $sol->setting->kittens;
	echo "Fail";
} catch (Exception $e) {
	echo "Success";
}

echo "<p>= Checking for existing puppies</p>";
if($sol->setting->exists('puppies')) {
	echo "Puppies found, removing";
	$sol->setting->unregisterSetting('puppies');
}

echo "<p>= Saving 'puppies' as permanent variable<br>";
$sol->setting->registerGlobalSetting('puppies','cute',true);

echo "+ Sol object destroyed<br>";
unset($sol);
echo "+ New Sol object constructed<br>";
$sol = new Sol();

echo "+ Output \$sol->settings->puppies</p>Test result : ";
if($sol->setting->puppies == "cute") {
	echo "Success";
} else {
	echo "Fail";
}

echo "<p>= Check locked puppies variable cannot be changed</p> Test result : ";
try {
	$sol->setting->puppies = "ugly";
	echo "Fail";
} catch (Exception $e) {
	echo "Success";
}
?>
