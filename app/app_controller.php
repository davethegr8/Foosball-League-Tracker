<?php
 
class AppController extends Controller {
	
	var $helpers = array('Form', 'Html', 'Javascript', 'Stats');
	
	function beforeRender() {
		if($this->params['prefix'] == 'admin') {
			$this->layout = 'admin';  
		}  
	}
	
	function __validateLoginStatus() {
		$controller = $this->params["controller"];
		$public = array('login', 'logout', 'signup');
		
		if($controller != 'accounts' && !in_array($this->action, $public)) {
			if($this->Session->check("Account") == false) {
				$this->Session->setFlash("You must login to view that page.");
				$this->redirect('/accounts/login');
				$this->exit();
			}
		}
	}
	
	function __validateAdminLogin() {
		$controller = $this->params["controller"];
		$public = array('admin_login', 'admin_logout');
		
		if(isset($this->params['admin']) && $this->params['admin'] == 1 && $controller != 'users') {
			if($this->Session->check("User") == false) {
				$this->Session->setFlash("You must login to view that page.");
				$this->redirect('/admin/users/login');
				$this->exit();
			}
		}
		elseif($controller == 'users' && !in_array($this->action, $public)) {
			if($this->Session->check("User") == false) {
				$this->Session->setFlash("You must login to view that page.");
				$this->redirect('/admin/users/login');
				$this->exit();
			}
		}
		
		return true;
	}
}

?>
