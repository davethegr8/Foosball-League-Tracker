<h2 id="page_title">New Game</h2>

<?php //echo $form->input('Player 1', array('options' => $players)); ?>

<?= $form->create('Game', array('class' => 'game', 'action' => 'create')) ?>
	<table border="1" cellspacing="1">
		<tr>
			<th>Side 1</th>
			<th class="vs">vs.</th>
			<th>Side 2</th>
		</tr>
		<tr>
			<td><?= $form->input('side_1_score', array('size' => 6, 'label' => 'Score')) ?></td>
			<td></td>
			<td><?= $form->input('side_2_score', array('size' => 6, 'label' => 'Score')) ?></td>
		</tr>
		<tr>
			<td><label>Players</label>
			<?= $form->input('side[1][]', array('options' => $players, 'label' => '')); ?><br />
			<?= $form->input('side[1][]', array('options' => $players, 'label' => '')); ?></td>
			<td></td>
			<td><label>Players</label>
			<?= $form->input('side[2][]', array('options' => $players, 'label' => '')); ?><br />
			<?= $form->input('side[2][]', array('options' => $players, 'label' => '')); ?></td>
		</tr>
		
	</table>
	
	<?= $form->submit('Save'); ?>
<?= $form->end(); ?>