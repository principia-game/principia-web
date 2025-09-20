<?php
if (isset($_GET['id'])) redirectPerma('/news/%d', $_GET['id']);

$newsid = $path[2] ?? 0;

if ($newsid == 'feed') {
	header('Content-Type: text/xml');

	$newsdata = News::retrieveList(20);

	twigloader()->display('news_feed.twig', [
		'newsid' => $newsid,
		'news' => $newsdata
	]);

	return;
} elseif ($newsid) {
	$newsdata = News::getArticle($newsid);

	if (!$newsdata) error('404');

	if (isset($newsdata['redirect']))
		redirect($newsdata['redirect']);

} else {
	$newsdata = News::retrieveList(100);
}

twigloader()->display('news.twig', [
	'newsid' => $newsid,
	'news' => $newsdata
]);
