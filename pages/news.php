<?php
if (isset($_GET['id'])) redirect('/news/'.$_GET['id']);

$newsid = $path[2] ?? 0;

if ($newsid) {
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
