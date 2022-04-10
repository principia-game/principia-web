<?php
require('lib/common.php');

$lid = $_GET['id'] ?? 0;

$pkg = fetch("SELECT $userfields p.* FROM packages p JOIN users u ON p.author = u.id WHERE p.id = ?", [$lid]);

if (!$pkg) {
	error('404', "The requested package wasn't found.");
}

query("UPDATE packages SET views = views + '1' WHERE id = ?", [$lid]);
$pkg['views']++;

clearMentions('package', $lid);

$markdown = new Parsedown();
$markdown->setSafeMode(true);
$pkg['description'] = $markdown->text($pkg['description']);

$comments = query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 6 AND c.level = ? ORDER BY c.time DESC", [$lid]);

$twig = twigloader();

echo $twig->render('package.twig', [
	'id' => $lid,
	'pkg' => $pkg,
	'comments' => fetchArray($comments)
]);
