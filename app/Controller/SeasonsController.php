<?php
class SeasonsController extends AppController {
	function beforeFilter() {
		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin' && $this->params['admin'] == 1) {
			$this->__validateAdminLogin();
		} else {
			$this->__validateLoginStatus();
		}
	}

	function index() {
		$seasons = $this->Season->findAll($this->Session->read('Account.id'));

		$data['seasons'] = $seasons;

		$this->set($data);
	}

	function view($id) {

	}

	function add() {

	}

	function edit($id) {

	}

}
