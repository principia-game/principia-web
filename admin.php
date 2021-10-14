<?php
require('lib/common.php');

$memcachedStats = $cache->memcached->getStats();

/*
echo '<pre>';
print_r($memcachedStats);
echo '</pre>';
*/

$memcachedStats['dummy-server.lol'] = ['offline' => true];

$twig = twigloader();
echo $twig->render('admin.twig', [
	'memcached_stats' => $memcachedStats
]);
