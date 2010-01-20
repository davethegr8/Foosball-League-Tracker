<?= $html->link('Add a new Post', 'add') ?>

<table border="1" cellspacing="0">
	<tr>
		<th>ID</th>
		<th></th>
		<th>Title</th>
	</tr>
	
	<? foreach($posts as $post): ?>
	
	<tr>
		<td><?= $post["Post"]["id"]?></td>
		<td>
			<?= $html->link('Edit', 'edit/'.$post["Post"]["id"]) ?>
			<?= $html->link('Delete', 'delete/'.$post["Post"]["id"]) ?>
		</td>
		<td><?= $post["Post"]["title"]?></td>
	</tr>
	
	<? endforeach; ?>
</table>