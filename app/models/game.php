<?php
class Game extends AppModel {
	function findAll($accountID) {
		$sql = "SELECT *, players.id as player_id
				FROM games
				LEFT JOIN games_players ON game_id=games.id
				LEFT JOIN players ON player_id=players.id
				WHERE games.account_id={$accountID} AND games_players.id IS NOT NULL
				ORDER BY created DESC, games.id DESC";

		return $this->query($sql);
	}

	function savePlayer($player) {
		$sql = "INSERT INTO games_players(game_id, player_id, side) VALUES ({$this->id}, {$player["player_id"]}, {$player["side"]})";
		return $this->query($sql);
	}
}
