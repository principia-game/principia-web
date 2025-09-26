<?php

$extrafields = !IS_ARCHIVE ? ', joined, rank' : '';

$users = query("SELECT id, name, customcolor {$extrafields} FROM users");

$maxid = result("SELECT MAX(id) FROM users");
$levelcounts = [];
for ($i = 1; $i <= $maxid; $i++)
	$levelcounts[$i] = getUserLevelCount($i);

twigloader()->display('userlist.twig', [
	'users' => $users,
	'levelcounts' => $levelcounts
]);
