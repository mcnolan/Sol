<?
/* 
| Project Sol
| A ~Nolan (http://github.com/mcnolan) production.
|
| Attached Version : 2010/M2
| 
| File : News/News.php
| 
| Purpose :
| 
| 
| Sol is copyright Nolan 2010, see README for more details
|
*/
class News extends AModule {

	private function archiveAction() {}
	private function submitAction() {}
	private function moderateAction() {}
	private function defaultAction() {}

	public function install() {}
	public function main() {
		if(!empty($this->args(1))) {
			$func = strtolower($this->args(1)) . "Action";
			if(function_exists($func,$this) {
				$this->$func();
			} 
		}
		$this->defaultAction();
	}
}

?>
