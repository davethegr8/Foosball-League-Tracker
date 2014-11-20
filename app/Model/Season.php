<?php

class Season extends AppModel {
	function findAll($accountID) {
		$sql = "SELECT *
				FROM seasons
				WHERE seasons.account_id={$accountID}
				ORDER BY created DESC";

		return $this->query($sql);
	}
}
