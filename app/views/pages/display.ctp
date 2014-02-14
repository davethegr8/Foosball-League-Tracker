
<h2 id="page_title"><?= $page["Page"]["title"] ?></h2>

<div class="page_body">
	<?= $page["Page"]["body"] ?>
</div>

<div class="page_footer">
	<p>Page last modified: <?= date("M d, Y", strtotime($page["Page"]["modified"])) ?></p>
</div>