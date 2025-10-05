<?php
$page = $_GET['page'] ?? 1;

$levels = topLevels($page);

$count = result("SELECT COUNT(*) FROM @levels WHERE visibility = 0");

twigloader()->display('top.twig', [
	'levels' => $levels,
	'page' => $page,
	'level_count' => $count
]);
