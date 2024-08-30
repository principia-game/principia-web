<?php
$query = (isset($_GET['query']) ? trim($_GET['query']) : '');
$page = $_GET['page'] ?? 1;
$descr = $_GET['descr'] ?? 0;
$booleanmode = $_GET['boolean'] ?? 0;

if ($query) {
	$boolean = $booleanmode ? ' IN BOOLEAN MODE' : '';

	$levels = query(
			"WITH cte AS (
				SELECT l.id, l.title, $userfields, COUNT(*) OVER () AS cnt
				FROM levels l JOIN users u ON l.author = u.id
				WHERE (
					(MATCH (l.title) AGAINST (? ${boolean})) OR
					(MATCH (l.description) AGAINST (? ${boolean})) AND ?
				) AND l.visibility = 0
			)

			SELECT * FROM cte ".paginate($page, LPP),
		[$query, $query, $descr]);
}

twigloader()->display('search.twig', [
	'query' => $query,
	'page' => $page,
	'levels' => $levels ?? null,
	'descr' => $descr,
	'boolean' => $booleanmode
]);
