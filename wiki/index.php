<?php
$page_slugified = str_replace('/wiki/', '', $uri) ?: 'Main_Page';
$page = str_replace('_', ' ', $page_slugified);

if (isset($_GET['rev']) || isset($_GET['action'])) {
	redirectPerma('/wiki/'.$page_slugified);
}

if (str_starts_with($page, 'Special:')) {
	$specialpage = strtolower(substr($page, 8));
	$specialpath = 'special/'.$specialpage.'.php';
	if (file_exists($specialpath))
		require($specialpath);
	elseif ($specialpage == 'recentchanges' || $specialpage == 'contributions')
		redirectPerma('/wiki/');
	elseif ($specialpage == 'longpages')
		_twigloader()->display('longpages.twig', [
			'longpages' => []
		]);
	elseif ($specialpage == 'pageindex')
		_twigloader()->display('pageindex.twig', [
			'pages' => getPageList()
		]);
	elseif ($specialpage == 'shortpages')
		_twigloader()->display('shortpages.twig', [
			'shortpages' => []
		]);
	elseif ($specialpage == 'specialpages')
		_twigloader()->display('specialpages.twig', [
			'specialpages' => [
				'LongPages' => 'Long pages',
				'OrphanedPages' => 'Orphaned pages',
				'PageIndex' => 'Page index',
				'ShortPages' => 'Short pages',
				'WantedPages' => 'Wanted pages'
			]
		]);
	elseif ($specialpage == 'version')
		_twigloader()->display('version.twig');
	else
		die('No such special page...');

	die();
}

$pagecontent = file_get_contents(
	WIKI_PAGES.str_replace('/', 'Ä', $page_slugified).'.md'
);

_twigloader()->display('index.twig', [
	'pagetitle' => $page,
	'pagetitle_slugified' => str_replace(' ', '_', $page),
	'pagecontent' => $pagecontent
]);
