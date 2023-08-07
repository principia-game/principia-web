<?php
$pages = query("SELECT title FROM wikipages ORDER BY title ASC");

echo _twigloader()->render('pageindex.twig', [
	'pages' => $pages
]);
