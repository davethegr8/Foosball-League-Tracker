<h2 id="page_title">Sign Up</h2>

<p>Signing up is super easy. All you need to do is give your email address, and pick a password.</p>

<?
echo $this->Form->create('Account', array('action' => 'signup'));
echo $this->Form->input('email');
echo $this->Form->input('password');
echo $this->Form->input('confirm', array('type' => 'password'));
echo $this->Form->submit('Create');
echo $this->Form->end();
?>