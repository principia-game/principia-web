<?php
require('lib/common.php');

$newsid = (isset($_GET['id']) ? $_GET['id'] : 0);

$twig = twigloader();

if (isset($_REQUEST['new']) && $log && $userdata['powerlevel'] > 2) {
	if (isset($_POST['ApOsTaL'])) {
		query("INSERT INTO news (title, text, time, author_userid) VALUES (?,?,?,?)",
			[$_POST['title'], $_POST['text'], time(), $userdata['id']]);

		$insertid = result("SELECT LAST_INSERT_ID()");
		redirect("./news.php?id=$insertid");
	}

	echo $twig->render('admin_news_add.twig');
	die();
}

if ($newsid) {
	$newsdata = fetch("SELECT * FROM news WHERE id = ?", [$newsid]);

	if (!$newsdata) {
		error('404', "The requested news article wasn't found.");
	}

	if (!isset($newsdata['redirect'])) {
		$time = date('jS F Y', $newsdata['time']).' at '.date('H:i:s', $newsdata['time']);

		$markdown = new Parsedown();
		$newsdata['text'] = $markdown->text($newsdata['text']);

		$comments = query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 2 AND c.level = ? ORDER BY c.time DESC", [$newsid]);

		echo $twig->render('news.twig', [
			'newsid' => $newsid,
			'news' => $newsdata,
			'time' => $time,
			'comments' => $comments
		]);
	} else {
		redirect($newsdata['redirect']);
	}
} else {
	$newsdata = query("SELECT id,title FROM news ORDER BY id DESC");

	echo $twig->render('news.twig', [
		'newsid' => $newsid,
		'news' => fetchArray($newsdata)
	]);
}
