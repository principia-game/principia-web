<?php
require('lib/common.php');

$comments = query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 5 ORDER BY c.time DESC LIMIT 50");

$twig = twigloader();
echo $twig->render('chat.twig', ['comments' => $comments]);