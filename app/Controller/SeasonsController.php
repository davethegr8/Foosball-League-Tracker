<?php
class SeasonsController extends AppController {
	public $uses = array('Game', 'Player', 'Season');
	public $components = array('Elo', 'FoosRank', 'Aggregate');

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
		$this->Season->id = $id;
		$this->Season->data = $this->Season->read();

		if (empty($this->data) == false) {
			$result = $this->Season->save($this->data);

			if (empty($result) == false) {
				$this->Session->setFlash('Season "'.$this->data["Season"]["name"].'" updated.');
				$this->redirect('/seasons');
				$this->exit();
			}
		}

		$viewData['season'] = $this->Season->data;

		$this->set($viewData);
	}

	function addGame() {
		$gameData = $this->params['Game'];

		// get current season
		$current = $this->Season->find('first', array(
			'conditions' => array(
				'Season.account_id' => $this->Session->read('Account.id'),
				'Season.status' => 'active'
			)
		));

		print_R($current);

		$this->Season->id = $current['Season']['id'];

		$sql = "INSERT INTO seasons_games (season_id, game_id) VALUES (
			'".intval($this->Season->id)."',
			'".intval($gameData['id'])."'
		)";
		$this->Season->query($sql);

		$result = $this->Season->save(array(
			'games_played' => $current['Season']['games_played'] + 1
		));

		echo '<pre>';
		print_R($gameData);
		echo '</pre>';
	}

}
