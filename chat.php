<?php
require('lib/common.php');

$comments = query("SELECT c.*,u.id u_id,u.name u_name FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 5 ORDER BY c.time DESC");

$twig = twigloader();
echo $twig->render('chat.twig', ['comments' => $comments]);