<?php
require('lib/common.php');

echo twigloader()->render('_markdown.twig', [
	'pagetitle' => 'Credits',
	'file' => 'credits.md'
]);