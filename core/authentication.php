<?php
/* 
| Project Sol
| A ~Nolan (http://github.com/mcnolan) production.
|
| Attached Version : 2010/M1
| 
| File : authentication.php
| 
| Purpose : Basic authentication class, stores and authenticates user login data
| 
| 
| Sol is copyright Nolan 2010, see README for more details
|
*/

class Authentication {
	private $_sol;
	private $_userid = 0;
	private $_username;
	private $_email;
	private $_lastlogin;
	private $_permissions = array();

	//Check if User is authenticated
	public function loggedIn() {
		if($this->_userid != 0) {
			return true;
		}
		return false;
	}

	public function getUserid() {
		return $this->_userid;
	}
	public function getUsername() {
		return $this->_username;
	}
	public function getEmail() {
		return $this->_email;
	}
	public function getLastLogin() {
		return $this->_lastlogin;
	}
	public function __get($name) {
		$func = "get".ucfirst($name);
		if(method_exists($this,$func)) {
			$this->$func();
		}
	}
	
	private function loadPermissions() {
		if($this->loggedIn()) {
			$permquery = "SELECT PermissionName FROM ".BaseSetting::$pre."userrole as userrole LEFT JOIN ".BaseSetting::$pre."rolepermission as rolepermission ON(userrole.RoleId = rolepermission.RoleId) LEFT JOIN ".BaseSetting::$pre."permission as permission ON(permission.PermissionId = rolepermission.PermissionId) WHERE userrole.UserId = " . $this->getUserId;
			$permresult = $this->_sol->data->query($permquery);
			while($permission = $this->_sol->data->returnObject($permresult)) {
				$this->_permissions[] = $permission;
			}
		} else {
			throw new exception("Not logged in, cannot access permissions",701);
		}
	}

	public function hasPermission($permission) {
		if(sizeof($this->_permissions) > 0) {
			if(in_array($permission,$this->_permissions)) {
				return true;
			}
		}
		return false;
	}

	//Check details against the database
	private function processCredentials($username,$hash) {
		$challenge = $this->_sol->data->tableSelect("user", "UserName = '$username' AND Password = '$hash'");
		if(sizeof($challenge) == 1) {
			$this->_userid = $challenge[0]->UserId;
			$this->_username = $challenge[0]->UserName;
			$this->_email = $challenge[0]->UserEmail;
			$this->_lastlogin = $challenge[0]->UserLastLogin;
			$this->loadPermissions();
			return true;
		}
		return false;
	}
	//Write login details to appropriate storage
	private function setCredentials() {
		$_COOKIE[BaseSetting::$rand] = $username."|".$password;
	}
	//Clear details from object and destroy creds
	private function removeCredentials() {
		$_COOKIE[BaseSetting::$rand] = "";
	}
	//Login function to be called by foreign classes
	public function login($username, $password) {
		return (this->processCredentials($username, md5($hash)));
	}

	public function logout() {
		$this->removeCredentials();
	}

	public function __construct(Sol $sol) {
		$this->_sol = $sol;
		if(isset($_COOKIE[BaseSetting::$rand])) {
			$split = split("|", $_COOKIE[BaseSetting::$rand]);
			$this->processCredentials($split[0], $split[1]);
		}
	}
}
?>
