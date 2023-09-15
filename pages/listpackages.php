<?php
$page = $_GET['page'] ?? 1;

$packages = query("SELECT p.id, p.title, $userfields
		FROM packages p JOIN users u ON p.author = u.id ORDER BY p.id DESC ".paginate($page, LPP));
$count = result("SELECT COUNT(*) FROM packages");

twigloader()->display('listpackages.twig', [
	'packages' => fetchArray($packages),
	'page' => $page,
	'package_count' => $count
]);