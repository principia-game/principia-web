<?php
$latestfeatured = query("SELECT l.id,l.title,$userfields FROM featured f JOIN levels l on f.level = l.id JOIN users u ON l.author = u.id ORDER BY f.id DESC LIMIT 4");

$toplevels = query("SELECT l.id,l.title,$userfields FROM levels l JOIN users u ON l.author = u.id WHERE l.visibility = 0 ORDER BY l.likes DESC, l.id DESC LIMIT 8");

$latestquery = "SELECT l.id,l.title,$userfields FROM levels l JOIN users u ON l.author = u.id WHERE l.cat = %d AND l.visibility = 0 ORDER BY l.id DESC LIMIT 8";

$latestcustom = query(sprintf($latestquery, 1));

$latestadvent = query(sprintf($latestquery, 2));

$randomlevels = randomLevels(8);

twigloader()->display('index.twig', [
	'featured_levels' => $latestfeatured,
	'top_levels' => $toplevels,
	'custom_levels' => $latestcustom,
	'adventure_levels' => $latestadvent,
	'random_levels' => $randomlevels
]);
