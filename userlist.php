<?php
require('lib/common.php');

$users = query("SELECT id, name, customcolor, joined, rank FROM users");

$ucount = result("SELECT COUNT(*) FROM users");
$levelcounts = [];
for ($i = 1; $i <= $ucount; $i++) {
	$levelcounts[$i] = $cache->hit('levelcount_'.$i, function () use ($i) {
		return result("SELECT COUNT(*) FROM levels l WHERE l.author = ? AND l.visibility = 0", [$i]);
	});
}

echo twigloader()->render('userlist.twig', [
	'users' => $users,
	'levelcounts' => $levelcounts
]);
