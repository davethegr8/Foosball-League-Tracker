<h2>Players: <?= $count ?></h2>

<table border="1" cellspacing="0">
	<tr>
		<th>ID</th>
		<th>Owner</th>
		<th>Name</th>
		<th>Rank</th>
	</tr>
	
	<? foreach($players as $player): ?>
	
	<tr>
		<td><?= $player["Player"]["id"]?></td>
		<td><?= $player["Account"]["email"]?></td>
		<td><?= $player["Player"]["name"] ?></td>
		<td><?= $player["Player"]["rank"] ?></td>
	</tr>
	
	<? endforeach; ?>
</table>