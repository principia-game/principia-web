#!/usr/bin/php
<?php

print("principia-web wiki tools - feed-data.php\n");
print("==================================================\n");
print("Import data from 'tools/outdata.json' into the wiki.\n\n");

require('lib/common.php');

if (!isCli()) die('no');

$data = json_decode(file_get_contents("tools/outdata.json"));

$stats = ['size' => 0, 'pages' => 0];

foreach ($data as $pagename => $content) {
	query("INSERT INTO wikipages (title) VALUES (?)",
		[$pagename]);

	$content = normalise($content);
	$size = strlen($content);

	query("INSERT INTO wikirevisions (page, author, time, size, description, content) VALUES (?,?,?,?,?,?)",
		[$pagename, 1, time(), $size, 'Automatic import from script', $content]);

	$cache->delete('wpe_'.base64_encode($pagename));

	$stats['size'] += $size;
	$stats['pages']++;
}

wikiImportHook($stats);
