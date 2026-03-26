<?php

if (isset($path[2]) && $path[2] == 'interest') {
	require('pages/contests/interest.php');
	return;
}

// add another field to know whether a contest is currently ongoing
$contestdata = query("SELECT id,title, NOW() <= time_to AS is_ongoing FROM contests WHERE NOW() >= time_from ORDER BY id DESC");

$archivedContests = query("SELECT id,title FROM archive_contests ORDER BY id DESC");

twigloader()->display('contests.twig', [
	'contests' => fetchArray($contestdata),
	'archived_contests' => fetchArray($archivedContests)
]);
