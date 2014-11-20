<h2 id="page_title">Edit Season</h2>

<?= $this->Form->create('Season', array('class' => 'big')) ?>
<?= $this->Form->input('name', array(
	'value' => $season['Season']['name']
)) ?>
<?= $this->Form->submit('Save'); ?>
<?= $this->Form->end(); ?>

