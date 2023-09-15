<?php

if (!IS_MOD) error('403');

if (isset($path[2])) {
	if (file_exists('pages/admin/'.$path[2].'.php')) {
		require('pages/admin/'.$path[2].'.php');
	}
} else {
	$latestRegisteredUsers = query("SELECT id, name, customcolor, joined FROM users ORDER BY joined DESC LIMIT 7");
	$latestSeenUsers = query("SELECT id, name, customcolor, lastview FROM users ORDER BY lastview DESC LIMIT 7");

	$thingsToCount = ['levels', 'packages', 'comments', 'users'];

	$query = "SELECT ";
	foreach ($thingsToCount as $thing) {
		if ($query != "SELECT ") $query .= ", ";
		$query .= sprintf("(SELECT COUNT(*) FROM %s) %s", $thing, $thing);
	}
	$count = fetch($query);

	$latestComments = query("SELECT c.*, $userfields FROM comments c JOIN users u ON c.author = u.id WHERE c.deleted = 0 ORDER BY c.time DESC LIMIT 7");

	twigloader()->display('admin/index.twig', [
		'latest_registered_users' => $latestRegisteredUsers,
		'latest_seen_users' => $latestSeenUsers,
		'things_to_count' => $thingsToCount,
		'count' => $count,
		'latest_comments' => $latestComments
	]);
}
