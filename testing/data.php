<?

include "../core/sol.php";
include "../basesetting.php";
include "../core/solsetting.php";
include "../core/data.php";

echo "Testing Sol data class";
$sol = new Sol();
echo "<p>= Testing General Connection : 'SHOW TABLES'</p>";

$result = $sol->data->query("SHOW TABLES");
while($table = $sol->data->resultArray($result)) {
	echo $table[0];
}
echo "<br>" . $sol->data->resultCount($result) . " table(s) in database";

echo "<p>Test Result : ";
if($sol->data->resultCount($result) > 0) {
	echo "Success";
} else {
	echo "Fail";
}

echo "</p><p>= Checking Insert/Update/Delete using base functions</p>";
echo "+ Inserting 4 setting Rows<br>";
$ids = array();
for($i=0;$i<=4;$i++) {
	$ids[] = $sol->data->queryReturnLastId("INSERT INTO sol_setting(SettingName, SettingValue) VALUES('i".$i."','t')");
}

echo "Identification Numbers : <pre>";
var_dump($ids);

echo "</pre>+ Updating last inserted row<p>Test result : ";
$update = "UPDATE sol_setting SET SettingValue = 'Hello World' WHERE SettingId = '".$ids[(sizeof($ids)-1)]."'";
$sol->data->query($update);

$result2 = $sol->data->query("SELECT SettingValue FROM sol_setting WHERE SettingId = '".$ids[(sizeof($ids)-1)]."'");

$check = $sol->data->resultArray($result2);

if($check["SettingValue"] == "Hello World") {
	echo "Success";
} else {
	echo "Fail";
}

echo "</p>+ Deleting rows<p>Test result : ";
$sol->data->query("DELETE FROM sol_setting");
$count = $sol->data->resultCount($sol->data->query("SELECT * FROM sol_setting"));
if($count == 0) {
	echo "Success";
} else {
	echo "Fail";
}

echo "</p><p>= Checking generic functions</p>";

echo "<p>+ Checking tableInsert + rowCount</p>";

$newrow[] = array('SettingName' => 'Test Setting One', 'SettingValue' => 'dfkjsdhfkjsdhkjsdh', 'SettingLocked' => 0);
$sol->data->tableInsert('setting',$newrow);
$id = $sol->data->insertRow('setting',$newrow[0]);

echo "+ Checking tableSelect...<pre>";

var_dump($sol->data->tableSelect("setting","","SettingId DESC"));
echo "</pre>";

echo "<p>+ Checking tableUpdate</p>Test Result : ";

$changes[] = array('SettingName' => 'Bonk', 'SettingValue' => 'This is a setting');
$sol->data->tableUpdate('setting',$changes,'SettingId = '.$id);

$checkupd = $sol->data->tableSelect("setting","SettingId = ".$id);

//var_dump($checkupd);

if($checkupd[0]->SettingName == "Bonk") {
	echo "Success";
} else {
	echo "Fail";
}

echo "<p>+ Checking rowCount</p>Test Result : ";
$rows = $sol->data->rowCount('setting');
if($rows == 2) {
	echo "Success";
} else {
	echo "Fail";
}
?>
