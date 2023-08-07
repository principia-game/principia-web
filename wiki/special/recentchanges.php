<?php
$revisions = query("SELECT r.page, r.revision, r.size, r.sizediff, r.time, r.description, $userfields
		FROM wikirevisions r JOIN users u ON r.author = u.id ORDER BY r.time DESC, r.id DESC LIMIT 50");

echo _twigloader()->render('recentchanges.twig', [
	'revisions' => $revisions
]);
