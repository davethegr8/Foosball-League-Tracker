<h2 id="page_title">Remove Player</h2>

<?= $this->Form->create('Player', array('class' => 'big')) ?>

<p>Are you SURE you want to delete this player? They will be gone forever!</p>

<h3><?= $player['Player']['name'] ?></h3>

<?= $this->Form->input('confirm', array(
	'type' => 'hidden',
	'value' => 1
)) ?>
<?= $this->Form->submit('Delete!'); ?>
<?= $this->Form->end(); ?>
