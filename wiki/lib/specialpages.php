<?php

$specialpages = [];

function registerSpecialPage($name, $func) {
	global $specialpages;

	$specialpages[$name] = $func;
}

// Pages that don't exist anymore
foreach (['contributions', 'recentchanges'] as $page)
	registerSpecialPage($page, function () {
		redirectPerma('/wiki/');
	});


// Stubs
foreach (['longpages', 'shortpages'] as $page)
	registerSpecialPage($page, function () {
		die('stub');
	});


registerSpecialPage('gotoitem', function () {
	require('lib/special/gotoitem.php');
});

registerSpecialPage('gotoobject', function () {
	require('lib/special/gotoobject.php');
});

// Special:OrphanedPages - Generates a list of "orphaned" pages, not linked from anywhere else on the wiki.
registerSpecialPage('orphanedpages', function () {
	//

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
});

// Special:PageIndex - Generates a list of pages
registerSpecialPage('pageindex', function () {
	_twigloader()->display('pageindex.twig', [
		'pages' => getPageList()
	]);
});

// Special:Sitemap - Generates a newline separated sitemap for Google Search Console et al.
registerSpecialPage('sitemap', function () {
	$sitemap = new Sitemap('https://principia-web.se/');

	$pages = glob(WIKI_PAGES.'*.md');

	foreach ($pages as $page)
		$sitemap->add('wiki/'.filepathToSlug($page));

	$sitemap->output();
});

// Special:SpecialPages - Lists special pages
registerSpecialPage('specialpages', function () {
	_twigloader()->display('specialpages.twig', [
		'specialpages' => [
			'LongPages' => 'Long pages',
			'OrphanedPages' => 'Orphaned pages',
			'PageIndex' => 'Page index',
			'ShortPages' => 'Short pages',
			'WantedPages' => 'Wanted pages'
		]
	]);
});

// 8)
registerSpecialPage('version', function () {
	_twigloader()->display('version.twig');
});

// Special:WantedPages - Generates a list of "wanted" pages, ones linked to but don't exist yet.
registerSpecialPage('wantedpages', function () {
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
});
