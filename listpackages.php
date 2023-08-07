<?php
require('lib/common.php');

$page = $_GET['page'] ?? 1;

$packages = query("SELECT p.id, p.title, $userfields
		FROM packages p JOIN users u ON p.author = u.id ORDER BY p.id DESC ".paginate($page, $lpp));
$count = result("SELECT COUNT(*) FROM packages");

echo twigloader()->render('listpackages.twig', [
	'packages' => fetchArray($packages),
	'page' => $page,
	'package_count' => $count
]);