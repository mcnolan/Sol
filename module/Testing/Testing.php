<?

class Testing extends AModule{

	function test() {
		echo "This testing file has been loaded<br><br>";
	}
	
	function templateTest() {
		echo $this->language("Hello");
	}

	function install() {
	
	}
	
	function main() {
	
	}

}

?>
