<h2>Games: <?= $count ?></h2>

<div id="games_graph" class="plot"></div>
<script>
$(function () {
	<? 
	foreach($range as $i => $value) {
		$temp[] = '['.($i * 1000).', '.$value.']';
	}
	?>
	
	var points = [ [<?= implode(", ", $temp) ?>] ];
	var options = {
		colors: ["#9cf"],
		points: { show: true },
		lines: { show: true },
		grid: { hoverable: true, color: "#999" },
		xaxis: {
			ticksize: 1,
			mode: 'time'
		}
	};
	
	$.plot($("#games_graph"), points, options);
	
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
    $("#games_graph").bind("plothover", function (event, pos, item) {
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