<?php
require('lib/common.php');

$newsid = (isset($_GET['id']) ? $_GET['id'] : 0);

if (isset($_REQUEST['new']) && $log && $userdata['powerlevel'] > 1) {
	if (isset($_POST['ApOsTaL'])) {
		query("INSERT INTO news (title, text, time, author_userid) VALUES (?,?,?,?)",
			[$_POST['title'], $_POST['text'], time(), $userdata['id']]);

		$insertid = result("SELECT LAST_INSERT_ID()");
		header("Location: ./news.php?id=$insertid");
	}

	$twig = twigloader('admin');

	echo $twig->render('news_add.php');
	die();
}

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
