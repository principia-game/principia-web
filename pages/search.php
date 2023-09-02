<?php

$query = (isset($_GET['query']) ? trim($_GET['query']) : '');
$page = $_GET['page'] ?? 1;

if ($query) {
	$levels = query("SELECT l.id id, l.title title, $userfields
			FROM levels l JOIN users u ON l.author = u.id
			WHERE l.title LIKE CONCAT('%', ?, '%') AND l.visibility = 0
			ORDER BY l.id DESC ".paginate($page, LPP),
		[$query]);

	$count = result("SELECT COUNT(*) FROM levels l WHERE title LIKE CONCAT('%', ?, '%')",
		[$query]);
}

echo twigloader()->render('search.twig', [
	'query' => $query,
	'page' => $page,
	'levels' => $levels ?? null,
	'level_count' => $count ?? null
]);
