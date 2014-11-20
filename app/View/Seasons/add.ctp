<h2 id="page_title">Add Season</h2>

<p>Note: When you add a new season, all previous ones will be deactivated.</p>

<?= $this->Form->create('Season', array('class' => 'big')) ?>
<?= $this->Form->input('name') ?>
<?= $this->Form->submit('Save'); ?>
<?= $this->Form->end(); ?>
