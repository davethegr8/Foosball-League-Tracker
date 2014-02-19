<p class="intro">
<span class="dropcap">T</span>his site was developed by the need to track the scores of
foosball games at work. It can be used to track a league of players of singles or doubles.
If you'd like to signup and start tracking your own league, all you have to do is
<a href="<?= $this->base ?>/accounts/signup/">start here</a>.
</p>

<? foreach($posts as $post): ?>

<div class="blog">
	<div class="blog_l">
		<div class="blogdate">
			<span class="daynum"><?= date("d", strtotime($post["Post"]["created"])) ?></span><br />
			<span class="month"><?= date("M", strtotime($post["Post"]["created"])) ?></span><br />
			<?= date("Y", strtotime($post["Post"]["created"])) ?>
		</div>
	</div>

	<div class="blog_r">
		<div class="blog_interior">
			<h2><?= $this->Html->link($post["Post"]["title"], 'view/'.$post["Post"]["id"]) ?></h2>

			<? if($post["Post"]["subtitle"]) { ?>
			<h5><?= $post["Post"]["subtitle"] ?></h5>
			<? } ?>

			<div class="post_body">
				<?= $post["Post"]["body"] ?>
			</div>
		</div>
	</div>

</div>

<? endforeach; ?>