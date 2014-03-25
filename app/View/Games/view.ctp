
<h2 id="page_title">View Game</h2>



<table border="1" cellspacing="1">
	<tr>
		<th>Side 1</th>
		<th>vs.</th>
		<th>Side 2</th>
	</tr>
	<tr>
		<td>
			<?php
			echo $Game['side_1_score']
			?>
		</td>

		<td></td>

		<td>
			<?php
			echo $Game['side_2_score']
			?>
		</td>
	</tr>
	<tr>
		<td>
		<?php
		foreach($Players as $item) {
			if($item['games_players']['side'] != 1) {
				continue;
			}

			echo $item['players']['name'].'<br />';
		}
		?>
		</td>

		<td>
			<img src="/img/table.png" style="width: 250px"/>
		</td>

		<td>
			<?php
			foreach($Players as $item) {
				if($item['games_players']['side'] != 2) {
					continue;
				}

				echo $item['players']['name'].'<br />';
			}
			?>
		</td>

	</tr>

</table>