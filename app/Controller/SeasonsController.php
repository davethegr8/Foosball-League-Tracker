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
		$current = $this->Season->getCurrent($this->Session->read('Account.id'));

		$this->Season->id = $current['Season']['id'];

		$result = $this->Season->addGame($gameData);
	}

	function trackGame($game_players, $side1Score, $side2Score) {
		$gameData = $this->params['Game'];

		$current = $this->Season->getCurrent($this->Session->read('Account.id'));
		$this->Season->id = $current['Season']['id'];

		debug($game_players);

		$season_players = array();

		$rank_fields = array(
			'rank', 'foos_rank', 'foos_performance_rank', 'elo_rank'
		);

		foreach($game_players as $id => $player) {
			// Find their data in seasons_ranks
			$season_players[$id] = $this->Season->getPlayer($this->Season->id, $player['Player']['id']);

			foreach($rank_fields as $ranking) {
				$game_players[$id]['Player'][$ranking] = $season_players[$id]['seasons_ranks'][$ranking];
			}
		}

		// calculate the 4 ranks
		$game_players = $this->requestAction('/games/rankGame', array(
			'pass' => array($game_players, $side1Score, $side2Score)
		));

		foreach($game_players as $id => $player) {
			foreach($rank_fields as $ranking) {
				$season_players[$id]['seasons_ranks'][$ranking] = $game_players[$id]['Player'][$ranking];
			}
		}

		// update seasons_ranks table
		foreach($season_players as $player) {
			$this->Season->updateRanking($player);
		}

		die;
	}

}
