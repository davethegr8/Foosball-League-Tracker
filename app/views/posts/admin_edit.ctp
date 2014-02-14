<h2>Edit Post</h2>

<?= $form->create('Post'); ?>
<?= $form->input('title'); ?>
<?= $form->input('subtitle'); ?>
<?= $form->radio('active', array('1' => 'Yes', '0' => 'No'));?>
<?= $form->label('body'); ?>
<?= $form->textarea('body'); ?>
<?= $form->submit('Save'); ?>
<?= $form->end(); ?>