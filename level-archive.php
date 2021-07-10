<?php
require('lib/common.php');

if (!$levelArchive) error('403', 'This instance of principia-web does not contain a level archive.');

$twig = twigloader();

echo $twig->render('level-archive.twig', [
	'llcache' => ($log ? file_get_contents("level_archive/cached_level_list.txt") : null)
]);
