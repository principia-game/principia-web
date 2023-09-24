<?php
$pagecontent = getPageContent();

$wantedpages = [];

foreach ($pagecontent as $content) {
	preg_match_all('/\[\[(.*?)\]\]/', $content, $links);
	foreach ($links[1] as $link){
		if (!checkPageExistance($link)) {
			if (!isset($wantedpages[$link])) $wantedpages[$link] = 0;
			$wantedpages[$link]++;
		}
	}
}

_twigloader()->display('wantedpages.twig', [
	'wantedpages' => $wantedpages
]);
