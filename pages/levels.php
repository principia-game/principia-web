<?php
$type = $_GET['type'] ?? 'all';
$sort = $_GET['sort'] ?? 'new';
$archive = $_GET['archive'] ?? 0;
$page = (int)($_GET['page'] ?? 1);

$orderby = match ($sort) {
	'old' => 'ORDER BY l.id ASC',
	'top' => 'ORDER BY l.likes DESC',
	'pop' => 'ORDER BY l.downloads DESC',
	'new' => 'ORDER BY l.id DESC',
};

$where = ($type != 'all' ? "WHERE l.cat = ".typeToCat($type)." AND l.visibility = 0" : 'WHERE visibility = 0');

$levels = fetchArray(query("SELECT l.id, l.title, l.likes, l.downloads, l.time, @userfields
		FROM @levels l JOIN @users u ON l.author = u.id
		$where $orderby ".paginate($page, LPP)));

$levelcount = result("SELECT COUNT(*) FROM @levels l $where");

twigloader()->display('levels.twig', [
	'type' => $type,
	'sort' => $sort,
	'levels' => $levels,
	'page' => $page,
	'archive' => $archive,
	'level_count' => $levelcount
]);
