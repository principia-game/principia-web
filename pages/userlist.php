<?php

$joined = !IS_ARCHIVE ? ', joined' : '';

$users = query("SELECT id, name, customcolor {$joined}, rank FROM users");

$maxid = result("SELECT MAX(id) FROM users");
$levelcounts = [];
for ($i = 1; $i <= $maxid; $i++) {
	$levelcounts[$i] = $cache->hit((IS_ARCHIVE ? 'archive:' : '').'levelcount_'.$i, function () use ($i) {
		return result("SELECT COUNT(*) FROM levels l WHERE l.author = ? AND l.visibility = 0", [$i]);
	});
}

twigloader()->display('userlist.twig', [
	'users' => $users,
	'levelcounts' => $levelcounts
]);
