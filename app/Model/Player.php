<?php


class Player extends AppModel {

	function changeRank($player) {
		$this->id = $player['Player']['id'];
		$this->data = $this->read();

		$this->data["Player"] = $player['Player'];

		return $this->save();
	}

	function playerStats($accountID, $playerID) {
		$sql = "
			SELECT name, players.id, wins, loss, rank
			FROM players
			LEFT JOIN (
				SELECT players.id as player_id,
					SUM(IF(side=1 AND side_1_score>side_2_score, 1, 0) + IF(side=2 AND side_2_score>side_1_score, 1, 0)) AS wins,
					SUM(IF(side=1 AND side_1_score<side_2_score, 1, 0) + IF(side=2 AND side_2_score<side_1_score, 1, 0)) AS loss
				FROM games_players
				LEFT JOIN games ON game_id=games.id

				LEFT JOIN players ON player_id=players.id

				WHERE players.account_id={$accountID}
				GROUP BY player_id
			) AS record ON players.id=player_id
			WHERE players.account_id={$accountID} AND players.id={$playerID}
			GROUP BY name
			ORDER BY rank DESC";

		$result = $this->query($sql);

		return $result[0]["record"];
	}

	function playerGames($accountID, $playerID) {
		$games = array();

		$sql = "SELECT *, players.id as player_id
				FROM games_players
				INNER JOIN players ON player_id=players.id
				INNER JOIN games ON game_id=games.id
				WHERE games.account_id={$accountID}
				ORDER BY created DESC, games.id DESC";
		$result = $this->query($sql);

		foreach ($result as $game) {
			//this is ugly. beware.
			$games[$game["games"]["id"]]["side_1_score"] = $game["games"]["side_1_score"];
			$games[$game["games"]["id"]]["side_2_score"] = $game["games"]["side_2_score"];

			$games[$game["games"]["id"]]["side_".$game["games_players"]["side"]."_players"][$game["players"]["id"]] = $game["players"]["name"];
		}

		$temp = $games;
		$games = array();

		foreach ($temp as $game) {
			if (
				!in_array($playerID, array_keys($game["side_1_players"])) &&
				!in_array($playerID, array_keys($game["side_2_players"]))
			) {
				continue;
			}

			$games[] = $game;
		}

		return $games;
	}

	function combinedGames($accountID, $players = array()) {
		$sum = $players;
		foreach($sum as $key => $value) {
			$sum[$key] = "IF(player_id={$value}, 1, 0)";
		}
		$sum = implode(" + ", $sum);

		$where = $players;
		foreach($where as $key => $value) {
			$where[$key] = "player_id={$value}";
		}
		$where = implode(" OR ", $where);

		$count = count($players);

		$sql = "SELECT *
				FROM games_players
				JOIN (
					SELECT game_id, SUM({$sum}) checksum
					FROM games_players
					LEFT JOIN games ON games.id = game_id
					WHERE games.account_id={$accountID} AND ({$where})
					GROUP BY game_id
					HAVING checksum={$count}
				) gameIDs ON games_players.game_id=gameIDs.game_id
				LEFT JOIN players ON games_players.player_id=players.id
				LEFT JOIN games ON games_players.game_id=games.id
				ORDER BY games.id DESC";

		$games = array();
		$data = $this->query($sql);

		foreach($data as $key => $result) {
			$gameID = $result['games']['id'];

			$games[$gameID]['games'] = $result['games'];

			$result['players']['side'] = $result['games_players']['side'];

			$games[$gameID]['players'][$result['players']['id']] = $result['players'];
		}

		return $games;
	}

	function playerRanks($accountID, $playerID) {
		$ranks = array();

		$sql = "SELECT *
				FROM rank_track
				WHERE players_id='".intval($playerID)."'
				ORDER BY games_id ASC";
		$result = $this->query($sql);

		foreach ($result as $row) {
			$ranks[] = $row["rank_track"]["rank"];
		}

		return $ranks;
	}
}
