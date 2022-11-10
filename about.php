<?php
require('lib/common.php');

echo twigloader()->render('_markdown.twig', [
	'pagetitle' => 'About',
	'file' => 'about.md'
]);