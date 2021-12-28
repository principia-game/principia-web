<?php
require('lib/common.php');

$id = (isset($_GET['id']) ? $_GET['id'] : 1);

clearMentions('chat', $id);

$comments = query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 5 AND c.level = ? ORDER BY c.time DESC LIMIT 50", [$id]);

$twig = twigloader();
echo $twig->render('chat.twig', ['id' => $id, 'comments' => $comments]);