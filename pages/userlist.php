<?php

$joined = !IS_ARCHIVE ? ', joined' : '';

$users = query("SELECT id, name, customcolor {$joined}, rank FROM users");

$maxid = result("SELECT MAX(id) FROM users");
$levelcounts = [];
for ($i = 1; $i <= $maxid; $i++)
	$levelcounts[$i] = getUserLevelCount($i);

twigloader()->display('userlist.twig', [
	'users' => $users,
	'levelcounts' => $levelcounts
]);
