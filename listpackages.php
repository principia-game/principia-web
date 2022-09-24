<?php
require('lib/common.php');

$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

$limit = sprintf("LIMIT %s,%s", (($page - 1) * $lpp), $lpp);
$packages = query("SELECT $userfields p.id id,p.title title FROM packages p JOIN users u ON p.author = u.id ORDER BY p.id DESC $limit");
$count = result("SELECT COUNT(*) FROM packages");

$twig = twigloader();
echo $twig->render('listpackages.twig', [
	'packages' => fetchArray($packages),
	'page' => $page,
	'package_count' => $count
]);