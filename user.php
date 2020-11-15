<?php
include('lib/common.php');

if (isset($_GET['id'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE id = ?", [$_GET['id']]);
} else if (isset($_GET['name'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE name = ?", [$_GET['name']]);
} else {
	// todo: we should have a error function
	die("no user specified");
}

$forceuser = isset($_GET['forceuser']);

$levels = query("SELECT l.id id,l.title title,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.author = ? ORDER BY l.id DESC",
	[$userpagedata['id']]);

$twig = twigloader();
echo $twig->render('user.twig', [
	'id' => $_GET['id'],
	'name' => $userpagedata['name'],
	'levels' => fetchArray($levels),
	'forceuser' => $forceuser
]);