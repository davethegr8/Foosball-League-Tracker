<h2 id="page_title">Sign Up</h2>

<p>Signing up is super easy. All you need to do is give your email address, and pick a password.</p>

<?
echo $form->create('Account', array('action' => 'signup'));
echo $form->input('email');
echo $form->input('password');
echo $form->input('confirm', array('type' => 'password'));
echo $form->submit('Create');
echo $form->end();
?>