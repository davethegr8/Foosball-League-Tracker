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
			<h2><?= $html->link($post["Post"]["title"], 'view/'.$post["Post"]["id"]) ?></h2>
		
			<? if($post["Post"]["subtitle"]) { ?>
			<h5><?= $post["Post"]["subtitle"] ?></h5>
			<? } ?>
		
			<div class="post_body">
				<?= $post["Post"]["body"] ?>
			</div>
		</div>
	</div>
	
</div>