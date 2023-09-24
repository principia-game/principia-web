<?php

// Generates a list of "orphaned" pages, not linked from anywhere else on the wiki.

$pagecontent = getPageContent();

// Iterate over page contents, get a list of linked pages.
$linkedpages = [];
foreach ($pagecontent as $content) {
	preg_match_all('/\[\[(.*?)\]\]/', $content, $links);
	foreach ($links[1] as $link)
		$linkedpages[$link] = true;
}

// Add a "blacklist" of pages that get linked by the wiki software or by the client and therefore aren't really orphans.
$blacklist = [
	'Main Page', 'Copyright', // wiki software
	'Bad Graphics', 'Getting Started', // Principia client
];

foreach ($blacklist as $page)
	$linkedpages[$page] = true;

// Iterate over pages, any pages not existing in $linkedpages is an orphan!
$orphanedpages = [];
foreach ($pagecontent as $pagename => $content) {
	if (!isset($linkedpages[$pagename]))
		$orphanedpages[] = $pagename;
}

_twigloader()->display('orphanedpages.twig', [
	'orphanedpages' => $orphanedpages
]);
