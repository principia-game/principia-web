<?php
$pagecontent = query("SELECT p.title, r.content FROM wikipages p JOIN wikirevisions r ON p.cur_revision = r.revision AND p.title = r.page");

$wantedpages = [];

foreach ($pagecontent as $page) {
	preg_match_all('/\[\[(.*?)\]\]/', $page['content'], $links);
	foreach ($links[1] as $link){
		if (!checkPageExistance($link)) {
			if (!isset($wantedpages[$link])) $wantedpages[$link] = 0;
			$wantedpages[$link]++;
		}
	}
}

echo _twigloader()->render('wantedpages.twig', [
	'wantedpages' => $wantedpages
]);
