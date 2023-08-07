#!/usr/bin/php
<?php

print("principia-web wiki tools - normalise-revisions.php\n");
print("==================================================\n");
print("Normalise the content of all revisions, if it has not already been for some reason.\n\n");

require('lib/common.php');

$revisions = query("SELECT * FROM wikirevisions");

while ($rev = $revisions->fetch()) {
	$content = normalise($rev['content']);
	$size = strlen($content);

	if ($rev['revision'] != 1) {
		$prevsize = result("SELECT size FROM wikirevisions WHERE page = ? AND revision = ?", [$rev['page'], $rev['revision'] - 1]);
		$sizediff = $size - $prevsize;
	} else {
		$sizediff = 0;
	}

	query("UPDATE wikirevisions SET content = ?, size = ?, sizediff = ? WHERE page = ? AND revision = ?",
		[$content, $size, $sizediff, $rev['page'], $rev['revision']]);
}
