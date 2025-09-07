<?php
$page = $_GET['page'] ?? 1;

$levels = query("SELECT $userfields, l.id,l.title FROM levels l
		JOIN users u ON l.author = u.id WHERE l.visibility = 0 ORDER BY l.likes DESC, l.id DESC ".paginate($page, LPP));
$count = result("SELECT COUNT(*) FROM levels WHERE visibility = 0");

twigloader()->display('top.twig', [
	'levels' => $levels,
	'page' => $page,
	'level_count' => $count
]);
