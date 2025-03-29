<?php

// Cache all index page queries unless explicitly flushed.

$toplevels = $cache->hit('idx_top', function () use ($userfields) {
	return fetchArray(query("SELECT l.id,l.title, $userfields FROM levels l JOIN users u ON l.author = u.id WHERE l.visibility = 0 ORDER BY l.likes DESC, l.id DESC LIMIT 8"));
});

$latestquery = "SELECT l.id,l.title, $userfields FROM levels l JOIN users u ON l.author = u.id WHERE l.cat = %d AND l.visibility = 0 ORDER BY l.id DESC LIMIT 8";

$latestcustom = $cache->hit('idx_anp', function () use ($latestquery) {
	return fetchArray(query(sprintf($latestquery, 1)));
});

$latestadvent = $cache->hit('idx_adv', function () use ($latestquery) {
	return fetchArray(query(sprintf($latestquery, 2)));
});

$latestpuzzle = $cache->hit('idx_puz', function () use ($latestquery) {
	return fetchArray(query(sprintf($latestquery, 3)));
});

twigloader()->display('browse.twig', [
	'top_levels' => $toplevels,
	'custom_levels' => $latestcustom,
	'adventure_levels' => $latestadvent,
	'puzzle_levels' => $latestpuzzle
]);
