<?php
include "../core/sol.php";
include "../basesetting.php";
include "../core/solsetting.php";

echo "Testing Sol settings class.";
$sol = new Sol();
echo "<p>+ Object constructed</p>";
echo "<p>= Setting 'kittens' to 'furry'<br>";
$sol->setting()->kittens = "furry";
echo "+ Output \$sol->settings()->kittens</p>";
echo $sol->setting()->kittens;
echo "<p>Test result : ";
if($sol->setting()->kittens == "furry") {
	echo "Success";
} else {
	echo "Fail";
}

echo "</p><p>= Getting data access type 'Mysql' </p>Test result : ";

if($sol->setting()->getDatabaseType() == "Mysql") {
	echo "Success";
} else {
	echo "Fail";
}

echo "<p>= Saving 'kittens' as permanent variable<br>";

echo "+ Sol object destroyed<br>";
echo "+ New Sol object constructed<br>";
echo "+ Output \$sol->settings()->kittens</p>";

echo "<p>Test result : ";

	echo "Success";

	echo "Fail";

?>
