<?php
require('lib/common.php');

// Cache all index page queries unless explicitly flushed.

$latestfeatured = $cache->hit('idx_feat', function () use ($userfields) {
	return fetchArray(query("SELECT $userfields l.id id,l.title title,l.locked locked FROM featured f JOIN levels l on f.level = l.id JOIN users u ON l.author = u.id ORDER BY f.id DESC LIMIT 4"));
});

$newsdata = $cache->hit('idx_news', function () {
	return fetchArray(query("SELECT id, title, time FROM news ORDER BY id DESC LIMIT 5"));
});

$toplevels = $cache->hit('idx_top', function () use ($userfields) {
	return fetchArray(query("SELECT $userfields l.id id,l.title title,l.locked locked FROM levels l JOIN users u ON l.author = u.id WHERE l.locked = 0 ORDER BY l.likes DESC, l.id DESC LIMIT 4"));
});

$latestquery = "SELECT $userfields l.id id,l.title title,l.locked locked FROM levels l JOIN users u ON l.author = u.id WHERE l.cat = %d AND l.locked = 0 ORDER BY l.id DESC LIMIT 4";

$latestcustom = $cache->hit('idx_anp', function () use ($latestquery) {
	return fetchArray(query(sprintf($latestquery, 1)));
});

$latestadvent = $cache->hit('idx_adv', function () use ($latestquery) {
	return fetchArray(query(sprintf($latestquery, 2)));
});

$justRegistered = (isset($_GET['rd']) ? true : false);

$twig = twigloader();

echo $twig->render('index.twig', [
	'just_registered' => $justRegistered,
	'featured_levels' => $latestfeatured,
	'news' => $newsdata,
	'top_levels' => $toplevels,
	'custom_levels' => $latestcustom,
	'adventure_levels' => $latestadvent,
]);
