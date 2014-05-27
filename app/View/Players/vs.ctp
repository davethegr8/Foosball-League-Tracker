<h3 class="name">
	<span class="blue bigger"><?= $player1['Player']['name'] ?></span>
	<span class="rank">(<?= $player1['Player']["rank"] ?>,
		<?= $player1['win'] ?>W <?= $player1['lose'] ?>L)</span>

	<i>-vs-</i>

	<span class="red bigger"><?= $player2['Player']['name'] ?></span>
	<span class="rank">(<?= $player2['Player']["rank"] ?>,
		<?= $player2['win'] ?>W <?= $player2['lose'] ?>L))</span>
</h3>

<canvas id="player_graph" height="200" width="690"></canvas>


<script>
$(function () {
	<?php
	$labels = array();

	$player1Ranks = $player1['ranks'];
	$player1Ranks[] = $player1['Player']['rank'];

	$player2Ranks = $player2['ranks'];
	$player2Ranks[] = $player2['Player']['rank'];

	foreach($player1Ranks as $i => $value) {
		$labels[$i] = $i;
	}

	foreach($player2Ranks as $i => $value) {
		$labels[$i] = $i;
	}
	?>

	var data = {
		labels: [<?= implode(", ", $labels) ?>],
		datasets: [
			{
				fillColor : "rgba(0,0,0,0)",
				strokeColor : "#99CCFF",
				pointColor : "#99CCFF",
				pointStrokeColor : "#fff",
				data: [<?= implode(", ", $player1Ranks) ?>]
			},
			{
				fillColor : "rgba(0,0,0,0)",
				strokeColor : "#FF6666",
				pointColor : "#FF6666",
				pointStrokeColor : "#fff",
				data: [<?= implode(", ", $player2Ranks) ?>]
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

<table border="1" cellspacing="0" class="pvp">
	<tr>
		<th colspan="2">Side 1</th><th class="vs">vs.</th><th colspan="2">Side 2</th>
	</tr>

	<?
	foreach($games as $game):
		$s1players = array();
		foreach($game["side_1_players"] as $id => $player) {
			$class = '';
			if($id == $player1['Player']['id']) {
				$class = 'blue';
			}
			elseif($id == $player2['Player']['id']) {
				$class = 'red';
			}

			$s1players[] = '<a href="'.$this->base.'/players/view/'.$id.'" class="'.$class.'">'.$player.'</a>';
		}
		$s1players = implode(', ', $s1players);

		$s2players = array();
		foreach($game["side_2_players"] as $id => $player) {
			$class = '';
			if($id == $player1['Player']['id']) {
				$class = 'blue';
			}
			elseif($id == $player2['Player']['id']) {
				$class = 'red';
			}

			$s2players[] = '<a href="'.$this->base.'/players/view/'.$id.'" class="'.$class.'">'.$player.'</a>';
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