<?php

class User extends AppModel {
	
	var $name = 'User';
	
	//setup validation rules
	var $validate = array();
	
	function validateLogin($data) {
		$result = $this->find( array('username' => $data["username"], 'password' => md5($data["password"])));
		
		if(empty($result) == false) {
			return $result;
		}
		else {
			return false;
		}
		
	}
	
}

?>