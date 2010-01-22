<?php
class GamesController extends AppController {
	var $uses = array('Game', 'Player');

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
		$players = $this->Player->findAll(array('account_id' => $this->Session->read("Account.id")));

		foreach ($players as $player) {
			$data["players"][$player["Player"]["id"]] = $player["Player"]["name"];
		}

		$this->set($data);
	}

	function create() {
		$this->data["Game"]["account_id"] = $this->Session->read("Account.id");

		if (empty($this->data) == false) {
			$result = $this->__saveGame();

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

	function __saveGame() {
		//put into game

		$sides = array();
		$sides[1] = $this->data["Game"]["side[1"]; //this is odd.
		$sides[2] = $this->data["Game"]["side[2"]; //this is odd.

		$game_players = array();

		//put into game player
		foreach ($sides as $side => $players) {
			foreach ($players as $player) {
				//make sure the insert array is empty
				$insert = array();

				if (empty($player) == false) {
					//insert into table
					$insert["player_id"] = $player;
					$insert["side"] = $side;

					$this->data["Players"][] = $insert;
				}
			}
		}

		unset($this->data["Game"]["side[1"]);
		unset($this->data["Game"]["side[2"]);

		$result = $this->Game->save($this->data);

		foreach ($this->data["Players"] as $player) {
			$this->Game->savePlayer($player);
		}

		//rank game
		$this->message = $this->__rankGame($this->data);

		return $result;
	}

	function __rankGame($data) {
		//** SETTINGS **//
 		//the amount of points you get for playing. Makes rank have an overall upwards trend
		//makes it kinda worthwhile to play, even if you suck
		$participation_points = 3;

		//how important the difference in goals is. 1 = not important.

		//setting higher makes larger score differences give more points.
		$goal_diff_multiplier = 1;

		//how many points you get for winning
		$win_points = 10;

		//how many points is the minimum for winning
		$win_min_points = 0;

		//how many points = expected goal diff of one
		$one_goal_diff = 100;

		//need players ranks
		foreach ($data["Players"] as $player) {
			$player_data[$player["player_id"]] = $this->Player->read(null, $player["player_id"]);

			//ranks
			if (!isset($ranks[$player["side"]])) {
				$ranks[$player["side"]] = 0;
			}
			$ranks[$player["side"]] += $player_data[$player["player_id"]]["Player"]["rank"];

			//sides
			$side_players[$player["side"]][] = $player_data[$player["player_id"]]["Player"]["name"];
		}

		$notes = implode(", ", $side_players[1]).': '.$data["Game"]["side_1_score"].'; ';
		$notes .= implode(", ", $side_players[2]).': '.$data["Game"]["side_2_score"];

		foreach ($player_data as $player_id => $player) {
			$insert = array (
				"players_id" => $player["Player"]["id"],
				"games_id" => $this->Game->id,
				"rank" => $player["Player"]["rank"],
				"notes" => $notes
			);

			//transform to mysqlsafe
			foreach ($insert as $key => $value) {
				$insert[$key] = "'".mysql_real_escape_string($value)."'";
			}

			//insert into rank tracking

			$sql = "INSERT INTO rank_track(".implode(', ', array_keys($insert)).") VALUES (".implode(", ", array_values($insert)).")";
			$result = $this->Game->query($sql);
		}

		//figure out expected and actual values

		$expected_diff[1] = ceil( ($ranks[1] - $ranks[2]) / $one_goal_diff);
		$expected_diff[2] = ceil( ($ranks[2] - $ranks[1]) / $one_goal_diff);

		$actual_diff[1] = $data["Game"]["side_1_score"] - $data["Game"]["side_2_score"];
		$actual_diff[2] = $data["Game"]["side_2_score"] - $data["Game"]["side_1_score"];

		$final_diff[1] = $actual_diff[1] - $expected_diff[1];
		$final_diff[2] = $actual_diff[2] - $expected_diff[2];

		$points[1] = $final_diff[1] * $goal_diff_multiplier + $participation_points;
		$points[2] = $final_diff[2] * $goal_diff_multiplier + $participation_points;

		if ($data["Game"]["side_1_score"] > $data["Game"]["side_2_score"]) {
			$points[1] += $win_points;
			$points[1] = max($points[1], $win_min_points);

			$points[2] -= $win_points;
		} elseif ($data["Game"]["side_1_score"] < $data["Game"]["side_2_score"]) {
			$points[2] += $win_points;
			$points[2] = max($points[2], $win_min_points);

			$points[1] -= $win_points;
		} else {
			//ties don't happen
		}

		//update player ranks
		foreach ($data["Players"] as $player) {
			$this->Player->changeRank($player["player_id"], $points[$player["side"]]);
		}

		//set message telling how many points were won/lost
		$message = implode(", ", $side_players[1]).': '.$points[1].'; ';
		$message .= implode(", ", $side_players[2]).': '.$points[2];
		return $message;
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
				$range[] = $daily[$date];
			} else {
				$range[] = 0;
			}

			$date = date("Y-m-d", strtotime($date." +1 day"));
		}

		$data["range"] = $range;

		$this->set($data);
	}
}
