<?php
require('lib/common.php');

$memcachedStats = $cache->memcached->getStats();

/*
echo '<pre>';
print_r($memcachedStats);
echo '</pre>';
*/

$latestRegisteredUsers = query("SELECT id, name, customcolor, joined FROM users ORDER BY joined DESC LIMIT 7");
$latestSeenUsers = query("SELECT id, name, customcolor, lastview FROM users ORDER BY lastview DESC LIMIT 7");


$memcachedStats['dummy-server.lol'] = ['offline' => true];

$twig = twigloader();
echo $twig->render('admin.twig', [
	'memcached_stats' => $memcachedStats,
	'latest_registered_users' => $latestRegisteredUsers,
	'latest_seen_users' => $latestSeenUsers
]);
