<?php
require('lib/common.php');

$type = (isset($_GET['type']) && type_to_cat($_GET['type']) != 99 ? $_GET['type'] : 'all');
$page = $_GET['page'] ?? 1;

$where = ($type != 'all' ? "WHERE l.cat = ".type_to_cat($type)." AND l.visibility = 0" : 'WHERE visibility = 0');

$levels = query("SELECT l.id, l.title, $userfields
	FROM levels l JOIN users u ON l.author = u.id $where ORDER BY l.id DESC ".paginate($page, $lpp));

$count = result("SELECT COUNT(*) FROM levels l $where");

echo twigloader()->render('latest.twig', [
	'type' => $type,
	'levels' => fetchArray($levels),
	'page' => $page,
	'level_count' => $count
]);
