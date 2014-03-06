<h3>Users -- View All</h3>

<table border="1" cellspacing="0">
	<tr><th></th><th>Username</th><th>Email</th></tr>

	<?php foreach($users as $user):?>
	<tr>
		<td></td>
		<td><?php echo $user['User']['username']; ?></td>
		<td><?php echo $user['User']['email']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>