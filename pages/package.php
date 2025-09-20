<?php
$pid = $path[2] ?? 0;

$pkg = fetch("SELECT p.*, $userfields FROM packages p JOIN users u ON p.author = u.id WHERE p.id = ?", [$pid]);

if (!$pkg) error('404');

if ($log) {
	query("UPDATE packages SET views = views + '1' WHERE id = ?", [$pid]);
	$pkg['views']++;
}

clearMentions('package', $pid);

twigloader()->display('package.twig', [
	'id' => $pid,
	'pkg' => $pkg,
	'comments' => getComments('package', $pid)
]);
