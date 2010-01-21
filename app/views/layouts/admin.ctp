<?php echo $html->doctype('xhtml-strict') ?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php echo $html->charset(); ?>
	<? $account = $session->read("Account"); ?>
    
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

    <div id="container" class="admin">
    
        <div id="top">   
            <h1 id="header">Foosball Score Tracker &bull; Admin</h1>

			<div id="menubar">
				<ul>
					<li><a href="<?= $this->base ?>/">Home</a></li>
				</ul>
			</div>
        </div>

		<div id="sidebar">
			<ul class="nav">
				<li><span><?php //echo $user["email"] ?></span></li>
				<li><a href="<?= $this->base ?>/admin/users/">Users</a></li>
				<li><a href="<?= $this->base ?>/admin/accounts/">Accounts</a></li>
				<li><a href="<?= $this->base ?>/admin/games/">Games</a></li>
				<li><a href="<?= $this->base ?>/admin/posts/">Posts</a></li>
				<li><a href="<?= $this->base ?>/admin/pages/">Pages</a></li>
				<li><a href="<?php echo $this->base ?>/admin/users/logout">Logout</a></li>
			</ul>
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
    
</body>
</html>

