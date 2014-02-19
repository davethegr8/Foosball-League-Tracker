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

		$viewData['player'] = $this->Player->data;

		$viewData["Record"] = $this->Player->playerStats($this->Session->read("Account.id"), $id);
		$viewData["Games"] = $this->Player->playerGames($this->Session->read("Account.id"), $id);
		$viewData["Ranks"] = $this->Player->playerRanks($this->Session->read("Account.id"), $id);

		$this->set($viewData);
	}

	function add() {
		if (empty($this->data) == false) {
			$data = $this->data['Player'];
			$data["rank"] = 1000;
			$data["account_id"] = $this->Session->read("Account.id");

			$result = $this->Player->save($data);

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
