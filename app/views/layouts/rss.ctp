<? echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0">
  <channel>
    <title>Zastica Foosball League Score Tracker Updates</title>
    <link>http://foos.zastica.com</link>
    <description>Updates for the foosball league store tracker at foos.zastica.com</description>
    <language>en-us</language>

	<? foreach($posts as $post): ?>
	
		<item>
			<title><?= $post["Post"]["title"] ?></title>
			<link><?= $this->base ?>/posts/view/<?= $post["Post"]["id"] ?></link>
			<pubDate><?= date("r", strtotime($post["Post"]["created"])) ?></pubDate>
			<description><?= $post["Post"]["body"] ?></description>
		</item>
		
	<? endforeach; ?>
	
	</channel>
</rss>
