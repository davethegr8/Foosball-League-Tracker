<?php

class UsersController extends AppController {
	
	var $name = 'Users';
	
	function beforeFilter() {
		$this->__validateAdminLogin();
	}
	
	function index() {
		$this->redirect('/admin/users');
	}
	
	function admin_login() {
		if(empty($this->data) == false) {
			//check login
			$user = $this->User->validateLogin($this->data["User"]);
			
			if(empty($user) == false) {
				$this->Session->write("User", $user);
				$this->Session->setFlash("Admin login successful.");
				$this->redirect('index');
				exit();
			}
			else {
				$this->Session->setFlash("Invalid login.");
				unset($this->data);
			}
		}
	}
	
	function admin_logout() {
		$this->Session->destroy('User');
        $this->Session->setFlash("Admin logout successful.");
        $this->redirect('login');
	}
	
	function admin_index() {
		
	}
	
	
}

?>