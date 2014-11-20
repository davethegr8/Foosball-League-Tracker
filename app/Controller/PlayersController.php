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

	function edit($id) {
		$this->Player->id = $id;
		$this->Player->data = $this->Player->read();

		if (empty($this->data) == false) {
			$result = $this->Player->save($this->data);

			if (empty($result) == false) {
				$this->Session->setFlash('Player "'.$this->data["Player"]["name"].'" updated.');
				$this->redirect('/accounts/league');
				$this->exit();
			}
		}

		$viewData['player'] = $this->Player->data;

		$this->set($viewData);
	}

	function delete($id) {
		$this->Player->id = $id;
		$this->Player->data = $this->Player->read();

		$played = $this->Player->played();

		if($played != 0) {
			$this->Session->setFlash('Sorry, deleting players with games played is not possible. It mucks up rankings.');
			$this->redirect('/accounts/league');
			$this->exit();
		}

		$viewData['player'] = $this->Player->data;

		if (empty($this->data) == false) {

			$this->Player->set('account_id', $this->Player->data['Player']['account_id'] * -1);
			$result = $this->Player->save();

			if (empty($result) == false) {
				$this->Session->setFlash('Player "'.$result['Player']['name'].'" removed.');
				$this->redirect('/accounts/league');
				$this->exit();
			}
		}

		$this->set($viewData);
	}

	function vs($player1ID, $player2ID) {
		$player1 = $this->Player->find('first',
			array(
				'conditions' => array(
					'id' => $player1ID
				)
			)
		);
		$player1['win'] = 0;
		$player1['lose'] = 0;
		$player1['ranks'] = $this->Player->playerRanks($this->Session->read("Account.id"), $player1ID);

		$player2 = $this->Player->find('first',
			array(
				'conditions' => array(
					'id' => $player2ID
				)
			)
		);
		$player2['win'] = 0;
		$player2['lose'] = 0;
		$player2['ranks'] = $this->Player->playerRanks($this->Session->read("Account.id"), $player2ID);

		$games = $this->Player->combinedGames($this->Session->read("Account.id"),
			array($player1ID, $player2ID)
		);

		foreach($games as $id => $item) {
			if(in_array($player1['Player']['name'], $item['side_1_players'])) {
				if($item['side_1_score'] > $item['side_2_score']) {
					$player1['win']++;
				}
				if($item['side_1_score'] < $item['side_2_score']) {
					$player1['lose']++;
				}
			}

			if(in_array($player1['Player']['name'], $item['side_2_players'])) {
				if($item['side_1_score'] < $item['side_2_score']) {
					$player1['win']++;
				}
				if($item['side_1_score'] > $item['side_2_score']) {
					$player1['lose']++;
				}
			}

			if(in_array($player2['Player']['name'], $item['side_1_players'])) {
				if($item['side_1_score'] > $item['side_2_score']) {
					$player2['win']++;
				}
				if($item['side_1_score'] < $item['side_2_score']) {
					$player2['lose']++;
				}
			}

			if(in_array($player2['Player']['name'], $item['side_2_players'])) {
				if($item['side_1_score'] < $item['side_2_score']) {
					$player2['win']++;
				}
				if($item['side_1_score'] > $item['side_2_score']) {
					$player2['lose']++;
				}
			}
		}

		$data['player1'] = $player1;
		$data['player2'] = $player2;

		$data['games'] = $games;

		$this->set($data);
	}

	function admin_index() {
		$data['count'] = $this->Player->find('count');
		$data['players'] = $this->Player->find('all');

		$this->set($data);
	}
}
