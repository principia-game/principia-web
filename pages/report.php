<?php
if (isset($_POST['url'])) {
	$url = $_POST['url'] ?? null;
	$message = $_POST['message'] ?? null;

	if (IS_ARCHIVE) {
		insertInto('reports', [
			'url' => $url,
			'reporttext' => $message,
			'ip' => $ipaddr
		]);
	} else {
		insertInto('reports', [
			'url' => $url,
			'message' => $message,
			'user' => $userdata['id']
		]);
	}

	$hasBeenSent = true;
}

if (!IS_ARCHIVE)
	$admin = fetch("SELECT $userfields FROM users u WHERE u.id = 1");

$url = $_GET['url'] ?? null;

twigloader()->display(maybeArchive('report.twig'), [
	'url' => $url,
	'has_been_sent' => $hasBeenSent ?? false,
	'admin' => $admin ?? null
]);
