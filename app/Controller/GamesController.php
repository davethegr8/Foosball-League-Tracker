<?php
class GamesController extends AppController {
	public $uses = array('Game', 'Player', 'Season');
	public $components = array('Elo', 'FoosRank', 'Aggregate', 'RequestHandler');

	function beforeFilter() {
		if(isset($this->params['admin']) && $this->params['admin']) {
			$this->__validateAdminLogin();
		} else {
			$this->__validateLoginStatus();
		}
	}

	function index() {
		$games = array();
		$temp = $this->Game->findAll($this->Session->read('Account.id'));

		//have to process the game data since the query isn't perfect
		foreach ($temp as $row) {
			$gameID = $row["games"]["id"];

			$games[$gameID]["side_1_score"] = $row["games"]["side_1_score"];
			$games[$gameID]["side_2_score"] = $row["games"]["side_2_score"];

			$sideNum = $row["games_players"]["side"];

			$games[$gameID]["side_".$sideNum."_players"][$row["players"]["id"]] = $row["players"]["name"];
		}

		$data["games"] = $games;

		$this->set($data);
	}

	function add() {
		$data["players"][""] = " - ";
		$players = $this->Player->find('all', array(
			'conditions' => array(
				'account_id' => $this->Session->read("Account.id"),
				'status' => 'active'
			),
			'order' => 'name ASC'
		));

		foreach ($players as $player) {
			$data["players"][$player["Player"]["id"]] = $player["Player"]["name"];
		}

		$this->set($data);
	}

	function create() {
		$data = array();
		$data["Game"] = $this->data;
		$data["Game"]["account_id"] = $this->Session->read("Account.id");

		if (empty($this->data) == false) {
			$result = $this->__saveGame($data);

			if (empty($result) == false) {

				$action = $this->requestAction('/seasons/addgame', $result);

				// update rank tracking in seasons_ranks

				print_R($result); die;

				$this->Session->setFlash('Game Added. '.$this->message);
				$this->redirect('add');
				exit();
			} else {
				$this->Session->setFlash('An error occurred. Please try again.');
				$this->redirect('add');
				exit();
			}
		} else {
			$this->redirect('add');
			exit();
		}
	}

	function mass() {
		// adds a whole bunch of games.
		$games = json_decode($this->data['gamedata'], true);
		$added = 0;

		foreach($games as $game) {
			$formatted = array();

			$formatted['Game']['side_1_score'] = $game['side1']['score'];
			$formatted['Game']['side_2_score'] = $game['side2']['score'];

			$formatted['Game']['side[1'] = array();
			foreach($game['side1']['players'] as $player) {
				$formatted['Game']['side[1'][] = $player['id'];
			}

			$formatted['Game']['side[2'] = array();
			foreach($game['side2']['players'] as $player) {
				$formatted['Game']['side[2'][] = $player['id'];
			}

			$formatted['Game']['account_id'] = $this->Session->read('Account.id');

			$result = $this->__saveGame($formatted);
			if($result) {
				$added++;
			}
		}

		if ($added > 0) {
			$this->Session->setFlash('Games Added: '.$added);
			$this->redirect('/games/index');
			exit();
		} else {
			$this->Session->setFlash('An error occurred. Please try again.');
			$this->redirect('/games/index');
			exit();
		}
	}

	function view($id) {
		$this->Game->id = $id;

		// validate game exists and we are allowed to view
		$this->data = $this->Game->read();

		$accountID = $this->Session->read('Account.id');

		if(!$accountID || $accountID != $this->data['Game']['account_id']) {
			$this->Session->setFlash('Error: the game {id: '.intval($id).'} does not exist.');
			$this->redirect('/games/index');
		}

		$viewData = array();
		$viewData['Game'] = $this->data['Game'];

		$sql = "SELECT *
				FROM games_players
				LEFT JOIN players on games_players.player_id=players.id
				LEFT JOIN rank_track
					ON rank_track.games_id=games_players.game_id
					AND rank_track.players_id=players.id
				WHERE game_id='".intval($this->data['Game']['id'])."'";
		$players = $this->Game->query($sql);

		$viewData['Players'] = $players;

		$this->set($viewData);
	}

	function delete($id) {
		$this->Game->id = $id;

		// validate game exists and we are allowed to view
		$this->data = $this->Game->read();

		$accountID = $this->Session->read('Account.id');

		if(!$accountID || $accountID != $this->data['Game']['account_id']) {
			$this->Session->setFlash('Error: the game {id: '.intval($id).'} does not exist.');
			$this->redirect('/games/index');
		}

		$viewData = array();
		$viewData['Game'] = $this->data['Game'];

		$sql = "SELECT *
				FROM games_players
				LEFT JOIN players on games_players.player_id=players.id
				LEFT JOIN rank_track
					ON rank_track.games_id=games_players.game_id
					AND rank_track.players_id=players.id
				WHERE game_id='".intval($this->data['Game']['id'])."'";
		$players = $this->Game->query($sql);

		$viewData['Players'] = $players;

		$this->set($viewData);
	}

	function remove($id) {
		$this->Game->id = $id;

		// validate game exists and we are allowed to view
		$this->data = $this->Game->read();

		$accountID = $this->Session->read('Account.id');

		if(!$accountID || $accountID != $this->data['Game']['account_id']) {
			$this->Session->setFlash('Error: the game {id: '.intval($id).'} does not exist.');
			$this->redirect('/games/index');
		}

		$gameID = $this->data['Game']['id'];

		$sql = "DELETE FROM rank_track WHERE games_id='".intval($gameID)."'";
		$this->Game->query($sql);

		$sql = "DELETE FROM games_players WHERE game_id='".intval($gameID)."'";
		$this->Game->query($sql);

		$this->Game->delete($gameID);

		$this->Session->setFlash('Game removed.');
		$this->redirect('/games/index');
	}

	function __saveGame($data) {
		$game_players = array();
		$this->Game->id = null;

		//put into game
		$sides = array();
		$sides[1] = $data["Game"]["side[1"]; //this is odd.
		$sides[2] = $data["Game"]["side[2"]; //this is odd.

		//put into game player
		foreach ($sides as $side => $person) {
			foreach ($person as $id) {
				//make sure the insert array is empty
				$insert = array();

				if (empty($id) == false) {
					//insert into table
					$insert["player_id"] = $id;
					$insert["side"] = $side;

					$data["Players"][$id] = $insert;

					$game_players[$id] = $this->Player->read(null, $id);
					$game_players[$id]['side'] = $side;
				}
			}
		}

		$result = $this->Game->save($data['Game']);

		foreach ($data["Players"] as $player) {
			$this->Game->savePlayer($player);
		}

		$this->trackRank($game_players, $data['Game']['side_1_score'], $data['Game']['side_2_score']);

		$game_players = $this->rankGame($game_players, $data['Game']['side_1_score'], $data['Game']['side_2_score']);

		//update player ranks
		foreach ($game_players as $player) {
			$this->Player->changeRank($player);

			$this->message .= $player['Player']['name'].': '.$player['diff'].', ';
		}

		$this->message = trim($this->message, ", ");

		return $result;
	}

	function trackRank($players, $side1_score, $side2_score) {
		$side_players = array(1 => array(), 2 => array());

		foreach($players as $player) {
			$side_players[$player['side']][] = $player['Player']['name'];
		}

		$notes = implode(", ", $side_players[1]).': '.$side1_score.'; ';
		$notes .= implode(", ", $side_players[2]).': '.$side2_score;

		foreach ($players as $player) {
			$insert = array(
				"players_id" => $player["Player"]["id"],
				"games_id" => $this->Game->id,
				"rank" => $player["Player"]["rank"],
				"foos_rank" => $player["Player"]["foos_rank"],
				"foos_performance_rank" => $player["Player"]["foos_performance_rank"],
				"elo_rank" => $player["Player"]["elo_rank"],
				"notes" => $notes
			);

			//transform to mysqlsafe
			foreach ($insert as $key => $value) {
				$insert[$key] = "'".addslashes($value)."'";
			}

			//insert into rank tracking

			$sql = "INSERT INTO rank_track(".implode(', ', array_keys($insert)).") VALUES (".implode(", ", array_values($insert)).")";
			$this->Game->query($sql);
		}

		return $notes;
	}

	function rankGame($players, $side1_score, $side2_score) {
		$players = $this->FoosRank->rank($players, $side1_score, $side2_score, array(
			// 'participation_points' => 0,
		));

		$players = $this->FoosRank->rank(
			$players,
			$side1_score,
			$side2_score,
			array(
				'field' => 'foos_performance_rank',
				'participation_points' => 0,
				// 'goal_diff_multiplier' => 10,
				// 'win_points' => 0,
				// 'win_min_points' => null,
			)
		);
		$players = $this->Elo->rank($players, $side1_score, $side2_score);

		$players = $this->Aggregate->rank($players);

		return $players;
	}

	function admin_index() {
		$data['count'] = $this->Game->find('count');

		$this->set($data);
	}

	function admin_rerank() {
		header('Content-type: text/plain');

		if(isset($_GET['reset'])) {
			$reset = array(
				'UPDATE players SET rank=1000, foos_rank=1000, foos_performance_rank=1000, elo_rank=1000;',
				'UPDATE rank_track SET rank=NULL WHERE rank > 0;',
				'UPDATE rank_track SET foos_rank=NULL WHERE foos_rank > 0;',
				'UPDATE rank_track SET foos_performance_rank=NULL WHERE foos_performance_rank > 0;',
				'UPDATE rank_track SET elo_rank=NULL WHERE elo_rank > 0;'
			);

			foreach ($reset as $sql) {
				$this->Game->query($sql);
			}
		}

		$sql = "SELECT DISTINCT(games_id)
				FROM rank_track
				WHERE foos_rank IS NULL AND games_id>0
				ORDER BY games_id ASC";

		$games = $this->Game->query($sql);

		foreach($games as $game) {
			$sql = "SELECT *
					FROM rank_track
					LEFT JOIN games Game ON games_id=Game.id
					LEFT JOIN players Player ON players_id=Player.id
					LEFT JOIN games_players ON games_players.game_id=games_id AND games_players.player_id=players_id
					WHERE games_id={$game['rank_track']['games_id']}
					";
			$game_data = $this->Game->query($sql);

			$players = array();
			$side1_score = 0;
			$side2_score = 0;

			foreach($game_data as $row) {
				$players[] = array(
					'Player' => $row['Player'],
					'side' => $row['games_players']['side'],
					'rank_track_id' => $row['rank_track']['id']
				);

				$side1_score = $row['Game']['side_1_score'];
				$side2_score = $row['Game']['side_2_score'];
			}

			foreach($players as $player) {
				$sql = "UPDATE rank_track
						SET
							foos_rank={$player['Player']['foos_rank']},
							foos_performance_rank={$player['Player']['foos_performance_rank']},
							elo_rank={$player['Player']['elo_rank']},
							rank={$player['Player']['rank']}
						WHERE id={$player['rank_track_id']}
						";
				$this->Game->query($sql);
			}

			$players = $this->rankGame($players, $side1_score, $side2_score);

			foreach($players as $player) {
				$sql = "UPDATE players
						SET
							foos_rank={$player['Player']['foos_rank']},
							foos_performance_rank={$player['Player']['foos_performance_rank']},
							elo_rank={$player['Player']['elo_rank']},
							rank={$player['Player']['rank']}
						WHERE id={$player['Player']['id']}";
				$this->Game->query($sql);
			}

			echo "done\n";
			print_r($players);
		}



		die;
	}
}
