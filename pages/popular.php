<?php
$page = $_GET['page'] ?? 1;

$levels = query("SELECT l.id, l.title, @userfields
		FROM levels l JOIN users u ON l.author = u.id
		WHERE l.visibility = 0 ORDER BY l.downloads DESC, l.id DESC "
		.paginate($page, LPP));

$count = result("SELECT COUNT(*) FROM levels WHERE visibility = 0");

twigloader()->display('popular.twig', [
	'levels' => $levels,
	'page' => $page,
	'level_count' => $count
]);
