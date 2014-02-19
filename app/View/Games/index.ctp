
<h2 id="page_title">Games</h2>

<p><a href="<?= $this->base ?>/games/add">Add a new game</a></p>

<table border="1" cellspacing="0">
	<tr>
		<th colspan="2">Side 1</th><th class="vs">vs.</th><th colspan="2">Side 2</th>
	</tr>
	
	<? foreach($games as $game): 
		
		$s1players = array();
		foreach($game["side_1_players"] as $id => $player) {
			$s1players[] = '<a href="'.$this->base.'/players/view/'.$id.'">'.$player.'</a>';
		}
		$s1players = implode($s1players, ', ');
		
		$s2players = array();
		foreach($game["side_2_players"] as $id => $player) {
			$s2players[] = '<a href="'.$this->base.'/players/view/'.$id.'">'.$player.'</a>';
		}
		$s2players = implode($s2players, ', ');
	?>
	<tr>
		<td class="side1 players <?= $game["side_1_score"] > $game["side_2_score"] ? 'win' : '' ?>">
			<?= $s1players ?>
		</td>
		
		<td class="side1 score <?= $game["side_1_score"] > $game["side_2_score"] ? 'win' : '' ?>">
			<?= $game["side_1_score"] ?>
		</td>
		
		<td></td>
		
		<td class="side2 score <?= $game["side_2_score"] > $game["side_1_score"] ? 'win' : ''?>">
			<?= $game["side_2_score"] ?>
		</td>
		
		<td class="side2 players <?= $game["side_2_score"] > $game["side_1_score"] ? 'win' : '' ?>">
			<?= $s2players; ?>
		</td>
	</tr>
	<? endforeach; ?>
	
</table>