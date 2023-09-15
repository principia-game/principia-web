<?php
$pages = query("SELECT title FROM wikipages ORDER BY title ASC");

_twigloader()->display('pageindex.twig', [
	'pages' => $pages
]);
