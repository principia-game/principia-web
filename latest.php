<?php
require('lib/common.php');

$type = (isset($_GET['type']) ? $_GET['type'] : 'all');
$page = (isset($_GET['page']) ? $_GET['page'] : 1);

$where = ($type != 'all' ? "WHERE l.cat = ".type_to_cat($type) : '');
$limit = sprintf("LIMIT %s,%s", (($page - 1) * $lpp), $lpp);
$levels = query("SELECT l.id id,l.title title,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id $where ORDER BY l.id DESC $limit");
$count = result("SELECT COUNT(*) FROM levels l $where");

$twig = twigloader();
echo $twig->render('latest.twig', [
	'type' => $type,
	'levels' => fetchArray($levels),
	'page' => $page,
	'level_count' => $count
]);