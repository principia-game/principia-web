<?php
require('lib/common.php');

clearMentions('chat', 1);

$comments = query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 5 ORDER BY c.time DESC LIMIT 50");

echo twigloader()->render('chat.twig', [
	'comments' => $comments,
	'chatmsg' => $chatmsg
]);