<?php
$lockedlevels = query("SELECT $userfields,l.id,l.title,l.visibility FROM levels l JOIN users u ON l.author = u.id WHERE l.visibility = 1 ORDER BY l.id DESC");

twigloader()->display('viewlocked.twig', [
	'levels' => fetchArray($lockedlevels)
]);
