#!/usr/bin/php
<?php

print("principia-web tools - generate-gourcelog-levels.php\n");
print("===================================================\n");
print("This script generates a gource log for all levels that have been uploaded to principia-web.\n");

require('lib/common.php');

function skriv($text) {
	file_put_contents('gource.log', $text . PHP_EOL, FILE_APPEND);
}

$levels = query("SELECT l.id,l.title,l.time,$userfields FROM levels l JOIN users u ON l.author = u.id ORDER BY l.id ASC");

while ($level = $levels->fetch()) {
	skriv(sprintf(
		"%s|%s|%s|%s",
	$level['time'], $level['u_name'], 'A', $level['title']));
}