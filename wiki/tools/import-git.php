<?php

if (php_sapi_name() != "cli") die();

// boostrap
chdir('../');
require('lib/common.php');
chdir('wiki');
foreach (glob("lib/*.php") as $filename)
	require($filename);

chdir('../data/');
system("mkdir wiki");
chdir("wiki/");
system("git init");
system("mkdir pages");
chdir("pages/");

$revs = query("SELECT * FROM wikirevisions");

putenv("LC_ALL=C");

while ($rev = $revs->fetch()) {
	file_put_contents(str_replace([' ', '/'], ['_', 'Ä'], $rev['page']).'.md', $rev['content']);

	$desc = $rev['page'].': '.($rev['description'] ?: 'edit');

	foreach (['GIT_AUTHOR_DATE', 'GIT_COMMITTER_DATE'] as $env) {
		putenv($env.'="'.$rev['time'].' +0000"');
	}

	system("git add -A");
	system('git commit -m '.escapeshellarg($desc));
}
