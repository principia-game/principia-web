<?php

if (!IS_ADMIN) error('403', 'You don\'t have access to this page.');

$lockedlevels = query("SELECT l.id, l.title, l.visibility, $userfields
		FROM levels l JOIN users u ON l.author = u.id WHERE l.visibility = 1 ORDER BY l.id DESC");

echo twigloader()->render('viewlocked.twig', [
	'levels' => fetchArray($lockedlevels)
]);
