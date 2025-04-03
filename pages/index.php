<?php

// Cache all index page queries unless explicitly flushed.

$latestfeatured = $cache->hit('idx_feat', function () use ($userfields) {
	return fetchArray(query("SELECT l.id,l.title, $userfields FROM featured f JOIN levels l on f.level = l.id JOIN users u ON l.author = u.id ORDER BY f.id DESC LIMIT 4"));
});

$newsdata = News::retrieveList(5);

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

$latestcomments = $cache->hit('idx_cmnts', function () use ($userfields) {
	return fetchArray(query("SELECT c.*, l.title level_name, $userfields FROM comments c
			JOIN users u ON c.author = u.id JOIN levels l ON c.level = l.id
			WHERE c.type = 1 AND c.deleted = 0 ORDER BY c.time DESC LIMIT 8"));
});

twigloader()->display('index.twig', [
	'just_registered' => isset($_GET['rd']),
	'already_logged' => isset($_GET['al']),
	'featured_levels' => $latestfeatured,
	'news' => $newsdata,
	'top_levels' => $toplevels,
	'custom_levels' => $latestcustom,
	'adventure_levels' => $latestadvent,
	'puzzle_levels' => $latestpuzzle,
	'latest_comments' => $latestcomments,
]);
