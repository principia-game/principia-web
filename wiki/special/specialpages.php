<?php

$specialpages = [
	'LongPages' => 'Long pages',
	'OrphanedPages' => 'Orphaned pages',
	'PageIndex' => 'Page index',
	'RecentChanges' => 'Recent changes',
	'ShortPages' => 'Short pages',
	'WantedPages' => 'Wanted pages'
];

echo _twigloader()->render('specialpages.twig', [
	'specialpages' => $specialpages
]);
