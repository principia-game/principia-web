<?php
$pages = [
	'contest' => 'pages/contest.php',
	'latest' => 'pages/latest.php',
	'levellist' => 'pages/levellist.php',
	'level' => 'pages/level.php',
	'popular' => 'pages/popular.php',
	'random' => 'pages/random.php',
	'sitemap' => 'pages/sitemap.php',
	'top' => 'pages/top.php',
	'user' => 'pages/user.php',
	'userlist' => 'pages/userlist.php',
	'viewlocked' => 'pages/viewlocked.php'
];

if (isset($path[1]) && $path[1] != '') {
	if (isset($pages[$path[1]]))
		require($pages[$path[1]]);

	else if ($path[1] == 'internal' && in_array($path[2], ['get_level', 'derive_level', 'edit_level'])) {
		require('pages/internal/get_level.php');
	} else
		error('404', "The requested page wasn't found.");
} else
	require('pages/index.php');
