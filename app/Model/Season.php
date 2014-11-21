<?php

class Season extends AppModel {
	protected $current;

	function findAll($accountID) {
		$sql = "SELECT *
				FROM seasons
				WHERE seasons.account_id={$accountID}
				ORDER BY created DESC";

		return $this->query($sql);
	}

	function archive(array $conditions = array()) {
		return $this->updateAll(array(
			'Season.status' => '"archived"',
			'Season.archived' => 'NOW()'
		), $conditions);
	}

	function addGame($gameData) {
		$current = $this->read();

		$sql = "INSERT INTO seasons_games (season_id, game_id) VALUES (
			'".intval($this->id)."',
			'".intval($gameData['id'])."'
		)";
		$this->query($sql);

		$result = $this->save(array(
			'games_played' => $current['Season']['games_played'] + 1
		));
	}

	function getCurrent($accountID) {
		// get current season

		if($this->current !== null) {
			return $this->current;
		}

		$current = $this->find('first', array(
			'conditions' => array(
				'Season.account_id' => $accountID,
				'Season.status' => 'active'
			)
		));

		$this->current = $current;

		return $current;
	}

	function getPlayer($seasonID, $playerID) {
		$find = "SELECT *
				FROM seasons_ranks
				WHERE season_id='".intval($seasonID)."' AND player_id='".intval($playerID)."'";
		$result = $this->query($find);

		if(empty($result)) {
			// if they don't have one, insert initial data
			$sql = "INSERT INTO seasons_ranks(season_id, player_id)
					VALUES ('".intval($seasonID)."', '".intval($playerID)."')";
			$this->query($sql);

			$result = $this->query($find, array('cache' => false));
		}

		return $result[0];
	}

	function updateRanking($player) {
		$db = $this->getDataSource();
		$data = array();
		foreach($player['seasons_ranks'] as $key => $value) {
			$data[] = "$key = :$key";
		}
		$data = implode(', ', $data);

		$where = "id='".intval($player['seasons_ranks']['id'])."'";

		$sql = "UPDATE seasons_ranks SET {$data} WHERE {$where}";
		$db->query($sql, $player['seasons_ranks']);
	}
}
