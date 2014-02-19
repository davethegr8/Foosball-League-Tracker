<h2 id="page_title">Your Account</h2>

<?
echo $this->Form->create('Account', array('action' => 'save', 'class' => 'big'));
echo $this->Form->input('email', array('value' => $email));
echo $this->Form->input('password');
echo $this->Form->input('confirm', array('type' => 'password'));
echo $this->Form->submit('Update');
echo $this->Form->end();
?>