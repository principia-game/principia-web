<?php
$prev = $_GET['prev'] ?? null;
$next = $_GET['next'] ?? null;

$content['prev'] = fetch("SELECT content FROM wikirevisions WHERE BINARY page = ? AND revision = ?", [$page, $prev]);
$content['next'] = fetch("SELECT content FROM wikirevisions WHERE BINARY page = ? AND revision = ?", [$page, $next]);

if (!$content['prev'] || !$content['next']) error('404', 'Invalid revisions provided.');

$diff = new Diff(
	explode("\n", $content['prev']['content']),
	explode("\n", $content['next']['content']));
$renderer = new Diff_Renderer_Html_Inline;
$output = $diff->render($renderer);

echo _twigloader()->render('diff.twig', [
	'pagetitle' => $page,
	'pagetitle_slugified' => $page_slugified,
	'prev' => $prev,
	'next' => $next,
	'diff' => $output
]);
