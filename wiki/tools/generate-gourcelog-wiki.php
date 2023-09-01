#!/usr/bin/php
<?php
print("This script generates a gource log for principia-web wiki edits.\n\n");

require('lib/common.php');

unlink('gource.log');

function skriv($text) {
	file_put_contents('gource.log', $text . PHP_EOL, FILE_APPEND);
}

$revisions = query("SELECT $userfields, r.page, r.revision, r.size, r.time
		FROM wikirevisions r JOIN users u ON r.author = u.id ORDER BY r.time ASC, r.id");

while ($rev = $revisions->fetch()) {
	skriv(sprintf(
		"%s|%s|%s|%s|%s",
	$rev['time'], $rev['u_name'], ($rev['revision'] == 1 ? 'A' : 'M'), $rev['page'], bin2hex(random_bytes(3))));
}
