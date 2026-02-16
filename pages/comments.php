<?php

$comments = query("SELECT c.*, l.title level_name, @userfields FROM comments c
		JOIN users u ON c.author = u.id JOIN levels l ON c.level = l.id
		WHERE c.type = 1 AND c.deleted = 0 ORDER BY c.time DESC LIMIT 100");

twigloader()->display('comments.twig', [
	'comments' => $comments
]);
