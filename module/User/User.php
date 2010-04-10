<?php

class User extends AModule {

	private function rolesAction() {}
	private function moderateAction() {}
	private function loginAction() {}
	private function registerAction() {}
	private function userAction($userid) {}
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
				$this->userAction($this->args(1));
			} else {
				$this->defaultAction();
			}
		}

	}

}
?>
