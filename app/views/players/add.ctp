<h2 id="page_title">New Player</h2>

<?= $form->create('Player', array('class' => 'big')) ?>
<?= $form->input('name') ?>
<?= $form->submit('Save'); ?>
<?= $form->end(); ?>