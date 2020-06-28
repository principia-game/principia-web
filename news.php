<?php
require('lib/common.php');

pageheader();

$newsid = (isset($_GET['id']) ? $_GET['id'] : 0);

$twig = twigloader();

if ($newsid) {
	$newsdata = fetch("SELECT * FROM news WHERE id = ?", [$newsid]);
	if (!isset($newsdata['redirect'])) {
		$time = date('jS F Y', $newsdata['time']).' at '.date('H:i:s', $newsdata['time']);

		$comments = query("SELECT c.*,u.id u_id,u.name u_name FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 2 AND c.level = ? ORDER BY c.time DESC", [$newsid]);

		echo $twig->render('news.php', [
			'newsid' => $newsid,
			'news' => $newsdata,
			'time' => $time,
			'comments' => $comments
		]);
	} else {
		header("Location: ".$newsdata['redirect']);
	}
} else {
	$newsdata = query("SELECT id,title FROM news ORDER BY id DESC");

	echo $twig->render('news.php', [
		'newsid' => $newsid,
		'news' => fetchArray($newsdata)
	]);
}

pagefooter();