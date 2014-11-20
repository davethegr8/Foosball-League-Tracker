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
		if (empty($this->data) == false) {
			$this->Season->archive(array(
				array('Season.account_id' => $this->Session->read('Account.id')),
				array('Season.status' => 'active')
			));


			$data = $this->data['Season'];
			$data['status'] = 'active';
			$data['account_id'] = $this->Session->read('Account.id');

			$result = $this->Season->save($data);

			if (empty($result) == false) {
				$this->Session->setFlash('Season "'.$this->data["Season"]["name"].'" created.');
				$this->redirect('/seasons');
				$this->exit();
			}
		}
	}

	function edit($id) {

	}

}
