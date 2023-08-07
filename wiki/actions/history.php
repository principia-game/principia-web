<?php
$pagedata = fetch("SELECT * FROM wikipages WHERE BINARY title = ?", [$page]);

if (!$pagedata) error('404', 'Invalid page name.');

$revisions = query("SELECT r.revision, r.size, r.sizediff, r.time, r.description, $userfields
		FROM wikirevisions r JOIN users u ON r.author = u.id WHERE BINARY r.page = ? ORDER BY r.revision DESC", [$page]);

echo _twigloader()->render('viewhistory.twig', [
	'pagetitle' => $page,
	'pagetitle_slugified' => str_replace(' ', '_', $page),
	'pagedata' => $pagedata,
	'revisions' => $revisions
]);
