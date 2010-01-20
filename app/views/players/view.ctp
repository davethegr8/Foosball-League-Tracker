<h3 class="name">
	<span class="blue bigger"><?= $player["name"] ?></span>
	<span class="rank"><?= $player["rank"] ?></span>
</h3>

<p>Wins: <span class="wins"><?= $record["wins"] ?></span> Losses: <span class="loss"><?= $record["loss"] ?></span></p>

<div id="player_graph"></div>
<script>
$(function () {
	<? 
	$ranks[] = $player["rank"];
	foreach($ranks as $i => $value) {
		$temp[] = '['.$i.', '.$value.']';
	}
	?>
	
	var points = [ [<?= implode(", ", $temp) ?>] ];
	var options = {
		colors: ["#9cf"],
		points: { show: true },
		lines: { show: true },
		grid: { hoverable: true, color: "#999" },
		xaxis: {
			min: 0,
			ticksize: 1
		}
	};
	
	$.plot($("#player_graph"), points, options);
	
	function showTooltip(x, y, contents) {
		$('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            padding: '5px',
			border: '1px solid #666',
            'background-color': '#222',
			color: '#9cf',
			'font-weight': 'bold'
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $("#player_graph").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

        if (item) {
            if (previousPoint != item.datapoint) {
                previousPoint = item.datapoint;

                $("#tooltip").remove();
                var x = item.datapoint[0],
                    y = item.datapoint[1];

                showTooltip(item.pageX, item.pageY, y);
            }
        }
        else {
            $("#tooltip").remove();
            previousPoint = null;            
        }
    });
	
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