<?php
$page = $_GET['page'] ?? 1;

$levels = popularLevels($page);

$count = result("SELECT COUNT(*) FROM @levels WHERE visibility = 0");

twigloader()->display('popular.twig', [
	'levels' => $levels,
	'page' => $page,
	'level_count' => $count
]);
