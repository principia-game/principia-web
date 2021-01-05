<?php
require('lib/common.php');

if ($userdata['powerlevel'] < 2) error('403', 'You don\'t have access to this page.');

$lockedlevels = query("SELECT l.id id,l.title title,l.locked locked,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.locked = 1 ORDER BY l.id DESC");

$twig = twigloader();
echo $twig->render('viewlocked.twig', [
	'levels' => fetchArray($lockedlevels)
]);