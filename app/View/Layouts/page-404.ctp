<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<?php
	$account = SessionHelper::read("Account");
	?>

	<title>Foosball League Score Tracker :: <?php echo $title_for_layout; ?></title>

	<meta name="description" content="" />

	<?php
	echo $this->Html->meta('icon');
	echo $this->Html->css('style');
	echo $this->Html->css('font-awesome.min');
	?>

	<link rel="alternate" type="application/rss+xml" href="<?= $this->base ?>/posts/rss/" />

	<?= $this->Html->script('jquery-1.10.2.min.js'); ?>

	<?= $this->Html->script('Chart.min.js'); ?>

    <script type="text/javascript">
        BASE = '<?php echo $this->base ?>';
    </script>
</head>
<body>

    <div id="container">

        <div id="top">
            <h1 id="header">Foosball Score Tracker</h1>

			<div id="menubar">
				<ul>
					<li><a href="<?= $this->base ?>/">Home</a></li>
					<li><a href="<?= $this->base ?>/pages/about/">About</a></li>
					<?php if(!$account["id"]) { ?>
					<li><a href="<?= $this->base ?>/accounts/signup/">Sign Up</a></li>
					<?php } ?>
					<li><a href="<?= $this->base ?>/pages/contact/">Contact</a></li>
				</ul>
			</div>
        </div>

		<div id="sidebar">
			<?
			if($account["id"]) {
				echo '<ul class="nav">';
				echo '	<li><span>'.$account["email"].'</span></li>';
				echo '	<li><a href="'.$this->base.'/accounts/view">Your Account</a></li>';
				echo '	<li><a href="'.$this->base.'/accounts/league">Your League</a></li>';
				echo '	<li><a href="'.$this->base.'/games">Games</a></li>';
				echo '	<li><a href="'.$this->base.'/accounts/logout">Logout</a></li>';
				echo '</ul>';
			}
			else {
				echo '<div class="sideitem">';
				echo '	<h3>Login</h3>';

				echo $this->Form->create('Account', array('action' => 'login'));
				echo $this->Form->input('email');
				echo $this->Form->input('password');
				echo $this->Form->submit('Login');
				echo $this->Form->end();

				echo '</div>';
			}
			?>

			<div class="sideitem">
				<h3>Stats</h3>
				<?= $this->Stats->countGames(); ?>
			</div>
		</div>

        <div id="content">
			<?php $this->Session->flash(); ?>
            <h1>File Not Found</h1>
			<p>The page you were looking for could not be found. Go <a href="javascript:window.history.go(-1);">Back</a>.</p>
            <span class="cleaner">&nbsp;</span>
        </div>

        <div id="footer">
			<p>&copy; 2007<?= (date("Y") > 2007 ? '-'.date("Y") : '') ?> zastica labs. All rights reserved.</p>
			<div class="blogfoot bottomlinks">
				<p><a href="http://www.zastica.com">zastica.com</a> | <a href="<?= $this->base ?>/pages/contact/">Contact</a></p>
			</div>
	    </div>
    </div>
    <?= $this->Html->script('functions.js'); ?>
</body>
</html>

