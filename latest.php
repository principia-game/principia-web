<?php
require('lib/common.php');

$type = (isset($_GET['type']) ? $_GET['type'] : 'all');
$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

$where = ($type != 'all' ? "WHERE l.cat = ".type_to_cat($type).' AND locked = 0' : 'WHERE locked = 0');
$limit = sprintf("LIMIT %s,%s", (($page - 1) * $lpp), $lpp);
$levels = query("SELECT $userfields l.id id,l.title title,l.locked locked FROM levels l JOIN users u ON l.author = u.id $where ORDER BY l.id DESC $limit");
$count = result("SELECT COUNT(*) FROM levels l $where");

$twig = twigloader();
echo $twig->render('latest.twig', [
	'type' => $type,
	'levels' => fetchArray($levels),
	'page' => $page,
	'level_count' => $count
]);