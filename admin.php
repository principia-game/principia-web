<?php
require('lib/common.php');

if ($userdata['powerlevel'] < 3) error('403', "You shouldn't be here, get out!");

$memcachedStats = $cache->memcached->getStats();

$latestRegisteredUsers = query("SELECT id, name, customcolor, joined FROM users ORDER BY joined DESC LIMIT 7");
$latestSeenUsers = query("SELECT id, name, customcolor, lastview FROM users ORDER BY lastview DESC LIMIT 7");

$thingsToCount = ['levels', 'packages', 'comments', 'users'];

$query = "SELECT ";
foreach ($thingsToCount as $thing) {
	if ($query != "SELECT ") $query .= ", ";
	$query .= sprintf("(SELECT COUNT(*) FROM %s) %s", $thing, $thing);
}
$count = fetch($query);

$latestComments = query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id ORDER BY c.time DESC LIMIT 7");

echo twigloader()->render('admin.twig', [
	'memcached_stats' => $memcachedStats,
	'latest_registered_users' => $latestRegisteredUsers,
	'latest_seen_users' => $latestSeenUsers,
	'things_to_count' => $thingsToCount,
	'count' => $count,
	'latest_comments' => $latestComments
]);
