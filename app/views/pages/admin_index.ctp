<?= $html->link('Add a new Page', 'add') ?>

<table border="1" cellspacing="0">
	<tr>
		<th>ID</th>
		<th></th>
		<th>Title</th>
	</tr>
	
	<? foreach($pages as $page): ?>
	
	<tr>
		<td><?= $page["Page"]["id"]?></td>
		<td>
			<?= $html->link('Edit', 'edit/'.$page["Page"]["id"]) ?>
			<?= $html->link('Delete', 'delete/'.$page["Page"]["id"]) ?>
		</td>
		<td><?= $page["Page"]["title"]?></td>
	</tr>
	
	<? endforeach; ?>
</table>