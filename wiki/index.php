<?php
$page_slugified = str_replace('/wiki/', '', $uri) ?: 'Main_Page';
$page = str_replace('_', ' ', $page_slugified);

if (isset($_GET['rev']) || isset($_GET['action']))
	redirectPerma('/wiki/'.$page_slugified);

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
else
	http_response_code(404);

_twigloader()->display('index.twig', [
	'pagetitle' => $page,
	'pagetitle_slugified' => str_replace(' ', '_', $page),
	'pagecontent' => $pagecontent ?? null
]);
