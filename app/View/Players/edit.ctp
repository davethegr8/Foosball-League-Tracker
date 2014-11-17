<h2 id="page_title">Edit Player</h2>

<?= $this->Form->create('Player', array('class' => 'big')) ?>
<?= $this->Form->input('name', array(
	'value' => $player['Player']['name']
)) ?>
<?= $this->Form->submit('Save'); ?>
<?= $this->Form->end(); ?>
