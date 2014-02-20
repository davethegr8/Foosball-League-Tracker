<h2 id="page_title">New Game</h2>

<?= $this->Form->create('Game', array('class' => 'game', 'action' => 'create')) ?>
	<table border="1" cellspacing="1">
		<tr>
			<th>Side 1</th>
			<th>vs.</th>
			<th>Side 2</th>
		</tr>
		<tr>
			<td>
				<i class="fa fa-minus score-minus"></i>
				<?php
				echo $this->form->input('side_1_score', array(
					'size' => 6,
					'label' => 'Score',
					'class' => 'score-box',
					'type' => 'number',
					'pattern' => '[0-9]*'
				));
				?>
				<i class="fa fa-plus score-plus"></i>
			</td>
			<td></td>
			<td>
				<i class="fa fa-minus score-minus"></i>
				<?php
				echo $this->form->input('side_2_score', array(
					'size' => 6,
					'label' => 'Score',
					'class' => 'score-box',
					'type' => 'number',
					'pattern' => '[0-9]*'
				));
				?>
				<i class="fa fa-plus score-plus"></i>
			</td>
		</tr>
		<tr>
			<td><label>Players</label>
			<?= $this->form->input('side[1][]', array('options' => $players, 'label' => '')); ?><br />
			<?= $this->form->input('side[1][]', array('options' => $players, 'label' => '')); ?></td>
			<td>
				<img src="/img/table.png" style="width: 250px"/>
			</td>
			<td><label>Players</label>
			<?= $this->form->input('side[2][]', array('options' => $players, 'label' => '')); ?><br />
			<?= $this->form->input('side[2][]', array('options' => $players, 'label' => '')); ?></td>
		</tr>

	</table>

	<?= $this->form->submit('Save'); ?>
<?= $this->form->end(); ?>