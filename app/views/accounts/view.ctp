<h2 id="page_title">Your Account</h2>

<?
echo $form->create('Account', array('action' => 'save', 'class' => 'big'));
echo $form->input('email', array('value' => $email));
echo $form->input('password');
echo $form->input('confirm', array('type' => 'password'));
echo $form->submit('Update');
echo $form->end();
?>