<?php
class Account extends AppModel {
	function validateLogin($data) {
		$sql = "SELECT * FROM accounts WHERE email='".($data["email"])."' AND password=PASSWORD('".$data["password"]."') LIMIT 1";
		$account = $this->query($sql);

		if (empty($account)) {
			//try the md5 version for recent signups
			$sql = "SELECT * FROM accounts WHERE email='".($data["email"])."' AND password=MD5('".$data["password"]."') LIMIT 1";
			$account = $this->query($sql);
		}

		if (empty($account) == false) {
			return $account[0];
		}

		return false;
	}

	function register($data) {
		$this->validate = array(
			'email' => array(
				'not_used' => array(
					'rule' => 'isUnique',
					'message' => 'That email is already in use.'
				),
				'valid' => array(
					'rule' => 'email',
					'message' => 'Please use a valid email address.'
				)
			)
		);

		unset($data["confirm"]);

		$data["password"] = md5($data["password"]);
		$data["created"] = date("Y-m-d H:i:s");

		$account = $this->save($data);

		if (empty($account) == false) {
			return $account["Account"];
		}

		return false;
	}

	function getLeague() {
		$sql = "
			SELECT players.*, wins, loss
			FROM players
			LEFT JOIN (
				SELECT players.id as player_id,
					SUM(IF(side=1 AND side_1_score>side_2_score, 1, 0) + IF(side=2 AND side_2_score>side_1_score, 1, 0)) AS wins,
					SUM(IF(side=1 AND side_1_score<side_2_score, 1, 0) + IF(side=2 AND side_2_score<side_1_score, 1, 0)) AS loss
				FROM games_players

				LEFT JOIN games ON game_id=games.id

				LEFT JOIN players ON player_id=players.id

				WHERE players.account_id=".$this->id."
				GROUP BY player_id
			) AS record ON players.id=player_id
			WHERE players.account_id=".$this->id."
			GROUP BY id
			ORDER BY rank DESC, name ASC";

		return $this->query($sql);
	}
}
