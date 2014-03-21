
<h2>All Accounts: <?= $count ?></h2>

<table border="1" cellspacing="0">
	<tr>
		<th>ID</th>
		<th>Email</th>
		<th>Games</th>
		<th></th>
	</tr>

	<? foreach($accounts as $account): ?>

	<tr>
		<td><?= $account["Account"]["id"] ?></td>
		<td><?= $account["Account"]["email"] ?></td>
		<td><?= $account["games"] ?></td>
		<td><a href="<?= $this->base ?>/admin/accounts/league/<?= $account["Account"]["id"] ?>">View</a></td>
	</tr>

	<? endforeach; ?>
</table>