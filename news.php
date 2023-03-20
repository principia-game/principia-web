<?php
require('lib/common.php');

$newsid = $_GET['id'] ?? 0;

if (isset($_REQUEST['new']) && $log && $userdata['rank'] > 2) {
	if (isset($_POST['ApOsTaL'])) {
		query("INSERT INTO news (title, text, time, author) VALUES (?,?,?,?)",
			[$_POST['title'], $_POST['text'], time(), $userdata['id']]);

		$cachectrl->invIndex();

		$insertid = result("SELECT LAST_INSERT_ID()");
		redirect("/news.php?id=$insertid");
	}

	echo twigloader()->render('admin_news_add.twig');
	die();
}

if ($newsid) {
	$newsdata = fetch("SELECT * FROM news WHERE id = ?", [$newsid]);

	if (!$newsdata) error('404', "The requested news article wasn't found.");

	if (!isset($newsdata['redirect'])) {
		clearMentions('news', $newsid);

		$time = date('jS F Y', $newsdata['time']).' at '.date('H:i:s', $newsdata['time']);

		$comments = query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 2 AND c.level = ? ORDER BY c.time DESC", [$newsid]);

		echo twigloader()->render('news.twig', [
			'newsid' => $newsid,
			'news' => $newsdata,
			'time' => $time,
			'comments' => $comments
		]);
	} else {
		redirect($newsdata['redirect']);
	}
} else {
	$newsdata = query("SELECT id,title,time FROM news ORDER BY id DESC");

	echo twigloader()->render('news.twig', [
		'newsid' => $newsid,
		'news' => fetchArray($newsdata)
	]);
}
