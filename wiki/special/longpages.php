<?php
$longpages = query("SELECT p.title, r.size FROM wikipages p JOIN wikirevisions r ON p.cur_revision = r.revision AND p.title = r.page ORDER BY r.size DESC, p.title LIMIT 50");

echo _twigloader()->render('longpages.twig', [
	'longpages' => $longpages
]);
