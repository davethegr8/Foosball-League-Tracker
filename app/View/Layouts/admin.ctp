<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>

	<?php
	$account = SessionHelper::read("Account");
	$user = SessionHelper::read("User");
	?>

	<?php
	echo $this->Html->meta('icon');
	echo $this->Html->css('admin');
	echo $this->Html->css('font-awesome.min');
	?>

	<?= $this->Html->script('jquery-1.10.2.min.js'); ?>

	<?= $this->Html->script('Chart.min.js'); ?>

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
			<? if($this->Session->check("User")):?>
			<ul class="nav">
				<li><span><?php echo $user['User']["email"] ?></span></li>
				<li><a href="<?= $this->base ?>/admin/users/">Users</a></li>
				<li><a href="<?= $this->base ?>/admin/accounts/">Accounts</a></li>
				<li><a href="<?= $this->base ?>/admin/games/">Games</a></li>
				<li><a href="<?= $this->base ?>/admin/posts/">Posts</a></li>
				<li><a href="<?= $this->base ?>/admin/pages/">Pages</a></li>
				<li><a href="<?php echo $this->base ?>/admin/users/logout">Logout</a></li>
			</ul>
			<? endif; ?>
		</div>

        <div id="content">
			<?php $this->Session->flash(); ?>
            <?php echo $content_for_layout; ?>
            <span class="cleaner">&nbsp;</span>
        </div>

        <div id="footer">
			<p>&copy; 2007<?= (date("Y") > 2007 ? '-'.date("Y") : '') ?> zastica labs. All rights reserved. Current version: <em>2.1.1</em></p>
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

