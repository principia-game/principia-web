<?php
require('lib/common.php');

$type = (isset($_GET['type']) ? $_GET['type'] : 'all');

$where = ($type != 'all' ? "WHERE l.cat = ".type_to_cat($type) : '');
$levels = query("SELECT l.id id,l.title title,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id $where ORDER BY l.id DESC");

$twig = twigloader();
echo $twig->render('latest.twig', [
	'type' => $type,
	'levels' => fetchArray($levels)
]);