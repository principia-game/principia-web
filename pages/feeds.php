<?php

$type = $path[2] ?? '';

switch ($type) {
	case 'news':
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: text/xml');

		$newsdata = News::retrieveList(20, true);

		twigloader()->display('feeds/news.twig', [
			'news' => $newsdata
		]);

		return;

	case 'levels':
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: text/xml');

		$levels = query("SELECT l.*, @userfields
				FROM @levels l JOIN @users u ON l.author = u.id
				WHERE l.visibility = 0
				ORDER BY l.id DESC LIMIT 200");

		twigloader()->display('feeds/levels.twig', [
			'levels' => $levels
		]);
		return;
}

twigloader()->display('feeds.twig', [
	'type' => $type
]);
