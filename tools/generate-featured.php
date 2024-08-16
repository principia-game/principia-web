#!/usr/bin/php
<?php
require('lib/common.php');

$data = [
	'featured_levels' => [],
	'gettingstarted_list' => [],
];

$featured = query("SELECT l.id,l.title,u.name u_name
		FROM featured f JOIN levels l on f.level = l.id JOIN users u ON l.author = u.id
		ORDER BY f.id DESC LIMIT 4");

while ($level = $featured->fetch()) {
	$data['featured_levels'][] = [
		'id' => $level['id'],
		'name' => $level['title'],
		'author' => $level['u_name'],
		'jpeg_image' => 'data/thumbs_low/'.$level['id'].'.jpg',
	];
}

foreach ($gettingstarted_links as $link => $name) {
	$data['gettingstarted_list'][] = [
		'name' => $name,
		'link' => $link
	];
}

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
