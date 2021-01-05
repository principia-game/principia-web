<?php
require('lib/common.php');

$query = (isset($_GET['query']) ? $_GET['query'] : '');
$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

$limit = sprintf("LIMIT %s,%s", (($page - 1) * $lpp), $lpp);
$levels = query("SELECT l.id id,l.title title,l.locked locked,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE title LIKE CONCAT('%', ?, '%') AND locked = 0 ORDER BY l.id DESC $limit",
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