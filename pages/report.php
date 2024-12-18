<?php
if (isset($_POST['url'])) {
	$url = $_POST['url'] ?? null;
	$message = $_POST['message'] ?? null;

	insertInto('reports', [
		'url' => $url,
		'message' => $message,
		'user' => $userdata['id']
	]);

	$hasBeenSent = true;
}

$admin = fetch("SELECT $userfields FROM users u WHERE u.id = 1");

$url = $_GET['url'] ?? null;

twigloader()->display('report.twig', [
	'url' => $url,
	'has_been_sent' => $hasBeenSent ?? false,
	'admin' => $admin
]);
