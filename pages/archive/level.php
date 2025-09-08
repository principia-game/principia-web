<?php
$lid = $path[2] ?? 0;

$level = fetch("SELECT $userfields, l.* FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ?", [$lid]);

if (!$level) error('404');

$derivatives = query("SELECT $userfields,l.id,l.title FROM levels l JOIN users u ON l.author = u.id WHERE l.parent = ? AND l.visibility = 0 ORDER BY l.id DESC", [$lid]);
if ($level['parent'])
	$parentLevel = fetch("SELECT $userfields,l.id,l.title FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ? AND l.visibility = 0", [$level['parent']]);

twigloader()->display('archive/level.twig', [
	'lid' => $lid,
	'level' => $level,
	'derivatives' => fetchArray($derivatives),
	'parentlevel' => $parentLevel ?? null
]);
