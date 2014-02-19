<h2 id="page_title">New Player</h2>

<?= $this->Form->create('Player', array('class' => 'big')) ?>
<?= $this->Form->input('name') ?>
<?= $this->Form->submit('Save'); ?>
<?= $this->Form->end(); ?>