<?php
require('lib/common.php');

$query = (isset($_GET['query']) ? $_GET['query'] : '');
$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

$limit = sprintf("LIMIT %s,%s", (($page - 1) * $lpp), $lpp);
$levels = query("SELECT $userfields l.id id,l.title title,l.locked locked FROM levels l JOIN users u ON l.author = u.id WHERE l.title LIKE CONCAT('%', ?, '%') AND l.locked = 0 ORDER BY l.id DESC $limit",
	[$query]);
$count = result("SELECT COUNT(*) FROM levels l WHERE title LIKE CONCAT('%', ?, '%')",
	[$query]);

$twig = twigloader();
echo $twig->render('search.twig', [
	'query' => $query,
	'page' => $page,
	'levels' => $levels,
	'level_count' => $count
]);