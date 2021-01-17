<?php
require('lib/common.php');

$levels = query("SELECT l.id id,l.title title,l.locked locked,l.likes likes,l.downloads downloads,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.locked = 0 ORDER BY l.downloads DESC, l.id DESC LIMIT $lpp");

$twig = twigloader();
echo $twig->render('popular.twig', ['levels' => fetchArray($levels)]);