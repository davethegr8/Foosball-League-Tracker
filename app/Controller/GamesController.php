<?php
class GamesController extends AppController {
	public $uses = array('Game', 'Player');
	public $components = array('Elo', 'FoosRank');

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
				'account_id' => $this->Session->read("Account.id")
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

	function view($id) {
		//print_R($this->Game->read(null, $id));
	}

	function __saveGame($data) {
		$game_players = array();

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

		$game['side1']['score'] = $data['Game']['side_1_score'];
		$game['side2']['score'] = $data['Game']['side_2_score'];

		$this->trackRank($game_players, $data['Game']['side_1_score'], $data['Game']['side_2_score']);

		$ranking = $this->FoosRank->rank($game_players, $data['Game']['side_1_score'], $data['Game']['side_2_score']);

		//update player ranks
		foreach ($ranking as $player) {
			$this->Player->changeRank($player["Player"]["id"], $player["Player"]["rank"], "rank");

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

	function admin_index() {
		$data['count'] = $this->Game->find('count');

		$sql = "SELECT CAST(created as date) as `date`, COUNT(*) AS num FROM games GROUP BY `date`";
		$daily = $this->Game->query($sql);

		$endpoints = array('min' => '', 'max' => '');

		foreach ($daily as $day) {
			$day = $day[0];

			if ($day["date"] < $endpoints['min'] || $endpoints['min'] == '') {
				$endpoints['min'] = $day["date"];
			}

			if ($day["date"] > $endpoints['max'] || $endpoints['max'] == '') {
				$endpoints['max'] = $day["date"];
			}

			$temp[$day["date"]] = $day["num"];
		}

		$daily = $temp;

		$date = $endpoints['min'];
		$end = $endpoints['max'];

		while ($date <= $end) {
			if (isset($daily[$date])) {
				$range[(strtotime($date))] = $daily[$date];
			} else {
				$range[(strtotime($date))] = 0;
			}

			$date = date("Y-m-d", strtotime($date." +1 day"));
		}

		$data["range"] = $range;

		$this->set($data);
	}
}
