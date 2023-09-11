<?php

$commentquery = query("SELECT c.*, $userfields
		FROM comments c JOIN users u ON c.author = u.id
		ORDER BY c.id DESC LIMIT 200");

$comments = [];
while ($comment = $commentquery->fetch()) {
	$comments[] = $comment;
}

echo twigloader()->render('admin/comments.twig', [
	'comments' => $comments
]);
