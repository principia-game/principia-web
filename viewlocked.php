<?php
require('lib/common.php');

if ($userdata['powerlevel'] < 2) error('403', 'You don\'t have access to this page.');

$lockedlevels = query("SELECT $userfields l.id id,l.title title,l.visibility visibility FROM levels l JOIN users u ON l.author = u.id WHERE l.visibility = 1 ORDER BY l.id DESC");

$twig = twigloader();
echo $twig->render('viewlocked.twig', [
	'levels' => fetchArray($lockedlevels)
]);