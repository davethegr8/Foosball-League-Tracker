<h2>Your League Seasons</h2>

<p>
	<a href="<?= $this->base ?>/seasons/add"><i class="fa fa-plus"></i> Start a new season</a>
</p>


<table border="1" cellspacing="0">
	<tr>
		<th></th>
		<th>Name</th>
		<th>Games Played</th>
		<th>Started</th>
		<th>Ended</th>
	</tr>

	<?php foreach($seasons as $season): ?>
	<tr>
		<td>
			<a href="<?= $this->base ?>/seasons/edit/<?= $season["seasons"]["id"] ?>" title="Edit Season"><i class="fa fa-pencil"></i></a>
		</td>
		<td><a href="<?= $this->base ?>/seasons/view/<?= $season["seasons"]["id"] ?>"><?php echo $season['seasons']['name'] ?></a></td>
		<td><?php echo $season['seasons']['games_played'] ?></td>
		<td><?php echo date_format(new DateTime($season['seasons']['created']), 'M j, Y') ?></td>
		<td><?php echo $season['seasons']['archived'] !== null ? date_format(new DateTime($season['seasons']['archived']), 'M j, Y') : '' ?></td>
	</tr>
	<?php endforeach; ?>

</table>

