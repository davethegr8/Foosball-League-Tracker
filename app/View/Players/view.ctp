<?php
$player = $this->get('player');
$player = $player['Player'];

$record = $this->get('Record');
$games = $this->get('Games');
$ranks = $this->get('Ranks');
?>
<h3 class="name">
	<span class="blue bigger"><?= $player['name'] ?></span>
	<span class="rank">(<?= $player["rank"] ?>)</span>
</h3>

<p>Wins: <span class="wins"><?= $record["wins"] ?></span> Losses: <span class="loss"><?= $record["loss"] ?></span></p>

<p><a href="<?= $this->base ?>/players/edit/<?= $player["id"] ?>" title="Edit Player"><i class="fa fa-pencil"></i> Edit Player</a></p>

<canvas id="player_graph" height="200" width="690"></canvas>

<script>
$(function () {
	<?php
	$ranks[] = $player["rank"];
	foreach($ranks as $i => $value) {
		$temp[] = '['.$i.', '.$value.']';
	}
	?>

	var data = {
		labels: [<?= implode(", ", array_keys($ranks)) ?>],
		datasets: [
			{
				fillColor : "rgba(0,0,0,0)",
				strokeColor : "#99CCFF",
				pointColor : "#99CCFF",
				pointStrokeColor : "#fff",
				data: [<?= implode(", ", $ranks) ?>]
			}
		]
	};

	var options = {
		scaleLineColor : "rgba(255,255,255,.25)",
		scaleLineWidth : 1,
		scaleGridLineColor : "rgba(255,255,255,.25)",
		scaleGridLineWidth : 1,

		animation : false,
	};

	var ctx = $("#player_graph").get(0).getContext("2d");
	var chart = new Chart(ctx).Line(data, options);

});
</script>

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
