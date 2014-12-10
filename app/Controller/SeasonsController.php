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
		$this->Season->id = $id;
		$data['season'] = $this->Season->read();

		$overview = $this->Season->overview($this->Session->read('Account.id'));
		$data = array_merge($data, $overview);

		$this->set($data);
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
		if(!$this->Season->data) {
			$current = $this->Season->getCurrent($this->Session->read('Account.id'));
		}
		else {
			$current = $this->Season->data;
		}

		$this->Season->id = $current['Season']['id'];

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
	}

	function admin_rerank($seasonID) {
		$this->Season->id = $seasonID;
		$this->Season->data = $this->Season->read();

		$sql = "
		SELECT DISTINCT players.id, players.*
		FROM seasons_games
		JOIN games_players ON seasons_games.game_id=games_players.game_id
		LEFT JOIN players ON games_players.player_id=players.id
		WHERE season_id=6
		";
		$rawPlayers = $this->Season->query($sql);

		$temp = array();
		foreach($rawPlayers as $player) {
			$player['record'] = array('wins' => 0, 'loss' => 0);
			$temp[$player['players']['id']] = $player;
		}
		$rawPlayers = $temp;

		$sql = "
			SELECT *
			FROM seasons_games
			LEFT JOIN games ON games.id=seasons_games.game_id
			JOIN games_players ON games.id=games_players.game_id
			WHERE season_id={$seasonID}
		";
		$rawGames = $this->Season->query($sql);

		$games = array();
		foreach($rawGames as $game) {
			if(!isset($games[$game['games']['id']])) {
				$initial = $game['games'];
				$initial['games_players'] = array();

				$games[$game['games']['id']] = $initial;
			}

			$ref = $games[$game['games']['id']]['games_players'];

			$playerID = $game['games_players']['player_id'];

			$ref[] = array(
				'Player' => $rawPlayers[$playerID]['players'],
				'side' => $game['games_players']['side']
			);

			$games[$game['games']['id']]['games_players'] = $ref;
		}

		foreach($games as $game) {
			$this->trackGame($game['games_players'], $game['side_1_score'], $game['side_2_score']);
		}

		die;
	}

}
