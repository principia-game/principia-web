<?php
require('lib/common.php');

$levels = query("SELECT $userfields l.id id,l.title title,l.locked locked,l.likes likes,l.downloads downloads FROM levels l JOIN users u ON l.author = u.id WHERE l.locked = 0 ORDER BY l.downloads DESC, l.id DESC LIMIT $lpp");

$twig = twigloader();
echo $twig->render('popular.twig', ['levels' => fetchArray($levels)]);