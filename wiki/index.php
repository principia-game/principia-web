<?php
require('lib/common.php');

$page = (isset($_GET['page']) ? str_replace('_', ' ', $_GET['page']) : 'Main Page');
$page_slugified = (isset($_GET['page']) ? $_GET['page'] : 'Main_Page');
$revision = $_GET['rev'] ?? null;

if (isset($_GET['action'])) {
	$actionpage = 'actions/'.$_GET['action'].'.php';
	if (file_exists($actionpage)) {
		require($actionpage);
		die();
	}
}

if (str_starts_with($page, 'Special:')) {
	$specialpage = 'special/'.strtolower(substr($page, 8)).'.php';
	if (file_exists($specialpage)) {
		require($specialpage);
		die();
	}
}

if ($revision) {
	$pagedata = fetch("SELECT p.*, $userfields, r.time, r.content FROM wikipages p
			JOIN wikirevisions r ON ? = r.revision
			JOIN users u ON r.author = u.id
			WHERE BINARY p.title = ? AND BINARY r.page = ? AND r.revision = ?", [$revision, $page, $page, $revision]);
} else {
	$pagedata = fetch("SELECT p.*, $userfields, r.time, r.content FROM wikipages p
			JOIN wikirevisions r ON p.cur_revision = r.revision
			JOIN users u ON r.author = u.id
			WHERE BINARY p.title = ? AND BINARY r.page = ?", [$page, $page]);
}

echo _twigloader()->render('index.twig', [
	'pagetitle' => $page,
	'pagetitle_slugified' => str_replace(' ', '_', $page),
	'page' => $pagedata,
	'revision' => $revision
]);
