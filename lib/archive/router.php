<?php
require('data/archive/public_levels.php');

query("USE `".DB_NAME_ARCHIVE."`");

$pages = [
	'latest' => 'pages/latest.php',
	'popular' => 'pages/popular.php',
	'report' => 'pages/report.php',
	'search' => 'pages/search.php',
	'sitemap' => 'pages/sitemap.php',
	'top' => 'pages/top.php',
	'viewlocked' => 'pages/viewlocked.php'
];

if (isset($path[1]) && $path[1] != '') {
	if (isset($pages[$path[1]]))
		require($pages[$path[1]]);

	else if (file_exists('pages/archive/'.$path[1].'.php'))
		require('pages/archive/'.$path[1].'.php');

	else if ($path[1] == 'internal' && in_array($path[2], ['get_level', 'derive_level', 'edit_level'])) {
		require('internal/get_level.php');
	} else
		error('404', "The requested page wasn't found.");
} else
	require('pages/archive/index.php');
