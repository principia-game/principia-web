<?php
if (!IS_ADMIN) error('403');

$commentquery = query("SELECT c.*, $userfields
		FROM comments c JOIN users u ON c.author = u.id
		ORDER BY c.id DESC LIMIT 200");

$comments = [];
while ($comment = $commentquery->fetch()) {
	$comments[] = $comment;
}

twigloader()->display('admin/comments.twig', [
	'comments' => $comments
]);
