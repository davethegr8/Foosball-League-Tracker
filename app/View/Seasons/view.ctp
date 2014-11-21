<h2 id="page_title">Season <?php echo $season['Season']['name'] ?> Details</h2>

<p>
Status: <?php echo $season['Season']['status'] ?> |
Started: <?php echo date_format(new DateTime($season['Season']['created']), 'M j, Y') ?> |
Ended: <?php echo $season['Season']['archived'] !== null ? date_format(new DateTime($season['Season']['archived']), 'M j, Y') : '' ?>
</p>

<h3>Players</h3>

<table border="1" cellspacing="0">
	<tr>
		<th></th>
		<th>Rank</th>
		<th>Win %</th>
		<th>Name</th>
		<th>W</th>
		<th>L</th>
	</tr>

<?php
$rank = 1;
?>

<? foreach($players as $player): ?>
	<?
	$played = $player["record"]["wins"] + $player["record"]["loss"];
	?>
	<tr>
		<td><?php echo $rank++ ?></td>
		<td><?= $player["players"]["rank"] ?></td>
		<td><?= sprintf("%.3f", $played > 0 ? $player["record"]["wins"] / $played : 0 ) ?></td>
		<td>
			<a href="<?= $this->base ?>/players/view/<?= $player["players"]["id"] ?>"><?= $player["players"]["name"] ?></a>
		</td>
		<td><?= ($player["record"]["wins"] != 0 ? $player["record"]["wins"] : 0) ?></td>
		<td><?= ($player["record"]["loss"] != 0 ? $player["record"]["loss"] : 0) ?></td>
	</tr>
<? endforeach; ?>
</table>


<h3>Games (<?php echo $season['Season']['games_played'] ?>)</h3>


<table border="1" cellspacing="0">
	<tr>
		<th colspan="2">Side 1</th><th class="vs">vs.</th><th colspan="2">Side 2</th>
	</tr>

	<?
	foreach($games as $game):
		$s1players = array();
		foreach($game["side_1_players"] as $id => $player) {
			$s1players[] = '<a href="'.$this->base.'/players/view/'.$id.'">'.$player.'</a>';
		}
		$s1players = implode(', ', $s1players);

		$s2players = array();
		foreach($game["side_2_players"] as $id => $player) {
			$s2players[] = '<a href="'.$this->base.'/players/view/'.$id.'">'.$player.'</a>';
		}
		$s2players = implode(', ', $s2players);
	?>
	<tr>
		<td class="side1 players <?= ($game["side_1_score"] > $game["side_2_score"] ? 'win' : '') ?>">
			<?= $s1players ?>
		</td>

		<td class="side1 score <?= ($game["side_1_score"] > $game["side_2_score"] ? 'win' : '') ?>">
			<?= $game["side_1_score"] ?>
		</td>

		<td></td>

		<td class="side2 score <?= ($game["side_2_score"] > $game["side_1_score"] ? 'win' : '') ?>">
			<?= $game["side_2_score"] ?>
		</td>

		<td class="side2 players <?= ($game["side_2_score"] > $game["side_1_score"] ? 'win' : '') ?>">
			<?= $s2players ?>
		</td>
	</tr>
	<? endforeach; ?>

</table>
