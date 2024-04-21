<?php
if (isset($_GET['id'])) redirect('/news/'.$_GET['id']);

$newsid = $path[2] ?? 0;

if ($newsid) {
	$newsdata = News::getArticle($newsid);

	if (!$newsdata) error('404');

	$time = date('jS F Y', $newsdata['time']).' at '.date('H:i:s', $newsdata['time']);

	twigloader()->display('news.twig', [
		'newsid' => $newsid,
		'news' => $newsdata,
		'time' => $time,
	]);

} else {
	$newsdata = News::retrieveList(100);

	twigloader()->display('news.twig', [
		'newsid' => $newsid,
		'news' => $newsdata
	]);
}
