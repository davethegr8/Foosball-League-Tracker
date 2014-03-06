<h3>Admin Login</h3>

<?
echo $this->Form->create('User', array('action' => 'login'));
echo $this->Form->input('username');
echo $this->Form->input('password');
echo $this->Form->submit('Login');
echo $this->Form->end();
?>