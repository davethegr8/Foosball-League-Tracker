<?php

class StatsHelper extends AppHelper {
	
	var $players;
	var $games;
	
	function __construct() {
		$this->players = ClassRegistry::init('Players');
		$this->games = ClassRegistry::init('Games');
	}
	
	function StatsHelper() {
		$this->__construct();
		
	}
	
	function topPlayers() {
		$sql = "SELECT name, players.id, rank
				FROM players
				ORDER BY rank DESC, name ASC
				LIMIT 0, 5";
		$players = $this->players->query($sql);
		
		$out = '<ul id="top_ranked">';
		foreach($players as $player) {
			$out .= '<li><span class="blue">'.$player["players"]["name"].'</span> ( '.intval($player["players"]["rank"]).' ) </li>';
		}
		$out .= '</ul>';
		
		return $this->output($out);
	}
	
	function countGames() {
		$sql = "SELECT COUNT(*) AS games,
				(SUM(side_1_score) + SUM(side_2_score)) AS goals
		        FROM games";
		$result = $this->games->query($sql);
		$row = $result[0];
		
		$out = '<p>So far there have been <span class="blue bigger">'.number_format($row[0]["goals"]).'</span> goals scored in <span class="blue bigger">'.number_format($row[0]["games"]).'</span> games.</p>';
		
		return $this->output($out);
	}
	
}

?>