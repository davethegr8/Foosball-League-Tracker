<?php echo $html->doctype('xhtml-strict') ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php echo $html->charset(); ?>
	<?php $account = $session->read("Account"); ?>

	<title>Foosball League Score Tracker :: <?php echo $title_for_layout; ?></title>

	<meta name="description" content="Tracks your work or home foosball games and record." />
	<meta name="keywords" content="foosball score league tracking track record" />

	<link rel="icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
	<link rel="alternate" type="application/rss+xml" href="<?= $this->base ?>/posts/rss/" />

	<?= $html->css('style.css'); ?>

	<?= $javascript->link('jquery-1.3.2.min.js'); ?>

	<!--[if IE]><?= $javascript->link("excanvas.pack.js") ?><![endif]-->
	<?= $javascript->link('jquery.flot.pack.js'); ?>


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

				echo $form->create('Account', array('action' => 'login'));
				echo $form->input('email');
				echo $form->input('password');
				echo $form->submit('Login');
				echo $form->end();

				echo '</div>';
			}
			?>
			<div class="sideitem">
				<h3>Top Players</h3>
				<p>These guys and gals are the highest ranked players in the system.</p>
				<?= $stats->topPlayers(); ?>
			</div>

			<div class="sideitem">
				<h3>Stats</h3>
				<?= $stats->countGames(); ?>
			</div>
		</div>

        <div id="content">
			<?php $session->flash(); ?>
            <?php echo $content_for_layout; ?>
            <span class="cleaner">&nbsp;</span>
        </div>

        <div id="footer">
			<p>&copy; 2007<?= (date("Y") > 2007 ? '-'.date("Y") : '') ?> zastica labs. All rights reserved. Current version: <em>2.0.4</em></p>
			<div class="blogfoot bottomlinks">
				<p>
					<a href="http://www.zastica.com">zastica.com</a> |
					<a href="<?= $this->base ?>/pages/contact/">Contact</a>
				</p>
			</div>
	    </div>

    </div>

<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-1770869-5");
	pageTracker._trackPageview();
	} catch(err) {}
</script>

</body>
</html>
