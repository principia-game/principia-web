<?php
$page_slugified = str_replace('/wiki/', '', $uri) ?: 'Main_Page';
$page = str_replace('_', ' ', $page_slugified);

if (isset($wikiPageRedirects[$page_slugified]) && $wikiPageRedirects[$page_slugified] != '')
	redirect('%s', $wikiPageRedirects[$page_slugified]);

if (str_starts_with($page, 'Special:')) {
	$specialpage = strtolower(substr($page, 8));
	if (isset($specialpages[$specialpage]))
		$specialpages[$specialpage]();
	else
		die('No such special page...');

	die();
}

$filename = WIKI_PAGES.str_replace('/', 'Ä', $page_slugified).'.md';

if (file_exists($filename))
	$pagecontent = file_get_contents($filename);
else {
	// Try to find a case insensitive match for the page name and redirect to that page
	$files = glob(WIKI_PAGES . '*.md');
	$pageTest = str_replace('/', 'Ä', strtolower($page_slugified));
	foreach ($files as $file) {
		if (strtolower(basename($file, '.md')) === $pageTest) {
			redirect('/wiki/%s', str_replace([WIKI_PAGES, '.md', 'Ä'], ['', '', '/'], $file));
		}
	}
}

twigloaderWiki()->display('wiki/index.twig', [
	'pagetitle' => $page,
	'pagetitle_slugified' => str_replace(' ', '_', $page),
	'pagecontent' => $pagecontent ?? null
]);
