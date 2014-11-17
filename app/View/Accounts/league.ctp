<h2 id="page_title">Your League</h2>

<p>
	<a href="<?= $this->base ?>/players/add"><i class="fa fa-plus"></i> Add a new player</a>

	<a href="<?= $this->base ?>/games/add"><i class="fa fa-plus"></i> New Game</a>
</p>

<table border="1" cellspacing="0">
	<tr>
		<th>Rank</th>
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
		<td><?= sprintf("%.3f", $played > 0 ? $player["record"]["wins"] / $played : 0 ) ?></td>
		<td>
			<a href="<?= $this->base ?>/players/view/<?= $player["players"]["id"] ?>"><?= $player["players"]["name"] ?></a>
			<a href="<?= $this->base ?>/players/edit/<?= $player["players"]["id"] ?>" title="Edit Player"><i class="fa fa-pencil"></i></a>
		</td>
		<td><?= ($player["record"]["wins"] != 0 ? $player["record"]["wins"] : 0) ?></td>
		<td><?= ($player["record"]["loss"] != 0 ? $player["record"]["loss"] : 0) ?></td>
	</tr>
<? endforeach; ?>

	<tr>
		<th colspan="5">Unranked Players</th>
	</tr>

<? foreach($unranked as $player): ?>
	<?
	$played = $player["record"]["wins"] + $player["record"]["loss"];
	?>
	<tr>
		<td></td>
		<td><?= sprintf("%.3f", $played > 0 ? $player["record"]["wins"] / $played : 0 ) ?></td>
		<td>
			<a href="<?= $this->base ?>/players/view/<?= $player["players"]["id"] ?>"><?= $player["players"]["name"] ?></a>
			<a href="<?= $this->base ?>/players/edit/<?= $player["players"]["id"] ?>" title="Edit Player"><i class="fa fa-pencil"></i></a>
		</td>
		<td><?= ($player["record"]["wins"] != 0 ? $player["record"]["wins"] : 0) ?></td>
		<td><?= ($player["record"]["loss"] != 0 ? $player["record"]["loss"] : 0) ?></td>
	</tr>
<? endforeach; ?>

</table>
