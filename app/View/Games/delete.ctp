
<h2 id="page_title">Delete Game</h2>

<div class="warning">
	<h3>Caution!</h3>

	<p>If you delete this game it will no longer be available in your league. Player
	win/loss totals will update <b>but rankings will not!</b> If you entered this
	game by mistake, just delete this game and then readd it again with the correct
	results. Since rankings are not changed when you delete a game, try to keep
	this to a minimum. One or two deleted games will not adversely affect rankings
	since they will rebalance over time, but a bunch will cause the unexpected
	results with the ranking results.</p>

	<?php
	echo $this->Form->create('Game', array('action' => 'remove'));

	echo "<h4>I'm sure I want to delete this game.</h4>";

	echo $this->form->submit('Delete');
	echo $this->form->end();
	?>
</div>

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