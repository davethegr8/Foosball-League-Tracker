
<h2 id="page_title">Games</h2>

<p>
	<a href="<?= $this->base ?>/players/add"><i class="fa fa-plus"></i> Add a new player</a>

	<a href="<?= $this->base ?>/games/add"><i class="fa fa-plus"></i> Add a new game</a>
</p>

<table border="1" cellspacing="0">
	<tr>
		<th></th><th colspan="2">Side 1</th><th class="vs">vs.</th><th colspan="2">Side 2</th>
	</tr>

	<?php
	foreach($games as $gameID => $game):
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
		<td>
			<a href="<?= $this->base ?>/games/delete/<?= $gameID ?>"><i class="fa fa-trash-o"></i></a>
			<a href="<?= $this->base ?>/games/view/<?= $gameID ?>"><i class="fa fa-eye"></i></a>
		</td>

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
<<<<<<< HEAD
	<? endforeach; ?>
=======
	<?php endforeach; ?>
>>>>>>> 3f0daee9c5d40204deeac0a93026da50b1f818ad

</table>