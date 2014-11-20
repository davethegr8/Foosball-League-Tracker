<?php

class Season extends AppModel {
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
		echo '<pre>';
		print_R($gameData);
		echo '</pre>';
	}
}
