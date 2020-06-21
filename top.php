<?php
require('lib/common.php');

$levels = query("SELECT l.id id,l.title title,l.likes likes,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id ORDER BY l.likes DESC, l.id DESC LIMIT 50");

pageheader();

$twig = twigloader();
echo $twig->render('top.php', ['levels' => fetchArray($levels)]);

pagefooter();