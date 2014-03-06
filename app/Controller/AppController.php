<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	var $helpers = array('Form', 'Html', 'Js', 'Stats', 'Session');

	function beforeRender() {
		if (isset($this->params['admin']) && $this->params['admin']) {
			$this->layout = 'admin';
		}
	}

	function __validateLoginStatus() {
		$controller = $this->params["controller"];
		$public = array('login', 'logout', 'signup');

		if ($controller != 'accounts' && !in_array($this->action, $public)) {
			if ($this->Session->check("Account") == false) {
				$this->Session->setFlash("You must login to view that page.");
				$this->redirect('/accounts/login');
				$this->exit();
			}
		}
	}

	function __validateAdminLogin() {
		$controller = $this->params["controller"];
		$public = array('admin_login', 'admin_logout');

		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin' && $controller != 'users') {
			if ($this->Session->check("User") == false) {
				$this->Session->setFlash("You must login to view that page.");
				$this->redirect('/admin/users/login');
				$this->exit();
			}
		} elseif ($controller == 'users' && !in_array($this->action, $public)) {
			if ($this->Session->check("User") == false) {
				$this->Session->setFlash("You must login to view that page.");
				$this->redirect('/admin/users/login');
				$this->exit();
			}
		}

		return true;
	}
}

