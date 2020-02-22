<?php
require('lib/common.php');

pageheader();

$newsid = (isset($_GET['id']) ? $_GET['id'] : 0);

if ($newsid) {
	$newsdata = fetch("SELECT * FROM news WHERE id = ?", [$newsid]);
	if (!isset($newsdata['redirect'])) {
		$time = date('jS F Y', $newsdata['time']).' at '.date('H:i:s', $newsdata['time']);
		echo <<<HTML
			<h2>{$newsdata['title']}</h2>
			<p>{$newsdata['text']}</p>
			<p><em>Published on the $time (GMT)</em></p>
		HTML;
	} else {
		header("Location: ".$newsdata['redirect']);
	}
} else {
	$newsdata = query("SELECT * FROM news ORDER BY id DESC");

	echo '<ul>';
	while ($record = $newsdata->fetch()) {
		echo "<li><a href=\"news.php?id={$record['id']}\">{$record['title']}</li>";
	}
	echo '</ul>';
}

pagefooter();