<?php
class User extends AppModel {
	function validateLogin($data) {
		$result = $this->find('first', array(
			'conditions' => array(
				'username' => $data["username"],
				'password' => md5($data["password"])
			)
		));

		if (empty($result) == false) {
			return $result;
		} else {
			return false;
		}
	}
}
