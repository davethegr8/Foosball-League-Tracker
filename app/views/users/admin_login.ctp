<h3>Admin Login</h3>

<?
echo $form->create('User', array('action' => 'login'));
echo $form->input('username');
echo $form->input('password');
echo $form->submit('Login');
echo $form->end();
?>