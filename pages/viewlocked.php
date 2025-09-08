<?php

if (!IS_ARCHIVE && !IS_MOD) error('403');

$lockedlevels = query("SELECT l.id, l.title, l.visibility, $userfields
		FROM levels l JOIN users u ON l.author = u.id WHERE l.visibility = 1 ORDER BY l.id DESC");

twigloader()->display('viewlocked.twig', [
	'levels' => fetchArray($lockedlevels)
]);
