<?php

$levelquery = query("SELECT l.id, l.title, l.visibility, l.views, l.downloads, l.parent, @userfields
		FROM levels l JOIN users u ON l.author = u.id
		ORDER BY l.id DESC");

$levels = [];
while ($level = $levelquery->fetch()) {
	$level['dlbloat'] = $level['views'] > 0 ? round($level['downloads'] / $level['views'], 2) : 'N/A';
	$level['hasthumb'] = file_exists('data/thumbs/'.$level['id'].'.jpg');

	$levels[] = $level;
}

$newestlevel = fetch("SELECT l.id, l.title FROM levels l ORDER BY l.id DESC");

twigloader()->display('admin/levels.twig', [
	'levels' => $levels,
	'newestlevel' => $newestlevel
]);
