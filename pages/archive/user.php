<?php
$arg = $path[2] ?? null;

if (is_numeric($arg))
	$user = fetch("SELECT * FROM users WHERE id = ?", [$arg]);
else
	$user = fetch("SELECT * FROM users WHERE name = ?", [$arg]);

if (!isset($user) || !$user) error('404');

$id = $user['id'];

$page = $_GET['page'] ?? 1;

$levels = query("SELECT $userfields,l.id,l.title
		FROM levels l JOIN users u ON l.author = u.id
		WHERE l.author = ? AND l.visibility = 0 ORDER BY l.id DESC ".paginate($page, LPP),
	[$id]);

$levelcount = result("SELECT COUNT(*) FROM levels WHERE author = ? AND visibility = 0", [$id]);

twigloader()->display('user.twig', [
	'id' => $id,
	'name' => $user['name'],
	'userpagedata' => $user,
	'levels' => fetchArray($levels),
	'level_count' => $levelcount,
	'page' => $page
]);
