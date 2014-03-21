<h2 id="page_title"><?= $account['Account']['email'] ?></h2>

<table border="1" cellspacing="0">
	<tr>
		<th>Rank</th>
		<th>Foos</th>
		<th>Performance</th>
		<th>Elo</th>
		<th>Win %</th>
		<th>Name</th>
		<th>W</th>
		<th>L</th>
	</tr>

<? foreach($players as $player): ?>
	<?
	$played = $player["record"]["wins"] + $player["record"]["loss"];
	?>
	<tr>
		<td><?= $player["players"]["rank"] ?></td>
		<td><?= $player["players"]["foos_rank"] ?></td>
		<td><?= $player["players"]["foos_performance_rank"] ?></td>
		<td><?= $player["players"]["elo_rank"] ?></td>
		<td><?= sprintf("%.3f", $played > 0 ? $player["record"]["wins"] / $played : 0 ) ?></td>
		<td><a href="<?= $this->base ?>/admin/players/view/<?= $player["players"]["id"] ?>"><?= $player["players"]["name"] ?></a></td>
		<td><?= ($player["record"]["wins"] != 0 ? $player["record"]["wins"] : 0) ?></td>
		<td><?= ($player["record"]["loss"] != 0 ? $player["record"]["loss"] : 0) ?></td>
	</tr>
<? endforeach; ?>

	<tr>
		<th colspan="8">Unranked Players</th>
	</tr>

<? foreach($unranked as $player): ?>
	<?
	$played = $player["record"]["wins"] + $player["record"]["loss"];
	?>
	<tr>
		<td></td>
		<td><?= sprintf("%.3f", $played > 0 ? $player["record"]["wins"] / $played : 0 ) ?></td>
		<td><?= $player["players"]["name"] ?></td>
		<td><?= ($player["record"]["wins"] != 0 ? $player["record"]["wins"] : 0) ?></td>
		<td><?= ($player["record"]["loss"] != 0 ? $player["record"]["loss"] : 0) ?></td>
	</tr>
<? endforeach; ?>

</table>
