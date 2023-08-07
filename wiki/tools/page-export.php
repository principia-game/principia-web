#!/usr/bin/php
<?php

print("principia-web wiki tools - page-export.php\n");
print("==================================================\n");
print("Export the wiki content. Cool.\n\n");

require('lib/common.php');

$pages = query("SELECT p.*, r.time, r.content, u.name u_name FROM wikipages p
			JOIN wikirevisions r ON p.cur_revision = r.revision AND p.title = r.page
			JOIN users u ON r.author = u.id");

$i = 0;
while ($page = $pages->fetch()) {
	printf('Exporting page "%s"'.PHP_EOL, $page['title']);
	$content = _twigloader()->render('_export_page.twig', [
		'page' => $page
	]);

	file_put_contents('_export/'.str_replace(' ', '_', $page['title']), $content);
	$i++;
}

printf("total: %d", $i);
