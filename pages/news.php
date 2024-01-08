<?php
if (isset($_GET['id'])) redirect('/news/'.$_GET['id']);

$newsid = $path[2] ?? 0;

if ($newsid) {
	$newsdata = News::getArticle($newsid);

	if (!$newsdata) error('404');

	clearMentions('news', $newsid);

	$time = date('jS F Y', $newsdata['time']).' at '.date('H:i:s', $newsdata['time']);

	$comments = query("SELECT c.*, $userfields
			FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 2 AND c.level = ?
			ORDER BY c.time DESC",
		[$newsid]);

	twigloader()->display('news.twig', [
		'newsid' => $newsid,
		'news' => $newsdata,
		'time' => $time,
		'comments' => $comments
	]);

} else {
	$newsdata = News::retrieveList(100);

	twigloader()->display('news.twig', [
		'newsid' => $newsid,
		'news' => $newsdata
	]);
}
