<?php
$pid = $path[2] ?? 0;

$pkg = fetch("SELECT p.*, $userfields FROM packages p JOIN users u ON p.author = u.id WHERE p.id = ?", [$pid]);

if (!$pkg) error('404');

if ($log) {
	query("UPDATE packages SET views = views + '1' WHERE id = ?", [$pid]);
	$pkg['views']++;
}

clearMentions('package', $pid);

$comments = query("SELECT c.*, $userfields FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 6 AND c.level = ? ORDER BY c.time DESC", [$pid]);

twigloader()->display('package.twig', [
	'id' => $pid,
	'pkg' => $pkg,
	'comments' => fetchArray($comments)
]);
