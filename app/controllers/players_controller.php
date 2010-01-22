<?php
class PlayersController extends AppController {
	function beforeFilter() {
		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin' && $this->params['admin'] == 1) {
			$this->__validateAdminLogin();
		} else {
			$this->__validateLoginStatus();
		}
	}

	function view($id) {
		$this->Player->id = $id;
		$this->Player->data = $this->Player->read();

		$this->data = $this->Player->data;
		$this->data["Record"] = $this->Player->playerStats($this->Session->read("Account.id"), $id);
		$this->data["Games"] = $this->Player->playerGames($this->Session->read("Account.id"), $id);
		$this->data["Ranks"] = $this->Player->playerRanks($this->Session->read("Account.id"), $id);

		$this->set($this->data);
	}

	function add() {
		if (empty($this->data) == false) {
			$this->data["Player"]["rank"] = 1000;
			$this->data["Player"]["account_id"] = $this->Session->read("Account.id");

			$result = $this->Player->save($this->data["Player"]);

			if (empty($result) == false) {
				$this->Session->setFlash('Player "'.$this->data["Player"]["name"].'" created.');
				$this->redirect(array('players', ''));
				$this->exit();
			}
		}
	}

	function admin_index() {
		$data['count'] = $this->Player->find('count');
		$data['players'] = $this->Player->find('all');

		$this->set($data);
	}
}
