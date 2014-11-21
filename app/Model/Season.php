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

	function overview($accountID) {
		$sql = "
			SELECT seasons_ranks.*, players.name, players.id
			FROM seasons_ranks
			LEFT JOIN players ON players.id=player_id
			WHERE season_id={$this->id}
			ORDER BY rank DESC, name ASC
		";
		$rawPlayers = $this->query($sql);

		$temp = array();
		foreach($rawPlayers as $player) {
			$player['record'] = array('wins' => 0, 'loss' => 0);
			$temp[$player['seasons_ranks']['player_id']] = $player;
		}
		$rawPlayers = $temp;

		$sql = "
			SELECT *
			FROM seasons_games
			LEFT JOIN games ON games.id=seasons_games.game_id
			JOIN games_players ON games.id=games_players.game_id
			WHERE season_id={$this->id}
		";
		$rawGames = $this->query($sql);

		$games = array();
		foreach($rawGames as $game) {
			if(!isset($games[$game['games']['id']])) {
				$initial = $game['games'];
				$initial['season_id'] = $game['seasons_games']['season_id'];
				$initial['season_games_id'] = $game['seasons_games']['id'];
				$initial['players'] = array(
					'side_1' => array(),
					'side_2' => array()
				);

				$games[$game['games']['id']] = $initial;
			}

			$ref = $games[$game['games']['id']]['players'];


			$playerID = $game['games_players']['player_id'];

			$ref['side_'.$game['games_players']['side']][] = $rawPlayers[$playerID]['players'];
			$games[$game['games']['id']]['players'] = $ref;
		}

		foreach($games as $game) {
			$winner = $game['side_1_score'] > $game['side_2_score'] ? 'side_1' : 'side_2';
			$loser = $game['side_1_score'] < $game['side_2_score'] ? 'side_1' : 'side_2';

			foreach($game['players'][$winner] as $player) {
				$rawPlayers[$player['id']]['record']['wins'] += 1;
			}

			foreach($game['players'][$loser] as $player) {
				$rawPlayers[$player['id']]['record']['loss'] += 1;
			}
		}

		debug($rawPlayers);

		die;
		return $this->query($sql);
	}

}
