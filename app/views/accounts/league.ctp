<h2 id="page_title">Your League</h2>

<p><a href="<?= $this->base ?>/players/add">Add a new player</a></p>

<table border="1" cellspacing="0">
	<tr>
		<th>Rank</th><th>Win %</th><th>Name</th><th>W</th><th>L</th>
	</tr>
	
<? foreach($players as $player): ?>
	<? $games = $player["record"]["wins"] + $player["record"]["loss"]; ?>
	<tr>
		<td><?= $player["players"]["rank"] ?></td>
		<td><?= sprintf("%.3f", $games > 0 ? $player["record"]["wins"] / $games : 0 ) ?></td>
		<td><a href="<?= $this->base ?>/players/view/<?= $player["players"]["id"] ?>"><?= $player["players"]["name"] ?></a></td>
		<td><?= ($player["record"]["wins"] != 0 ? $player["record"]["wins"] : 0) ?></td>
		<td><?= ($player["record"]["loss"] != 0 ? $player["record"]["loss"] : 0) ?></td>
	</tr>
<? endforeach; ?>

</table>