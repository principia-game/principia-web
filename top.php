<?php
require('lib/common.php');

$levels = query("SELECT $userfields l.id id,l.title title,l.locked locked,l.likes likes FROM levels l JOIN users u ON l.author = u.id WHERE l.locked = 0 ORDER BY l.likes DESC, l.id DESC LIMIT $lpp");

$twig = twigloader();
echo $twig->render('top.twig', ['levels' => fetchArray($levels)]);