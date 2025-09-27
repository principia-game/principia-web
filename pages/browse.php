<?php

// Cache all index page queries unless explicitly flushed.

$toplevels = $cache->hit('idx_top', fn() => fetchArray(topLevels()));

$latestquery = "SELECT l.id,l.title, @userfields FROM levels l JOIN users u ON l.author = u.id WHERE l.cat = %d AND l.visibility = 0 ORDER BY l.id DESC LIMIT 8";

$latestcustom = $cache->hit('idx_anp', fn() => fetchArray(latestLevels(1)));
$latestadvent = $cache->hit('idx_adv', fn() => fetchArray(latestLevels(2)));
$latestpuzzle = $cache->hit('idx_puz', fn() => fetchArray(latestLevels(3)));

$randomlevels = randomLevels(8);

twigloader()->display('browse.twig', [
	'top_levels' => $toplevels,
	'custom_levels' => $latestcustom,
	'adventure_levels' => $latestadvent,
	'puzzle_levels' => $latestpuzzle,
	'random_levels' => $randomlevels
]);
