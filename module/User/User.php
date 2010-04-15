<?php
/* 
| Project Sol
| A ~Nolan (http://github.com/mcnolan) production.
|
| Attached Version : 2010/M2
| 
| File : User/User.php
| 
| Purpose :
| 
| 
| Sol is copyright Nolan 2010, see README for more details
|
*/
class User extends AModule {

	private function rolesAction() {}
	private function moderateAction() {}
	private function loginAction() {}
	private function registerAction() {}
	private function userAction() {}
	private function controlAction() {}
	private function defaultAction() {}

	public function install() {

	}

	public function main() {
		//if supplied with an action, use it. Otherwise, pick off query arguments
		$func = strtolower($this->args(1)) + "Action";
		if(method_exists($this,$func)) {
			$this->$func();
		} else {
			if(is_int($this->args(1))) {
				//If a userid has been passed, go to that users page equiv of /User/User/<1234>
				$this->userAction();
			} else {
				$this->defaultAction();
			}
		}

	}

}
?>
