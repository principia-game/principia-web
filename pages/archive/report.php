<?php
if (isset($_POST['kuk'])) {
	$text = $_POST['kuk'] ?? null;
	$url = $_POST['helvete'] ?? null;

	query("INSERT INTO reports (reporttext, url, ip) VALUES (?,?,?)",
		[$text, $url, $_SERVER['REMOTE_ADDR']]);

	$hasBeenSent = true;
}

$url = $_GET['url'] ?? null;

twigloader()->display('report.twig', [
	'url' => $url,
	'has_been_sent' => $hasBeenSent ?? false
]);
